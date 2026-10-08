<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Terminal;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItineraryStockTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
    }

    public function test_creating_a_departure_stores_the_requested_stock(): void
    {
        $product = $this->product();
        $origin = Terminal::factory()->create();
        $destination = Terminal::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('admin.tour.date.store'), [
                'product_id' => $product->id,
                'departure_date' => '2026-11-01 10:00:00',
                'arrival_date' => '2026-11-08 18:00:00',
                'departure_terminal_id' => $origin->id,
                'arrival_terminal_id' => $destination->id,
                'price' => 100,
                'taxes' => 10,
                'total_stock' => 12,
            ])
            ->assertRedirect();

        $itinerary = Itinerary::query()->where('product_id', $product->id)->first();
        $this->assertNotNull($itinerary);
        $this->assertSame(12, $itinerary->total_stock);
        $this->assertSame(12, $itinerary->available_stock);

        $this->actingAs($this->admin)
            ->get(route('admin.catalog.edit', ['kind' => 'tours', 'id' => $product->id]))
            ->assertOk()
            ->assertSee('name="total_stock"', false)
            ->assertSee('>Plazas<', false)
            ->assertSee('>Disponibles<', false)
            ->assertSee('table-danger', false)
            ->assertSee('Agotada', false)
            ->assertSee('table-warning', false)
            ->assertSee('Pocas plazas', false);
    }

    public function test_editor_can_raise_stock_and_keeps_sold_seats(): void
    {
        $itinerary = Itinerary::factory()->create([
            'product_id' => $this->product()->id,
            'total_stock' => 12,
            'available_stock' => 10,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.itineraries.segments.index', $itinerary))
            ->assertOk()
            ->assertSee('Plazas totales')
            ->assertSee('Disponibles:')
            ->assertSee('Vendidas:');

        $this->actingAs($this->admin)
            ->put(route('admin.itineraries.stock.update', $itinerary), [
                'total_stock' => 18,
            ])
            ->assertRedirect(route('admin.itineraries.segments.index', $itinerary))
            ->assertSessionHas('success');

        $itinerary->refresh();
        $this->assertSame(18, $itinerary->total_stock);
        $this->assertSame(16, $itinerary->available_stock);
    }

    public function test_base_fare_can_be_updated_without_touching_passenger_prices(): void
    {
        $itinerary = Itinerary::factory()->create([
            'product_id' => $this->product()->id,
            'price' => 100,
            'taxes' => 10,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.itineraries.prices.edit', $itinerary))
            ->assertOk()
            ->assertSee('Guardar tarifa base');

        $this->actingAs($this->admin)
            ->put(route('admin.itineraries.base-price.update', $itinerary), [
                'price' => 180.5,
                'taxes' => 12,
            ])
            ->assertRedirect(route('admin.itineraries.prices.edit', $itinerary))
            ->assertSessionHas('success');

        $itinerary->refresh();
        $this->assertEquals(180.5, (float) $itinerary->price);
        $this->assertEquals(12, (float) $itinerary->taxes);
    }

    public function test_stock_cannot_drop_below_sold_seats(): void
    {
        $itinerary = Itinerary::factory()->create([
            'product_id' => $this->product()->id,
            'total_stock' => 12,
            'available_stock' => 10,
        ]);

        $this->actingAs($this->admin)
            ->from(route('admin.itineraries.segments.index', $itinerary))
            ->put(route('admin.itineraries.stock.update', $itinerary), [
                'total_stock' => 1,
            ])
            ->assertRedirect(route('admin.itineraries.segments.index', $itinerary))
            ->assertSessionHasErrors('total_stock');

        $itinerary->refresh();
        $this->assertSame(12, $itinerary->total_stock);
        $this->assertSame(10, $itinerary->available_stock);
    }

    private function product(): Product
    {
        return Product::factory()->create([
            'category_id' => Category::factory(),
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            'supplier_id' => Supplier::factory(),
            'created_user_id' => $this->admin->id,
        ]);
    }
}
