<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email_personal' => fake()->unique()->safeEmail(),
            'email_company' => fake()->unique()->companyEmail(),
            'dni_passport' => strtoupper(fake()->bothify('########?')),
            'phone' => fake()->numerify('+34#########'),
            'position_id' => Position::factory(),
            'status_id' => Status::EMPLOYEE_ACTIVE,
            'created_user_id' => User::factory(),
        ];
    }
}
