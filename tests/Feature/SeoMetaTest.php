<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Image;
use App\Models\Itinerary;
use App\Models\Metadata;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Terminal;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoMetaTest extends TestCase
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

    public function test_public_pages_have_their_own_title_and_description(): void
    {
        $country = $this->country('Colombia', 'colombia');
        Metadata::query()->create([
            'meta_dataable_type' => Category::class,
            'meta_dataable_id' => $country->id,
            'meta_data' => ['description' => '<p>Playas y ciudades de Colombia.</p>'],
        ]);
        $province = $this->province($country, 'Medellín', 'medellin');
        $tour = $this->tour($province, 'Free tour del centro', 'free-tour-del-centro');
        $post = Blog::factory()->create([
            'name' => 'Guía de Cartagena',
            'slug' => 'guia-de-cartagena',
            'content' => '<p>Qué ver el primer día en la ciudad amurallada.</p>',
        ]);
        $hidden = $this->country('Cuba oculta', 'cuba-oculta', false);

        $this->get(route('inicio'))
            ->assertOk()
            ->assertSee('<title>Especialistas en viajes al Caribe | CaribeTour.es</title>', false)
            ->assertSee('content="Viaja al Caribe con CaribeTour. Vuelos, hoteles y paquetes', false)
            ->assertSee('content="index, follow"', false)
            ->assertDontSee('og_title', false);

        $this->get(route('destinos'))
            ->assertOk()
            ->assertSee('<title>Destinos del Caribe | CaribeTour.es</title>', false)
            ->assertSee('content="Elige un país del Caribe y compara tours', false);

        $this->get(route('destinos.pais', 'colombia'))
            ->assertOk()
            ->assertSee('<title>Colombia | CaribeTour.es</title>', false)
            ->assertSee('content="Playas y ciudades de Colombia."', false);

        $this->get(route('destinos.provincia', ['country' => 'colombia', 'province' => 'medellin']))
            ->assertOk()
            ->assertSee('<title>Medellín, Colombia | CaribeTour.es</title>', false)
            ->assertSee('content="Tours en Medellín, Colombia.', false);

        $this->get(route('destinos.tour', [
            'country' => 'colombia',
            'province' => 'medellin',
            'tour' => $tour->slug,
        ]))
            ->assertOk()
            ->assertSee('<title>Free tour del centro | CaribeTour.es</title>', false)
            ->assertSee('content="Free tour del centro en Medellín, Colombia. Desde 110,00 € por persona."', false);

        $this->get(route('blogs.show', ['post' => $post->slug]))
            ->assertOk()
            ->assertSee('<title>Guía de Cartagena | CaribeTour.es</title>', false)
            ->assertSee('content="Qué ver el primer día en la ciudad amurallada."', false);

        $this->get(route('destinos.pais', $hidden->slug))
            ->assertOk()
            ->assertSee('<title>Destino no disponible | CaribeTour.es</title>', false)
            ->assertSee('content="noindex, nofollow"', false);

        $this->get(route('reservation.lookup'))
            ->assertOk()
            ->assertSee('<title>Consultar reserva | CaribeTour.es</title>', false)
            ->assertSee('content="noindex, nofollow"', false);

        $this->get(route('reservation.create', [$tour, $tour->itineraries()->first()]))
            ->assertOk()
            ->assertSee('<title>Reservar | CaribeTour.es</title>', false)
            ->assertSee('content="noindex, nofollow"', false);
    }

    public function test_public_pages_expose_one_heading_and_structured_data(): void
    {
        $user = User::factory()->create();
        $country = $this->country('Colombia', 'colombia');
        $province = $this->province($country, 'Medellín', 'medellin');
        $tour = $this->tour($province, 'Free tour del centro', 'free-tour-del-centro');
        $post = Blog::factory()->create([
            'name' => 'Guía de Cartagena',
            'slug' => 'guia-de-cartagena',
            'content' => '<p>Qué ver el primer día en la ciudad amurallada.</p>',
            'created_user_id' => $user->id,
        ]);
        $this->image($country, 'images/paises/colombia.jpg', $user->id);
        $this->image($tour, 'images/tours/centro.jpg', $user->id);
        $this->image($post, 'images/posts/cartagena.jpg', $user->id);

        $home = $this->get(route('inicio'))->assertOk()->getContent();
        $this->assertOneHeading($home, 'Explora el mundo con CaribeTour');
        $this->assertJsonLdInHead($home);
        $this->assertStringContainsString('"@type":"Organization"', $home);
        $this->assertStringNotContainsString('SearchAction', $home);
        $this->assertStringNotContainsString('"@type":"WebSite"', $home);
        $this->assertStringContainsString('images/og-default.jpg', $home);
        $this->assertStringNotContainsString('1126,40', $home);
        $this->assertStringNotContainsString('Ocean Blue', $home);

        $destinations = $this->get(route('destinos'))->assertOk()->getContent();
        $this->assertOneHeading($destinations, 'Destinos del Caribe');
        $this->assertJsonLdInHead($destinations);
        $this->assertStringContainsString('"@type":"BreadcrumbList"', $destinations);
        $this->assertStringContainsString('"name":"Destinos"', $destinations);

        $countryPage = $this->get(route('destinos.pais', 'colombia'))->assertOk()->getContent();
        $this->assertOneHeading($countryPage, 'Colombia');
        $this->assertStringContainsString('"name":"Colombia"', $countryPage);
        $this->assertStringContainsString('images/paises/colombia.jpg', $countryPage);
        $this->assertStringNotContainsString('og-default.jpg', $countryPage);

        $provincePage = $this->get(route('destinos.provincia', [
            'country' => 'colombia',
            'province' => 'medellin',
        ]))->assertOk()->getContent();
        $this->assertOneHeading($provincePage, 'Medellín');
        $this->assertStringContainsString('"name":"Medellín"', $provincePage);
        $this->assertStringContainsString('images/tours/centro.jpg', $provincePage);
        $this->assertStringNotContainsString('og-default.jpg', $provincePage);

        $tourPage = $this->get(route('destinos.tour', [
            'country' => 'colombia',
            'province' => 'medellin',
            'tour' => $tour->slug,
        ]))->assertOk()->getContent();
        $this->assertOneHeading($tourPage, 'Free tour del centro');
        $this->assertJsonLdInHead($tourPage);
        $this->assertStringContainsString('"@type":"TouristTrip"', $tourPage);
        $this->assertStringContainsString('"price":"110.00"', $tourPage);
        $this->assertStringContainsString('images/tours/centro.jpg', $tourPage);
        $this->assertStringNotContainsString('og-default.jpg', $tourPage);
        $this->assertStringNotContainsString('tour.html', $tourPage);
        $this->assertStringNotContainsString('Productos relacionados', $tourPage);

        $postPage = $this->get(route('blogs.show', ['post' => $post->slug]))->assertOk()->getContent();
        $this->assertOneHeading($postPage, 'Guía de Cartagena');
        $this->assertJsonLdInHead($postPage);
        $this->assertStringContainsString('"@type":"Article"', $postPage);
        $this->assertStringContainsString('images/posts/cartagena.jpg', $postPage);
        $this->assertStringNotContainsString('og-default.jpg', $postPage);
        $this->assertStringContainsString('<time datetime="2026-10-18">18-10-2026</time>', $postPage);

        $blogIndex = $this->get(route('blogs'))->assertOk()->getContent();
        $this->assertStringContainsString('<time datetime="2026-10-18">18-10-2026</time>', $blogIndex);
        $this->assertStringNotContainsString('Este es el título del blog', $blogIndex);
        $this->assertStringNotContainsString('19-09-2024', $blogIndex);

        $this->assertOneHeading($this->get(route('blogs'))->assertOk()->getContent(), 'Blog de viajes al Caribe');
        $this->assertOneHeading($this->get(route('galeria'))->assertOk()->getContent(), 'Galería del Caribe');
        $this->assertOneHeading($this->get(route('servicios'))->assertOk()->getContent(), 'Servicios de viaje');
        $this->assertOneHeading($this->get(route('contacto'))->assertOk()->getContent(), 'Contacto');
        $this->assertOneHeading(
            $this->get(route('reservation.create', [$tour, $tour->itineraries()->first()]))->assertOk()->getContent(),
            'Reservar'
        );
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

    private function tour(Category $province, string $name, string $slug): Product
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
            'price' => 100,
            'taxes' => 10,
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

    private function image(Blog|Product|Category $owner, string $path, int $userId): void
    {
        Image::query()->create([
            'name' => 'Foto',
            'alt' => 'Foto del contenido',
            'is_main' => true,
            'path' => $path,
            'imageable_type' => $owner::class,
            'imageable_id' => $owner->id,
            'uploaded_user_id' => $userId,
        ]);
    }

    private function assertJsonLdInHead(string $html): void
    {
        $headEnd = strpos($html, '</head>');
        $this->assertNotFalse($headEnd);

        $head = substr($html, 0, $headEnd);
        $body = substr($html, $headEnd);

        $this->assertStringContainsString('application/ld+json', $head);
        $this->assertStringNotContainsString('application/ld+json', $body);
    }

    private function assertOneHeading(string $html, string $text): void
    {
        preg_match_all('/<h1\b[^>]*>.*?<\/h1>/s', $html, $headings);

        $this->assertCount(1, $headings[0]);
        $this->assertStringContainsString($text, strip_tags($headings[0][0]));
    }
}
