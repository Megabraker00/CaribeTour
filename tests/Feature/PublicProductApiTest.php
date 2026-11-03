<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
    }

    public function test_tour_itineraries_are_only_listed_for_a_public_product(): void
    {
        $public = $this->tour('Tour visible');
        $hidden = $this->tour('Tour oculto', Status::PRODUCT_NOT_ACTIVE);

        $this->getJson('/api/v1/products/'.$hidden->id.'/itineraries')->assertNotFound();

        $this->getJson('/api/v1/products/'.$public->id.'/itineraries')
            ->assertOk()
            ->assertJsonPath('data.0.price', $public->itineraries()->value('price'));
    }

    private function tour(string $name, string $status = Status::PRODUCT_ACTIVE): Product
    {
        $product = Product::factory()->create([
            'name' => $name,
            'category_id' => Category::factory(),
            'type_id' => Type::idFor(Product::class, Type::TOUR),
            'status_id' => Status::idFor(Product::class, $status),
            'supplier_id' => Supplier::factory(),
            'created_user_id' => User::factory(),
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $product->id,
            'available_stock' => 4,
            'total_stock' => 4,
            'price' => 120,
            'taxes' => 15,
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'sort_order' => 1,
            'departure_date' => now()->addDays(10),
        ]);

        return $product;
    }
}
