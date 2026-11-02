<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Itinerary;
use App\Models\Product;
use App\Models\Segment;
use App\Models\Type;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([StatusSeeder::class, TypeSeeder::class]);
    }

    public function test_guest_can_send_a_contact_message(): void
    {
        $this->post(route('contacto.store'), [
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'subject' => 'Viaje a Punta Cana',
            'message' => 'Quiero un viaje a Punta Cana.',
        ])->assertRedirect(route('contacto'))
            ->assertSessionHas('success', 'Hemos recibido tu mensaje. Te responderemos lo antes posible.');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'subject' => 'Viaje a Punta Cana',
            'message' => 'Quiero un viaje a Punta Cana.',
            'product_id' => null,
            'read_at' => null,
        ]);

        $this->get(route('contacto'))
            ->assertOk()
            ->assertSee('Hemos recibido tu mensaje. Te responderemos lo antes posible.');
    }

    public function test_contact_form_requires_name_email_and_message(): void
    {
        $this->from(route('contacto'))
            ->post(route('contacto.store'), [
                'name' => '',
                'email' => 'no-es-un-correo',
                'subject' => '',
                'message' => '',
            ])
            ->assertRedirect(route('contacto'))
            ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_rate_limits_repeated_posts(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.20']);

        $payload = [
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'subject' => 'Hola',
            'message' => 'Hola',
        ];

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post(route('contacto.store'), $payload)->assertRedirect(route('contacto'));
        }

        $this->from(route('contacto'))
            ->post(route('contacto.store'), $payload)
            ->assertRedirect(route('contacto'))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('contact_messages', 5);
    }

    public function test_guest_cannot_open_contact_messages(): void
    {
        $this->get(route('admin.contact-messages.index'))->assertRedirect(route('login'));
    }

    public function test_staff_can_read_a_message_and_only_writers_can_delete_it(): void
    {
        $message = ContactMessage::create([
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'message' => 'Quiero un viaje a Punta Cana.',
        ]);

        $viewer = User::factory()->viewer()->create();

        $this->actingAs($viewer)
            ->get(route('admin.contact-messages.index'))
            ->assertOk()
            ->assertSee('Lista de mensajes')
            ->assertSee(route('api.datatable.contact-messages'), false);

        $this->actingAs($viewer)
            ->getJson(route('api.datatable.contact-messages', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search' => ['value' => 'Punta Cana'],
            ]))
            ->assertOk()
            ->assertJsonPath('recordsFiltered', 1)
            ->assertJsonFragment([
                'name' => 'Ana Pérez',
                'status_label' => 'Nuevo',
            ]);

        $this->actingAs($viewer)
            ->get(route('admin.contact-messages.show', $message))
            ->assertOk()
            ->assertSee('Quiero un viaje a Punta Cana.')
            ->assertSee('Tour')
            ->assertSee('—');

        $this->assertNotNull($message->fresh()->read_at);

        $this->actingAs($viewer)
            ->delete(route('admin.contact-messages.destroy', $message))
            ->assertForbidden();

        $this->assertDatabaseHas('contact_messages', ['id' => $message->id]);

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete(route('admin.contact-messages.destroy', $message))
            ->assertRedirect(route('admin.contact-messages.index'));

        $this->assertSoftDeleted('contact_messages', ['id' => $message->id]);

        $this->actingAs($admin)
            ->getJson(route('api.datatable.contact-messages', [
                'draw' => 1,
                'start' => 0,
                'length' => 10,
                'search' => ['value' => 'Punta Cana'],
            ]))
            ->assertOk()
            ->assertJsonPath('recordsFiltered', 0);

        $this->actingAs($admin)
            ->get(route('admin.contact-messages.show', $message))
            ->assertNotFound();
    }

    public function test_consultar_opens_contact_with_the_tour_subject_and_keeps_the_tour(): void
    {
        $tour = $this->publicTour();
        $inquiry = route('contacto', ['tour' => $tour->slug]);

        $this->get(route('destinos.provincia', ['country' => 'mexico', 'province' => 'cancun']))
            ->assertOk()
            ->assertSee($inquiry, false);

        $this->get(route('destinos.tour', ['country' => 'mexico', 'province' => 'cancun', 'tour' => $tour->slug]))
            ->assertOk()
            ->assertSee($inquiry, false);

        $this->get($inquiry)
            ->assertOk()
            ->assertSee('value="'.$tour->name.'"', false)
            ->assertSee('value="'.$tour->slug.'"', false);

        $this->post(route('contacto.store'), [
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'subject' => 'Fechas en agosto',
            'message' => '¿Hay salida en agosto?',
            'product' => $tour->slug,
        ])->assertRedirect(route('contacto'));

        $message = ContactMessage::query()->firstOrFail();
        $this->assertSame('Fechas en agosto', $message->subject);
        $this->assertSame($tour->id, $message->product_id);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.contact-messages.show', $message))
            ->assertOk()
            ->assertSee('Fechas en agosto')
            ->assertSee($tour->name);

        $this->get(route('contacto', ['tour' => 'no-existe']))
            ->assertOk()
            ->assertDontSee('name="product"', false);
    }

    private function publicTour(): Product
    {
        $country = Category::factory()->create(['name' => 'México', 'slug' => 'mexico']);
        $province = Category::factory()->create([
            'name' => 'Cancún',
            'slug' => 'cancun',
            'parent_id' => $country->id,
        ]);
        $tour = Product::factory()->tour()->active()->create([
            'name' => 'Free tour por el casco antiguo',
            'slug' => 'free-tour-por-el-casco-antiguo',
            'category_id' => $province->id,
        ]);
        $itinerary = Itinerary::factory()->create([
            'product_id' => $tour->id,
            'available_stock' => 4,
            'total_stock' => 4,
        ]);
        Segment::factory()->create([
            'itinerary_id' => $itinerary->id,
            'type_id' => Type::idFor(Product::class, Type::FLIGHT),
            'departure_date' => now()->addWeek(),
        ]);

        return $tour;
    }
}
