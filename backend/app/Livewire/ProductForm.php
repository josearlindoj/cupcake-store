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
    public array $photos = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products,slug',
        'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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

            $this->photoPreviews = $this->product->images->pluck('image_path')->toArray();
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

    public function updatedPhotos()
    {
        $this->validateOnly('photos');

        $totalImages = count($this->photoPreviews) + count($this->photos);
        if ($totalImages > 6) {
            $this->addError('photos', 'You can upload a maximum of 6 images.');
            $this->photos = array_slice($this->photos, 0, 6 - count($this->photoPreviews));
        }
    }

    public function removeImage($index, $type = 'photos')
    {
        if ($type === 'previews' && isset($this->photoPreviews[$index])) {
            $imagePath = $this->photoPreviews[$index];
            Storage::disk('public')->delete($imagePath);
            $this->product->images()->where('image_path', $imagePath)->delete();
            unset($this->photoPreviews[$index]);
            $this->photoPreviews = array_values($this->photoPreviews);
        } elseif ($type === 'photos' && isset($this->photos[$index])) {
            unset($this->photos[$index]);
            $this->photos = array_values($this->photos);
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

        // Handle image upload and storage
        $totalImages = count($this->photoPreviews) + count($this->photos);
        if ($totalImages > 6) {
            $this->addError('photos', 'You can upload a maximum of 6 images.');
            return;
        }

        if (!empty($this->photos)) {
            foreach ($this->photos as $photo) {
                $path = $photo->store('products', 'public');
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
