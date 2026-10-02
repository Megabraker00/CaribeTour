<?php

namespace Tests\Feature;

use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTypeLookupTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
        $this->admin = User::factory()->create();
    }

    public function test_id_for_keeps_resolving_the_system_slug_when_another_activo_exists(): void
    {
        $systemId = Status::idFor(Product::class, Status::PRODUCT_ACTIVE);

        Status::query()->create([
            'name' => 'Activo',
            'slug' => 'activo-extra',
            'statusable' => Product::class,
            'is_system' => false,
        ]);

        Status::forgetLookupCache();

        $this->assertSame($systemId, Status::idFor(Product::class, Status::PRODUCT_ACTIVE));
        $this->assertNotSame($systemId, Status::idFor(Product::class, 'activo-extra'));
    }

    public function test_public_catalog_still_shows_tours_after_a_duplicate_activo_name(): void
    {
        $product = Product::factory()->tour()->create([
            'status_id' => Status::idFor(Product::class, Status::PRODUCT_ACTIVE),
            'created_user_id' => $this->admin->id,
        ]);
        $itinerary = Itinerary::factory()->create(['product_id' => $product->id]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'departure_date' => now()->addWeek(),
        ]);

        Status::query()->create([
            'name' => 'Activo',
            'slug' => 'activo-extra',
            'statusable' => Product::class,
        ]);

        $this->assertTrue(
            Product::query()->publicVisibleTour()->whereKey($product->id)->exists()
        );
    }

    public function test_admin_can_create_a_custom_status_and_cannot_delete_system_ones(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.statuses.store'), [
                'name' => 'Pendiente revisión',
                'slug' => 'pendiente-revision',
                'statusable' => Product::class,
            ])
            ->assertRedirect(route('admin.statuses.index'));

        $custom = Status::query()->where('slug', 'pendiente-revision')->first();
        $this->assertNotNull($custom);
        $this->assertFalse($custom->is_system);

        $system = Status::query()
            ->where('statusable', Product::class)
            ->where('slug', Status::PRODUCT_ACTIVE)
            ->first();

        $this->actingAs($this->admin)
            ->from(route('admin.statuses.index'))
            ->delete(route('admin.statuses.destroy', $system))
            ->assertRedirect(route('admin.statuses.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('statuses', ['id' => $system->id]);

        $this->actingAs($this->admin)
            ->put(route('admin.statuses.update', $system), [
                'name' => 'Disponible',
                'slug' => 'hacked',
                'statusable' => Product::class,
            ])
            ->assertRedirect(route('admin.statuses.index'));

        $system->refresh();
        $this->assertSame('Disponible', $system->name);
        $this->assertSame(Status::PRODUCT_ACTIVE, $system->slug);
    }

    public function test_admin_cannot_delete_a_system_type(): void
    {
        $tour = Type::query()
            ->where('typeable', Product::class)
            ->where('slug', Type::TOUR)
            ->first();

        $this->actingAs($this->admin)
            ->from(route('admin.types.index'))
            ->delete(route('admin.types.destroy', $tour))
            ->assertRedirect(route('admin.types.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('types', ['id' => $tour->id, 'slug' => Type::TOUR]);
    }

    public function test_status_and_type_index_show_slug_and_system_badge(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.statuses.index'))
            ->assertOk()
            ->assertSee('sistema')
            ->assertSee('active')
            ->assertSee('published');

        $this->actingAs($this->admin)
            ->get(route('admin.types.index'))
            ->assertOk()
            ->assertSee('sistema')
            ->assertSee('tour')
            ->assertSee('stripe');
    }
}
