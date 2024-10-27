<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SKU;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\SKURequest;

class SKUController extends Controller
{
    // List all SKUs for a product
    public function index($productId)
    {
        $skus = SKU::where('product_id', $productId)->get();

        return response()->json([
            'data' => $skus,
        ]);
    }

    // Show a specific SKU
    public function show($productId, $skuId)
    {
        $sku = SKU::where('product_id', $productId)->findOrFail($skuId);

        return response()->json([
            'data' => $sku,
        ]);
    }

    // Store a new SKU
    public function store(SKURequest $request, $productId)
    {
        $product = Product::findOrFail($productId);

        $skuData = $request->validated();
        $skuData['product_id'] = $productId;

        $sku = SKU::create($skuData);

        return response()->json([
            'message' => 'SKU created successfully.',
            'data' => $sku,
        ], 201);
    }

    // Update a SKU
    public function update(SKURequest $request, $productId, $skuId)
    {
        $sku = SKU::where('product_id', $productId)->findOrFail($skuId);

        $sku->update($request->validated());

        return response()->json([
            'message' => 'SKU updated successfully.',
            'data' => $sku,
        ]);
    }

    // Delete a SKU
    public function destroy($productId, $skuId)
    {
        $sku = SKU::where('product_id', $productId)->findOrFail($skuId);
        $sku->delete();

        return response()->json([
            'message' => 'SKU deleted successfully.',
        ]);
    }
}
