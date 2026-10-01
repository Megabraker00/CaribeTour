<?php

namespace Database\Factories;

use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('+34#########'),
            'date_of_birth' => fake()->date(),
            'dni_passport' => strtoupper(fake()->bothify('########?')),
            'nationality' => 'ES',
            'status_id' => Status::CLIENT_ACTIVE,
        ];
    }
}
