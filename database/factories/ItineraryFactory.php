<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Itinerary>
 */
class ItineraryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalStock = $this->faker->numberBetween(10, 50);

        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'total_stock' => $totalStock,
            'available_stock' => $this->faker->numberBetween(0, $totalStock),
            'price' => $this->faker->randomFloat(2, 200, 1000),
            'taxes' => $this->faker->randomFloat(2, 10, 50),
            'currency' => 'EUR',
        ];
    }
}
