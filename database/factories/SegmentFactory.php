<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Itinerary;
use App\Models\Terminal;

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
            'type_id' => $this->faker->numberBetween(1, 8), // IDs de TOUR a FREETOUR
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
