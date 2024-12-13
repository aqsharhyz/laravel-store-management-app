<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Shipper;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'buyer_name' => $this->faker->name,
            'order_date' => $this->faker->dateTimeThisYear(),
            'required_date' => $this->faker->dateTimeThisYear('+ 10 days'),
            'shipped_date' => $this->faker->dateTimeThisYear('+ 15 days'),
            'shipping_name' => $this->faker->name,
            'shipping_address' => $this->faker->address,
            'shipping_phone' => $this->faker->phoneNumber,
            'shipper_name' => Shipper::all()->random()->name,
            'comments' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['Shipped', 'Delivered', 'Cancelled', 'On Hold', 'Disputed']),
            'total_quantity' => $this->faker->numberBetween(1, 20),
            'total_weight' => $this->faker->numberBetween(1, 20),
            'total_product_amount' => $this->faker->randomFloat(2, 100, 1000),
            'total_shipping_cost' => $this->faker->randomFloat(2, 10, 100),
            'total_shopping_amount' => $this->faker->randomFloat(2, 110, 1100),
            'service_charge' => $this->faker->randomFloat(2, 1, 10),
            'total_amount' => $this->faker->randomFloat(2, 111, 1111),
        ];
    }
}
