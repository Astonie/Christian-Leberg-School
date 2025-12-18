<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\GradingSystem;

class GradingSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_grading_system()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $this->actingAs($admin)
            ->post(route('admin.grading_systems.store'), ['name' => 'Letters', 'slug' => 'letters'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('grading_systems', ['slug' => 'letters']);
    }

    public function test_admin_can_create_grading_scale_under_system()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $system = GradingSystem::create(['name' => 'Letters', 'slug' => 'letters']);

        $this->actingAs($admin)
            ->post(route('admin.grading_scales.store'), ['grading_system_id' => $system->id, 'label' => 'A', 'min_score' => 80, 'max_score' => 100, 'points' => 4])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('grading_scales', ['grading_system_id' => $system->id, 'label' => 'A']);
    }
}
