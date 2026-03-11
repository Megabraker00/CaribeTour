<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Type;
use App\Models\Status;

class ProductMetadataSeeder extends Seeder
{
    public function run(): void
    {
        $tours = Product::where('type_id', Type::TOUR)
            ->where('status_id', Status::PRODUCT_ACTIVE)
            ->get();

        foreach ($tours as $tour) {

            $tour->metaData()->create([
                'meta_data' => [
                    //'description' => fake()->paragraph(3),
                    'description' => fake()->realText(700),
                    'includes' => fake()->randomElements([
                        'Guía profesional',
                        'Vuelos de ida y vuelta',
                        'Traslados aeropuerto - hotel - aeropuerto',
                        'Alojamiento en hotel 5 estrellas',
                        'Régimen de todo incluido',
                        'Seguro de viaje',
                        'Mapa turístico',
                        'Entradas a monumentos',
                        'Degustación gastronómica',
                        'Transporte en autobús',
                        'Mapa turístico',
                        'Botella de agua'
                    ], rand(3,5))
                ]
            ]);

        }
    }
}