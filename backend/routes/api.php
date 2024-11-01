<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SKUController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;

// User Registration Route
Route::post('/register', [RegisterController::class, 'register']);
// User Login Route
Route::post('/login', [LoginController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/catalogs', [CatalogController::class, 'index']);

Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckTokenExpiry::class])->group(function () {
    // Product Management
    Route::post('/products', [ProductController::class, 'store']); // Create a new product
    Route::put('/products/{product}', [ProductController::class, 'update']); // Update a product
    Route::delete('/products/{product}', [ProductController::class, 'destroy']); // Delete a product

    // SKU Management
    Route::get('/products/{product}/skus', [SKUController::class, 'index']); // List SKUs of a product
    Route::get('/products/{product}/skus/{sku}', [SKUController::class, 'show']); // Show a specific SKU
    Route::post('/products/{product}/skus', [SKUController::class, 'store']); // Create a new SKU
    Route::put('/products/{product}/skus/{sku}', [SKUController::class, 'update']); // Update a SKU
    Route::delete('/products/{product}/skus/{sku}', [SKUController::class, 'destroy']); // Delete a SKU

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout']);
    // Token Refresh Route
    Route::post('/refresh-token', [LoginController::class, 'refreshToken']);
});
