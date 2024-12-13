<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShipperController;
use App\Http\Controllers\SupplierController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('categories', [CategoryController::class, 'index']);
// Route::post('categories', [CategoryController::class, 'store']);
// Route::get('categories/{category}', [CategoryController::class, 'show']);
// Route::patch('categories/{category}', [CategoryController::class, 'update']);
// Route::delete('categories/{category}', [CategoryController::class, 'destroy']);

Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('products', ProductController::class);
Route::resource('shippers', ShipperController::class);
Route::resource('orders', OrderController::class);

Route::get('categories/{category}/products', [CategoryController::class, 'showProducts']);
Route::get('suppliers/{supplier}/products', [SupplierController::class, 'showProducts']);
