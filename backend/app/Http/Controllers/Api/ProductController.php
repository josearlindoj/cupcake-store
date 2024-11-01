<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    // List all products
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'skus.attributeOptions', 'catalogs'])->get();

        return response()->json($products);
    }

    // Show a specific product
    public function show(string $slug): JsonResponse
    {
        $product = Product::with(['category', 'skus.attributeOptions', 'catalogs'])->where('slug', $slug)->first();

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => $product->category,
            'skus' => $product->skus->map(function ($sku) {
                return [
                    'id' => $sku->id,
                    'code' => $sku->code,
                    'price' => $sku->price,
                    'attribute_options' => $sku->attributeOptions->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'value' => $option->value,
                        ];
                    }),
                ];
            }),
            'catalogs' => $product->catalogs->map(function ($catalog) {
                return [
                    'id' => $catalog->id,
                    'name' => $catalog->name,
                    'subsection' => $catalog->pivot->subsection,
                ];
            }),
        ]);
    }

    // Store a new product
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json([
            'message' => 'Product created successfully.',
            'data' => $product,
        ], 201);
    }

    // Update a product
    public function update(ProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());

        return response()->json([
            'message' => 'Product updated successfully.',
            'data' => $product,
        ]);
    }

    // Delete a product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.',
        ]);
    }
}
