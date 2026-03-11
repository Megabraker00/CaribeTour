<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::factory()->count(3)->forProduct()->create();
        Status::factory()->count(2)->forCategory()->create();
        Status::factory()->count(8)->forBooking()->create();
        Status::factory()->count(1)->forClient()->create();
        Status::factory()->count(3)->forPayment()->create();
        Status::factory()->count(2)->forSupplier()->create();
        Status::factory()->count(2)->forBlog()->create();
    }
}