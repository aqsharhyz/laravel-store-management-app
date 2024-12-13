<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $order_id = Order::all()->random()->id;
        $product_id = Product::all()->random()->id;
        return [
            'order_id' => $order_id,
            'product_id' => $product_id,
            'product_name' => Product::find($product_id)->name,
            'product_price_each' => Product::find($product_id)->price,
            'product_weight' => Product::find($product_id)->weight,
            'quantity_ordered' => $this->faker->numberBetween(1, 10),
        ];
    }
}
