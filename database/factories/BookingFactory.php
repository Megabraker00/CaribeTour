<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'external_ref' => 'LOC-'.strtoupper(fake()->bothify('????????')),
            'client_id' => Client::factory(),
            'status_id' => Status::BOOKING_PENDING,
            'total_price' => 199.99,
            'currency' => 'EUR',
        ];
    }
}
