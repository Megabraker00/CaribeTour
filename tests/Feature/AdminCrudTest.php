<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Itinerary;
use App\Models\Position;
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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
    }

    public function test_admin_pages_use_the_navy_favicon(): void
    {
        $login = $this->get(route('login'))->assertOk()->getContent();
        $dashboard = $this->actingAs($this->admin)->get(route('admin'))->assertOk()->getContent();

        foreach ([$login, $dashboard] as $html) {
            $this->assertStringContainsString('favicons/favicon.svg', $html);
            $this->assertStringContainsString('favicons/favicon.ico', $html);
            $this->assertStringNotContainsString('images/favicon.svg', $html);
        }

        $this->assertGreaterThan(1000, filesize(public_path('favicons/favicon.ico')));
        $this->assertGreaterThan(1000, filesize(public_path('favicons/favicon.svg')));
    }

    public function test_admin_dashboard_uses_the_same_bootstrap_skin_as_the_lists(): void
    {
        $css = 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css';

        $dashboard = $this->actingAs($this->admin)
            ->get(route('admin'))
            ->assertOk()
            ->getContent();
        $reservations = $this->actingAs($this->admin)
            ->get(route('admin.booking.index'))
            ->assertOk()
            ->getContent();

        $this->assertSame(1, substr_count($dashboard, $css));
        $this->assertSame(1, substr_count($reservations, $css));
    }

    public function test_client_bookings_are_listed_from_newest_to_oldest(): void
    {
        $client = Client::factory()->create();
        $older = Booking::factory()->create([
            'client_id' => $client->id,
            'external_ref' => 'LOC-OLDER',
        ]);
        $older->forceFill(['created_at' => '2026-01-15 08:30:00'])->save();
        $newer = Booking::factory()->create([
            'client_id' => $client->id,
            'external_ref' => 'LOC-NEWER',
        ]);
        $newer->forceFill(['created_at' => '2026-03-02 18:45:00'])->save();

        $newerOn = $newer->fresh()->created_at->timezone('Europe/Madrid')->format('d/m/Y H:i');
        $olderOn = $older->fresh()->created_at->timezone('Europe/Madrid')->format('d/m/Y H:i');

        $this->actingAs($this->admin)
            ->get(route('admin.client.show', $client))
            ->assertOk()
            ->assertSeeInOrder([$newerOn, 'LOC-NEWER', $olderOn, 'LOC-OLDER']);
    }

    public function test_guests_cannot_open_admin_cruds(): void
    {
        $this->get(route('admin.blogs.index'))->assertRedirect(route('login'));
        $this->get(route('admin.employees.index'))->assertRedirect(route('login'));
        $this->get(route('admin.suppliers.index'))->assertRedirect(route('login'));
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
    }

    public function test_blogs_datatable_is_available_for_admin(): void
    {
        Blog::factory()->create([
            'name' => 'Post DataTable',
            'created_user_id' => $this->admin->id,
            'status_id' => Status::idFor(Blog::class, Status::BLOG_PUBLISHED),
        ]);

        $this->actingAs($this->admin)
            ->getJson(route('api.datatable.blogs', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search' => ['value' => 'DataTable'],
                'columns' => [
                    ['data' => 'id', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'name', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'slug', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'status_name', 'searchable' => 'true', 'orderable' => 'true'],
                    ['data' => 'author', 'searchable' => 'true', 'orderable' => 'true'],
                ],
            ]))
            ->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonFragment(['name' => 'Post DataTable']);
    }

    public function test_admin_can_create_and_update_a_blog(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.blogs.store'), [
                'name' => 'Guía Punta Cana',
                'slug' => 'guia-punta-cana',
                'content' => 'Texto del post',
                'status_id' => Status::idFor(Blog::class, Status::BLOG_PUBLISHED),
            ])
            ->assertRedirect(route('admin.blogs.index'));

        $blog = Blog::query()->first();
        $this->assertSame('Guía Punta Cana', $blog->name);
        $this->assertSame($this->admin->id, $blog->created_user_id);

        $image = UploadedFile::fake()->image('portada.jpg', 640, 360);

        $this->actingAs($this->admin)
            ->put(route('admin.blogs.update', $blog), [
                'name' => 'Guía Punta Cana 2026',
                'slug' => 'guia-punta-cana',
                'content' => 'Actualizado',
                'status_id' => Status::idFor(Blog::class, Status::BLOG_DRAFT),
                'blog_image' => $image,
                'blog_image_alt' => 'Playa de Punta Cana al atardecer',
                'author_linkedin' => 'https://www.linkedin.com/in/guia-punta-cana',
            ])
            ->assertRedirect(route('admin.blogs.show', $blog));

        $blog = $blog->fresh();
        $this->assertSame('Guía Punta Cana 2026', $blog->name);
        $this->assertTrue($blog->hasStatusSlug(Status::BLOG_DRAFT));
        $this->assertSame('https://www.linkedin.com/in/guia-punta-cana', $blog->author_linkedin);

        $mainImage = $blog->mainImage();
        $this->assertNotNull($mainImage);
        $this->assertTrue($mainImage->is_main);
        $this->assertSame('Playa de Punta Cana al atardecer', $mainImage->alt);
        $this->assertFileExists(public_path($mainImage->path));

        $this->actingAs($this->admin)
            ->get(route('admin.blogs.edit', $blog))
            ->assertOk()
            ->assertSee('pell-blog-content-mount', false)
            ->assertSee('pell.min.js', false)
            ->assertSee('pell.min.css', false)
            ->assertSee('name="blog_image"', false)
            ->assertSee('name="blog_image_alt"', false)
            ->assertSee('Playa de Punta Cana al atardecer')
            ->assertSee('name="author_linkedin"', false)
            ->assertSee($mainImage->path, false);

        $this->actingAs($this->admin)
            ->get(route('admin.blogs.show', $blog))
            ->assertOk()
            ->assertSee('Guía Punta Cana 2026')
            ->assertSee(route('blogs.show', $blog->slug), false)
            ->assertSee('target="_blank"', false)
            ->assertSee($mainImage->path, false)
            ->assertSee('Playa de Punta Cana al atardecer');

        File::delete(public_path($mainImage->path));
        File::deleteDirectory(public_path('images/blogs/guia-punta-cana'));
    }

    public function test_admin_can_create_a_supplier_and_cannot_delete_it_when_it_has_products(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.suppliers.store'), [
                'name' => 'Hotelera Caribe',
                'status_id' => Status::idFor(Supplier::class, Status::SUPPLIER_ACTIVE),
            ])
            ->assertRedirect(route('admin.suppliers.index'));

        $supplier = Supplier::query()->first();
        Product::factory()->create([
            'supplier_id' => $supplier->id,
            'created_user_id' => $this->admin->id,
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.suppliers.destroy', $supplier))
            ->assertRedirect(route('admin.suppliers.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);
    }

    public function test_admin_can_create_an_employee(): void
    {
        $position = Position::factory()->create();

        $this->actingAs($this->admin)
            ->post(route('admin.employees.store'), [
                'name' => 'Laura',
                'last_name' => 'Sanz',
                'email_personal' => 'laura@example.com',
                'email_company' => 'laura@caribetour.test',
                'dni_passport' => '12345678A',
                'phone' => '+34600111222',
                'position_id' => $position->id,
                'status_id' => Status::idFor(Employee::class, Status::EMPLOYEE_ACTIVE),
            ])
            ->assertRedirect(route('admin.employees.index'));

        $employee = Employee::query()->first();
        $this->assertSame('Laura', $employee->name);
        $this->assertSame($this->admin->id, $employee->created_user_id);
    }

    public function test_admin_can_create_a_user_and_cannot_delete_themselves(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.users.store'), [
                'name' => 'Editor',
                'email' => 'editor@caribetour.test',
                'password' => 'password1',
                'password_confirmation' => 'password1',
                'role' => 'agent',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'editor@caribetour.test']);

        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_listing_action_buttons_use_the_same_text_group(): void
    {
        Category::factory()->create();
        Terminal::factory()->create();
        Employee::factory()->create(['created_user_id' => $this->admin->id]);
        Supplier::factory()->create();
        $client = Client::factory()->create();
        Booking::factory()->create(['client_id' => $client->id]);
        $product = Product::factory()->create([
            'category_id' => Category::factory(),
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            'supplier_id' => Supplier::factory(),
            'created_user_id' => $this->admin->id,
        ]);

        $pages = [
            route('admin.blogs.index'),
            route('admin.booking.index'),
            route('admin.facturas.index'),
            route('admin.client.index'),
            route('admin.contact-messages.index'),
            route('admin.catalog.index', 'tours'),
            route('admin.users.index'),
            route('admin.types.index'),
            route('admin.statuses.index'),
            route('admin.categories.index'),
            route('admin.terminals.index'),
            route('admin.employees.index'),
            route('admin.suppliers.index'),
        ];

        foreach ($pages as $url) {
            $html = $this->actingAs($this->admin)->get($url)->assertOk()->getContent();
            $this->assertStringContainsString('class="btn-group" role="group"', $html, $url);
            $this->assertStringNotContainsString('fa-pencil', $html, $url);
            $this->assertStringNotContainsString('fa-trash', $html, $url);
            $this->assertStringNotContainsString('fa-info', $html, $url);
        }

        $edit = $this->actingAs($this->admin)
            ->get(route('admin.catalog.edit', ['kind' => 'tours', 'id' => $product->id]))
            ->assertOk()
            ->getContent();
        $this->assertStringContainsString('class="btn-group" role="group"', $edit);
        $this->assertStringContainsString('btn btn-sm btn-info" href="\' + base + \'/\' + row.id + \'/segments"', $edit);
        $this->assertStringContainsString('btn btn-sm btn-secondary" href="\' + base + \'/\' + row.id + \'/prices"', $edit);
        $this->assertStringNotContainsString('fa-route', $edit);

        $this->actingAs($this->admin)
            ->get(route('admin.client.show', $client))
            ->assertOk()
            ->assertSee('class="btn-group" role="group"', false)
            ->assertSee('>Más Info</a>', false)
            ->assertDontSee('fa-info', false);

        $itinerary = Itinerary::factory()->create(['product_id' => $product->id]);
        Segment::factory()->create(['itinerary_id' => $itinerary->id]);

        $this->actingAs($this->admin)
            ->get(route('admin.itineraries.segments.index', $itinerary))
            ->assertOk()
            ->assertSee('class="btn-group" role="group"', false)
            ->assertSee('>Editar</a>', false)
            ->assertSee('>Eliminar</button>', false)
            ->assertDontSee('fa-trash', false)
            ->assertDontSee('Borrar', false);
    }
}
