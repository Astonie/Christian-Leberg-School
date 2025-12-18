<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\SchoolClass;

class ClassEditViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_class_edit_form()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $schoolClass = SchoolClass::create(['name' => 'Grade X', 'level' => 1]);

        $response = $this->actingAs($admin)->get(route('classes.edit', $schoolClass));
        $response->assertStatus(200);
        $response->assertSee('Edit Class');
    }
}
