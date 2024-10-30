<?php

namespace App\Livewire;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\Sku;
use Livewire\Component;

class ProductForm extends Component
{
    public Product $product;
    public array $skus = [];
    public string $name = '';
    public string $slug = '';
    public array $deleteSkuIds = [];
    public array $productAttributes = [];
    public bool $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products,slug',
        'skus.*.code' => 'required|string|max:50|unique:skus,code',
        'skus.*.price' => 'required|numeric|min:0',
        'skus.*.stock' => 'required|integer|min:0',
    ];

    public function mount($productId = null)
    {
        $this->productAttributes = Attribute::with('options')->get()->toArray();

        if ($productId) {
            $this->product = Product::with('skus.attributeOptions')->findOrFail($productId);
            $this->isEdit = true;
            $this->name = $this->product->name;
            $this->slug = $this->product->slug;
            $this->skus = $this->product->skus->map(function ($sku) {
                return [
                    'id' => $sku->id,
                    'code' => $sku->code,
                    'price' => $sku->price,
                    'stock' => $sku->stock,
                    'attributes' => $sku->attributeOptions->pluck('id')->toArray(),
                ];
            })->toArray();
        } else {
            $this->product = new Product();
            $this->skus = [];
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
        $this->skus = array_values($this->skus); // Reindexa o array
    }

    public function save()
    {
        $this->validate();

        $this->product->name = $this->name;
        $this->product->slug = $this->slug;
        $this->product->save();

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
