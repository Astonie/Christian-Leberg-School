<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Teacher', 'slug' => 'teacher']);
        Role::create(['name' => 'Student', 'slug' => 'student']);
        Role::create(['name' => 'Guardian', 'slug' => 'guardian']);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $role = Role::where('slug', 'student')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard.student'));
    }

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $role = Role::where('slug', 'admin')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard.admin'));
    }

    public function test_users_can_logout(): void
    {
        $role = Role::where('slug', 'student')->first();
        $user = User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
