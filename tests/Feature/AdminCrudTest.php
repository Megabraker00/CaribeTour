<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Product;
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
            'status_id' => Status::BLOG_PUBLISHED,
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
                'status_id' => Status::BLOG_PUBLISHED,
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
                'status_id' => Status::BLOG_DRAFT,
                'blog_image' => $image,
                'blog_image_alt' => 'Playa de Punta Cana al atardecer',
                'author_linkedin' => 'https://www.linkedin.com/in/guia-punta-cana',
            ])
            ->assertRedirect(route('admin.blogs.show', $blog));

        $blog = $blog->fresh();
        $this->assertSame('Guía Punta Cana 2026', $blog->name);
        $this->assertSame(Status::BLOG_DRAFT, $blog->status_id);
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
                'status_id' => Status::SUPPLIER_ACTIVE,
            ])
            ->assertRedirect(route('admin.suppliers.index'));

        $supplier = Supplier::query()->first();
        Product::factory()->create([
            'supplier_id' => $supplier->id,
            'created_user_id' => $this->admin->id,
            'type_id' => Type::TOUR,
            'status_id' => Status::PRODUCT_ACTIVE,
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
                'status_id' => Status::EMPLOYEE_ACTIVE,
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
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'editor@caribetour.test']);

        $this->actingAs($this->admin)
            ->delete(route('admin.users.destroy', $this->admin))
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
