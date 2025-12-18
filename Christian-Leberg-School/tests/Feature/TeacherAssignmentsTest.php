<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use App\Models\Role;

class TeacherAssignmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_teachers_have_streams_and_subjects_after_seeding()
    {
        $this->seed();

        $teacherRole = Role::where('slug', 'teacher')->first();
        $this->assertNotNull($teacherRole, 'Teacher role exists');

        $user = \App\Models\User::where('role_id', $teacherRole->id)->first();
        $this->assertNotNull($user, 'At least one teacher user exists');

        $teacher = $user->teacher;
        $this->assertNotNull($teacher, 'Teacher model exists for user');

        $this->assertTrue($teacher->subjects()->count() > 0, 'Teacher has subjects assigned');
        $this->assertTrue(\DB::table('stream_teacher')->where('teacher_id', $teacher->id)->exists(), 'Teacher is assigned to at least one stream');

        // Admin can visit assignments page and update
        $adminRole = Role::where('slug', 'admin')->first();
        $admin = \App\Models\User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->get(route('admin.teachers.assignments.edit', $teacher));
        $response->assertStatus(200);
    }
}
