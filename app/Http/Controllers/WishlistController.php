<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        return auth()->user()->wishlists()->with('product')->get();
    }

    //! route
    public function store(Product $product)
    {
        $wishlist = auth()->user()->wishlists()->create([
            'product_id' => $product->id,
        ]);

        return response()->json($wishlist, 201);
    }

    //! route
    public function destroy(Product $product)
    {
        $wishlist = auth()->user()->wishlists()->where('product_id', $product->id)->firstOrFail();
        $wishlist->delete();

        return response()->json(null, 204);
    }
}
