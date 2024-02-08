<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\State;
use App\Models\Suplier;
use App\Models\Category;
use App\Models\Product;
use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
        $categories = Category::all();
        $types = Type::where('typeable', Product::class)->get();
        $states = State::where('stateable', Product::class )->get();
        $suplier = Suplier::all();
        $name = fake()->name();
        $users = User::all();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category_id' => fake()->randomElement($categories),
            'type_id' => fake()->randomElement($types),
            'state_id' => fake()->randomElement($states),
            'suplier_id' => fake()->randomElement($suplier),
            'created_user_id' => fake()->randomElement($users),
        ];
    }
}
