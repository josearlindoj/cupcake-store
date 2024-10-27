<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
//            'category_id' => 'required|exists:categories,id',
        ]);

        // Check that either `new_skus` or `skus` is provided
        if (!$request->has('new_skus')) {
            return back()->withErrors(['sku' => 'Please add at least one SKU for the product.'])->withInput();
        }

            Log::debug('sku', $request->input('new_skus'));
        // Filter out empty SKU entries
        $newSkus = collect($request->input('new_skus', []))->filter(function ($sku) {
            return !empty($sku['sku_code']) && !empty($sku['price']) && !empty($sku['stock']);
        });

        // Check if there is at least one valid SKU
        if ($newSkus->isEmpty()) {
            return back()->withErrors(['sku' => 'Please add at least one SKU for the product.'])->withInput();
        }

        // Validate only the filtered `new_skus` entries
        $request->merge(['new_skus' => $newSkus->toArray()]);

        // Validate the `new_skus` fields if any entries are present
        if ($newSkus->isNotEmpty()) {
            $request->validate([
                'new_skus.*.sku_code' => 'required|string|unique:skus,sku_code|max:50',
                'new_skus.*.price' => 'required|numeric|min:0',
                'new_skus.*.stock' => 'required|integer|min:0',
            ]);
        }

        // Create the product
        $product = Product::create($validated);

        // Add the valid new SKUs to the product
        foreach ($newSkus as $skuData) {
            $product->skus()->create($skuData);
        }

        // Update existing SKUs if provided
        if ($request->has('skus')) {
            foreach ($request->input('skus') as $skuId => $skuData) {
                $product->skus()->where('id', $skuId)->update($skuData);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product and SKUs created successfully');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        $product->load('skus');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Basic validation for product fields
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
//            'category_id' => 'required|exists:categories,id',
        ]);

        // Update the product details
        $product->update($validated);

        // Validate and handle new SKUs
        $newSkus = collect($request->input('new_skus', []))->filter(function ($sku) {
            // Filter out any empty SKUs in new_skus
            return !empty($sku['sku_code']) && !empty($sku['price']) && !empty($sku['stock']);
        });

        if ($newSkus->isNotEmpty()) {
            $request->merge(['new_skus' => $newSkus->toArray()]);

            // Validate each new SKU
            $request->validate([
                'new_skus.*.sku_code' => 'required|string|unique:skus,sku_code|max:50',
                'new_skus.*.price' => 'required|numeric|min:0',
                'new_skus.*.stock' => 'required|integer|min:0',
            ]);

            // Add each new SKU to the product
            foreach ($newSkus as $skuData) {
                $product->skus()->create($skuData);
            }
        }

        // Update existing SKUs, excluding those marked for deletion
        if ($request->has('skus')) {
            $deleteSkuIds = $request->input('delete_skus', []);
            foreach ($request->input('skus') as $skuId => $skuData) {
                // Only update SKUs that are not marked for deletion
                if (!in_array($skuId, $deleteSkuIds)) {
                    $sku = $product->skus()->find($skuId);
                    if ($sku) {
                        $sku->update([
                            'sku_code' => $skuData['sku_code'],
                            'price' => $skuData['price'],
                            'stock' => $skuData['stock'],
                        ]);
                    }
                }
            }
        }

        // Delete SKUs that are marked for deletion
        if (!empty($deleteSkuIds)) {
            $product->skus()->whereIn('id', $deleteSkuIds)->delete();
        }

        return redirect()->route('products.index')->with('success', 'Product and SKUs updated successfully');
    }
}
