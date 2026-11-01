<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class HttpErrorPageTest extends TestCase
{
    public function test_branded_error_pages_keep_the_site_layout(): void
    {
        $pages = [
            419 => 'La sesión ha caducado',
            429 => 'Demasiados intentos',
            500 => 'Algo no ha salido bien',
            503 => 'Estamos en mantenimiento',
        ];

        foreach ($pages as $status => $heading) {
            Route::get('/__error-'.$status, fn () => abort($status));

            $this->get('/__error-'.$status)
                ->assertStatus($status)
                ->assertSee((string) $status)
                ->assertSee($heading)
                ->assertSee('noindex, nofollow', false)
                ->assertSee('Ir al inicio')
                ->assertSee(route('destinos'), false)
                ->assertDontSee('aria-label="breadcrumb"', false);

            $this->getJson('/__error-'.$status)
                ->assertStatus($status)
                ->assertJsonStructure(['message']);
        }
    }
}
