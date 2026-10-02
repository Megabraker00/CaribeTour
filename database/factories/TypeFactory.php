<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Type;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Type>
 */
class TypeFactory extends Factory
{
    public function definition(): array
    {
        $typeables = [Client::class, Product::class, Booking::class, Payment::class, Employee::class];

        return [
            'name' => fake()->word(),
            'slug' => fake()->unique()->slug(2),
            'typeable' => fake()->randomElement($typeables),
            'is_system' => false,
        ];
    }

    public function forProduct(): static
    {
        return $this->sequence(
            ['id' => 1, 'slug' => Type::TOUR, 'name' => 'Tour', 'typeable' => Product::class, 'is_system' => true],
            ['id' => 2, 'slug' => Type::EXCURSION, 'name' => 'Excursion', 'typeable' => Product::class, 'is_system' => true],
            ['id' => 3, 'slug' => Type::HOTEL, 'name' => 'Hotel', 'typeable' => Product::class, 'is_system' => true],
            ['id' => 4, 'slug' => Type::INSURANCE, 'name' => 'Seguro', 'typeable' => Product::class, 'is_system' => true],
            ['id' => 5, 'slug' => Type::CRUISE, 'name' => 'Crucero', 'typeable' => Product::class, 'is_system' => true],
            ['id' => 6, 'slug' => Type::FLIGHT, 'name' => 'Vuelo', 'typeable' => Product::class, 'is_system' => true],
            ['id' => 7, 'slug' => Type::TRANSFER, 'name' => 'Traslado', 'typeable' => Product::class, 'is_system' => true],
            ['id' => 8, 'slug' => Type::FREETOUR, 'name' => 'Free Tour', 'typeable' => Product::class, 'is_system' => true],
        );
    }

    public function forPayment(): static
    {
        return $this->sequence(
            ['id' => 10, 'slug' => Type::PAID_BY_CARD, 'name' => 'Tarjeta', 'typeable' => Payment::class, 'is_system' => true],
            ['id' => 11, 'slug' => Type::MONETARY_TRANSFER, 'name' => 'Transferencia', 'typeable' => Payment::class, 'is_system' => true],
            ['id' => 12, 'slug' => Type::PAID_BY_STRIPE, 'name' => 'Stripe', 'typeable' => Payment::class, 'is_system' => true],
            ['id' => 13, 'slug' => Type::PAID_BY_PAYPAL, 'name' => 'Paypal', 'typeable' => Payment::class, 'is_system' => true],
            ['id' => 14, 'slug' => Type::PAID_BY_CASH, 'name' => 'Efectivo', 'typeable' => Payment::class, 'is_system' => true],
        );
    }

    public function forPassenger(): static
    {
        return $this->sequence(
            ['id' => 22, 'slug' => Type::ADULT, 'name' => 'Adulto', 'typeable' => Passenger::class, 'is_system' => true],
            ['id' => 21, 'slug' => Type::CHILD, 'name' => 'Niño/a', 'typeable' => Passenger::class, 'is_system' => true],
            ['id' => 20, 'slug' => Type::INFANT, 'name' => 'Bebé', 'typeable' => Passenger::class, 'is_system' => true],
            ['id' => 23, 'slug' => Type::SENIOR, 'name' => 'Mayor', 'typeable' => Passenger::class, 'is_system' => true],
        );
    }
}
