<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Status;
use App\Models\Suplier;
use App\Models\Terminal;
use App\Models\Type;
use Database\Factories\ParentCategoryFactory;
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

        $this->call([
            TypeSeeder::class,
            StatusSeeder::class,
            CategorySeeder::class,
            SuplierSeeder::class,
            ProductSeeder::class,
            TerminalSeeder::class,
            ImageSeeder::class,
        ]);

        Type::factory(20)->create();
        Status::factory(20)->create();
        ParentCategoryFactory::factoryForModel('Category')->create();
        Category::factory(10)->create();
        Suplier::factory(10)->create();
        Product::factory(50)->create();
        Terminal::factory(10)->create();
        Itinerary::factory(100)->create();
    }
}
