<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Slide;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SlideTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
        $this->travelTo('2026-11-03 12:00:00');
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(public_path('images/slides'));
        parent::tearDown();
    }

    public function test_the_home_shows_active_slides_in_order_and_hides_the_rest(): void
    {
        $tour = $this->publicTour();

        Slide::query()->create([
            'title' => 'Segundo',
            'text' => 'Sale más tarde',
            'image_path' => 'images/slides/segundo.jpg',
            'product_id' => $tour->id,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        Slide::query()->create([
            'title' => 'Primero',
            'text' => 'Oferta de vuelos',
            'image_path' => 'images/slides/primero.jpg',
            'link_url' => 'https://example.com/vuelos',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        Slide::query()->create([
            'title' => 'No mostrar',
            'image_path' => 'images/slides/oculta.jpg',
            'link_url' => '/contacto',
            'sort_order' => 3,
            'is_active' => false,
        ]);
        Slide::query()->create([
            'title' => 'Programada',
            'image_path' => 'images/slides/futura.jpg',
            'link_url' => '/contacto',
            'sort_order' => 4,
            'is_active' => true,
            'starts_on' => '2026-12-01',
        ]);
        Slide::query()->create([
            'title' => 'Ya acabó',
            'image_path' => 'images/slides/pasada.jpg',
            'link_url' => '/contacto',
            'sort_order' => 5,
            'is_active' => true,
            'ends_on' => '2026-11-02',
        ]);

        $html = $this->get(route('inicio'))->assertOk()->getContent();

        $this->assertLessThan(strpos($html, 'Segundo'), strpos($html, 'Primero'));
        $this->assertStringContainsString('Oferta de vuelos', $html);
        $this->assertStringContainsString('https://example.com/vuelos', $html);
        $this->assertStringContainsString('target="_blank"', $html);
        $this->assertStringContainsString(route('destinos.tour', [
            'country' => 'cuba',
            'province' => 'la-habana',
            'tour' => 'tour-de-la-habana',
        ]), $html);
        $this->assertStringContainsString('home-carousel', $html);
        $this->assertStringContainsString('color: #fff', $html);
        $this->assertStringContainsString('aspect-ratio: 16 / 9', $html);
        $this->assertStringContainsString('object-fit: cover', $html);
        $this->assertStringNotContainsString('First slide label', $html);
        $this->assertStringNotContainsString('No mostrar', $html);
        $this->assertStringNotContainsString('Programada', $html);
        $this->assertStringNotContainsString('Ya acabó', $html);

        $tour->update(['status_id' => Status::idFor(Product::class, Status::PRODUCT_NOT_ACTIVE)]);

        $after = $this->get(route('inicio'))->assertOk()->getContent();
        $this->assertStringNotContainsString('Segundo', $after);
        $this->assertStringContainsString('Primero', $after);
    }

    public function test_an_empty_home_does_not_show_the_placeholder_carousel(): void
    {
        $html = $this->get(route('inicio'))->assertOk()->getContent();

        $this->assertStringNotContainsString('carouselExampleCaptions', $html);
        $this->assertStringNotContainsString('First slide label', $html);
    }

    public function test_admin_can_create_and_delete_a_slide(): void
    {
        $image = UploadedFile::fake()->image('promo.jpg', 640, 360);

        $this->actingAs($this->admin)
            ->post(route('admin.slides.store'), [
                'title' => 'Varadero al 20%',
                'text' => 'Hasta el domingo',
                'image' => $image,
                'link_url' => '/contacto',
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.slides.index'));

        $slide = Slide::query()->first();
        $this->assertNotNull($slide);
        $this->assertSame('Varadero al 20%', $slide->title);
        $this->assertTrue($slide->is_active);
        $this->assertFileExists(public_path($slide->image_path));

        $list = $this->actingAs($this->admin)
            ->get(route('admin.slides.index'))
            ->assertOk()
            ->getContent();
        $this->assertStringContainsString('class="btn-group" role="group"', $list);
        $this->assertStringContainsString('En portada', $list);
        $this->assertStringNotContainsString('fa-pencil', $list);
        $this->assertStringNotContainsString('fa-trash', $list);
        $this->assertStringNotContainsString('fa-info', $list);

        $home = $this->get(route('inicio'))->assertOk()->getContent();
        $this->assertStringContainsString('Varadero al 20%', $home);
        $this->assertStringContainsString('Hasta el domingo', $home);

        $this->actingAs($this->admin)
            ->delete(route('admin.slides.destroy', $slide))
            ->assertRedirect(route('admin.slides.index'));

        $this->assertDatabaseMissing('slides', ['id' => $slide->id]);
        $this->assertFileDoesNotExist(public_path($slide->image_path));
    }

    public function test_a_slide_needs_a_product_or_a_safe_link(): void
    {
        $image = UploadedFile::fake()->image('promo.jpg', 640, 360);

        $this->actingAs($this->admin)
            ->from(route('admin.slides.create'))
            ->post(route('admin.slides.store'), [
                'title' => 'Sin destino',
                'image' => $image,
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.slides.create'))
            ->assertSessionHasErrors(['product_id', 'link_url']);

        $this->actingAs($this->admin)
            ->from(route('admin.slides.create'))
            ->post(route('admin.slides.store'), [
                'title' => 'Enlace malo',
                'image' => UploadedFile::fake()->image('promo.jpg', 640, 360),
                'link_url' => 'javascript:alert(1)',
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.slides.create'))
            ->assertSessionHasErrors('link_url');

        $this->assertDatabaseCount('slides', 0);
    }

    public function test_a_slide_image_must_be_an_allowed_format(): void
    {
        $svg = UploadedFile::fake()->createWithContent(
            'foto.jpg',
            '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'
        );
        $this->postSlideImage($svg)
            ->assertSessionHasErrors([
                'image' => 'Formatos permitidos: JPEG, PNG, GIF, WebP.',
            ]);

        $this->postSlideImage(UploadedFile::fake()->create('icono.svg', 20, 'image/svg+xml'))
            ->assertSessionHasErrors([
                'image' => 'Formatos permitidos: JPEG, PNG, GIF, WebP.',
            ]);

        $this->assertDatabaseCount('slides', 0);

        $jpeg = UploadedFile::fake()->image('origen.jpg', 800, 450);
        $disguised = new UploadedFile(
            $jpeg->getRealPath(),
            'foto.png',
            'image/png',
            null,
            true
        );

        $this->postSlideImage($disguised)
            ->assertRedirect(route('admin.slides.index'));

        $slide = Slide::query()->first();
        $this->assertNotNull($slide);
        $this->assertStringEndsWith('.jpg', $slide->image_path);
        $this->assertFileExists(public_path($slide->image_path));
    }

    public function test_a_slide_image_is_center_cropped_to_16_by_9(): void
    {
        $this->postSlideImage(UploadedFile::fake()->image('retrato.jpg', 360, 640))
            ->assertRedirect(route('admin.slides.index'));

        $portrait = Slide::query()->first();
        $this->assertNotNull($portrait);
        $this->assertSame([360, (int) round(360 * 9 / 16)], $this->imageSize($portrait->image_path));

        $this->postSlideImage(UploadedFile::fake()->image('cuadrada.png', 400, 400))
            ->assertRedirect(route('admin.slides.index'));

        $square = Slide::query()->orderByDesc('id')->first();
        $this->assertNotNull($square);
        $this->assertStringEndsWith('.png', $square->image_path);
        $this->assertSame([400, (int) round(400 * 9 / 16)], $this->imageSize($square->image_path));

        $this->postSlideImage(UploadedFile::fake()->image('ancha.jpg', 1600, 400))
            ->assertRedirect(route('admin.slides.index'));

        $wide = Slide::query()->orderByDesc('id')->first();
        $this->assertNotNull($wide);
        $this->assertSame([(int) round(400 * 16 / 9), 400], $this->imageSize($wide->image_path));
    }

    public function test_a_valid_image_stays_selected_when_another_field_fails(): void
    {
        $this->actingAs($this->admin)
            ->from(route('admin.slides.create'))
            ->post(route('admin.slides.store'), [
                'title' => 'Varadero',
                'image' => UploadedFile::fake()->image('promo.jpg', 800, 450),
                'sort_order' => 1,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.slides.create'))
            ->assertSessionHasErrors('link_url');

        $pending = session('slide_pending_image');
        $this->assertIsArray($pending);
        $this->assertFileExists(public_path($pending['path']));

        $form = $this->actingAs($this->admin)
            ->get(route('admin.slides.create'))
            ->assertOk()
            ->getContent();
        $this->assertStringContainsString('Imagen seleccionada. Si no eliges otra, se usará esta.', $form);
        $this->assertStringContainsString('name="kept_image" value="'.$pending['token'].'"', $form);
        $this->assertDoesNotMatchRegularExpression(
            '/<input type="file" name="image"[^>]*required/',
            $form
        );

        $this->actingAs($this->admin)
            ->post(route('admin.slides.store'), [
                'title' => 'Varadero',
                'link_url' => '/contacto',
                'sort_order' => 1,
                'is_active' => '1',
                'kept_image' => $pending['token'],
            ])
            ->assertRedirect(route('admin.slides.index'));

        $slide = Slide::query()->first();
        $this->assertNotNull($slide);
        $this->assertStringEndsWith('.jpg', $slide->image_path);
        $this->assertFileExists(public_path($slide->image_path));
        $this->assertFileDoesNotExist(public_path($pending['path']));
    }

    public function test_a_viewer_cannot_create_a_slide(): void
    {
        $viewer = User::factory()->viewer()->create();

        $this->actingAs($viewer)
            ->get(route('admin.slides.create'))
            ->assertForbidden();

        $this->actingAs($viewer)
            ->post(route('admin.slides.store'), [
                'title' => 'No',
                'link_url' => '/contacto',
                'sort_order' => 1,
            ])
            ->assertForbidden();
    }

    /**
     * @return array{0: int, 1: int}
     */
    private function imageSize(string $relativePath): array
    {
        $size = getimagesize(public_path($relativePath));
        $this->assertNotFalse($size);

        return [$size[0], $size[1]];
    }

    private function postSlideImage(UploadedFile $image): \Illuminate\Testing\TestResponse
    {
        return $this->actingAs($this->admin)
            ->from(route('admin.slides.create'))
            ->post(route('admin.slides.store'), [
                'title' => 'Imagen',
                'image' => $image,
                'link_url' => '/contacto',
                'sort_order' => 1,
                'is_active' => '1',
            ]);
    }

    private function publicTour(): Product
    {
        $country = Category::factory()->create([
            'name' => 'Cuba',
            'slug' => 'cuba',
        ]);
        $province = Category::factory()->create([
            'name' => 'La Habana',
            'slug' => 'la-habana',
            'parent_id' => $country->id,
        ]);
        $tour = Product::factory()->create([
            'name' => 'Tour de La Habana',
            'slug' => 'tour-de-la-habana',
            'category_id' => $province->id,
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            'supplier_id' => Supplier::factory(),
            'created_user_id' => $this->admin->id,
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $tour->id,
            'available_stock' => 5,
            'total_stock' => 5,
            'price' => 100,
            'taxes' => 10,
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_date' => '2026-12-01 10:00:00',
        ]);

        return $tour;
    }
}
