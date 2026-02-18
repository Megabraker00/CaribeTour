<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Crea 5 imágenes por producto para los primeros 8 productos,
     * usando archivos de public/images/tours/
     */
    public function run(): void
    {
        $toursPath = public_path('images/tours');

        if (! File::isDirectory($toursPath)) {
            File::makeDirectory($toursPath, 0755, true);
        }

        $imagePaths = $this->getImagePaths($toursPath);
        $uploadedBy = User::first() ?? User::factory()->create();

        $products = Product::query()
            ->orderBy('id')
            ->limit(8)
            ->get();

        if ($products->isEmpty()) {
            $this->command->warn('No hay productos en la base de datos. Ejecuta ProductSeeder antes.');

            return;
        }

        foreach ($products as $index => $product) {
            for ($i = 0; $i < 5; $i++) {
                $path = $imagePaths[$i % count($imagePaths)];
                $name = pathinfo($path, PATHINFO_FILENAME) . '-' . ($i + 1);

                $product->images()->create([
                    'name' => $name,
                    'path' => $path,
                    'is_main' => $i === 0,
                    'uploaded_user_id' => $uploadedBy->id,
                ]);
            }
        }

        $this->command->info('Creadas ' . (5 * $products->count()) . ' imágenes para ' . $products->count() . ' productos.');
    }

    /**
     * Obtiene rutas relativas (desde public) de imágenes en images/tours/.
     * Si no hay archivos, devuelve 5 rutas por defecto (tour-1.jpg ... tour-5.jpg).
     */
    private function getImagePaths(string $toursPath): array
    {
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $files = [];

        if (File::isDirectory($toursPath)) {
            foreach (File::files($toursPath) as $file) {
                if (in_array(strtolower($file->getExtension()), $extensions)) {
                    $files[] = 'images/tours/' . $file->getFilename();
                }
            }
        }

        if (empty($files)) {
            return [
                'images/tours/tour-1.jpg',
                'images/tours/tour-2.jpg',
                'images/tours/tour-3.jpg',
                'images/tours/tour-4.jpg',
                'images/tours/tour-5.jpg',
            ];
        }

        return array_values($files);
    }
}
