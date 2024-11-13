<?php

namespace App\Livewire;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\Sku;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductForm extends Component
{
    use WithFileUploads;

    public Product $product;
    public array $skus = [];
    public string $name = '';
    public string $slug = '';
    public array $deleteSkuIds = [];
    public array $productAttributes = [];
    public bool $isEdit = false;
    public array $photoPreviews = [];

    public $photo1;
    public $photo2 = null;
    public $photo3 = null;
    public $photo4 = null;


    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products,slug',
        'photo1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'photo2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'photo3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'photo4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'skus.*.code' => 'required|string|max:50|unique:skus,code',
        'skus.*.price' => 'required|numeric|min:0',
        'skus.*.stock' => 'required|integer|min:0',
    ];

    public function mount($productId = null)
    {
        $this->productAttributes = Attribute::with('options')->get()->toArray();

        if ($productId) {
            // Load existing product for editing
            $this->product = Product::with('skus.attributeOptions', 'images')->findOrFail($productId);
            $this->isEdit = true;
            $this->name = $this->product->name;
            $this->slug = $this->product->slug;

            $this->skus = $this->product->skus->map(function ($sku) {
                $attributes = [];
                foreach ($sku->attributeOptions as $option) {
                    $attributes[$option->attribute_id] = $option->id;
                }
                return [
                    'id' => $sku->id,
                    'code' => $sku->code,
                    'price' => $sku->price,
                    'stock' => $sku->stock,
                    'attributes' => $attributes,
                ];
            })->toArray();

            // Load existing images and generate URLs
            $this->photoPreviews = $this->product->images->map(function ($image) {
                return [
                    'path' => $image->image_path,
                    'url'  => Storage::disk('s3')->url($image->image_path),
                ];
            })->toArray();
        } else {
            $this->product = new Product();
            $this->isEdit = false;
        }
    }

    public function addSku(): void
    {
        $this->skus[] = ['code' => '', 'price' => 0, 'stock' => 0, 'attributes' => []];
    }

    public function removeSku($index): void
    {
        $skuId = $this->skus[$index]['id'] ?? null;
        if ($skuId) {
            $this->deleteSkuIds[] = $skuId;
        }
        unset($this->skus[$index]);
        $this->skus = array_values($this->skus);
    }

    public function removeImage($index, $type = 'photos')
    {
        if ($type === 'photoPreviews' && isset($this->photoPreviews[$index])) {
            $imagePath = $this->photoPreviews[$index]['path'];

            // Delete image from S3
            Storage::disk('s3')->delete($imagePath);

            // Remove image record from the database
            $this->product->images()->where('image_path', $imagePath)->delete();

            // Remove from the photo previews array
            unset($this->photoPreviews[$index]);
            $this->photoPreviews = array_values($this->photoPreviews);
        } elseif ($type === 'photos') {
            // Handle removal of new photos
            switch ($index) {
                case 1:
                    $this->photo1 = null;
                    break;
                case 2:
                    $this->photo2 = null;
                    break;
                case 3:
                    $this->photo3 = null;
                    break;
                case 4:
                    $this->photo4 = null;
                    break;
            }
        }
    }

    public function save()
    {
        if ($this->isEdit) {
            $this->rules['slug'] = 'required|string|max:255|unique:products,slug,' . $this->product->id;

            foreach ($this->skus as $index => $sku) {
                $skuId = $sku['id'] ?? 'NULL';
                $this->rules["skus.$index.code"] = "required|string|max:50|unique:skus,code,$skuId";
            }
        }

        $this->validate();

        // Save or update product
        $this->product->name = $this->name;
        $this->product->slug = $this->slug;
        $this->product->save();

        $photos = [$this->photo1, $this->photo2, $this->photo3, $this->photo4];
        foreach ($photos as $photo) {
            if ($photo) {
                $path = $photo->store('products', 's3');
                $this->product->images()->create(['image_path' => $path]);
            }
        }

        // Handle SKUs
        foreach ($this->skus as $skuData) {
            $sku = Sku::updateOrCreate(
                ['id' => $skuData['id'] ?? null],
                [
                    'product_id' => $this->product->id,
                    'code' => $skuData['code'],
                    'price' => $skuData['price'],
                    'stock' => $skuData['stock'],
                ]
            );

            if (isset($skuData['attributes'])) {
                $sku->attributeOptions()->sync($skuData['attributes']);
            }
        }

        if (!empty($this->deleteSkuIds)) {
            Sku::whereIn('id', $this->deleteSkuIds)->delete();
        }

        session()->flash('success', 'Product and SKUs saved successfully!');
        return redirect()->route('products.index');
    }

    public function render()
    {
        return view('livewire.product-form');
    }
}
