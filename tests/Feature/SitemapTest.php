<?php

namespace Tests\Feature;

use App\Models\Blog;
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
use Tests\TestCase;

class SitemapTest extends TestCase
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

    public function test_sitemap_lists_public_pages_and_omits_hidden_ones(): void
    {
        $country = $this->country('Colombia', 'colombia');
        $openProvince = $this->province($country, 'Medellín', 'medellin');
        $closedProvince = $this->province($country, 'Bogotá', 'bogota', false);
        $tour = $this->tour($openProvince, 'Tour visible', 'tour-visible');
        $hiddenTour = $this->tour($closedProvince, 'Tour oculto', 'tour-oculto');
        $pastTour = $this->tour($openProvince, 'Tour pasado', 'tour-pasado', '2026-09-01 10:00:00');

        $hiddenCountry = $this->country('Cuba', 'cuba', false);
        $hiddenCountryProvince = $this->province($hiddenCountry, 'La Habana', 'la-habana');
        $this->tour($hiddenCountryProvince, 'Tour de cuba', 'tour-de-cuba');

        $service = Product::factory()->insurance()->active()->create([
            'name' => 'Seguro visible',
            'slug' => 'seguro-visible',
            'category_id' => $openProvince->id,
        ]);
        Product::factory()->insurance()->active()->create([
            'name' => 'Seguro oculto',
            'slug' => 'seguro-oculto',
            'category_id' => $closedProvince->id,
        ]);

        $post = Blog::factory()->create(['name' => 'Post publicado', 'slug' => 'post-publicado']);
        Blog::factory()->draft()->create(['name' => 'Post borrador', 'slug' => 'post-borrador']);

        $sitemap = $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = $sitemap->getContent();
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', ltrim($xml));

        foreach ([
            route('inicio'),
            route('destinos'),
            route('servicios'),
            route('blogs'),
            route('contacto'),
            route('galeria'),
            route('destinos.pais', ['country' => 'colombia']),
            route('destinos.provincia', ['country' => 'colombia', 'province' => 'medellin']),
            route('destinos.tour', ['country' => 'colombia', 'province' => 'medellin', 'tour' => $tour->slug]),
            route('servicios.detalle', ['servicio' => $service->slug]),
            route('blogs.show', ['post' => $post->slug]),
        ] as $url) {
            $this->assertStringContainsString('<loc>'.$url.'</loc>', $xml);
        }

        foreach ([
            route('destinos.pais', ['country' => 'cuba']),
            route('destinos.provincia', ['country' => 'cuba', 'province' => 'la-habana']),
            route('destinos.provincia', ['country' => 'colombia', 'province' => 'bogota']),
            route('destinos.tour', ['country' => 'colombia', 'province' => 'bogota', 'tour' => $hiddenTour->slug]),
            route('destinos.tour', ['country' => 'colombia', 'province' => 'medellin', 'tour' => $pastTour->slug]),
            route('servicios.detalle', ['servicio' => 'seguro-oculto']),
            route('blogs.show', ['post' => 'post-borrador']),
            route('reservation.lookup'),
            route('login'),
        ] as $url) {
            $this->assertStringNotContainsString('<loc>'.$url.'</loc>', $xml);
        }
    }

    public function test_robots_points_at_the_sitemap(): void
    {
        $this->get(route('robots'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('User-agent: *', false)
            ->assertSee('Sitemap: '.route('sitemap'), false);
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

    private function tour(
        Category $province,
        string $name,
        string $slug,
        string $departure = '2026-11-20 10:00:00',
    ): Product {
        $product = Product::factory()->tour()->active()->create([
            'name' => $name,
            'slug' => $slug,
            'category_id' => $province->id,
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => 5,
            'total_stock' => 5,
            'price' => 100,
            'taxes' => 10,
        ]);
        $terminal = Terminal::factory()->create();
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_date' => $departure,
            'arrival_date' => Carbon::parse($departure)->addDays(7),
            'departure_terminal_id' => $terminal->id,
            'arrival_terminal_id' => $terminal->id,
        ]);

        return $product;
    }
}
