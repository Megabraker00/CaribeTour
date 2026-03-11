<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Status;
use App\Models\Blog;

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
            Supplier::class
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
            ['id' => Status::PRODUCT_ACTIVE, 'name' => 'Activo', 'statusable' => Product::class],
            ['id' => Status::PRODUCT_NOT_ACTIVE, 'name' => 'Inactivo', 'statusable' => Product::class],
            ['id' => Status::PRODUCT_DRAFT, 'name' => 'Borrador', 'statusable' => Product::class],
        );
    }

    public function forBooking(): static
    {
        return $this->sequence(
            ['id' => Status::BOOKING_PENDING_PAYMENT, 'name' => 'Pendiente de Pago', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_PAID, 'name' => 'Pagado', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_CANCELLED, 'name' => 'Cancelado', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_PENDING, 'name' => 'Pendiente', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_CONFIRMED, 'name' => 'Confirmado', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_COMPLETED, 'name' => 'Completado', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_REFUNDED, 'name' => 'Reembolsado', 'statusable' => Booking::class],
            ['id' => Status::BOOKING_NO_SHOW, 'name' => 'No presentado', 'statusable' => Booking::class],
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

    public function forSupplier(): static
    {
        return $this->sequence(
            ['id' => Status::SUPPLIER_ACTIVE, 'name' => 'Activo', 'statusable' => Supplier::class],
            ['id' => Status::SUPPLIER_INACTIVE, 'name' => 'Inactivo', 'statusable' => Supplier::class],
        );
    }

    public function forCategory(): static
    {
        return $this->sequence(
            ['id' => Status::CATEGORY_ACTIVE, 'name' => 'Activo', 'statusable' => Category::class],
            ['id' => Status::CATEGORY_INACTIVE, 'name' => 'Inactivo', 'statusable' => Category::class],
        );
    }

    public function forBlog(): static
    {
        return $this->sequence(
            ['id' => Status::BLOG_PUBLISHED, 'name' => 'Publicado', 'statusable' => Blog::class],
            ['id' => Status::BLOG_DRAFT, 'name' => 'Borrador', 'statusable' => Blog::class],
        );
    }
}
