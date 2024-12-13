<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer_id' => $this->user_id,
            'buyer_name' => $this->buyer_name,
            'order_date' => $this->order_date,
            'required_date' => $this->required_date,
            'shipped_date' => $this->shipped_date,
            'shipping_name' => $this->shipping_name,
            'shipping_address' => $this->shipping_address,
            'shipping_phone' => $this->shipping_phone,
            'shipper_name' => $this->shipper_name,
            'comments' => $this->comments,
            'status' => $this->status,
            'total_quantity' => $this->total_quantity,
            'total_weight' => $this->total_weight,
            'total_product_amount' => $this->total_product_amount,
            'total_shipping_cost' => $this->total_shipping_cost,
            'total_shopping_amount' => $this->total_shopping_amount,
            'service_charge' => $this->service_charge,
            'total_amount' => $this->total_amount,
        ];
    }
}
