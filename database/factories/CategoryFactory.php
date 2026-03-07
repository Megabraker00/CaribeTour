<?php

namespace Database\Factories;

use App\Models\Status;
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
        $name = fake()->words(2, true);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'status_id' => Status::CATEGORY_ACTIVE,
            'parent_id' => null,
        ];
    }

    /**
     * Indicate that the model's default state should be a specific country.
     */
    public function countries(): static
    {
        return $this->sequence(
            ['id' => 1, 'name' => 'República Dominicana', 'slug' => 'republica-dominicana'],
            ['id' => 2, 'name' => 'México', 'slug' => 'mexico'],
            ['id' => 3, 'name' => 'Cuba', 'slug' => 'cuba'],
            ['id' => 4, 'name' => 'Colombia', 'slug' => 'colombia'],
        );
    }
}
