<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Stream;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $teacher;
    protected $teacherUser;
    protected $stream;
    protected $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed Roles
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Teacher', 'slug' => 'teacher'],
            ['name' => 'Student', 'slug' => 'student'],
        ];
        foreach ($roles as $role) {
            Role::create($role);
        }

        // Create Admin
        $this->admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        // Create Teacher
        $this->teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $this->teacher = Teacher::create([
            'user_id' => $this->teacherUser->id,
            'employee_number' => 'T123',
            'hire_date' => now(),
            'phone_number' => '1234567890',
            'qualification' => 'B.Ed',
            'employment_type' => 'full-time'
        ]);

        // Create Academic Structure
        $academicYear = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        $class = SchoolClass::create(['name' => 'Grade 1', 'level' => 1]);
        $this->stream = Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $academicYear->id]);

        // Create Student and Assign to Stream
        $studentUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $this->student = Student::create([
            'user_id' => $studentUser->id,
            'admission_number' => 'ADM001',
            'admission_date' => now(),
            'date_of_birth' => now()->subYears(10),
            'gender' => 'male'
        ]);

        $this->student->streams()->attach($this->stream->id, [
            'academic_year_id' => $academicYear->id,
            'is_active' => true,
            'enrollment_date' => now(),
        ]);
    }

    public function test_teacher_can_view_attendance_index_page()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('attendance.index'));

        $response->assertStatus(200);
        $response->assertSee('Select Stream');
    }

    public function test_teacher_can_view_marking_page()
    {
        $response = $this->actingAs($this->teacherUser)
            ->get(route('attendance.create', [
                'stream_id' => $this->stream->id,
                'date' => now()->format('Y-m-d')
            ]));

        $response->assertStatus(200);
        $response->assertSee($this->student->user->name);
    }

    public function test_teacher_can_record_attendance()
    {
        $this->withoutExceptionHandling();
        $date = now()->format('Y-m-d');
        
        $response = $this->actingAs($this->teacherUser)
            ->post(route('attendance.store'), [
                'stream_id' => $this->stream->id,
                'date' => $date,
                'attendance' => [
                    [
                        'student_id' => $this->student->id,
                        'status' => 'present',
                        'remarks' => 'On time'
                    ]
                ]
            ]);

        $response->assertRedirect(route('attendance.index'));
        $this->assertDatabaseHas('attendance_records', [
            'student_id' => $this->student->id,
            'stream_id' => $this->stream->id,
            'date' => $date . ' 00:00:00',
            'status' => 'present',
            'remarks' => 'On time'
        ]);
    }
}
