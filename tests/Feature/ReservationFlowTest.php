<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmed;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Terminal;
use App\Models\Type;
use App\Models\User;
use App\Services\ReservationService;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReservationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
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
