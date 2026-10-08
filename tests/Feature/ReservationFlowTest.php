<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Itinerary;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Terminal;
use App\Models\Type;
use App\Models\User;
use App\Services\ReservationService;
use Carbon\Carbon;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Stripe\ApiRequestor;
use Stripe\HttpClient\CurlClient;
use Tests\Support\FakeStripeClient;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
    }

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(CurlClient::instance());
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_reservation_create_returns_404_when_itinerary_does_not_belong_to_product(): void
    {
        [$productA] = $this->makeTourWithItinerary();
        [, $itineraryB] = $this->makeTourWithItinerary();

        $this->get(route('reservation.create', [$productA, $itineraryB]))
            ->assertNotFound();
    }

    public function test_store_requires_passenger_count_to_match_quantity(): void
    {
        [$product, $itinerary] = $this->makeTourWithItinerary(5);

        $payload = $this->reservationPayload($itinerary, 2);
        $payload['quantity'] = 3;

        $this->from(route('reservation.create', [$product, $itinerary]))
            ->post(route('reservation.store', [$product, $itinerary]), $payload)
            ->assertSessionHasErrors('passengers');
    }

    public function test_store_rejects_when_there_is_not_enough_stock(): void
    {
        [$product, $itinerary] = $this->makeTourWithItinerary(1);

        $this->from(route('reservation.create', [$product, $itinerary]))
            ->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2))
            ->assertSessionHasErrors('quantity');

        $this->assertSame(1, $itinerary->fresh()->available_stock);
    }

    public function test_stock_error_is_shown_and_the_form_keeps_what_was_typed(): void
    {
        [$product, $itinerary] = $this->makeTourWithItinerary(1);

        $this->followingRedirects()
            ->from(route('reservation.create', [$product, $itinerary]))
            ->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2))
            ->assertOk()
            ->assertSee('No hay plazas suficientes para esta salida.')
            ->assertSee('Ana');
    }

    public function test_store_creates_booking_decrements_stock_and_keeps_session(): void
    {
        [$product, $itinerary] = $this->makeTourWithItinerary(5);

        $this->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2))
            ->assertRedirect(route('reservation.payment', [$product, $itinerary]));

        $this->assertSame(3, $itinerary->fresh()->available_stock);
        $this->assertNotNull(session('booking_id'));
        $this->assertDatabaseCount('bookings', 1);
        $this->assertDatabaseCount('passengers', 2);
    }

    public function test_store_does_not_overwrite_an_existing_client_with_the_same_email(): void
    {
        [$product, $itinerary] = $this->makeTourWithItinerary(5);
        $client = Client::factory()->create([
            'email' => 'ana@example.com',
            'name' => 'NombreOriginal',
            'last_name' => 'ApellidoOriginal',
        ]);

        $this->post(
            route('reservation.store', [$product, $itinerary]),
            $this->reservationPayload($itinerary, 2)
        )->assertRedirect();

        $client->refresh();
        $this->assertSame('NombreOriginal', $client->name);
        $this->assertSame('ApellidoOriginal', $client->last_name);
        $this->assertSame($client->id, Booking::query()->first()->client_id);
        $this->assertSame(1, Client::query()->where('email', 'ana@example.com')->count());
    }

    public function test_payment_page_returns_404_when_session_booking_does_not_match_itinerary(): void
    {
        [$productA, $itineraryA] = $this->makeTourWithItinerary(5);
        [$productB, $itineraryB] = $this->makeTourWithItinerary(5);

        $this->post(route('reservation.store', [$productA, $itineraryA]), $this->reservationPayload($itineraryA, 2))
            ->assertRedirect();

        $this->get(route('reservation.payment', [$productB, $itineraryB]))
            ->assertNotFound();
    }

    public function test_confirming_stripe_payment_marks_booking_paid_not_payment_status(): void
    {
        Mail::fake();
        [$product, $itinerary] = $this->makeTourWithItinerary(5);
        $this->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2));

        $booking = Booking::query()->first();
        $service = app(ReservationService::class);
        $service->confirmSuccessfulPayment($booking, 'pi_test_123', 19999);

        $booking->refresh();
        $this->assertTrue($booking->hasStatusSlug(Status::BOOKING_PAID));
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'transaction_id' => 'pi_test_123',
            'status_id' => Status::idFor(\App\Models\Payment::class, Status::PAYMENT_PAID),
        ]);
        Mail::assertSent(BookingConfirmed::class);
    }

    public function test_amount_in_cents_avoids_float_rounding(): void
    {
        $booking = new Booking(['total_price' => '19.99', 'currency' => 'EUR']);
        $cents = app(ReservationService::class)->amountInCents($booking);

        $this->assertSame(1999, $cents);
    }

    public function test_booking_can_be_looked_up_by_locator_and_email(): void
    {
        $client = Client::factory()->create(['email' => 'lookup@example.com']);
        $booking = Booking::factory()->create([
            'client_id' => $client->id,
            'external_ref' => 'LOC-ABCDEFGH',
        ]);

        $this->post(route('reservation.lookup.submit'), [
            'external_ref' => 'LOC-ABCDEFGH',
            'email' => 'lookup@example.com',
        ])->assertOk()->assertSee($booking->external_ref);
    }

    public function test_unpaid_reservation_inside_the_hold_keeps_its_stock(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-06 10:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary(5);
        $this->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2));

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:14:00'));
        $this->artisan('reservations:release-unpaid')->assertSuccessful();

        $this->assertSame(3, $itinerary->fresh()->available_stock);
        $this->assertTrue(Booking::query()->first()->hasStatusSlug(Status::BOOKING_PENDING));
    }

    public function test_expired_unpaid_reservation_releases_stock_and_cancels_the_booking(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-06 10:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary(5);
        $this->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2));

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:15:00'));
        $this->artisan('reservations:release-unpaid')
            ->expectsOutput('Released 1 unpaid reservation(s).')
            ->assertSuccessful();

        $this->assertSame(5, $itinerary->fresh()->available_stock);
        $this->assertTrue(Booking::query()->first()->hasStatusSlug(Status::BOOKING_CANCELLED));
    }

    public function test_expired_unpaid_reservation_cancels_the_open_payment_intent(): void
    {
        $stripe = new FakeStripeClient();
        ApiRequestor::setHttpClient($stripe);
        config(['services.stripe.secret' => 'sk_test_fake']);

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary(5);
        $this->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2));
        $booking = Booking::query()->first();
        $booking->payments()->create([
            'amount' => $booking->total_price,
            'currency' => 'EUR',
            'transaction_id' => 'pi_test_expire',
            'status_id' => Status::idFor(Payment::class, Status::PAYMENT_PENDING),
            'type_id' => Type::idFor(Payment::class, Type::PAID_BY_STRIPE),
        ]);

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:15:00'));
        $this->artisan('reservations:release-unpaid')->assertSuccessful();

        $this->assertSame(5, $itinerary->fresh()->available_stock);
        $this->assertTrue($booking->fresh()->hasStatusSlug(Status::BOOKING_CANCELLED));
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'transaction_id' => 'pi_test_expire',
            'status_id' => Status::idFor(Payment::class, Status::PAYMENT_CANCELLED),
        ]);
        $this->assertTrue(collect($stripe->requests)->contains(
            fn (array $request) => $request[0] === 'post' && str_contains($request[1], '/cancel')
        ));
    }

    public function test_expired_reservation_is_kept_when_stripe_already_succeeded(): void
    {
        Mail::fake();
        ApiRequestor::setHttpClient(new FakeStripeClient(Status::PAYMENT_STRIPE_SUCCEEDED));
        config(['services.stripe.secret' => 'sk_test_fake']);

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary(5);
        $this->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2));
        $booking = Booking::query()->first();
        $booking->payments()->create([
            'amount' => $booking->total_price,
            'currency' => 'EUR',
            'transaction_id' => 'pi_test_expire',
            'status_id' => Status::idFor(Payment::class, Status::PAYMENT_PENDING),
            'type_id' => Type::idFor(Payment::class, Type::PAID_BY_STRIPE),
        ]);

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:15:00'));
        $this->artisan('reservations:release-unpaid')
            ->expectsOutput('Released 0 unpaid reservation(s).')
            ->assertSuccessful();

        $this->assertSame(3, $itinerary->fresh()->available_stock);
        $this->assertTrue($booking->fresh()->hasStatusSlug(Status::BOOKING_PAID));
        Mail::assertSent(BookingConfirmed::class);
    }

    public function test_pending_booking_without_an_itinerary_is_not_released(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-06 10:00:00'));
        $booking = Booking::factory()->create();

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:15:00'));
        $this->artisan('reservations:release-unpaid')->assertSuccessful();

        $this->assertTrue($booking->fresh()->hasStatusSlug(Status::BOOKING_PENDING));
    }

    public function test_returning_to_payment_after_the_hold_redirects_and_releases_stock(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-06 10:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary(5);
        $this->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2));

        Carbon::setTestNow(Carbon::parse('2026-10-06 10:15:00'));
        $this->get(route('reservation.payment', [$product, $itinerary]))
            ->assertRedirect(route('reservation.create', [$product, $itinerary]))
            ->assertSessionHas('error', 'La reserva ha caducado y las plazas se han liberado. Vuelve a intentarlo.');

        $this->assertSame(5, $itinerary->fresh()->available_stock);
        $this->assertTrue(Booking::query()->first()->hasStatusSlug(Status::BOOKING_CANCELLED));
    }

    public function test_calendar_omits_sold_out_departures_and_connecting_segments(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-07 21:00:00'));
        [$product, $open] = $this->makeTourWithItinerary(5);
        $open->update(['price' => 100, 'taxes' => 10]);
        $open->segments()->update([
            'departure_date' => '2026-10-13 10:00:00',
            'arrival_date' => '2026-10-13 18:00:00',
            'sort_order' => 1,
        ]);
        $terminalId = $open->segments()->value('departure_terminal_id');
        Segment::factory()->create([
            'itinerary_id' => $open->id,
            'sort_order' => 2,
            'departure_date' => '2026-10-14 16:00:00',
            'arrival_date' => '2026-10-14 20:00:00',
            'departure_terminal_id' => $terminalId,
            'arrival_terminal_id' => $terminalId,
        ]);
        $soldOut = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => 0,
            'total_stock' => 12,
            'price' => 10,
            'taxes' => 0,
        ]);
        Segment::factory()->create([
            'itinerary_id' => $soldOut->id,
            'departure_date' => '2026-10-14 01:00:00',
            'arrival_date' => '2026-10-14 08:00:00',
            'departure_terminal_id' => $terminalId,
            'arrival_terminal_id' => $terminalId,
        ]);

        $this->assertSame($open->id, $product->cheapestItinerary()->id);

        $this->getJson('/api/v1/products/'.$product->id.'/itineraries?month=10&year=2026')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $open->id)
            ->assertJsonPath('data.0.departure_date', '2026-10-13');
    }

    public function test_reservation_summary_uses_the_opening_segment(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-07 21:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary();
        $terminalId = $itinerary->segments()->value('departure_terminal_id');
        $itinerary->segments()->update([
            'sort_order' => 1,
            'departure_date' => '2026-10-13 10:00:00',
            'arrival_date' => '2026-10-13 18:00:00',
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 2,
            'departure_date' => '2026-10-08 09:00:00',
            'arrival_date' => '2026-10-08 12:00:00',
            'departure_terminal_id' => $terminalId,
            'arrival_terminal_id' => $terminalId,
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 3,
            'departure_date' => '2026-10-20 11:00:00',
            'arrival_date' => '2026-10-20 18:00:00',
            'departure_terminal_id' => $terminalId,
            'arrival_terminal_id' => $terminalId,
        ]);

        $this->get(route('reservation.create', [$product, $itinerary]))
            ->assertOk()
            ->assertSee('13 de octubre de 2026', false)
            ->assertSee('20 de octubre de 2026', false)
            ->assertDontSee('08 de octubre de 2026', false);
    }

    public function test_tour_page_and_reservation_show_the_same_duration(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-07 21:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary();
        $country = Category::factory()->create(['slug' => 'colombia']);
        $province = Category::factory()->create([
            'slug' => 'medellin',
            'parent_id' => $country->id,
        ]);
        $product->update(['category_id' => $province->id]);
        $terminalId = $itinerary->segments()->value('departure_terminal_id');
        $itinerary->segments()->update([
            'sort_order' => 1,
            'departure_date' => '2026-10-14 01:41:28',
            'arrival_date' => '2026-10-14 08:00:00',
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 2,
            'departure_date' => '2026-09-30 07:36:08',
            'arrival_date' => '2026-09-30 18:00:00',
            'departure_terminal_id' => $terminalId,
            'arrival_terminal_id' => $terminalId,
        ]);

        $itinerary->refresh();
        $this->assertSame(15, $itinerary->days);
        $this->assertSame(14, $itinerary->nights);
        $this->assertSame(15, $itinerary->reservableSummary()['days']);
        $this->assertSame(14, $itinerary->reservableSummary()['nights']);

        $duration = '15 Días - 14 Noches';

        $this->get(route('destinos.tour', [
            'country' => $country->slug,
            'province' => $province->slug,
            'tour' => $product->slug,
        ]))
            ->assertOk()
            ->assertSee($duration, false);

        $this->get(route('reservation.create', [$product, $itinerary]))
            ->assertOk()
            ->assertSee($duration, false);
    }

    public function test_past_opening_segment_is_not_offered(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-07 21:00:00'));
        [$product, $itinerary] = $this->makeTourWithItinerary();
        $terminalId = $itinerary->segments()->value('departure_terminal_id');
        $itinerary->segments()->update([
            'sort_order' => 1,
            'departure_date' => '2026-09-24 22:00:00',
            'arrival_date' => '2026-09-25 02:00:00',
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 2,
            'departure_date' => '2026-10-14 11:00:00',
            'arrival_date' => '2026-10-14 18:00:00',
            'departure_terminal_id' => $terminalId,
            'arrival_terminal_id' => $terminalId,
        ]);

        $this->get(route('reservation.create', [$product, $itinerary]))
            ->assertNotFound();

        $this->from(route('reservation.create', [$product, $itinerary]))
            ->post(route('reservation.store', [$product, $itinerary]), $this->reservationPayload($itinerary, 2))
            ->assertSessionHasErrors('itId');

        $this->assertSame(5, $itinerary->fresh()->available_stock);
        $this->assertNull($product->cheapestItinerary());
        $this->assertFalse(Product::query()->publicVisibleTour()->whereKey($product->id)->exists());

        $this->getJson('/api/v1/products/'.$product->id.'/itineraries?month=10&year=2026')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    /**
     * @return array{0: Product, 1: Itinerary}
     */
    private function makeTourWithItinerary(int $stock = 5): array
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            'supplier_id' => $supplier->id,
            'created_user_id' => $user->id,
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => $stock,
            'total_stock' => $stock,
            'price' => 100,
            'taxes' => 10,
        ]);
        $terminal = Terminal::factory()->create();
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'type_id' => Type::idFor(Product::class, Type::FLIGHT),
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $terminal->id,
        ]);

        return [$product, $itinerary];
    }

    private function reservationPayload(Itinerary $itinerary, int $quantity = 2): array
    {
        $passengers = [];
        for ($i = 1; $i <= $quantity; $i++) {
            $passengers[$i] = [
                'first_name' => 'Pax'.$i,
                'last_name' => 'Test',
                'nationality' => 'ES',
                'document' => 'DOC'.$i,
                'gender' => 'male',
                'birth_date' => '1990-01-0'.$i,
            ];
        }

        return [
            'itId' => $itinerary->id,
            'quantity' => $quantity,
            'customer_name' => 'Ana',
            'customer_last_name' => 'Garcia',
            'customer_nationality' => 'ES',
            'customer_document' => '12345678A',
            'customer_email' => 'ana@example.com',
            'customer_phone' => '+34600000000',
            'passengers' => $passengers,
        ];
    }
}
