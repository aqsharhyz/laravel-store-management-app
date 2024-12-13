<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // protected $model;
    public function __construct()
    {
        // $this->model = new Category();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = request()->get('page', 1);
        $categories = Category::paginate(perPage: 10, page: $page);
        return CategoryCollection::make($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $slug = Str::slug($validated['name']);
        $validated['slug'] = $slug;

        $category = new Category($validated);
        $category->save();

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): CategoryResource
    {
        return CategoryResource::make($category);
    }

    /**
     * Display the specified resource with products.
     */
    public function showProducts(Category $category): CategoryResource
    {
        $category->load('products');
        // $category->load('products.supplier');
        return new CategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id, UpdateCategoryRequest $request): CategoryResource
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        $validated = $request->validated();

        if (isset($validated['name'])) {
            $slug = Str::slug($validated['name']);
            $validated['slug'] = $slug;
        }

        $category->fill($validated);
        $category->save();

        return CategoryResource::make($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $category = Category::where('id', $id)->first();
        if (!$category) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "message" => [
                        "not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        $category->delete();
        return response()->json(['data' => true])->setStatusCode(200);
    }
}
