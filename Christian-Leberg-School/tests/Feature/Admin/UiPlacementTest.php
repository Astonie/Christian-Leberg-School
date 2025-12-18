<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;

class UiPlacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sees_academic_records_nav()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $this->actingAs($admin)
            ->get(route('dashboard.admin'))
            ->assertStatus(200)
            ->assertSee('Academic Records')
            ->assertSee('Grading Systems')
            ->assertSee('Grading Scales')
            ->assertSee('Assessment Structures');
    }

    public function test_teacher_sees_enter_scores_quick_link()
    {
        $teacherRole = Role::where('slug','teacher')->first() ?? Role::create(['name'=>'Teacher','slug'=>'teacher']);
        $teacher = User::factory()->create(['role_id' => $teacherRole->id]);

        $this->actingAs($teacher)
            ->get(route('dashboard.teacher'))
            ->assertStatus(200)
            ->assertSee('Enter Results');
    }
}
