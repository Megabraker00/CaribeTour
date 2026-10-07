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

        if (!Status::query()->where('statusable', \App\Models\Payment::class)->where('slug', Status::PAYMENT_REFUNDED)->exists()) {
            Status::query()->create([
                'id' => 53,
                'name' => 'Reembolsado',
                'slug' => Status::PAYMENT_REFUNDED,
                'statusable' => \App\Models\Payment::class,
                'is_system' => true,
            ]);
        }
        Status::factory()->count(2)->forSupplier()->create();
        Status::factory()->count(2)->forBlog()->create();

        if (!Status::query()->where('statusable', \App\Models\Employee::class)->where('slug', Status::EMPLOYEE_ACTIVE)->exists()) {
            Status::factory()->count(2)->forEmployee()->create();
        }

        if (!Status::query()->where('statusable', \App\Models\Position::class)->where('slug', Status::POSITION_ACTIVE)->exists()) {
            Status::factory()->count(1)->forPosition()->create();
        }
    }
}
