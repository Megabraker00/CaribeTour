<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Itinerary;
use Illuminate\Database\Seeder;

class ItinerarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            Itinerary::factory()->count(4)->create([
                'product_id' => $product->id,
            ]);
        }
    }
}
