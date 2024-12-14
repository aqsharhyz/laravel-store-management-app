<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use AuthorizesRequests;

    public function showCart()
    {
        return auth()->user()->carts()->with('product')->get();
    }

    public function store(Product $product, Request $request)
    {
        // $this->authorize('store', $product);

        $validated = $request->validate([
            // 'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = auth()->user()->carts()->create([
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
        ]);

        return response()->json($cart, 201);
    }

    public function update(Product $product, Request $request)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = auth()->user()->carts()->where('product_id', $product->id)->firstOrFail();
        $cart->update([
            'quantity' => $validated['quantity'],
        ]);

        return response()->json($cart);
    }

    public function destroy(Product $product)
    {
        $cart = auth()->user()->carts()->where('product_id', $product->id)->firstOrFail();
        $cart->delete();

        return response()->json(null, 204);
    }

    public function checkout()
    {
        // $carts = auth()->user()->carts()->with('product')->get();
        // $total = $carts->sum(fn($cart) => $cart->product->price * $cart->quantity);

        // // $this->authorize('checkout', $carts);

        // $order = auth()->user()->orders()->create([
        //     'total' => $total,
        // ]);

        // $carts->each(function ($cart) use ($order) {
        //     $order->orderDetails()->create([
        //         'product_id' => $cart->product_id,
        //         'quantity' => $cart->quantity,
        //         'price' => $cart->product->price,
        //     ]);
        //     $cart->delete();
        // });

        // return response()->json($order, 201);
    }
}
