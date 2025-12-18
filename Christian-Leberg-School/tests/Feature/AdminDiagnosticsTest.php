<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;

class AdminDiagnosticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_diagnostics()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->get(route('admin.diagnostics.index'));
        $response->assertStatus(200);
        // HTML will escape ampersand; assert presence of both keywords instead
        $response->assertSee('Diagnostics');
        $response->assertSee('Logs');
    }

    public function test_non_admin_cannot_view_diagnostics()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.diagnostics.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_download_log()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        // Ensure there is a log file
        $path = storage_path('logs/laravel.log');
        file_put_contents($path, "Test log entry\n", FILE_APPEND);

        $response = $this->actingAs($admin)->post(route('admin.diagnostics.download'));
        $response->assertStatus(200);
        $this->assertEquals('attachment; filename=laravel.log', $response->headers->get('content-disposition'));
    }
}
