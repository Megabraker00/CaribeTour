<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Metadata;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Terminal;
use Carbon\Carbon;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelatedProductsTest extends TestCase
{
    use RefreshDatabase;

    private Terminal $terminal;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        Carbon::setTestNow(Carbon::parse('2026-10-18 12:00:00'));
        $this->terminal = Terminal::factory()->create();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_same_province_tours_are_shown_first(): void
    {
        $country = $this->country('Colombia', 'colombia');
        $province = $this->province($country, 'Medellín', 'medellin');
        $otherProvince = $this->province($country, 'Cartagena', 'cartagena');
        $current = $this->product($province, 'tour', 'Tour actual', 'tour-actual');
        $neighbour = $this->product($province, 'tour', 'Tour vecino', 'tour-vecino');
        Metadata::query()->create([
            'meta_dataable_type' => Product::class,
            'meta_dataable_id' => $neighbour->id,
            'meta_data' => ['stars' => 4],
        ]);
        $this->product($province, 'excursion', 'Excursión local', 'excursion-local');
        $this->product($otherProvince, 'tour', 'Tour de Cartagena', 'tour-cartagena');
        $this->product($province, 'tour', 'Tour pasado', 'tour-pasado', '2026-10-01 10:00:00');
        $this->product($province, 'hotel', 'Hotel local', 'hotel-local');

        $related = $current->relatedProducts();

        $this->assertSame(['Tour vecino'], $related->pluck('name')->all());

        $this->get(route('destinos.tour', [
            'country' => 'colombia',
            'province' => 'medellin',
            'tour' => 'tour-actual',
        ]))
            ->assertOk()
            ->assertSee('Productos relacionados')
            ->assertSee('Tour vecino')
            ->assertSee('star-4', false)
            ->assertSee('Categoría: 4 estrellas', false)
            ->assertSee(route('destinos.tour', [
                'country' => 'colombia',
                'province' => 'medellin',
                'tour' => 'tour-vecino',
            ]), false)
            ->assertDontSee('Excursión local')
            ->assertDontSee('tour.html', false)
            ->assertDontSee('1126,40', false);
    }

    public function test_same_province_excursions_follow_when_no_other_tour_exists(): void
    {
        $country = $this->country('Colombia', 'colombia');
        $province = $this->province($country, 'Medellín', 'medellin');
        $current = $this->product($province, 'tour', 'Tour actual', 'tour-actual');
        $this->product($province, 'excursion', 'Excursión local', 'excursion-local');

        $related = $current->relatedProducts();

        $this->assertSame(['Excursión local'], $related->pluck('name')->all());
        $this->assertSame(
            route('servicios.detalle', 'excursion-local'),
            route('servicios.detalle', $related->first()->slug)
        );
    }

    public function test_tours_from_another_province_of_the_same_country_come_next(): void
    {
        $country = $this->country('Colombia', 'colombia');
        $province = $this->province($country, 'Medellín', 'medellin');
        $otherProvince = $this->province($country, 'Cartagena', 'cartagena');
        $abroad = $this->country('Cuba', 'cuba');
        $otherCountry = $this->province($abroad, 'Varadero', 'varadero');
        $current = $this->product($province, 'tour', 'Tour actual', 'tour-actual');
        $this->product($otherProvince, 'tour', 'Tour de Cartagena', 'tour-cartagena');
        $this->product($otherCountry, 'tour', 'Tour de Varadero', 'tour-varadero');

        $this->assertSame(['Tour de Cartagena'], $current->relatedProducts()->pluck('name')->all());
    }

    public function test_excursions_from_other_provinces_come_before_tours_abroad(): void
    {
        $country = $this->country('Colombia', 'colombia');
        $province = $this->province($country, 'Medellín', 'medellin');
        $otherProvince = $this->province($country, 'Cartagena', 'cartagena');
        $abroad = $this->country('Cuba', 'cuba');
        $otherCountry = $this->province($abroad, 'Varadero', 'varadero');
        $current = $this->product($province, 'tour', 'Tour actual', 'tour-actual');
        $this->product($otherProvince, 'excursion', 'Excursión en Cartagena', 'excursion-cartagena');
        $this->product($otherCountry, 'tour', 'Tour de Varadero', 'tour-varadero');

        $this->assertSame(['Excursión en Cartagena'], $current->relatedProducts()->pluck('name')->all());
    }

    public function test_tours_from_other_countries_are_the_last_option(): void
    {
        $country = $this->country('Colombia', 'colombia');
        $province = $this->province($country, 'Medellín', 'medellin');
        $abroad = $this->country('Cuba', 'cuba');
        $otherCountry = $this->province($abroad, 'Varadero', 'varadero');
        $current = $this->product($province, 'tour', 'Tour actual', 'tour-actual');
        $this->product($otherCountry, 'tour', 'Tour de Varadero', 'tour-varadero');
        $this->product($otherCountry, 'excursion', 'Excursión en Varadero', 'excursion-varadero');

        $this->assertSame(['Tour de Varadero'], $current->relatedProducts()->pluck('name')->all());
    }

    public function test_an_inactive_parent_hides_the_related_tour(): void
    {
        $country = $this->country('Colombia', 'colombia', false);
        $province = $this->province($country, 'Medellín', 'medellin');
        $current = $this->product($province, 'tour', 'Tour actual', 'tour-actual');
        $this->product($province, 'tour', 'Tour vecino', 'tour-vecino');

        $this->assertTrue($current->relatedProducts()->isEmpty());
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

    private function province(Category $country, string $name, string $slug): Category
    {
        return Category::factory()->create([
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $country->id,
            'status_id' => Status::idFor(Category::class, Status::CATEGORY_ACTIVE),
        ]);
    }

    private function product(
        Category $province,
        string $kind,
        string $name,
        string $slug,
        string $departure = '2026-11-20 10:00:00'
    ): Product {
        $product = Product::factory()->{$kind}()->active()->create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => $province->id,
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => 5,
            'total_stock' => 5,
            'price' => 80,
            'taxes' => 5,
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_date' => $departure,
            'arrival_date' => '2026-11-27 18:00:00',
            'departure_terminal_id' => $this->terminal->id,
            'arrival_terminal_id' => $this->terminal->id,
        ]);

        return $product;
    }
}
