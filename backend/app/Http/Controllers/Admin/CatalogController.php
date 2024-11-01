<?php

namespace App\Http\Controllers\Admin;

use App\Models\Catalog;

class CatalogController
{
    public function index()
    {
        $attributes = Catalog::all();
        return view('admin.catalogs.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.catalogs.create');
    }

    public function edit($id)
    {
        return view('admin.catalogs.edit', compact('id'));
    }
}
