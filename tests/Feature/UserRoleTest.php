<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Blog;
use App\Models\Status;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Database\Seeders\TypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([TypeSeeder::class, StatusSeeder::class]);
    }

    public function test_staff_roles_can_open_the_admin_panel(): void
    {
        foreach ([UserRole::Admin, UserRole::Agent, UserRole::Viewer] as $role) {
            $user = User::factory()->create(['role' => $role, 'email' => $role->value.'@caribetour.test']);

            $this->actingAs($user)->get('/admin')->assertOk();
        }
    }

    public function test_viewer_can_read_but_cannot_change_data(): void
    {
        $viewer = User::factory()->viewer()->create();

        $this->actingAs($viewer)->get(route('admin.blogs.index'))->assertOk();
        $this->actingAs($viewer)->get(route('admin.blogs.create'))->assertForbidden();
        $this->actingAs($viewer)
            ->post(route('admin.blogs.store'), $this->blogPayload('no-deberia'))
            ->assertForbidden();
        $this->actingAs($viewer)->get(route('admin.users.index'))->assertForbidden();

        $this->assertDatabaseMissing('blogs', ['slug' => 'no-deberia']);
    }

    public function test_agent_can_write_but_cannot_manage_users(): void
    {
        $agent = User::factory()->agent()->create();

        $this->actingAs($agent)
            ->get('/admin')
            ->assertOk()
            ->assertDontSee('admin/usuarios', false);

        $this->actingAs($agent)->get(route('admin.users.index'))->assertForbidden();

        $this->actingAs($agent)
            ->post(route('admin.blogs.store'), $this->blogPayload('nota-del-agente'))
            ->assertRedirect(route('admin.blogs.index'));

        $this->assertDatabaseHas('blogs', ['slug' => 'nota-del-agente']);
    }

    public function test_admin_assigns_roles_and_cannot_remove_the_last_admin(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Administrador', false)
            ->assertSee('admin/usuarios', false);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Agente Nuevo',
                'email' => 'agente@caribetour.test',
                'password' => 'password1',
                'password_confirmation' => 'password1',
                'role' => UserRole::Agent->value,
            ])
            ->assertRedirect(route('admin.users.index'));

        $agent = User::query()->where('email', 'agente@caribetour.test')->first();
        $this->assertSame(UserRole::Agent, $agent->role);
        $this->assertFalse($agent->isAdmin());

        $this->actingAs($admin)
            ->from(route('admin.users.edit', $admin))
            ->put(route('admin.users.update', $admin), [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => UserRole::Viewer->value,
            ])
            ->assertRedirect(route('admin.users.edit', $admin))
            ->assertSessionHasErrors('role');

        $this->assertTrue($admin->fresh()->isAdmin());

        $second = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put(route('admin.users.update', $second), [
                'name' => $second->name,
                'email' => $second->email,
                'role' => UserRole::Viewer->value,
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertSame(UserRole::Viewer, $second->fresh()->role);
    }

    public function test_later_public_registration_is_read_only(): void
    {
        User::factory()->admin()->create();

        $this->post('/register', [
            'name' => 'Publico',
            'email' => 'publico@caribetour.test',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ])->assertRedirect('/admin');

        $registered = User::query()->where('email', 'publico@caribetour.test')->first();
        $this->assertSame(UserRole::Viewer, $registered->role);
        $this->assertFalse($registered->isAdmin());
    }

    public function test_first_registered_user_becomes_admin(): void
    {
        $this->post('/register', [
            'name' => 'Primero',
            'email' => 'primero@caribetour.test',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ])->assertRedirect('/admin');

        $this->assertTrue(
            User::query()->where('email', 'primero@caribetour.test')->first()->isAdmin()
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function blogPayload(string $slug): array
    {
        return [
            'name' => 'Post de prueba',
            'slug' => $slug,
            'content' => 'Texto',
            'status_id' => Status::idFor(Blog::class, Status::BLOG_PUBLISHED),
        ];
    }
}
