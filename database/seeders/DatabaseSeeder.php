<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Date;
use App\Models\Product;
use App\Models\State;
use App\Models\Suplier;
use App\Models\Terminal;
use App\Models\Type;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //$this->call(TypeSeeder::class);
        Type::factory(20)->create();
        State::factory(20)->create();
        Category::factory(10)->create();
        Suplier::factory(10)->create();
        Product::factory(50)->create();
        Terminal::factory(10)->create();
        Date::factory(100)->create();
    }
}
