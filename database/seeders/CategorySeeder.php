<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Category::factory(10)->create(); // Crea 10 categorías aleatorias

        Category::factory()->count(4)->countries()->create(); // Crea las categorías de países predefinidas
        $this->createSubCategories(1, ['Santo Domingo', 'Punta Cana', 'Puerto Plata', 'Samaná', 'La Romana', 'Bayahibe', 'Bávaro', 'Las Terrenas']);
        $this->createSubCategories(2, ['Cancún', 'Playa del Carmen', 'Tulum', 'Los Cabos', 'Puerto Vallarta', 'Acapulco']);
        $this->createSubCategories(3, ['La Habana', 'Varadero', 'Santiago de Cuba', 'Cayo Cono', 'Trinidad']);
        $this->createSubCategories(4, ['Cartagena', 'Bogotá', 'Medellín', 'Cali', 'Santa Marta']);
    }

    private function createSubCategories(int $parentId, array $names): void
    {
        foreach ($names as $name) {
            Category::factory()->create([
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
                'parent_id' => $parentId,
                'status_id' => \App\Models\Status::CATEGORY_ACTIVE,
            ]);
        }
    }
}