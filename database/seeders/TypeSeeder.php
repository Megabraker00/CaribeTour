<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::factory()->count(8)->forProduct()->create();
        Type::factory()->count(5)->forPayment()->create();
        Type::factory()->count(4)->forPassenger()->create();
    }
}