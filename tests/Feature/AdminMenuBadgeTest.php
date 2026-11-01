<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\ContactMessage;
use App\Models\Status;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMenuBadgeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusSeeder::class);
    }

    public function test_sidebar_badges_count_unpaid_bookings_and_unread_messages(): void
    {
        Booking::factory()->create();
        Booking::factory()->create([
            'status_id' => Status::idFor(Booking::class, Status::BOOKING_PENDING_PAYMENT),
        ]);
        Booking::factory()->create([
            'status_id' => Status::idFor(Booking::class, Status::BOOKING_PAID),
        ]);

        ContactMessage::create([
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'message' => 'Sin abrir',
        ]);
        $read = ContactMessage::create([
            'name' => 'Luis',
            'email' => 'luis@example.com',
            'message' => 'Ya leído',
        ]);
        $read->markAsRead();
        $deleted = ContactMessage::create([
            'name' => 'Nuria',
            'email' => 'nuria@example.com',
            'message' => 'Eliminado',
        ]);
        $deleted->delete();

        $html = $this->actingAs(User::factory()->admin()->create())
            ->get('/admin')
            ->assertOk()
            ->getContent();

        $this->assertSame('2', $this->badgeFor($html, 'admin/reservas'));
        $this->assertSame('1', $this->badgeFor($html, 'admin/mensajes'));
        $this->assertNull($this->badgeFor($html, 'admin/clientes'));
        $this->assertNull($this->badgeFor($html, 'admin/facturas'));
        $this->assertNull($this->badgeFor($html, 'admin/pagos'));
    }

    public function test_sidebar_hides_badges_when_nothing_needs_attention(): void
    {
        $html = $this->actingAs(User::factory()->admin()->create())
            ->get('/admin')
            ->assertOk()
            ->getContent();

        $this->assertNull($this->badgeFor($html, 'admin/reservas'));
        $this->assertNull($this->badgeFor($html, 'admin/mensajes'));
    }

    private function badgeFor(string $html, string $url): ?string
    {
        $href = 'href="'.url($url).'"';
        $start = strpos($html, $href);
        $this->assertNotFalse($start, $url);

        $end = strpos($html, '</a>', $start);
        $item = substr($html, $start, $end - $start);
        if (!preg_match('/badge badge-success right">\s*(\d+)\s*</', $item, $matches)) {
            return null;
        }

        return $matches[1];
    }
}
