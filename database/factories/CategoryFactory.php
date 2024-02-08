<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $states = State::where('stateable', Category::class )->get();
        $name = fake()->words(3, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'state_id' => fake()->randomElement($states),
        ];
    }
}
