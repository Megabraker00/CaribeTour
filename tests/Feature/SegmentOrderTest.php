<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Terminal;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SegmentOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
    }

    public function test_a_segment_cannot_reuse_an_existing_order(): void
    {
        [$itinerary, $terminal] = $this->itineraryWithOpeningSegment();

        $this->actingAs($this->admin)
            ->followingRedirects()
            ->from(route('admin.itineraries.segments.index', $itinerary))
            ->post(
                route('admin.itineraries.segments.store', $itinerary),
                $this->segmentPayload($terminal, 1)
            )
            ->assertOk()
            ->assertSee('Ya existe un tramo con ese orden.');

        $this->assertSame(1, $itinerary->segments()->count());
    }

    public function test_updating_a_segment_keeps_its_own_order_and_rejects_another(): void
    {
        [$itinerary, $terminal] = $this->itineraryWithOpeningSegment();
        $second = Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 2,
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $terminal->id,
        ]);

        $this->actingAs($this->admin)
            ->from(route('admin.itineraries.segments.edit', [$itinerary, $second]))
            ->put(
                route('admin.itineraries.segments.update', [$itinerary, $second]),
                $this->segmentPayload($terminal, 1)
            )
            ->assertRedirect(route('admin.itineraries.segments.edit', [$itinerary, $second]))
            ->assertSessionHasErrors('sort_order');

        $this->assertSame(2, $second->fresh()->sort_order);

        $this->actingAs($this->admin)
            ->put(
                route('admin.itineraries.segments.update', [$itinerary, $second]),
                $this->segmentPayload($terminal, 2)
            )
            ->assertRedirect(route('admin.itineraries.segments.index', $itinerary))
            ->assertSessionHas('success');

        $this->assertSame(2, $second->fresh()->sort_order);
    }

    /**
     * @return array{0: Itinerary, 1: Terminal}
     */
    private function itineraryWithOpeningSegment(): array
    {
        $product = Product::factory()->create([
            'category_id' => Category::factory(),
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            'supplier_id' => Supplier::factory(),
            'created_user_id' => $this->admin->id,
        ]);
        $itinerary = Itinerary::factory()->create(['product_id' => $product->id]);
        $terminal = Terminal::factory()->create();
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $terminal->id,
        ]);

        return [$itinerary, $terminal];
    }

    /**
     * @return array<string, mixed>
     */
    private function segmentPayload(Terminal $terminal, int $sortOrder): array
    {
        return [
            'type_id' => Type::idFor(Product::class, Type::FLIGHT),
            'sort_order' => $sortOrder,
            'departure_date' => '2026-11-02 10:00:00',
            'arrival_date' => '2026-11-02 18:00:00',
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $terminal->id,
            'origin' => 'Madrid',
            'destination' => 'Punta Cana',
        ];
    }
}
