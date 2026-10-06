<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->admin()->create([
            'name' => 'administrador',
            'email' => 'admin@caribetour.es',
        ]);
        \App\Models\User::factory()->agent()->count(2)->create();
        \App\Models\User::factory()->viewer()->create();

        $this->call([
            TypeSeeder::class,
            StatusSeeder::class,
            PositionSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            ProductMetadataSeeder::class,
            TerminalSeeder::class,
            ImageSeeder::class,
            ItinerarySeeder::class,
            SegmentSeeder::class,
        ]);
    }
}
