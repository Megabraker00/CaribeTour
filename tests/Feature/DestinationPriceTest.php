<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Terminal;
use App\Models\Type;
use Carbon\Carbon;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinationPriceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        Carbon::setTestNow(Carbon::parse('2026-10-18 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_destination_cards_show_the_cheapest_bookable_fare(): void
    {
        $colombia = Category::factory()->create(['name' => 'Colombia', 'slug' => 'colombia']);
        $medellin = Category::factory()->create([
            'name' => 'Medellín',
            'slug' => 'medellin',
            'parent_id' => $colombia->id,
        ]);
        $cartagena = Category::factory()->create([
            'name' => 'Cartagena',
            'slug' => 'cartagena',
            'parent_id' => $colombia->id,
        ]);
        $bogota = Category::factory()->create([
            'name' => 'Bogotá',
            'slug' => 'bogota',
            'parent_id' => $colombia->id,
        ]);
        $cuba = Category::factory()->create(['name' => 'Cuba', 'slug' => 'cuba']);
        $mexico = Category::factory()->create(['name' => 'México', 'slug' => 'mexico']);
        $cancun = Category::factory()->create([
            'name' => 'Cancún',
            'slug' => 'cancun',
            'parent_id' => $mexico->id,
        ]);

        $medellinItinerary = $this->itinerary($medellin, 200, 10, '2026-11-20 10:00:00');
        $this->itinerary($medellin, 40, 0, '2026-09-01 10:00:00', $medellinItinerary->product);
        $this->itinerary($medellin, 80, 5, '2026-11-21 10:00:00', null, 0);
        $cartagenaItinerary = $this->itinerary($cartagena, 100, 10, '2026-10-18 08:00:00');
        $this->itinerary($cartagena, 300, 0, '2026-12-01 10:00:00');
        $past = $this->itinerary($cartagena, 50, 0, '2026-09-01 10:00:00');
        Segment::factory()->create([
            'itinerary_id' => $past->id,
            'sort_order' => 2,
            'departure_date' => '2026-12-15 10:00:00',
            'arrival_date' => '2026-12-15 18:00:00',
            'departure_terminal_id' => $past->segments()->value('departure_terminal_id'),
            'arrival_terminal_id' => $past->segments()->value('arrival_terminal_id'),
        ]);
        $this->itinerary($cartagena, 10, 0, '2026-11-20 10:00:00', null, 5, Status::PRODUCT_NOT_ACTIVE);
        $this->itinerary($cartagena, 1, 0, '2026-11-20 10:00:00', null, 5, Status::PRODUCT_ACTIVE, Type::HOTEL);
        $this->itinerary($bogota, 15, 0, '2026-08-01 10:00:00');
        $this->itinerary($cancun, 20, 0, '2026-11-20 10:00:00', null, 0);

        $this->assertSame([], Product::lowestAdvertisedPrices([]));
        $this->assertSame(110.0, Product::lowestAdvertisedPrices([$colombia->id], true)[$colombia->id]);
        $this->assertSame(210.0, Product::lowestAdvertisedPrices([$medellin->id])[$medellin->id]);
        $this->assertSame(110.0, Product::lowestAdvertisedPrices([$cartagena->id])[$cartagena->id]);
        $this->assertSame(110.0, $cartagenaItinerary->product->cheapestItinerary()->fullPrice());
        $this->assertArrayNotHasKey($cuba->id, Product::lowestAdvertisedPrices([$cuba->id], true));
        $this->assertArrayNotHasKey($mexico->id, Product::lowestAdvertisedPrices([$mexico->id], true));
        $this->assertArrayNotHasKey($cancun->id, Product::lowestAdvertisedPrices([$cancun->id]));
        $this->assertArrayNotHasKey($bogota->id, Product::lowestAdvertisedPrices([$bogota->id]));

        $countries = $this->get(route('destinos'))
            ->assertOk()
            ->assertSee('Colombia')
            ->assertDontSee('Cuba')
            ->assertSee('México')
            ->assertSee('Precio desde 110,00', false)
            ->assertDontSee('1126,40', false)
            ->assertDontSee('Precio desde 210,00', false)
            ->assertDontSee('Precio desde 85,00', false)
            ->assertDontSee('Precio desde 50,00', false)
            ->assertDontSee('Precio desde 40,00', false)
            ->assertDontSee('Precio desde 10,00', false)
            ->assertDontSee('Precio desde 1,00', false)
            ->assertDontSee('Precio desde 15,00', false)
            ->assertDontSee('Precio desde 20,00', false)
            ->assertDontSee('Precio desde 300,00', false);

        $this->assertSame(1, substr_count($countries->getContent(), 'class="price"'));

        $provinces = $this->get(route('destinos.pais', 'colombia'))
            ->assertOk()
            ->assertSee('Medellín')
            ->assertSee('Cartagena')
            ->assertDontSee('Bogotá')
            ->assertSee('Precio desde 210,00', false)
            ->assertSee('Precio desde 110,00', false)
            ->assertDontSee('1126,40', false)
            ->assertDontSee('Precio desde 85,00', false)
            ->assertDontSee('Precio desde 50,00', false)
            ->assertDontSee('Precio desde 300,00', false);

        $this->assertSame(2, substr_count($provinces->getContent(), 'class="price"'));

        $mexicoPage = $this->get(route('destinos.pais', 'mexico'))
            ->assertOk()
            ->assertSee('Cancún')
            ->assertDontSee('1126,40', false);

        $this->assertSame(0, substr_count($mexicoPage->getContent(), 'class="price"'));
    }

    private function itinerary(
        Category $province,
        float $price,
        float $taxes,
        string $departure,
        ?Product $product = null,
        int $stock = 5,
        string $status = Status::PRODUCT_ACTIVE,
        string $type = Type::TOUR,
    ): Itinerary {
        if ($product === null) {
            $product = Product::factory()->create([
                'category_id' => $province->id,
                'type_id' => Type::idFor(Product::class, $type),
                'status_id' => Status::idFor(Product::class, $status),
            ]);
        }

        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => $stock,
            'total_stock' => max($stock, 1),
            'price' => $price,
            'taxes' => $taxes,
        ]);
        $terminal = Terminal::factory()->create();
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_date' => $departure,
            'arrival_date' => Carbon::parse($departure)->addHours(4),
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $terminal->id,
        ]);

        return $itinerary;
    }
}
