<?php

namespace Tests\Feature;

use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestRateLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
    }

    public function test_login_locks_one_account_after_five_failures(): void
    {
        $user = User::factory()->create();

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->from(route('login'))
                ->post('/login', [
                    'email' => $user->email,
                    'password' => 'wrong-password',
                ])
                ->assertRedirect(route('login'));

            $this->assertStringNotContainsString(
                'Demasiados intentos de acceso',
                session('errors')->first('email')
            );
        }

        $this->from(route('login'))
            ->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'));

        $this->assertStringContainsString(
            'Demasiados intentos de acceso',
            session('errors')->first('email')
        );
    }

    public function test_login_locks_an_ip_after_twenty_failures_with_different_emails(): void
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $this->from(route('login'))
                ->post('/login', [
                    'email' => 'persona'.$attempt.'@example.com',
                    'password' => 'wrong-password',
                ])
                ->assertRedirect(route('login'));
        }

        $this->from(route('login'))
            ->post('/login', [
                'email' => 'otra@example.com',
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'));

        $this->assertStringContainsString(
            'Demasiados intentos de acceso',
            session('errors')->first('email')
        );
    }

    public function test_a_successful_login_still_works_before_the_limit(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertRedirect();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/admin');

        $this->assertAuthenticatedAs($user);
    }

    public function test_closed_registration_returns_too_many_requests_after_five_posts(): void
    {
        $payload = [
            'name' => 'Publico',
            'email' => 'publico@caribetour.test',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ];

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/register', $payload)->assertNotFound();
        }

        $this->post('/register', $payload)
            ->assertStatus(429)
            ->assertSee(__('auth.too_many'));

        $this->assertDatabaseMissing('users', [
            'email' => 'publico@caribetour.test',
        ]);
    }

    public function test_booking_lookup_shows_the_limit_message(): void
    {
        for ($attempt = 0; $attempt < 10; $attempt++) {
            $this->from(route('reservation.lookup'))
                ->post(route('reservation.lookup.submit'), [
                    'external_ref' => 'NOEXISTE',
                    'email' => 'no@example.com',
                ])
                ->assertOk();
        }

        $this->followingRedirects()
            ->from(route('reservation.lookup'))
            ->post(route('reservation.lookup.submit'), [
                'external_ref' => 'NOEXISTE',
                'email' => 'no@example.com',
            ])
            ->assertOk()
            ->assertSee(__('auth.too_many'));
    }

    public function test_reservation_creation_shows_the_limit_message_and_does_not_book(): void
    {
        $product = Product::factory()->create();
        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => 5,
            'total_stock' => 5,
        ]);
        Segment::factory()->create(['itinerary_id' => $itinerary->id]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->from(route('reservation.create', [$product, $itinerary]))
                ->post(route('reservation.store', [$product, $itinerary]), [])
                ->assertRedirect()
                ->assertSessionHasErrors();
        }

        $this->followingRedirects()
            ->from(route('reservation.create', [$product, $itinerary]))
            ->post(route('reservation.store', [$product, $itinerary]), [])
            ->assertOk()
            ->assertSee(__('auth.too_many'));

        $this->assertSame(5, $itinerary->fresh()->available_stock);
        $this->assertDatabaseCount('bookings', 0);
    }
}
