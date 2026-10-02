<?php

namespace Database\Seeders;

use App\Models\Position;
use App\Models\Status;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Guía', 'Comercial', 'Administración'] as $name) {
            Position::query()->firstOrCreate(
                ['name' => $name],
                ['status_id' => Status::POSITION_ACTIVE]
            );
        }
    }
}
