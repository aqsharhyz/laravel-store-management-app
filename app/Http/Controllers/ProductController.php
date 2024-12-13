<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->get('page', 1);
        $products = Product::paginate(perPage: 10, page: $page);
        return ProductCollection::make($products);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): ProductCollection
    {
        $product->load('category');
        return new ProductCollection($product);
    }

    public function showProductsByCategory(Category $category): ProductCollection
    {
        $products = $category->products()->paginate(perPage: 10);
        return ProductCollection::make($products);
    }

    public function showProductsByTag(Tag $tag): ProductCollection
    {
        $products = $tag->products()->paginate(perPage: 10);
        return ProductCollection::make($products);
    }
}
