<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'products' => new ProductCollection($this->whenLoaded('products')),
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->header('X-Powered-By', 'Malik');
        $response->header('X-Framework', 'Laravel');
    }
}
