<?php

namespace Database\Seeders;

use App\Models\Itinerary;
use App\Models\Segment;
use App\Models\Type;
use Illuminate\Database\Seeder;

class SegmentSeeder extends Seeder
{
    public function run(): void
    {
        $itineraries = Itinerary::all();

        foreach ($itineraries as $itinerary) {
            // Creamos 3 segmentos por cada itinerario para que tengan contenido
            Segment::factory()->create([
                'itinerary_id' => $itinerary->id,
                'type_id' => Type::FLIGHT,
                'sort_order' => 1,
                'origin' => 'MAD (Madrid)',
                'destination' => 'PUJ (Punta Cana)'
            ]);

            Segment::factory()->create([
                'itinerary_id' => $itinerary->id,
                'type_id' => Type::TRANSFER,
                'sort_order' => 2,
                'origin' => 'PUJ Airport',
                'destination' => 'Hotel Resort'
            ]);

            Segment::factory()->create([
                'itinerary_id' => $itinerary->id,
                'type_id' => Type::EXCURSION,
                'sort_order' => 3,
                'origin' => 'Hotel Resort',
                'destination' => 'Isla Saona'
            ]);
        }
    }
}