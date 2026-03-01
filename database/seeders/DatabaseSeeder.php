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
        \App\Models\User::factory(10)->create();

        $this->call([
            TypeSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class,
            ProductSeeder::class,
            TerminalSeeder::class,
            ImageSeeder::class,
            ItinerarySeeder::class,
            SegmentSeeder::class,
        ]);
    }
}
