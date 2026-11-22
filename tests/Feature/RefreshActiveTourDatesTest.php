<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Terminal;
use App\Services\ActiveTourDateRefresher;
use Carbon\Carbon;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefreshActiveTourDatesTest extends TestCase
{
    use RefreshDatabase;

    private Terminal $terminal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->terminal = Terminal::factory()->create();
        Carbon::setTestNow(Carbon::parse('2026-11-22 12:00:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_the_earliest_departure_moves_to_one_month_from_today_and_keeps_the_gaps(): void
    {
        $tour = $this->tour('Playa Bávaro', 'playa-bavaro');
        $first = $this->itinerary($tour, 4);
        $opening = $this->segment($first, 1, '2026-11-01 08:30:00', '2026-11-01 12:00:00');
        $return = $this->segment($first, 2, '2026-11-08 09:00:00', '2026-11-10 18:00:00');
        $later = $this->itinerary($tour, 2);
        $secondOpening = $this->segment($later, 1, '2026-11-15 08:30:00', null);

        $this->refresh();

        $this->assertSame('2026-12-22 08:30:00', $opening->fresh()->departure_date->format('Y-m-d H:i:s'));
        $this->assertSame('2026-12-22 12:00:00', $opening->fresh()->arrival_date->format('Y-m-d H:i:s'));
        $this->assertSame('2026-12-29 09:00:00', $return->fresh()->departure_date->format('Y-m-d H:i:s'));
        $this->assertSame('2026-12-31 18:00:00', $return->fresh()->arrival_date->format('Y-m-d H:i:s'));
        $this->assertSame('2027-01-05 08:30:00', $secondOpening->fresh()->departure_date->format('Y-m-d H:i:s'));
        $this->assertNull($secondOpening->fresh()->arrival_date);

        $this->refresh();

        $this->assertSame('2026-12-22 08:30:00', $opening->fresh()->departure_date->format('Y-m-d H:i:s'));
    }

    public function test_a_month_that_has_no_matching_day_stays_inside_the_next_month(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-01-31 15:00:00'));
        $tour = $this->tour('Fin de mes', 'fin-de-mes');
        $itinerary = $this->itinerary($tour, 3);
        $opening = $this->segment($itinerary, 1, '2026-01-10 08:00:00', '2026-01-12 08:00:00');

        $this->refresh();

        $this->assertSame('2026-02-28 08:00:00', $opening->fresh()->departure_date->format('Y-m-d H:i:s'));
        $this->assertSame('2026-03-02 08:00:00', $opening->fresh()->arrival_date->format('Y-m-d H:i:s'));
    }

    public function test_inactive_tours_excursions_and_departures_already_on_the_horizon_stay_put(): void
    {
        $inactive = Product::factory()->tour()->create([
            'name' => 'Tour inactivo',
            'slug' => 'tour-inactivo',
            'category_id' => $this->province()->id,
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_NOT_ACTIVE),
        ]);
        $inactiveOpening = $this->segment($this->itinerary($inactive, 5), 1, '2026-11-01 08:00:00', '2026-11-03 08:00:00');

        $excursion = Product::factory()->excursion()->active()->create([
            'name' => 'Excursión',
            'slug' => 'excursion',
            'category_id' => $this->province()->id,
        ]);
        $excursionOpening = $this->segment($this->itinerary($excursion, 5), 1, '2026-11-01 08:00:00', '2026-11-01 12:00:00');

        $ready = $this->tour('Ya a tiempo', 'ya-a-tiempo');
        $readyOpening = $this->segment($this->itinerary($ready, 5), 1, '2026-12-22 08:00:00', '2026-12-24 08:00:00');

        $later = $this->tour('Más adelante', 'mas-adelante');
        $laterOpening = $this->segment($this->itinerary($later, 5), 1, '2027-02-01 08:00:00', '2027-02-03 08:00:00');

        $this->refresh();

        $this->assertSame('2026-11-01 08:00:00', $inactiveOpening->fresh()->departure_date->format('Y-m-d H:i:s'));
        $this->assertSame('2026-11-01 08:00:00', $excursionOpening->fresh()->departure_date->format('Y-m-d H:i:s'));
        $this->assertSame('2026-12-22 08:00:00', $readyOpening->fresh()->departure_date->format('Y-m-d H:i:s'));
        $this->assertSame('2027-02-01 08:00:00', $laterOpening->fresh()->departure_date->format('Y-m-d H:i:s'));
    }

    public function test_dry_run_reports_the_new_date_without_writing_it(): void
    {
        $tour = $this->tour('Solo lectura', 'solo-lectura');
        $opening = $this->segment($this->itinerary($tour, 5), 1, '2026-11-01 08:00:00', '2026-11-08 08:00:00');

        $this->artisan('tours:refresh-dates', ['--dry-run' => true])
            ->expectsOutputToContain('Simulación')
            ->expectsOutputToContain('Solo lectura')
            ->expectsOutputToContain('22/12/2026')
            ->assertSuccessful();

        $this->assertSame('2026-11-01 08:00:00', $opening->fresh()->departure_date->format('Y-m-d H:i:s'));
    }

    public function test_production_refuses_the_rewrite_unless_forced(): void
    {
        $tour = $this->tour('Producción', 'produccion');
        $opening = $this->segment($this->itinerary($tour, 5), 1, '2026-11-01 08:00:00', '2026-11-08 08:00:00');
        $this->app['env'] = 'production';

        $this->artisan('tours:refresh-dates')
            ->expectsOutputToContain('--force')
            ->assertFailed();

        $this->assertSame('2026-11-01 08:00:00', $opening->fresh()->departure_date->format('Y-m-d H:i:s'));

        $this->artisan('tours:refresh-dates', ['--force' => true])->assertSuccessful();

        $this->assertSame('2026-12-22 08:00:00', $opening->fresh()->departure_date->format('Y-m-d H:i:s'));
    }

    public function test_refresh_makes_the_country_and_the_tour_visible_again(): void
    {
        $country = Category::factory()->create([
            'name' => 'República de prueba',
            'slug' => 'republica-de-prueba',
            'parent_id' => null,
        ]);
        $province = Category::factory()->create([
            'name' => 'Costa de prueba',
            'slug' => 'costa-de-prueba',
            'parent_id' => $country->id,
        ]);
        $tour = $this->tour('Tour caducado', 'tour-caducado', $province);
        $this->segment($this->itinerary($tour, 5), 1, '2026-10-01 08:00:00', '2026-10-08 08:00:00');

        $this->get(route('destinos'))->assertOk()->assertDontSee('República de prueba');
        $this->get(route('inicio'))->assertOk()->assertDontSee('Tour caducado');

        $this->refresh();

        $this->get(route('destinos'))->assertOk()->assertSee('República de prueba');
        $this->get(route('inicio'))->assertOk()->assertSee('Tour caducado');
        $this->assertTrue(Product::query()->publicVisibleTour()->whereKey($tour->id)->exists());
    }

    public function test_a_booked_departure_moves_and_the_command_says_so(): void
    {
        $tour = $this->tour('Con reserva', 'con-reserva');
        $itinerary = $this->itinerary($tour, 0);
        $opening = $this->segment($itinerary, 1, '2026-11-01 08:00:00', '2026-11-08 08:00:00');
        $booking = Booking::factory()->create();
        $itinerary->bookings()->attach($booking->id, ['itinerary_order' => 1]);

        $this->artisan('tours:refresh-dates')
            ->expectsOutputToContain('1 salidas tienen reservas')
            ->expectsOutputToContain('0 plazas')
            ->assertSuccessful();

        $this->assertSame('2026-12-22 08:00:00', $opening->fresh()->departure_date->format('Y-m-d H:i:s'));
    }

    public function test_a_tour_without_segments_is_left_untouched(): void
    {
        $this->tour('Sin fechas', 'sin-fechas');

        $this->artisan('tours:refresh-dates')
            ->expectsOutputToContain('Sin fechas')
            ->assertSuccessful();

        $this->assertSame(0, Segment::query()->count());
    }

    private function refresh(): void
    {
        app(ActiveTourDateRefresher::class)->refresh();
    }

    private function province(): Category
    {
        $country = Category::factory()->create(['parent_id' => null]);

        return Category::factory()->create(['parent_id' => $country->id]);
    }

    private function tour(string $name, string $slug, ?Category $province = null): Product
    {
        return Product::factory()->tour()->active()->create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => ($province ?? $this->province())->id,
        ]);
    }

    private function itinerary(Product $tour, int $stock): Itinerary
    {
        return Itinerary::factory()->create([
            'product_id' => $tour->id,
            'available_stock' => $stock,
            'total_stock' => max($stock, 1),
            'price' => 100,
            'taxes' => 0,
        ]);
    }

    private function segment(Itinerary $itinerary, int $order, string $departure, ?string $arrival): Segment
    {
        return Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => $order,
            'departure_date' => $departure,
            'arrival_date' => $arrival,
            'departure_terminal_id' => $this->terminal->id,
            'arrival_terminal_id' => $this->terminal->id,
        ]);
    }
}
