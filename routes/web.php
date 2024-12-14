<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('wishlists', WishlistController::class)->only(['index', 'store', 'destroy']);

    Route::get('carts', [CartController::class, 'showCart']);
    Route::post('/products/{product}/cart', [CartController::class, 'store']);
    Route::patch('carts/{product}', [CartController::class, 'update']);
    Route::delete('carts/{product}', [CartController::class, 'destroy']);
    Route::post('carts/checkout', [CartController::class, 'checkout']);

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'create', 'store']);
    Route::get('orders/{order}/payment', [OrderController::class, 'showPayment']);
    Route::get('orders/{order}/shipment', [OrderController::class, 'showShipment']);
});

Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::get('categories/{category}/products', [ProductController::class, 'showProductsByCategory']);
Route::get('tags/{tag}/products', [ProductController::class, 'showProductsByTag']);

// Route::get('suppliers/{supplier}/products', [SupplierController::class, 'showProducts']);

require __DIR__ . '/auth.php';
