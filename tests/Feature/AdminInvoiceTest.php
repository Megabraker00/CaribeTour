<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Status;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInvoiceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
    }

    public function test_guest_cannot_issue_an_invoice(): void
    {
        $booking = Booking::factory()->create(['status_id' => Status::BOOKING_PAID]);

        $this->post(route('admin.booking.invoices.store', $booking))
            ->assertRedirect(route('login'));
    }

    public function test_admin_cannot_invoice_a_pending_booking(): void
    {
        $booking = Booking::factory()->create(['status_id' => Status::BOOKING_PENDING]);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.invoices.store', $booking))
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('invoices', 0);
    }

    public function test_admin_can_issue_an_invoice_for_a_paid_booking(): void
    {
        $booking = Booking::factory()->create([
            'status_id' => Status::BOOKING_PAID,
            'total_price' => 199.99,
            'currency' => 'EUR',
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.invoices.store', $booking))
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('success');

        $invoice = Invoice::query()->first();
        $this->assertNotNull($invoice);
        $this->assertSame('FAC-'.now()->year.'-0001', $invoice->number);
        $this->assertSame('199.99', $invoice->total_amount);
        $this->assertSame($this->admin->id, $invoice->created_user_id);
        $this->assertSame($booking->client->dni_passport, $invoice->billing_vat);
        $this->assertNull($invoice->rectifies_invoice_id);

        $this->actingAs($this->admin)
            ->get(route('admin.booking.show', $booking))
            ->assertOk()
            ->assertSee($invoice->number)
            ->assertDontSee('Emitir factura');
    }

    public function test_admin_cannot_issue_a_second_positive_invoice(): void
    {
        $booking = Booking::factory()->create(['status_id' => Status::BOOKING_PAID]);

        $this->actingAs($this->admin)->post(route('admin.booking.invoices.store', $booking));
        $this->actingAs($this->admin)
            ->post(route('admin.booking.invoices.store', $booking))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('invoices', 1);
    }

    public function test_admin_cannot_credit_until_the_booking_is_cancelled(): void
    {
        $booking = Booking::factory()->create(['status_id' => Status::BOOKING_PAID]);
        $this->actingAs($this->admin)->post(route('admin.booking.invoices.store', $booking));
        $invoice = Invoice::query()->first();

        $this->actingAs($this->admin)
            ->post(route('admin.booking.invoices.credit', [$booking, $invoice]))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('invoices', 1);
    }

    public function test_admin_can_issue_a_credit_note_after_cancel(): void
    {
        $booking = Booking::factory()->create([
            'status_id' => Status::BOOKING_PAID,
            'total_price' => 80.50,
        ]);
        $this->actingAs($this->admin)->post(route('admin.booking.invoices.store', $booking));
        $invoice = Invoice::query()->first();

        $booking->update(['status_id' => Status::BOOKING_CANCELLED]);

        $this->actingAs($this->admin)
            ->from(route('admin.booking.show', $booking))
            ->post(route('admin.booking.invoices.credit', [$booking, $invoice]))
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('success');

        $credit = Invoice::query()->whereNotNull('rectifies_invoice_id')->first();
        $this->assertNotNull($credit);
        $this->assertSame('FAC-'.now()->year.'-0002', $credit->number);
        $this->assertSame('-80.50', $credit->total_amount);
        $this->assertSame($invoice->id, $credit->rectifies_invoice_id);

        $this->actingAs($this->admin)
            ->get(route('admin.facturas.show', $credit))
            ->assertOk()
            ->assertSee('Abono')
            ->assertSee($invoice->number);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.invoices.credit', [$booking, $invoice]))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('invoices', 2);
    }

    public function test_invoices_datatable_is_available_for_admin(): void
    {
        $booking = Booking::factory()->create(['status_id' => Status::BOOKING_PAID]);
        $this->actingAs($this->admin)->post(route('admin.booking.invoices.store', $booking));

        $this->actingAs($this->admin)
            ->getJson(route('api.datatable.invoices', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search' => ['value' => 'FAC-'],
                'columns' => [
                    ['data' => 'number', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'booking_ref', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'kind', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'amount_label', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'issued_on', 'searchable' => 'true', 'orderable' => 'true'],
                ],
            ]))
            ->assertOk()
            ->assertJsonPath('recordsFiltered', 1);
    }
}
