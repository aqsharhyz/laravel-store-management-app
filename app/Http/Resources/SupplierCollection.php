<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SupplierCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'total' => count($this->collection),
            'data' => SupplierResource::collection($this->collection),
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->header('X-Powered-By', 'Malik');
        $response->header('X-Framework', 'Laravel');
    }
}
