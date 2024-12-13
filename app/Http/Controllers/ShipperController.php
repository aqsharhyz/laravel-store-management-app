<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateShipperRequest;
use App\Http\Requests\UpdateShipperRequest;
use App\Http\Resources\ShipperCollection;
use App\Http\Resources\ShipperResource;
use App\Models\Shipper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ShipperCollection
    {
        $shippers = Shipper::all();
        return new ShipperCollection($shippers);
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
    public function store(CreateShipperRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $shipper = new Shipper($validated);
        $shipper->save();

        return (new ShipperResource($shipper))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipper $shipper): ShipperResource
    {
        return new ShipperResource($shipper);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipper $shipper)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipperRequest $request, Shipper $shipper): ShipperResource
    {
        $validated = $request->validated();

        $shipper->fill($validated);
        $shipper->save();

        return new ShipperResource($shipper);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipper $shipper): JsonResponse
    {
        $shipper->delete();
        return response()->json(['data' => true]);
    }
}
