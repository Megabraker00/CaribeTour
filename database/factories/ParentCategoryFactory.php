<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class ParentCategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $states = Status::where('statusable', Category::class )->get();
        $name = fake()->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'status_id' => fake()->randomElement($states),
        ];
    }
}
