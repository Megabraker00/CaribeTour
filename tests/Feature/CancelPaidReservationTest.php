<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Itinerary;
use App\Models\Passenger;
use App\Models\Product;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\ApiRequestor;
use Stripe\HttpClient\CurlClient;
use Tests\Support\FakeStripeClient;
use Tests\TestCase;

class CancelPaidReservationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
        config(['services.stripe.secret' => 'sk_test_fake']);
    }

    protected function tearDown(): void
    {
        ApiRequestor::setHttpClient(CurlClient::instance());
        parent::tearDown();
    }

    public function test_admin_refunds_a_paid_booking_and_issues_the_credit_note(): void
    {
        [$booking, $itinerary, $stripe] = $this->paidBooking();
        $this->actingAs($this->admin)->post(route('admin.booking.invoices.store', $booking));
        $invoice = Invoice::query()->first();

        $this->actingAs($this->admin)
            ->post(route('admin.booking.cancel', $booking))
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('success');

        $this->assertSame(5, $itinerary->fresh()->available_stock);
        $this->assertTrue($booking->fresh()->hasStatusSlug(Status::BOOKING_REFUNDED));
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'transaction_id' => 'pi_test_expire',
            'refund_id' => 're_test_refund',
            'status_id' => Status::idFor(\App\Models\Payment::class, Status::PAYMENT_REFUNDED),
        ]);

        $credit = Invoice::query()->whereNotNull('rectifies_invoice_id')->first();
        $this->assertNotNull($credit);
        $this->assertSame($invoice->id, $credit->rectifies_invoice_id);
        $this->assertSame('-220.00', $credit->total_amount);
        $this->assertTrue(collect($stripe->requests)->contains(
            fn (array $request) => $request[0] === 'post' && str_contains($request[1], '/refunds')
        ));

        $this->actingAs($this->admin)
            ->get(route('admin.booking.show', $booking))
            ->assertOk()
            ->assertSee($credit->number)
            ->assertDontSee('Cancelar y reembolsar');
    }

    public function test_cancel_issues_an_invoice_when_the_booking_was_not_billed_yet(): void
    {
        [$booking] = $this->paidBooking();

        $this->actingAs($this->admin)
            ->post(route('admin.booking.cancel', $booking))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('invoices', 2);
        $this->assertNotNull(Invoice::query()->whereNotNull('rectifies_invoice_id')->first());
    }

    public function test_a_second_cancel_does_not_refund_again(): void
    {
        [$booking, , $stripe] = $this->paidBooking();

        $this->actingAs($this->admin)->post(route('admin.booking.cancel', $booking));
        $refunds = collect($stripe->requests)->filter(
            fn (array $request) => $request[0] === 'post' && str_contains($request[1], '/refunds')
        )->count();

        $this->actingAs($this->admin)
            ->post(route('admin.booking.cancel', $booking))
            ->assertSessionHas('error');

        $this->assertSame($refunds, collect($stripe->requests)->filter(
            fn (array $request) => $request[0] === 'post' && str_contains($request[1], '/refunds')
        )->count());
        $this->assertDatabaseCount('invoices', 2);
    }

    public function test_pending_booking_is_not_refunded(): void
    {
        [$booking, $itinerary, $stripe] = $this->paidBooking();
        $booking->update(['status_id' => Status::idFor(Booking::class, Status::BOOKING_PENDING)]);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.cancel', $booking))
            ->assertSessionHas('error');

        $this->assertSame(3, $itinerary->fresh()->available_stock);
        $this->assertTrue($booking->fresh()->hasStatusSlug(Status::BOOKING_PENDING));
        $this->assertFalse(collect($stripe->requests)->contains(
            fn (array $request) => str_contains($request[1], '/refunds')
        ));
    }

    public function test_stripe_refusal_keeps_the_booking_and_the_seats(): void
    {
        [$booking, $itinerary, $stripe] = $this->paidBooking();
        $stripe->refundStatusCode = 400;

        $this->actingAs($this->admin)
            ->post(route('admin.booking.cancel', $booking))
            ->assertSessionHas('error');

        $this->assertSame(3, $itinerary->fresh()->available_stock);
        $this->assertTrue($booking->fresh()->hasStatusSlug(Status::BOOKING_PAID));
        $this->assertDatabaseCount('invoices', 0);
    }

    public function test_viewer_cannot_cancel_a_paid_booking(): void
    {
        [$booking, $itinerary] = $this->paidBooking();
        $viewer = User::factory()->viewer()->create();

        $this->actingAs($viewer)
            ->post(route('admin.booking.cancel', $booking))
            ->assertForbidden();

        $this->assertSame(3, $itinerary->fresh()->available_stock);
        $this->assertTrue($booking->fresh()->hasStatusSlug(Status::BOOKING_PAID));
    }

    public function test_already_refunded_stripe_charge_still_releases_the_booking(): void
    {
        [$booking, $itinerary, $stripe] = $this->paidBooking();
        $stripe->amountRefunded = 22000;

        $this->actingAs($this->admin)
            ->post(route('admin.booking.cancel', $booking))
            ->assertSessionHas('success');

        $this->assertSame(5, $itinerary->fresh()->available_stock);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'refund_id' => 're_test_refund',
        ]);
        $this->assertFalse(collect($stripe->requests)->contains(
            fn (array $request) => $request[0] === 'post' && str_contains($request[1], '/refunds')
        ));
    }

    /**
     * @return array{0: Booking, 1: Itinerary, 2: FakeStripeClient}
     */
    private function paidBooking(): array
    {
        $stripe = new FakeStripeClient(Status::PAYMENT_STRIPE_SUCCEEDED);
        ApiRequestor::setHttpClient($stripe);

        $user = User::factory()->create();
        $product = Product::factory()->create([
            'category_id' => Category::factory(),
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            'supplier_id' => Supplier::factory(),
            'created_user_id' => $user->id,
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => 3,
            'total_stock' => 5,
        ]);
        $booking = Booking::factory()->create([
            'status_id' => Status::idFor(Booking::class, Status::BOOKING_PAID),
            'total_price' => 220,
            'currency' => 'EUR',
        ]);
        $booking->itineraries()->attach($itinerary->id, ['itinerary_order' => 1]);

        foreach (['A', 'B'] as $suffix) {
            Passenger::query()->create([
                'name' => 'Pax',
                'last_name' => $suffix,
                'dni_passport' => 'DOC'.$suffix,
                'passenger_type_id' => Type::idFor(Passenger::class, Type::ADULT),
                'status_id' => Status::idFor(\App\Models\Client::class, Status::CLIENT_ACTIVE),
                'booking_id' => $booking->id,
                'price_at_booking' => 100,
                'taxes_at_booking' => 10,
            ]);
        }

        $booking->payments()->create([
            'amount' => 220,
            'currency' => 'EUR',
            'transaction_id' => 'pi_test_expire',
            'status_id' => Status::idFor(\App\Models\Payment::class, Status::PAYMENT_PAID),
            'type_id' => Type::idFor(\App\Models\Payment::class, Type::PAID_BY_STRIPE),
        ]);

        return [$booking, $itinerary, $stripe];
    }
}
