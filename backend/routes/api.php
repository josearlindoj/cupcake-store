<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SKUController;
use App\Http\Controllers\Api\UserAddressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;

// User Registration Route
Route::post('/register', [RegisterController::class, 'register']);
// User Login Route
Route::post('/login', [LoginController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);
Route::get('/catalogs', [CatalogController::class, 'index']);

// Logout Route


Route::get('/products/image/{product}', [ProductController::class, 'showImageByName'])->name('products.image');

Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckTokenExpiry::class])->group(function () {
    // Product Management
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);

    // SKU Management
    Route::get('/products/{product}/skus', [SKUController::class, 'index']);
    Route::get('/products/{product}/skus/{sku}', [SKUController::class, 'show']);
    Route::post('/products/{product}/skus', [SKUController::class, 'store']);
    Route::put('/products/{product}/skus/{sku}', [SKUController::class, 'update']);
    Route::delete('/products/{product}/skus/{sku}', [SKUController::class, 'destroy']);

    //User Address
    Route::post('/user-addresses/upsert', [UserAddressController::class, 'upsert']);

    // Token Refresh Route
    Route::post('/refresh-token', [LoginController::class, 'refreshToken']);

    Route::post('/logout', [LoginController::class, 'logout']);
});
