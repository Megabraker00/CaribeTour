<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_public_pages_allow_stripe_payment_element_hosts(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('https://m.stripe.network', false);
        $response->assertSee('https://merchant-ui-api.stripe.com', false);
        $response->assertSee('https://r.stripe.com', false);
    }

    public function test_unknown_url_shows_the_branded_not_found_page(): void
    {
        $this->get('/esta-pagina-no-existe')
            ->assertNotFound()
            ->assertSee('Página no encontrada')
            ->assertSee('noindex, nofollow', false)
            ->assertSee('Ir al inicio')
            ->assertSee(route('destinos'), false)
            ->assertDontSee('aria-label="breadcrumb"', false);

        $this->getJson('/esta-pagina-no-existe')
            ->assertNotFound()
            ->assertJsonStructure(['message']);
    }
}
