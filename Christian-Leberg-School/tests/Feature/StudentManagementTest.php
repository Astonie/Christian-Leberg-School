<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Student', 'slug' => 'student']);
        Role::create(['name' => 'Guardian', 'slug' => 'guardian']);
        Role::create(['name' => 'Teacher', 'slug' => 'teacher']);
        
        // Setup Academic Data
        $year = AcademicYear::create([
            'name' => '2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);
        
        $class = SchoolClass::create(['name' => 'Form 1', 'level' => 1]);
        Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);
    }

    public function test_admin_can_view_students_list(): void
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        $response = $this->actingAs($admin)->get(route('students.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_student(): void
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $stream = Stream::first();

        $response = $this->actingAs($admin)->post(route('students.store'), [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'admission_number' => 'ADM001',
            'admission_date' => '2025-01-10',
            'date_of_birth' => '2010-05-15',
            'gender' => 'male',
            'stream_id' => $stream->id,
            'nationality' => 'Kenyan',
            'address' => 'Nairobi',
        ]);

        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertDatabaseHas('students', ['admission_number' => 'ADM001']);
        $this->assertDatabaseHas('student_stream', ['stream_id' => $stream->id]);
    }

    public function test_admin_can_update_student(): void
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $studentRole = Role::where('slug', 'student')->first();
        
        // Create user first
        $user = User::factory()->create(['role_id' => $studentRole->id]);
        $student = Student::create([
            'user_id' => $user->id,
            'admission_number' => 'ADM002',
            'admission_date' => now(),
            'date_of_birth' => now()->subYears(15),
            'gender' => 'female',
            'status' => 'active',
        ]);

        $response = $this->actingAs($admin)->put(route('students.update', $student), [
            'first_name' => 'Jane', // Updated name
            'last_name' => 'Doe',
            'email' => $user->email,
            'admission_number' => 'ADM002',
            'admission_date' => '2025-01-10',
            'date_of_birth' => '2010-05-15', // Must be valid date
            'gender' => 'female',
            'nationality' => 'Ugandan', // New field
        ]);

        if (session('errors')) {
            file_put_contents(base_path('test_errors.log'), print_r(session('errors')->all(), true));
        }
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('users', ['name' => 'Jane Doe']);
        $this->assertDatabaseHas('students', ['nationality' => 'Ugandan']);
    }
}
