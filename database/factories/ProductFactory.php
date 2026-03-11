<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Status;
use App\Models\Supplier;
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
        $suppliers = Supplier::all();
        $users = User::all();
        $name = fake()->randomElement([
            'Tour por la ciudad',
            'Excursión a la playa',
            'Hotel en el centro',
            'Hotel frente al mar',
            'Hotel todo incluido',
            'Hotel boutique',
            'Hotel de lujo',
            'Hotel económico',
            'Seguro de viaje',
            'Crucero por el Caribe',
            'Vuelo a Cancún',
            'Traslado al aeropuerto',
            'Free Tour por el casco antiguo'
        ]);

        $name .= '-' . fake()->unique()->numberBetween(1, 1000);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'category_id' => $categories->isNotEmpty() ? fake()->randomElement($categories)->id : Category::factory()->create()->id,
            'type_id' => $types->isNotEmpty() ? fake()->randomElement($types)->id : Type::factory()->create(['typeable' => Product::class])->id,
            'status_id' => $states->isNotEmpty() ? fake()->randomElement($states)->id : Status::factory()->create(['statusable' => Product::class])->id,
            'supplier_id' => $suppliers->isNotEmpty() ? fake()->randomElement($suppliers)->id : Supplier::factory()->create()->id,
            'created_user_id' => $users->isNotEmpty() ? fake()->randomElement($users)->id : User::factory()->create()->id,
        ];
    }

    public function tour(): static
    {
        return $this->state(fn () => [
            'type_id' => Type::TOUR,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status_id' => Status::PRODUCT_ACTIVE,
        ]);
    }
}
