<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;

class StudentShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_show_page_loads()
    {
        $studentRole = Role::where('slug','student')->first() ?? Role::create(['name'=>'Student','slug'=>'student']);
        $user = User::factory()->create(['role_id' => $studentRole->id]);
        $student = Student::create(['user_id' => $user->id, 'admission_number' => 'S1', 'admission_date' => now(), 'date_of_birth' => now()->subYears(10), 'gender' => 'male']);

        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $response = $this->actingAs($admin)->get(route('students.show', $student));
        $response->assertStatus(200);
        $response->assertSee($user->name);
    }
}
