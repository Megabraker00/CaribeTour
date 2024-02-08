<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Suplier;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\State>
 */
class StateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeables = [Client::class, Product::class, Booking::class, Payment::class, Employee::class, User::class, Category::class, Suplier::class];

        return [
            'name' => fake()->word(),
            'stateable' => fake()->randomElement($typeables),
        ];
    }
}
