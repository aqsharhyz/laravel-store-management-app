<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::get('categories/{category}/products', [ProductController::class, 'showProductsByCategory']);
Route::get('tags/{tag}/products', [ProductController::class, 'showProductsByTag']);

Route::group(['middleware' => 'auth'], function () {
    Route::resource('wishlists', WishlistController::class)->only(['index', 'store', 'destroy']);

    Route::get('cart', [ProductController::class, 'showCart']);
    Route::post('/products/{product}/cart', [CartController::class, 'store']);
    Route::patch('cart/{product}', [CartController::class, 'update']);
    Route::delete('cart/{product}', [CartController::class, 'destroy']);
    Route::post('cart/checkout', [CartController::class, 'checkout']);

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'store']);
    Route::get('orders/{order}/payment', [OrderController::class, 'showPayment']);
    Route::get('orders/{order}/shipment', [OrderController::class, 'showShipment']);
});

// Route::get('suppliers/{supplier}/products', [SupplierController::class, 'showProducts']);