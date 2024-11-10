<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CatalogController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/', function () {
    return redirect()->route('admin.login');
})->name('home');

Route::get('/admin', function () {
    return redirect()->route('admin.login');
})->name('admin.home');

Route::prefix('admin')->group(function () {
    Route::get('login', [AdminController::class, 'showLoginForm'])->name('admin.login');

    Route::post('login', [AdminController::class, 'login']);

    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');

    Route::middleware(['web', 'auth:admin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('attributes', AttributeController::class);
        Route::resource('catalogs', CatalogController::class);
    });
});
