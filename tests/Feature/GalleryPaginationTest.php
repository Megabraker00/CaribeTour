<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_pagination_uses_the_site_style(): void
    {
        $user = User::factory()->create();

        foreach (range(1, 13) as $number) {
            Image::query()->create([
                'name' => "Foto $number",
                'alt' => 'Foto de la galería',
                'is_main' => false,
                'path' => "images/foto-$number.jpg",
                'imageable_type' => Product::class,
                'imageable_id' => 1,
                'uploaded_user_id' => $user->id,
            ]);
        }

        $this->get(route('galeria'))
            ->assertOk()
            ->assertSee('site-pagination', false)
            ->assertSee('1–12 de 13', false)
            ->assertSee('aria-current="page"', false)
            ->assertSee('aria-label="Siguiente"', false)
            ->assertDontSee('Mostrando', false);
    }
}
