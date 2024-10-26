<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;

Route::prefix('admin')->group(function () {
    // Show login form
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('admin.login');

    // Handle admin login
    Route::post('login', [AdminController::class, 'login']);

    // Admin logout
    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');

    // Protect dashboard with admin guard
    Route::middleware(['web', 'auth:admin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
    });
});
