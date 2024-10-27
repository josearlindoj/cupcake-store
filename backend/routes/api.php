<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;

// User Registration Route
Route::post('/register', [RegisterController::class, 'register']);
// User Login Route
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:sanctum', \App\Http\Middleware\CheckTokenExpiry::class])->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
    });

    // Logout Route
    Route::post('/logout', [LoginController::class, 'logout']);
    // Token Refresh Route
    Route::post('/refresh-token', [LoginController::class, 'refreshToken']);
});
