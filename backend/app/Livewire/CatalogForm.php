<?php

namespace App\Livewire;

use App\Models\Catalog;
use App\Models\Product;
use Livewire\Component;

class CatalogForm extends Component
{
    public $catalogId, $name, $description, $is_active = true, $products = [], $selectedProducts = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
        'selectedProducts' => 'array',
    ];

    public function mount($catalogId = null)
    {
        $this->products = Product::all();
        if ($catalogId) {
            $catalog = Catalog::with('products')->findOrFail($catalogId);
            $this->catalogId = $catalog->id;
            $this->name = $catalog->name;
            $this->description = $catalog->description;
            $this->is_active = $catalog->is_active;
            $this->selectedProducts = $catalog->products->pluck('id')->toArray();
        }
    }

    public function save()
    {
        $this->validate();

        $catalog = Catalog::updateOrCreate(
            ['id' => $this->catalogId],
            ['name' => $this->name, 'description' => $this->description, 'is_active' => $this->is_active]
        );

        $catalog->products()->sync($this->selectedProducts);

        session()->flash('success', 'Catalog saved successfully.');
        return redirect()->route('catalogs.index');
    }

    public function render()
    {
        return view('livewire.catalog-form');
    }
}
