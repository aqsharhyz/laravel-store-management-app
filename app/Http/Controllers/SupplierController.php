<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierCollection;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): SupplierCollection
    {
        $suppliers = Supplier::all();
        return SupplierCollection::make($suppliers);
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
    public function store(CreateSupplierRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $supplier = new Supplier($validated);
        $supplier->save();

        return (new SupplierResource($supplier))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier): SupplierResource
    {
        return new SupplierResource($supplier);
    }

    public function showProducts(Supplier $supplier): SupplierResource
    {
        $supplier->load('products');
        $supplier->load('products.category');
        $supplier->products->setHidden(['supplier_id']);
        return new SupplierResource($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): SupplierResource
    {
        $validated = $request->validated();

        $supplier->fill($validated);
        $supplier->save();

        return new SupplierResource($supplier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier): JsonResponse
    {
        $supplier->delete();
        return response()->json(['data' => true], 200);
    }
}
