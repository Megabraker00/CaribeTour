<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Position;
use App\Models\Product;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Status>
 */
class StatusFactory extends Factory
{
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
            Supplier::class,
        ];

        return [
            'name' => fake()->word(),
            'slug' => fake()->unique()->slug(2),
            'statusable' => fake()->randomElement($typeables),
            'is_system' => false,
        ];
    }

    public function forClient(): static
    {
        return $this->sequence(
            ['id' => 20, 'slug' => Status::CLIENT_ACTIVE, 'name' => 'Activo', 'statusable' => Client::class, 'is_system' => true],
        );
    }

    public function forProduct(): static
    {
        return $this->sequence(
            ['id' => 1, 'slug' => Status::PRODUCT_ACTIVE, 'name' => 'Activo', 'statusable' => Product::class, 'is_system' => true],
            ['id' => 2, 'slug' => Status::PRODUCT_NOT_ACTIVE, 'name' => 'Inactivo', 'statusable' => Product::class, 'is_system' => true],
            ['id' => 3, 'slug' => Status::PRODUCT_DRAFT, 'name' => 'Borrador', 'statusable' => Product::class, 'is_system' => true],
        );
    }

    public function forBooking(): static
    {
        return $this->sequence(
            ['id' => 10, 'slug' => Status::BOOKING_PENDING_PAYMENT, 'name' => 'Pendiente de Pago', 'statusable' => Booking::class, 'is_system' => true],
            ['id' => 11, 'slug' => Status::BOOKING_PAID, 'name' => 'Pagado', 'statusable' => Booking::class, 'is_system' => true],
            ['id' => 15, 'slug' => Status::BOOKING_CANCELLED, 'name' => 'Cancelado', 'statusable' => Booking::class, 'is_system' => true],
            ['id' => 12, 'slug' => Status::BOOKING_PENDING, 'name' => 'Pendiente', 'statusable' => Booking::class, 'is_system' => true],
            ['id' => 13, 'slug' => Status::BOOKING_CONFIRMED, 'name' => 'Confirmado', 'statusable' => Booking::class, 'is_system' => true],
            ['id' => 14, 'slug' => Status::BOOKING_COMPLETED, 'name' => 'Completado', 'statusable' => Booking::class, 'is_system' => true],
            ['id' => 16, 'slug' => Status::BOOKING_REFUNDED, 'name' => 'Reembolsado', 'statusable' => Booking::class, 'is_system' => true],
            ['id' => 17, 'slug' => Status::BOOKING_NO_SHOW, 'name' => 'No presentado', 'statusable' => Booking::class, 'is_system' => true],
        );
    }

    public function forPayment(): static
    {
        return $this->sequence(
            ['id' => 50, 'slug' => Status::PAYMENT_PENDING, 'name' => 'Pendiente de Pago', 'statusable' => Payment::class, 'is_system' => true],
            ['id' => 51, 'slug' => Status::PAYMENT_PAID, 'name' => 'Pagado', 'statusable' => Payment::class, 'is_system' => true],
            ['id' => 52, 'slug' => Status::PAYMENT_CANCELLED, 'name' => 'Cancelado', 'statusable' => Payment::class, 'is_system' => true],
        );
    }

    public function forSupplier(): static
    {
        return $this->sequence(
            ['id' => 40, 'slug' => Status::SUPPLIER_ACTIVE, 'name' => 'Activo', 'statusable' => Supplier::class, 'is_system' => true],
            ['id' => 41, 'slug' => Status::SUPPLIER_INACTIVE, 'name' => 'Inactivo', 'statusable' => Supplier::class, 'is_system' => true],
        );
    }

    public function forCategory(): static
    {
        return $this->sequence(
            ['id' => 30, 'slug' => Status::CATEGORY_ACTIVE, 'name' => 'Activo', 'statusable' => Category::class, 'is_system' => true],
            ['id' => 31, 'slug' => Status::CATEGORY_INACTIVE, 'name' => 'Inactivo', 'statusable' => Category::class, 'is_system' => true],
        );
    }

    public function forBlog(): static
    {
        return $this->sequence(
            ['id' => 60, 'slug' => Status::BLOG_PUBLISHED, 'name' => 'Publicado', 'statusable' => Blog::class, 'is_system' => true],
            ['id' => 61, 'slug' => Status::BLOG_DRAFT, 'name' => 'Borrador', 'statusable' => Blog::class, 'is_system' => true],
        );
    }

    public function forEmployee(): static
    {
        return $this->sequence(
            ['id' => 70, 'slug' => Status::EMPLOYEE_ACTIVE, 'name' => 'Activo', 'statusable' => Employee::class, 'is_system' => true],
            ['id' => 71, 'slug' => Status::EMPLOYEE_INACTIVE, 'name' => 'Inactivo', 'statusable' => Employee::class, 'is_system' => true],
        );
    }

    public function forPosition(): static
    {
        return $this->sequence(
            ['id' => 80, 'slug' => Status::POSITION_ACTIVE, 'name' => 'Activo', 'statusable' => Position::class, 'is_system' => true],
        );
    }
}
