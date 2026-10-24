<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Terminal;
use Carbon\Carbon;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PublicCategoryCascadeTest extends TestCase
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

    public function test_an_inactive_parent_hides_its_provinces_and_tours(): void
    {
        $country = $this->country('Pais Oculto', 'pais-oculto', false);
        $province = $this->province($country, 'Provincia Oculta', 'provincia-oculta');
        $tour = $this->tour($province, 'Tour oculto', 'tour-oculto', 80);

        $this->assertFalse($province->isPubliclyAvailable());
        $this->assertFalse($tour->isInPublicCategory());
        $this->assertFalse(Product::query()->publicVisibleTour()->whereKey($tour->id)->exists());

        $this->get(route('destinos'))
            ->assertOk()
            ->assertDontSee('Pais Oculto');

        $this->get(route('destinos.pais', 'pais-oculto'))
            ->assertOk()
            ->assertSee('Actualmente este destino no está disponible', false)
            ->assertSee('Ver otros destinos')
            ->assertDontSee('Tour oculto');

        $this->get(route('destinos.provincia', ['country' => 'pais-oculto', 'province' => 'provincia-oculta']))
            ->assertOk()
            ->assertSee('Ver otros destinos')
            ->assertDontSee('Tour oculto');

        $hiddenTour = $this->get(route('destinos.tour', [
            'country' => 'pais-oculto',
            'province' => 'provincia-oculta',
            'tour' => 'tour-oculto',
        ]))
            ->assertOk()
            ->assertSee('Actualmente este destino no está disponible', false);
        $this->assertHiddenAfterBreadcrumb($hiddenTour, 'Tour oculto');

        $this->get(route('inicio'))
            ->assertOk()
            ->assertDontSee('Tour oculto');

        $this->get(route('reservation.create', [$tour, $tour->itineraries()->first()]))
            ->assertNotFound();

        $this->get(route('destinos.pais', 'pais-inexistente'))->assertNotFound();
    }

    public function test_an_inactive_province_stays_hidden_when_its_country_is_active(): void
    {
        $country = $this->country('Pais Visible', 'pais-visible');
        $hidden = $this->province($country, 'Provincia Cerrada', 'provincia-cerrada', false);
        $open = $this->province($country, 'Provincia Abierta', 'provincia-abierta');
        $cheapHidden = $this->tour($hidden, 'Tour barato oculto', 'tour-barato-oculto', 10);
        $visible = $this->tour($open, 'Tour visible', 'tour-visible', 200);

        $this->assertSame(
            200.0,
            Product::lowestAdvertisedPrices([$country->id], true)[$country->id]
        );
        $this->assertArrayNotHasKey(
            $hidden->id,
            Product::lowestAdvertisedPrices([$hidden->id, $open->id])
        );

        $this->get(route('destinos'))
            ->assertOk()
            ->assertSee('Pais Visible')
            ->assertSee('Precio desde 200,00', false)
            ->assertDontSee('Precio desde 10,00', false);

        $this->get(route('destinos.pais', 'pais-visible'))
            ->assertOk()
            ->assertSee('Provincia Abierta')
            ->assertDontSee('Provincia Cerrada')
            ->assertSee('Precio desde 200,00', false);

        $this->get(route('destinos.provincia', ['country' => 'pais-visible', 'province' => 'provincia-cerrada']))
            ->assertOk()
            ->assertSee('Actualmente este destino no está disponible', false)
            ->assertDontSee('Tour barato oculto');

        $this->get(route('destinos.provincia', ['country' => 'pais-visible', 'province' => 'provincia-abierta']))
            ->assertOk()
            ->assertSee('Tour visible')
            ->assertDontSee('Tour barato oculto');

        $this->get(route('destinos.tour', [
            'country' => 'pais-visible',
            'province' => 'provincia-abierta',
            'tour' => 'tour-visible',
        ]))
            ->assertOk()
            ->assertSee('Tour visible');

        $hiddenFare = $this->get(route('destinos.tour', [
            'country' => 'pais-visible',
            'province' => 'provincia-cerrada',
            'tour' => 'tour-barato-oculto',
        ]))->assertOk();
        $this->assertHiddenAfterBreadcrumb($hiddenFare, 'Tour barato oculto');

        $this->get(route('destinos.tour', [
            'country' => 'pais-visible',
            'province' => 'provincia-abierta',
            'tour' => 'no-existe',
        ]))->assertNotFound();

        $this->get(route('reservation.create', [$visible, $visible->itineraries()->first()]))
            ->assertOk();

        $this->get(route('reservation.create', [$cheapHidden, $cheapHidden->itineraries()->first()]))
            ->assertNotFound();
    }

    public function test_an_inactive_category_hides_a_service(): void
    {
        $country = $this->country('Pais Servicio', 'pais-servicio', false);
        $province = $this->province($country, 'Provincia Servicio', 'provincia-servicio');
        $service = Product::factory()->insurance()->active()->create([
            'name' => 'Seguro oculto',
            'slug' => 'seguro-oculto',
            'category_id' => $province->id,
        ]);

        $hiddenService = $this->get(route('servicios.detalle', $service->slug))
            ->assertOk()
            ->assertSee('Actualmente este destino no está disponible', false)
            ->assertSee('Ver otros destinos');
        $this->assertHiddenAfterBreadcrumb($hiddenService, 'Seguro oculto');

        $province->update([
            'status_id' => Status::idFor(Category::class, Status::CATEGORY_ACTIVE),
        ]);
        $country->update([
            'status_id' => Status::idFor(Category::class, Status::CATEGORY_ACTIVE),
        ]);

        $this->get(route('servicios.detalle', $service->slug))
            ->assertOk()
            ->assertSee('Seguro oculto');
    }

    private function assertHiddenAfterBreadcrumb(TestResponse $response, string $text): void
    {
        $html = $response->getContent();
        $breadcrumb = strpos($html, 'aria-label="breadcrumb"');
        $cut = $breadcrumb === false ? 0 : strpos($html, '</nav>', $breadcrumb);
        $body = substr($html, (int) $cut);

        $this->assertStringNotContainsString($text, $body);
    }

    private function country(string $name, string $slug, bool $active = true): Category
    {
        return Category::factory()->create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => null,
            'status_id' => Status::idFor(
                Category::class,
                $active ? Status::CATEGORY_ACTIVE : Status::CATEGORY_INACTIVE
            ),
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

    private function tour(Category $province, string $name, string $slug, float $price): Product
    {
        $product = Product::factory()->tour()->active()->create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => $province->id,
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => 5,
            'total_stock' => 5,
            'price' => $price,
            'taxes' => 0,
        ]);
        $terminal = Terminal::factory()->create();
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_date' => '2026-11-20 10:00:00',
            'arrival_date' => '2026-11-27 18:00:00',
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $terminal->id,
        ]);

        return $product;
    }
}
