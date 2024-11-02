<?php

namespace App\Livewire;

use App\Models\Catalog;
use Livewire\Component;

class CatalogList extends Component
{
    public function render()
    {
        return view('livewire.catalog-list', [
            'catalogs' => Catalog::with('products')->get()
        ]);
    }
}
