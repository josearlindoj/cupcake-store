<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // List all products
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'skus.attributeOptions', 'catalogs', 'images'])->get();

        return response()->json($products);
    }

    // Show a specific product
    public function show(string $slug): JsonResponse
    {
        $product = Product::with([
            'category',
            'skus.attributeOptions.attribute',
            'catalogs',
            'images'
        ])->where('slug', $slug)->first();

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'category' => $product->category,
            'images' => $product->images,
            'skus' => $product->skus->map(function ($sku) {
                return [
                    'id' => $sku->id,
                    'code' => $sku->code,
                    'price' => $sku->price,
                    'attribute_options' => $sku->attributeOptions->map(function ($option) {
                        return [
                            'options' => $option->attribute->options,
                            'variants' => $option->attribute
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

    public function showImageByName($filename)
    {
        $productImage = ProductImage::where('image_path', 'like', "%/{$filename}")->first();

        if (!$productImage) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Generate the full URL based on `image_path`
        $imageUrl = asset("storage/{$productImage->image_path}");

        return response()->json([
            'image_url' => $imageUrl
        ]);

    }
}
