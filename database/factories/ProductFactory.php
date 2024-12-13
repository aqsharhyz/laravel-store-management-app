<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->randomNumber(5),
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 1000000),
            'weight' => $this->faker->randomFloat(2, 1, 100),
            'stock' => $this->faker->randomNumber(3),
            'product_image' => $this->faker->imageUrl(),
            'category_id' => $this->faker->numberBetween(1, 5),
            'supplier_id' => $this->faker->numberBetween(1, 5),
        ];
    }
}
