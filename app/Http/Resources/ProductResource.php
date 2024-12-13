<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'weight' => $this->weight,
            'product_image' => $this->product_image,
            'category' => $this->whenLoaded('category', function () {
                return $this->category->name;
            }),
            'supplier' => $this->whenLoaded('supplier', function () {
                return $this->supplier->name;
            }),
        ];
    }
}
