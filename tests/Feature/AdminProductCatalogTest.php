<?php

namespace Tests\Feature;

use App\Models\Category;
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

class AdminProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
    }

    public function test_guests_cannot_open_product_catalogs(): void
    {
        $this->get(route('admin.catalog.index', 'tours'))->assertRedirect(route('login'));
        $this->get(route('admin.catalog.index', 'excursiones'))->assertRedirect(route('login'));
        $this->get(route('admin.catalog.index', 'hoteles'))->assertRedirect(route('login'));
        $this->get(route('admin.catalog.index', 'seguros'))->assertRedirect(route('login'));
    }

    public function test_admin_can_open_each_product_catalog(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.catalog.index', 'tours'))
            ->assertOk()
            ->assertSee('Tours');

        $this->actingAs($this->admin)
            ->get(route('admin.catalog.index', 'excursiones'))
            ->assertOk()
            ->assertSee('Excursiones');

        $this->actingAs($this->admin)
            ->get(route('admin.catalog.index', 'hoteles'))
            ->assertOk()
            ->assertSee('Hoteles');

        $this->actingAs($this->admin)
            ->get(route('admin.catalog.index', 'seguros'))
            ->assertOk()
            ->assertSee('Seguros');
    }

    public function test_admin_can_create_an_excursion_hotel_and_insurance(): void
    {
        $category = Category::factory()->create();
        $supplier = Supplier::factory()->create();

        foreach ([
            ['excursiones', Type::EXCURSION, 'Excursión Saona'],
            ['hoteles', Type::HOTEL, 'Hotel Caribe'],
            ['seguros', Type::INSURANCE, 'Seguro de viaje'],
        ] as [$kind, $typeId, $name]) {
            $slug = strtolower(str_replace(' ', '-', $name)).'-test';

            $this->actingAs($this->admin)
                ->post(route('admin.catalog.store', $kind), [
                    'name' => $name,
                    'slug' => $slug,
                    'type_id' => Type::TOUR,
                    'category_id' => $category->id,
                    'supplier_id' => $supplier->id,
                    'status_id' => Status::PRODUCT_ACTIVE,
                    'meta_description' => '<p>Descripción</p>',
                    'meta_includes' => "Traslado\nGuía",
                    'meta_stars' => 4,
                ])
                ->assertRedirect(route('admin.catalog.show', [$kind, Product::query()->where('slug', $slug)->value('id')]));

            $product = Product::query()->where('slug', $slug)->first();
            $this->assertNotNull($product);
            $this->assertSame($typeId, (int) $product->type_id);
            $this->assertSame($this->admin->id, $product->created_user_id);
            $this->assertSame(4, $product->stars());
        }
    }

    public function test_product_show_is_scoped_to_its_catalog(): void
    {
        $hotel = Product::factory()->hotel()->create([
            'created_user_id' => $this->admin->id,
            'status_id' => Status::PRODUCT_ACTIVE,
        ]);
        $tour = Product::factory()->tour()->create([
            'created_user_id' => $this->admin->id,
            'status_id' => Status::PRODUCT_ACTIVE,
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.catalog.show', ['hoteles', $hotel->id]))
            ->assertOk()
            ->assertSee($hotel->name);

        $this->actingAs($this->admin)
            ->get(route('admin.catalog.show', ['tours', $hotel->id]))
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->get(route('admin.catalog.show', ['hoteles', $tour->id]))
            ->assertNotFound();
    }

    public function test_admin_can_update_a_hotel(): void
    {
        $hotel = Product::factory()->hotel()->create([
            'name' => 'Hotel Viejo',
            'slug' => 'hotel-viejo',
            'created_user_id' => $this->admin->id,
            'status_id' => Status::PRODUCT_DRAFT,
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.catalog.update', ['hoteles', $hotel->id]), [
                'name' => 'Hotel Nuevo',
                'slug' => 'hotel-nuevo',
                'category_id' => $hotel->category_id,
                'supplier_id' => $hotel->supplier_id,
                'status_id' => Status::PRODUCT_ACTIVE,
                'meta_description' => '<p>Actualizado</p>',
                'meta_includes' => 'Desayuno',
                'meta_stars' => 5,
            ])
            ->assertRedirect(route('admin.catalog.show', ['hoteles', $hotel->id]));

        $hotel->refresh();
        $this->assertSame('Hotel Nuevo', $hotel->name);
        $this->assertSame(Type::HOTEL, (int) $hotel->type_id);
        $this->assertSame(Status::PRODUCT_ACTIVE, (int) $hotel->status_id);
        $this->assertSame(5, $hotel->stars());
    }

    public function test_catalog_datatable_only_returns_products_of_that_type(): void
    {
        Product::factory()->tour()->create([
            'name' => 'Tour Visible',
            'created_user_id' => $this->admin->id,
            'status_id' => Status::PRODUCT_ACTIVE,
        ]);
        Product::factory()->excursion()->create([
            'name' => 'Excursión Visible',
            'created_user_id' => $this->admin->id,
            'status_id' => Status::PRODUCT_ACTIVE,
        ]);

        $columns = [
            ['data' => 'id', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'name', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'category', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'status_name', 'searchable' => 'true', 'orderable' => 'true'],
            ['data' => 'precio', 'searchable' => 'true', 'orderable' => 'true'],
        ];

        $this->actingAs($this->admin)
            ->getJson(route('api.datatable.catalog', [
                'kind' => 'excursiones',
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search' => ['value' => ''],
                'columns' => $columns,
            ]))
            ->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonFragment(['name' => 'Excursión Visible'])
            ->assertJsonMissing(['name' => 'Tour Visible']);

        $this->actingAs($this->admin)
            ->getJson(route('api.datatable.tours', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search' => ['value' => ''],
                'columns' => $columns,
            ]))
            ->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonFragment(['name' => 'Tour Visible'])
            ->assertJsonMissing(['name' => 'Excursión Visible']);
    }

    public function test_admin_can_upload_a_hotel_image(): void
    {
        $hotel = Product::factory()->hotel()->create([
            'slug' => 'hotel-imagen-test',
            'created_user_id' => $this->admin->id,
            'status_id' => Status::PRODUCT_ACTIVE,
        ]);

        $this->actingAs($this->admin)
            ->post(route('admin.catalog.images.store', ['hoteles', $hotel->id]), [
                'images' => [UploadedFile::fake()->image('fachada.jpg', 200, 150)],
            ])
            ->assertRedirect();

        $hotel->refresh();
        $this->assertSame(1, $hotel->images()->count());
        $this->assertStringStartsWith('images/hoteles/hotel-imagen-test/', $hotel->images()->first()->path);

        File::deleteDirectory(public_path('images/hoteles/hotel-imagen-test'));
    }
}
