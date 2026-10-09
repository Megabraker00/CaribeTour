<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Itinerary;
use App\Models\Passenger;
use App\Models\Status;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPassengerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
    }

    public function test_guest_cannot_edit_a_passenger(): void
    {
        [$booking, $passenger] = $this->makeBookingWithPassenger();

        $this->get(route('admin.booking.passengers.edit', [$booking, $passenger]))
            ->assertRedirect(route('login'));
    }

    public function test_admin_cannot_edit_a_passenger_from_another_booking(): void
    {
        [, $passenger] = $this->makeBookingWithPassenger();
        $otherBooking = Booking::factory()->create();

        $this->actingAs($this->admin)
            ->get(route('admin.booking.passengers.edit', [$otherBooking, $passenger]))
            ->assertNotFound();
    }

    public function test_admin_can_update_passenger_identity_without_changing_price(): void
    {
        [$booking, $passenger] = $this->makeBookingWithPassenger();

        $this->actingAs($this->admin)
            ->get(route('admin.booking.show', $booking))
            ->assertOk()
            ->assertSee(route('admin.booking.passengers.edit', [$booking, $passenger]), false);

        $this->actingAs($this->admin)
            ->put(route('admin.booking.passengers.update', [$booking, $passenger]), [
                'name' => 'Laura',
                'last_name' => 'Sanz',
                'dni_passport' => '12345678Z',
                'nationality' => 'ES',
                'gender' => 'female',
                'date_of_birth' => '1990-05-01',
            ])
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('success');

        $passenger->refresh();
        $this->assertSame('Laura', $passenger->name);
        $this->assertSame('Sanz', $passenger->last_name);
        $this->assertSame('12345678Z', $passenger->dni_passport);
        $this->assertSame('ES', $passenger->nationality);
        $this->assertSame('female', $passenger->gender);
        $this->assertSame('1990-05-01', $passenger->date_of_birth->format('Y-m-d'));
        $this->assertSame(Type::ADULT, $passenger->type?->slug);
        $this->assertEquals(100, $passenger->price_at_booking);
        $this->assertEquals(10, $passenger->taxes_at_booking);
    }

    public function test_admin_can_add_a_passenger_and_the_fare_is_added_to_the_total(): void
    {
        [$booking, $itinerary] = $this->bookingReadyForAnotherPassenger(Status::BOOKING_PENDING);

        $this->actingAs($this->admin)
            ->get(route('admin.booking.show', $booking))
            ->assertOk()
            ->assertSee('Añadir pasajero', false);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.passengers.store', $booking), $this->newPassengerPayload())
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('success', 'Pasajero añadido. Se ha reservado una plaza y el total incluye su tarifa.');

        $this->actingAs($this->admin)
            ->get(route('admin.booking.show', $booking))
            ->assertOk()
            ->assertSee('PVP', false)
            ->assertSee('110,00', false)
            ->assertSee('Total PVP', false)
            ->assertSee('220,00', false);

        $this->assertSame(2, $booking->passengers()->count());
        $this->assertEquals(220, (float) $booking->fresh()->total_price);
        $this->assertSame(3, $itinerary->fresh()->available_stock);
        $this->assertEquals(100, $booking->passengers()->where('name', 'Luis')->value('price_at_booking'));
    }

    public function test_adding_a_passenger_to_a_paid_booking_does_not_change_the_charge(): void
    {
        [$booking] = $this->bookingReadyForAnotherPassenger(Status::BOOKING_PAID);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.passengers.store', $booking), $this->newPassengerPayload())
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('success', 'Pasajero añadido. El total incluye su tarifa. El cobro ya realizado no cubre este importe.');

        $this->assertEquals(220, (float) $booking->fresh()->total_price);
    }

    public function test_admin_cannot_add_a_passenger_when_the_departure_is_sold_out(): void
    {
        [$booking, $itinerary] = $this->bookingReadyForAnotherPassenger(Status::BOOKING_PENDING);
        $itinerary->update(['available_stock' => 0]);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.passengers.store', $booking), $this->newPassengerPayload())
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('error', 'No quedan plazas en esta salida.');

        $this->assertSame(1, $booking->passengers()->count());
        $this->assertEquals(110, (float) $booking->fresh()->total_price);
    }

    public function test_admin_cannot_add_a_passenger_to_a_cancelled_booking(): void
    {
        [$booking] = $this->bookingReadyForAnotherPassenger(Status::BOOKING_CANCELLED);

        $this->actingAs($this->admin)
            ->post(route('admin.booking.passengers.store', $booking), $this->newPassengerPayload())
            ->assertRedirect(route('admin.booking.show', $booking))
            ->assertSessionHas('error', 'No se pueden añadir pasajeros a esta reserva.');

        $this->assertSame(1, $booking->passengers()->count());
    }

    /**
     * @return array{0: Booking, 1: Itinerary}
     */
    private function bookingReadyForAnotherPassenger(string $status): array
    {
        [$booking] = $this->makeBookingWithPassenger();
        $booking->update([
            'total_price' => 110,
            'status_id' => Status::idFor(Booking::class, $status),
        ]);
        $itinerary = Itinerary::factory()->create([
            'available_stock' => 4,
            'total_stock' => 5,
            'price' => 100,
            'taxes' => 10,
        ]);
        $booking->itineraries()->attach($itinerary->id, ['itinerary_order' => 1]);

        return [$booking, $itinerary];
    }

    /**
     * @return array<string, string>
     */
    private function newPassengerPayload(): array
    {
        return [
            'name' => 'Luis',
            'last_name' => 'Garcia',
            'dni_passport' => '22222222B',
            'nationality' => 'ES',
            'gender' => 'male',
            'date_of_birth' => '1992-03-04',
        ];
    }

    /**
     * @return array{0: Booking, 1: Passenger}
     */
    private function makeBookingWithPassenger(): array
    {
        $booking = Booking::factory()->create([
            'status_id' => Status::idFor(Booking::class, Status::BOOKING_PAID),
        ]);
        $passenger = Passenger::query()->create([
            'booking_id' => $booking->id,
            'name' => 'Ana',
            'last_name' => 'Perez',
            'date_of_birth' => '1985-01-15',
            'dni_passport' => '11111111A',
            'nationality' => 'ES',
            'gender' => 'female',
            'passenger_type_id' => Type::idFor(Passenger::class, Type::ADULT),
            'status_id' => Status::idFor(\App\Models\Client::class, Status::CLIENT_ACTIVE),
            'price_at_booking' => 100,
            'taxes_at_booking' => 10,
        ]);

        return [$booking, $passenger];
    }
}
