<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Status;
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
        $states = Status::where('statusable', Product::class)->get();
        $supliers = Suplier::all();
        $users = User::all();
        $name = fake()->name();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category_id' => $categories->isNotEmpty() ? fake()->randomElement($categories)->id : Category::factory()->create()->id,
            'type_id' => $types->isNotEmpty() ? fake()->randomElement($types)->id : Type::factory()->create(['typeable' => Product::class])->id,
            'status_id' => $states->isNotEmpty() ? fake()->randomElement($states)->id : Status::factory()->create(['statusable' => Product::class])->id,
            'suplier_id' => $supliers->isNotEmpty() ? fake()->randomElement($supliers)->id : Suplier::factory()->create()->id,
            'created_user_id' => $users->isNotEmpty() ? fake()->randomElement($users)->id : User::factory()->create()->id,
        ];
    }
}
