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
    
    /**
     * Indica que el tipo pertenece al modelo Product, con nombres específicos para cada tipo de producto.
     */
    public function forProduct(): static
    {
        return $this->sequence(
            ['id' => Type::TOUR, 'name' => 'Tour', 'typeable' => Product::class],
            ['id' => Type::EXCURSION, 'name' => 'Excursion', 'typeable' => Product::class],
            ['id' => Type::HOTEL, 'name' => 'Hotel', 'typeable' => Product::class],
            ['id' => Type::INSURANCE, 'name' => 'Seguro', 'typeable' => Product::class],
            ['id' => Type::CRUISE, 'name' => 'Crucero', 'typeable' => Product::class],
            ['id' => Type::FLIGHT, 'name' => 'Vuelo', 'typeable' => Product::class],
            ['id' => Type::TRANSFER, 'name' => 'Traslado', 'typeable' => Product::class],
            ['id' => Type::FREETOUR, 'name' => 'Free Tour', 'typeable' => Product::class],
        );
    }

    /**
     * Indica que el tipo pertenece al modelo Payment, con nombres específicos para cada tipo de pago.
     */
    public function forPayment(): static
    {
        return $this->sequence(
            ['id' => Type::CARD, 'name' => 'Tarjeta', 'typeable' => Payment::class],
            ['id' => Type::MONETARY_TRANSFER, 'name' => 'Transferencia', 'typeable' => Payment::class],
            ['id' => Type::STRIPE, 'name' => 'Stripe', 'typeable' => Payment::class],
            ['id' => Type::PAYPAL, 'name' => 'Paypal', 'typeable' => Payment::class],
            ['id' => Type::CASH, 'name' => 'Efectivo', 'typeable' => Payment::class],
        );
    }

    /**
     * Indica que el tipo pertenece al modelo Client, con nombres específicos para cada tipo de cliente.
     */
    public function forPassenger(): static
    {
        return $this->sequence(
            ['id' => Type::ADULT, 'name' => 'Adulto', 'typeable' => Passenger::class],
            ['id' => Type::CHILD, 'name' => 'Niño/a', 'typeable' => Passenger::class],
            ['id' => Type::INFANT, 'name' => 'Bebé', 'typeable' => Passenger::class],
            ['id' => Type::SENIOR, 'name' => 'Mayor', 'typeable' => Passenger::class],
        );
    }
}
