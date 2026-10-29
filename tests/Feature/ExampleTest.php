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
}
