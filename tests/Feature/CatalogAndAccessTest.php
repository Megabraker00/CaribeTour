<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Product;
use App\Models\Status;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAndAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
    }

    public function test_admin_redirects_guests_to_login(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_open_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertOk();
    }

    public function test_api_product_store_requires_authentication(): void
    {
        $this->postJson('/api/v1/products', [
            'name' => 'Hack',
            'slug' => 'hack',
            'category_id' => 1,
            'type_id' => 1,
            'status_id' => 1,
            'supplier_id' => 1,
        ])->assertUnauthorized();
    }

    public function test_blog_show_returns_404_for_drafts(): void
    {
        $draft = Blog::factory()->draft()->create();

        $this->get(route('blogs.show', $draft->slug))->assertNotFound();
    }

    public function test_published_blog_is_visible(): void
    {
        $post = Blog::factory()->create(['name' => 'Post publicado']);

        $this->get(route('blogs.show', $post->slug))
            ->assertOk()
            ->assertSee('Post publicado');
    }

    public function test_service_detail_returns_404_for_tours(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'slug' => 'tour-no-servicio',
            'type_id' => Type::TOUR,
            'status_id' => Status::PRODUCT_ACTIVE,
            'created_user_id' => $user->id,
        ]);

        $this->get(route('servicios.detalle', $product->slug))->assertNotFound();
    }
}
