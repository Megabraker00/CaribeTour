<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\StatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusSeeder::class);
    }

    public function test_guest_is_sent_to_login(): void
    {
        $this->get('/profile')->assertRedirect(route('login'));
    }

    public function test_staff_can_update_their_own_profile_without_changing_role(): void
    {
        $user = User::factory()->viewer()->create([
            'name' => 'Ana Pérez',
            'email' => 'ana@caribetour.test',
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->get('/profile')
            ->assertOk()
            ->assertSee('Perfil')
            ->assertSee('Solo lectura')
            ->assertSee(url('/profile'), false)
            ->assertDontSee('admin/configuracion', false)
            ->assertDontSee('admin/settings', false);

        $this->actingAs($user)
            ->put('/profile', [
                'name' => 'Ana García',
                'email' => 'ana.garcia@caribetour.test',
                'role' => UserRole::Admin->value,
                'password' => 'nueva-clave',
                'password_confirmation' => 'nueva-clave',
                'current_password' => 'password',
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHas('success');

        $user->refresh();
        $this->assertSame('Ana García', $user->name);
        $this->assertSame('ana.garcia@caribetour.test', $user->email);
        $this->assertSame(UserRole::Viewer, $user->role);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_password_change_requires_the_current_password(): void
    {
        $user = User::factory()->agent()->create([
            'password' => 'password',
        ]);

        $this->actingAs($user)
            ->from('/profile')
            ->put('/profile/password', [
                'password' => 'nueva-clave',
                'password_confirmation' => 'nueva-clave',
            ])
            ->assertRedirect('/profile')
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));

        $this->actingAs($user)
            ->put('/profile/password', [
                'current_password' => 'password',
                'password' => 'nueva-clave',
                'password_confirmation' => 'nueva-clave',
            ])
            ->assertRedirect(route('profile'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('nueva-clave', $user->fresh()->password));
    }
}
