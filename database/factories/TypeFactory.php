<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type>
 */
class TypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeables = [Client::class, Product::class, Booking::class, Payment::class, Employee::class];

        return [
            'name' => fake()->word(),
            'typeable' => fake()->randomElement($typeables),
        ];
    }
}
