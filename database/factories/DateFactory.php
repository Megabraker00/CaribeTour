<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Terminal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Date>
 */
class DateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products = Product::all();
        $terminals = Terminal::all();

        return [
            'product_id' => fake()->randomElement($products),
            'departure_date' => fake()->date('Y-m-d H:i:s'),
            'departure_terminal_id' => fake()->randomElement($terminals),
            'return_date' => fake()->date('Y-m-d H:i:s'),
            'return_terminal_id' => fake()->randomElement($terminals),
            'price' => fake()->randomFloat(2, 600, 200),
            'taxes' => fake()->randomFloat(2, 50, 100),
        ];
    }
}
