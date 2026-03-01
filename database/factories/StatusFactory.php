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
use App\Models\Status;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $typeables = [
            Client::class,
            Product::class,
            Booking::class,
            Payment::class,
            Employee::class,
            User::class,
            Category::class,
            Suplier::class
        ];

        return [
            'name' => fake()->word(),
            'statusable' => fake()->randomElement($typeables),
        ];
    }

    public function forClient(): static
    {
        return $this->sequence(
            ['id' => Status::CLIENT_ACTIVE, 'name' => 'Activo', 'statusable' => Client::class],
        );
    }

    public function forProduct(): static
    {
        return $this->sequence(
            ['id' => Status::TOUR_ACTIVE, 'name' => 'Activo', 'statusable' => Product::class],
            ['id' => Status::TOUR_NOT_ACTIVE, 'name' => 'Inactivo', 'statusable' => Product::class],
            ['id' => Status::TOUR_DRAFT, 'name' => 'Borrador', 'statusable' => Product::class],
        );
    }

    public function forBooking(): static
    {
        return $this->sequence(
            ['id' => Status::BOOKING_PENDING_PAYMENT, 'name' => 'Pendiente de Pago', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_PAID, 'name' => 'Pagado', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_CANCELLED, 'name' => 'Cancelado', 'statusable' => Booking::class],
        );
    }

    public function forPayment(): static
    {
        return $this->sequence(
            ['id' => Status::PAYMENT_PENDING, 'name' => 'Pendiente de Pago', 'statusable' => Payment::class],
            ['id' => Status::PAYMENT_PAID, 'name' => 'Pagado', 'statusable' => Payment::class],
            ['id' => Status::PAYMENT_CANCELLED, 'name' => 'Cancelado', 'statusable' => Payment::class],
        );
    }    

    public function forSuplier(): static
    {
        return $this->sequence(
            ['id' => Status::SUPLIER_ACTIVE, 'name' => 'Activo', 'statusable' => Suplier::class],
            ['id' => Status::SUPLIER_INACTIVE, 'name' => 'Inactivo', 'statusable' => Suplier::class],
        );
    }

    public function forCategory(): static
    {
        return $this->sequence(
            ['id' => Status::CATEGORY_ACTIVE, 'name' => 'Activo', 'statusable' => Category::class],
            ['id' => Status::CATEGORY_INACTIVE, 'name' => 'Inactivo', 'statusable' => Category::class],
        );
    }
}
