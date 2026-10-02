<?php

namespace Database\Factories;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->sentence(4);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 9999),
            'content' => fake()->paragraphs(2, true),
            'status_id' => Status::idFor(\App\Models\Blog::class, Status::BLOG_PUBLISHED),
            'created_user_id' => User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status_id' => Status::idFor(\App\Models\Blog::class, Status::BLOG_DRAFT),
        ]);
    }
}
