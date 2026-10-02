<?php

namespace Database\Factories;

use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Terminal;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Segment>
 */
class SegmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departure = $this->faker->dateTimeBetween('+1 day', '+1 month');
        // Sumamos unas horas a la salida para la llegada
        $arrival = (clone $departure)->modify('+' . rand(2, 8) . ' hours');

        return [
            'itinerary_id' => Itinerary::factory(),
            'type_id' => Type::idFor(Product::class, Type::FLIGHT),
            'sort_order' => 1,
            'departure_date' => $departure,
            'departure_terminal_id' => Terminal::inRandomOrder()->first()?->id ?? Terminal::factory(),
            'origin' => $this->faker->city() . " (" . strtoupper($this->faker->lexify('???')) . ")",
            'arrival_date' => $arrival,
            'arrival_terminal_id' => Terminal::inRandomOrder()->first()?->id ?? Terminal::factory(),
            'destination' => $this->faker->city() . " (" . strtoupper($this->faker->lexify('???')) . ")",
        ];
    }
}
