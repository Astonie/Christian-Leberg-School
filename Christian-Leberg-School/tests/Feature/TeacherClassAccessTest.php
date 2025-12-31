<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\User;
use App\Models\Teacher;

class TeacherClassAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_view_assigned_class()
    {
        $this->seed();

        $teacherUser = User::whereHas('role', function ($q) { $q->where('slug', 'teacher'); })->first();
        $teacher = $teacherUser->teacher;
        $year = AcademicYear::active()->first();

        $schoolClass = SchoolClass::first();
        // create a fresh stream in the active academic year and attach teacher
        $stream = Stream::create(['name' => 'X', 'class_id' => $schoolClass->id, 'academic_year_id' => $year->id]);

        // Assign the teacher to this stream
        \DB::table('stream_teacher')->insert([
            'stream_id' => $stream->id,
            'teacher_id' => $teacher->id,
            'subject_id' => null,
            'academic_year_id' => $year->id,
            'is_class_teacher' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertTrue($teacher->streams()->where('class_id', $schoolClass->id)->where('academic_year_id', $year->id)->exists());

        $this->assertTrue($teacherUser->hasRole('teacher'));

        $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName('classes.show');
        $this->assertNotNull($route);
        $this->assertContains('role:admin|teacher', $route->gatherMiddleware());

        // Teachers should use the teacher-scoped route
        $response = $this->actingAs($teacherUser)->get(route('teacher.classes.show', $schoolClass));
        $response->assertStatus(200);
        $response->assertSeeText($schoolClass->name);
        
        // Confirm admin can also view the same class
        $admin = User::whereHas('role', function ($q) { $q->where('slug', 'admin'); })->first();
        $this->actingAs($admin)->get(route('classes.show', $schoolClass))->assertStatus(200);
    }

    public function test_teacher_cannot_view_unassigned_class()
    {
        $this->seed();

        $teacherUser = User::whereHas('role', function ($q) { $q->where('slug', 'teacher'); })->first();
        $teacher = $teacherUser->teacher;
        $year = AcademicYear::active()->first();

        // Create a class and stream that the teacher is NOT assigned to
        $otherClass = SchoolClass::create(['name' => 'Z Class', 'level' => 99]);
        $stream = Stream::create(['name' => 'Z', 'class_id' => $otherClass->id, 'academic_year_id' => $year->id]);

        $response = $this->actingAs($teacherUser)->get(route('classes.show', $otherClass));
        $response->assertStatus(403);
    }
}
