<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Terminal;
use Carbon\Carbon;
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
        $products = Product::all();
        $terminals = Terminal::all();

        //$departureDate = fake()->date('Y-m-d H:i:s');
        $departureDate = fake()->dateTimeBetween('2023-01-01', '2023-12-31');
        $carbonReturnDate = Carbon::parse($departureDate);
        $arrivalDate = $carbonReturnDate->addDays(7)->format('Y-m-d H:i:s');

        return [
            'product_id' => fake()->randomElement($products),
            'departure_date' => $departureDate,
            'departure_terminal_id' => fake()->randomElement($terminals),
            'arrival_date' => $arrivalDate,
            'arrival_terminal_id' => fake()->randomElement($terminals),
            'price' => fake()->randomFloat(2, 600, 200),
            'taxes' => fake()->randomFloat(2, 50, 100),
        ];
    }
}
