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

class TourSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        Carbon::setTestNow(Carbon::parse('2026-11-03 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_the_form_offers_provinces_and_terminals_with_seats(): void
    {
        $colombia = $this->country('Colombia', 'colombia');
        $medellin = $this->province($colombia, 'Medellín', 'medellin');
        $cuba = $this->country('Cuba', 'cuba');
        $habana = $this->province($cuba, 'La Habana', 'la-habana');
        $hidden = $this->province($colombia, 'Provincia oculta', 'provincia-oculta', false);

        $madrid = Terminal::factory()->create(['name' => 'Madrid']);
        $barajas = Terminal::factory()->create(['name' => 'Barajas', 'parent_id' => $madrid->id]);
        $connection = Terminal::factory()->create(['name' => 'Escala Caribe']);
        $unused = Terminal::factory()->create(['name' => 'Terminal sin salidas']);

        $this->departure($medellin, $barajas, '2026-11-20 10:00:00', 200, $connection);
        $this->departure($habana, $barajas, '2026-10-01 10:00:00', 90);
        $this->departure($hidden, $barajas, '2026-11-20 10:00:00', 70);

        $form = $this->get(route('inicio'))
            ->assertOk()
            ->assertSee('action="'.route('resultados').'"', false)
            ->assertSee('Todos los destinos')
            ->assertSee('Cualquier origen')
            ->assertSee('label="Colombia"', false)
            ->assertSee('Medellín')
            ->assertSee('value="colombia/medellin"', false)
            ->assertSee('label="Madrid"', false)
            ->assertSee('Barajas')
            ->assertDontSee('La Habana')
            ->assertDontSee('Provincia oculta')
            ->assertDontSee('Terminal sin salidas')
            ->assertDontSee('Escala Caribe')
            ->assertDontSee('value="madrid"', false);

        $this->assertSame(0, substr_count($form->getContent(), '>Madrid<'));
    }

    public function test_results_follow_destination_origin_and_date(): void
    {
        $colombia = $this->country('Colombia', 'colombia');
        $medellin = $this->province($colombia, 'Medellín', 'medellin');
        $cartagena = $this->province($colombia, 'Cartagena', 'cartagena');

        $barajas = Terminal::factory()->create(['name' => 'Barajas']);
        $prat = Terminal::factory()->create(['name' => 'El Prat']);

        $tour = Product::factory()->create([
            'name' => 'Tour del centro',
            'slug' => 'tour-del-centro',
            'category_id' => $medellin->id,
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
        ]);

        $this->departure($medellin, $barajas, '2026-11-10 10:00:00', 40, null, 5, $tour);
        $this->departure($medellin, $prat, '2026-11-25 10:00:00', 180, null, 5, $tour);
        $this->departure($medellin, $prat, '2026-12-20 10:00:00', 90, null, 5, $tour);
        $this->departure($cartagena, $prat, '2026-11-25 10:00:00', 300);
        $this->departure($medellin, $prat, '2026-11-25 10:00:00', 15, null, 0);
        $this->departure($medellin, $prat, '2026-11-25 10:00:00', 12, null, 5, null, Type::HOTEL);

        $fromPrat = $this->get(route('resultados', [
            'destino' => 'colombia/medellin',
            'origen' => $prat->id,
            'salida' => '2026-11-20',
        ]))
            ->assertOk()
            ->assertSee('Tour del centro')
            ->assertSee('90,00', false)
            ->assertSee('20 de diciembre de 2026', false)
            ->assertSee('Destino: Medellín.')
            ->assertSee('Origen: El Prat.')
            ->assertSee('Desde el 20 de noviembre de 2026.')
            ->assertSee('value="'.$prat->id.'" selected', false)
            ->assertSee('value="colombia/medellin" selected', false)
            ->assertSee('value="2026-11-20"', false)
            ->assertDontSee('40,00', false)
            ->assertDontSee('180,00', false)
            ->assertDontSee('300,00', false)
            ->assertDontSee('15,00', false)
            ->assertDontSee('12,00', false);

        $this->assertSame(1, substr_count($fromPrat->getContent(), '<h2>Tour del centro</h2>'));

        $this->get(route('resultados', ['salida' => '2026-10-01']))
            ->assertOk()
            ->assertSee('40,00', false)
            ->assertDontSee('Desde el', false);

        $this->get(route('resultados', ['destino' => 'no-existe/tampoco', 'origen' => 'no-es-un-id']))
            ->assertOk()
            ->assertSee('Tour del centro')
            ->assertSee('40,00', false);

        $this->get(route('resultados', [
            'destino' => 'colombia/medellin',
            'origen' => $prat->id,
            'salida' => '2027-01-01',
        ]))
            ->assertOk()
            ->assertSee('No hay salidas con esos filtros')
            ->assertDontSee('Tour del centro');
    }

    private function country(string $name, string $slug): Category
    {
        return Category::factory()->create([
            'name' => $name,
            'slug' => $slug,
        ]);
    }

    private function province(Category $country, string $name, string $slug, bool $active = true): Category
    {
        return Category::factory()->create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $country->id,
            'status_id' => Status::idFor(
                Category::class,
                $active ? Status::CATEGORY_ACTIVE : Status::CATEGORY_INACTIVE
            ),
        ]);
    }

    private function departure(
        Category $province,
        Terminal $terminal,
        string $when,
        float $price,
        ?Terminal $connection = null,
        int $stock = 5,
        ?Product $product = null,
        string $type = Type::TOUR,
    ): Itinerary {
        if ($product === null) {
            $product = Product::factory()->create([
                'category_id' => $province->id,
                'type_id' => Type::idFor(Product::class, $type),
                'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            ]);
        }

        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => $stock,
            'total_stock' => max($stock, 1),
            'price' => $price,
            'taxes' => 0,
        ]);

        $arrival = $connection ?? $terminal;
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_date' => $when,
            'arrival_date' => Carbon::parse($when)->addHours(4),
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $arrival->id,
        ]);

        if ($connection) {
            Segment::factory()->create([
                'itinerary_id' => $itinerary->id,
                'sort_order' => 2,
                'departure_date' => Carbon::parse($when)->addDay(),
                'arrival_date' => Carbon::parse($when)->addDay()->addHours(3),
                'departure_terminal_id' => $connection->id,
                'arrival_terminal_id' => $terminal->id,
            ]);
        }

        return $itinerary;
    }
}
