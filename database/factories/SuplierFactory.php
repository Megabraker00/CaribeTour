<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\State;
use App\Models\Suplier;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Suplier>
 */
class SuplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $states = State::where('stateable', Suplier::class )->get();
        return [
            'name' => fake()->company(),
            'state_id' => fake()->randomElement($states),
        ];
    }
}
