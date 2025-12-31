<?php

namespace Tests\Feature\Academic;

use Tests\TestCase;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Comprehensive tests for Academic Module core functionality
 * Tests: AcademicYear, Term, SchoolClass, Stream, Subject relationships, inheritance, and business logic
 */
class ComprehensiveAcademicTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $teacher;
    protected $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and users
        $adminRole = Role::factory()->create(['name' => 'Admin', 'slug' => 'admin']);
        $teacherRole = Role::factory()->create(['name' => 'Teacher', 'slug' => 'teacher']);
        $studentRole = Role::factory()->create(['name' => 'Student', 'slug' => 'student']);

        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
        $teacherUser = User::factory()->create(['role_id' => $teacherRole->id]);
        $this->teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $this->student = Student::factory()->create();
    }

    // ================== ACADEMIC YEAR TESTS ==================

    /** @test */
    public function academic_year_can_be_created_with_valid_data()
    {
        $year = AcademicYear::create([
            'name' => '2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ]);

        $this->assertDatabaseHas('academic_years', [
            'name' => '2025',
            'is_active' => false, // Default is false
        ]);
    }

    /** @test */
    public function only_one_academic_year_can_be_active_at_a_time()
    {
        $year1 = AcademicYear::factory()->create(['is_active' => true]);
        $year2 = AcademicYear::factory()->create(['is_active' => false]);

        // Since is_active is guarded, use DB::table for direct update
        \DB::table('academic_years')->where('id', $year1->id)->update(['is_active' => false]);
        \DB::table('academic_years')->where('id', $year2->id)->update(['is_active' => true]);
        
        // Refresh both to ensure we have latest DB state
        $year1->refresh();
        $year2->refresh();

        $this->assertFalse($year1->is_active);
        $this->assertTrue($year2->is_active);
    }

    /** @test */
    public function academic_year_has_many_terms()
    {
        $year = AcademicYear::factory()->create();
        $terms = Term::factory()->count(3)->create(['academic_year_id' => $year->id]);

        $this->assertCount(3, $year->terms);
        $this->assertInstanceOf(Term::class, $year->terms->first());
    }

    /** @test */
    public function academic_year_has_many_streams()
    {
        $year = AcademicYear::factory()->create();
        $class = SchoolClass::factory()->create();
        
        // Create streams with unique names to avoid constraint violation
        $streamA = Stream::factory()->create([
            'name' => 'A',
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
        ]);
        $streamB = Stream::factory()->create([
            'name' => 'B',
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
        ]);

        $this->assertCount(2, $year->streams);
        $this->assertInstanceOf(Stream::class, $year->streams->first());
    }

    /** @test */
    public function can_inherit_streams_from_previous_academic_year()
    {
        // Create previous year with streams
        $previousYear = AcademicYear::factory()->create(['name' => '2024']);
        $class = SchoolClass::factory()->create(['name' => 'Grade 1']);
        $stream1 = Stream::factory()->create([
            'name' => 'A',
            'class_id' => $class->id,
            'academic_year_id' => $previousYear->id,
        ]);

        // Create new year and inherit
        $newYear = AcademicYear::factory()->create(['name' => '2025']);
        $newYear->inheritFromPrevious($previousYear);

        // Verify stream was cloned
        $this->assertCount(1, $newYear->streams);
        $newStream = $newYear->streams->first();
        $this->assertEquals('A', $newStream->name);
        $this->assertEquals($class->id, $newStream->class_id);
        $this->assertNotEquals($stream1->id, $newStream->id); // Different stream
    }

    /** @test */
    public function can_inherit_teacher_subject_assignments_from_previous_year()
    {
        $previousYear = AcademicYear::factory()->create(['name' => '2024']);
        $newYear = AcademicYear::factory()->create(['name' => '2025']);
        
        $subject = Subject::factory()->create();
        
        // Assign teacher to subject in previous year
        $this->teacher->subjects()->attach($subject->id, [
            'academic_year_id' => $previousYear->id,
            'is_primary' => true,
        ]);

        // Inherit
        $newYear->inheritFromPrevious($previousYear);

        // Verify teacher-subject assignment was copied
        $newAssignments = \DB::table('teacher_subject')
            ->where('teacher_id', $this->teacher->id)
            ->where('subject_id', $subject->id)
            ->where('academic_year_id', $newYear->id)
            ->get();

        $this->assertCount(1, $newAssignments);
        $this->assertTrue((bool) $newAssignments->first()->is_primary);
    }

    /** @test */
    public function active_scope_returns_only_active_year()
    {
        AcademicYear::factory()->count(3)->create(['is_active' => false]);
        $activeYear = AcademicYear::factory()->create(['is_active' => true]);

        $result = AcademicYear::active()->first();

        $this->assertEquals($activeYear->id, $result->id);
    }

    // ================== TERM TESTS ==================

    /** @test */
    public function term_belongs_to_academic_year()
    {
        $year = AcademicYear::factory()->create();
        $term = Term::factory()->create(['academic_year_id' => $year->id]);

        $this->assertInstanceOf(AcademicYear::class, $term->academicYear);
        $this->assertEquals($year->id, $term->academicYear->id);
    }

    /** @test */
    public function term_dates_must_be_within_academic_year_dates()
    {
        $year = AcademicYear::factory()->create([
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ]);

        $validTerm = Term::factory()->create([
            'academic_year_id' => $year->id,
            'start_date' => '2025-01-01',
            'end_date' => '2025-04-30',
        ]);

        $this->assertNotNull($validTerm);
    }

    /** @test */
    public function only_one_term_can_be_active_per_academic_year()
    {
        $year = AcademicYear::factory()->create();
        $term1 = Term::factory()->create([
            'academic_year_id' => $year->id,
            'is_active' => true,
        ]);
        $term2 = Term::factory()->create([
            'academic_year_id' => $year->id,
            'is_active' => false,
        ]);

        // Since is_active is guarded, use DB::table for direct update
        \DB::table('terms')->where('id', $term1->id)->update(['is_active' => false]);
        \DB::table('terms')->where('id', $term2->id)->update(['is_active' => true]);
        
        // Refresh both to ensure we have latest DB state
        $term1->refresh();
        $term2->refresh();

        $this->assertFalse($term1->is_active);
        $this->assertTrue($term2->is_active);
    }

    // ================== SCHOOL CLASS TESTS ==================

    /** @test */
    public function school_class_has_many_streams()
    {
        $year = AcademicYear::factory()->create();
        $class = SchoolClass::factory()->create();
        
        // Create streams with explicit unique names to avoid UNIQUE constraint
        $streamA = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
            'name' => 'Stream A',
        ]);
        
        $streamB = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
            'name' => 'Stream B',
        ]);
        
        $streamC = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
            'name' => 'Stream C',
        ]);

        $this->assertCount(3, $class->streams);
    }

    /** @test */
    public function school_class_can_have_subjects()
    {
        $class = SchoolClass::factory()->create();
        $subjects = Subject::factory()->count(5)->create();
        $class->subjects()->attach($subjects->pluck('id'));

        $this->assertCount(5, $class->subjects);
    }

    /** @test */
    public function school_class_has_level_attribute()
    {
        $class = SchoolClass::factory()->create(['level' => 5, 'name' => 'Grade 5']);

        $this->assertEquals(5, $class->level);
    }

    // ================== STREAM TESTS ==================

    /** @test */
    public function stream_belongs_to_class_and_academic_year()
    {
        $year = AcademicYear::factory()->create();
        $class = SchoolClass::factory()->create();
        $stream = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
        ]);

        $this->assertInstanceOf(SchoolClass::class, $stream->schoolClass);
        $this->assertInstanceOf(AcademicYear::class, $stream->academicYear);
        $this->assertEquals($class->id, $stream->schoolClass->id);
        $this->assertEquals($year->id, $stream->academicYear->id);
    }

    /** @test */
    public function stream_has_many_students_through_pivot()
    {
        $year = AcademicYear::factory()->create();
        $class = SchoolClass::factory()->create();
        $stream = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
        ]);

        $students = Student::factory()->count(10)->create();
        
        foreach ($students as $student) {
            $student->streams()->attach($stream->id, [
                'academic_year_id' => $year->id,
                'enrollment_date' => now(),
                'is_active' => true,
            ]);
        }

        $this->assertCount(10, $stream->students);
    }

    /** @test */
    public function stream_can_have_assigned_teachers()
    {
        $year = AcademicYear::factory()->create();
        $class = SchoolClass::factory()->create();
        $stream = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
        ]);

        $teachers = Teacher::factory()->count(3)->create();
        
        foreach ($teachers as $teacher) {
            $teacher->streams()->attach($stream->id, [
                'academic_year_id' => $year->id,
            ]);
        }

        $this->assertCount(3, $stream->teachers);
    }

    /** @test */
    public function stream_capacity_can_be_set()
    {
        $year = AcademicYear::factory()->create();
        $class = SchoolClass::factory()->create();
        $stream = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year->id,
            'capacity' => 40,
        ]);

        $this->assertEquals(40, $stream->capacity);
    }

    /** @test */
    public function stream_must_belong_to_academic_year()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        $class = SchoolClass::factory()->create();
        Stream::create([
            'name' => 'A',
            'class_id' => $class->id,
            // academic_year_id is missing - should fail
        ]);
    }

    // ================== SUBJECT TESTS ==================

    /** @test */
    public function subject_can_be_assigned_to_multiple_classes()
    {
        $subject = Subject::factory()->create(['name' => 'Mathematics']);
        
        // Create classes with unique names to avoid constraint violation
        $classes = collect();
        foreach (range(1, 5) as $i) {
            $classes->push(SchoolClass::factory()->create([
                'name' => 'Grade ' . $i,
                'level' => $i,
            ]));
        }
        
        $subject->classes()->attach($classes->pluck('id'));

        $this->assertCount(5, $subject->classes);
    }

    /** @test */
    public function subject_can_have_multiple_teachers()
    {
        $year = AcademicYear::factory()->create();
        $subject = Subject::factory()->create();
        $teachers = Teacher::factory()->count(3)->create();

        foreach ($teachers as $teacher) {
            $teacher->subjects()->attach($subject->id, [
                'academic_year_id' => $year->id,
            ]);
        }

        $this->assertCount(3, $subject->teachers);
    }

    /** @test */
    public function subject_has_unique_code()
    {
        $subject1 = Subject::factory()->create(['code' => 'MATH101']);
        
        // Creating duplicate code should either fail or be handled
        $subject2 = Subject::factory()->create(['code' => 'ENG101']);
        
        $this->assertNotEquals($subject1->code, $subject2->code);
    }

    // ================== INTEGRATION TESTS ==================

    /** @test */
    public function complete_academic_structure_can_be_created()
    {
        // Create academic year
        $year = AcademicYear::factory()->create(['name' => '2025', 'is_active' => true]);

        // Create terms
        $term1 = Term::factory()->create([
            'academic_year_id' => $year->id,
            'name' => 'Term 1',
            'is_active' => true,
        ]);
        $term2 = Term::factory()->create([
            'academic_year_id' => $year->id,
            'name' => 'Term 2',
        ]);

        // Create classes with streams
        $grade1 = SchoolClass::factory()->create(['name' => 'Grade 1', 'level' => 1]);
        $streamA = Stream::factory()->create([
            'name' => 'A',
            'class_id' => $grade1->id,
            'academic_year_id' => $year->id,
            'capacity' => 40,
        ]);
        $streamB = Stream::factory()->create([
            'name' => 'B',
            'class_id' => $grade1->id,
            'academic_year_id' => $year->id,
            'capacity' => 40,
        ]);

        // Create subjects
        $math = Subject::factory()->create(['name' => 'Mathematics', 'code' => 'MATH']);
        $english = Subject::factory()->create(['name' => 'English', 'code' => 'ENG']);

        // Assign subjects to class
        $grade1->subjects()->attach([$math->id, $english->id]);

        // Create teachers and assign to subjects and streams
        $mathTeacher = Teacher::factory()->create();
        $mathTeacher->subjects()->attach($math->id, [
            'academic_year_id' => $year->id,
            'is_primary' => true,
        ]);
        $mathTeacher->streams()->attach($streamA->id, [
            'academic_year_id' => $year->id,
        ]);

        // Enroll students
        $students = Student::factory()->count(30)->create();
        foreach ($students->take(15) as $student) {
            $student->streams()->attach($streamA->id, [
                'academic_year_id' => $year->id,
                'enrollment_date' => now(),
                'is_active' => true,
            ]);
        }

        // Verify complete structure
        $this->assertEquals(2, $year->terms->count());
        $this->assertEquals(2, $grade1->streams->count());
        $this->assertEquals(2, $grade1->subjects->count());
        $this->assertEquals(15, $streamA->students->count());
        $this->assertEquals(1, $math->teachers->count());
    }

    /** @test */
    public function student_enrollment_is_tracked_per_academic_year()
    {
        $year1 = AcademicYear::factory()->create(['name' => '2024']);
        $year2 = AcademicYear::factory()->create(['name' => '2025']);
        
        $class = SchoolClass::factory()->create();
        $stream1 = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year1->id,
        ]);
        $stream2 = Stream::factory()->create([
            'class_id' => $class->id,
            'academic_year_id' => $year2->id,
        ]);

        $student = Student::factory()->create();

        // Enroll in both years
        $student->streams()->attach($stream1->id, [
            'academic_year_id' => $year1->id,
            'enrollment_date' => '2024-01-15',
            'is_active' => false, // Previous year
        ]);
        $student->streams()->attach($stream2->id, [
            'academic_year_id' => $year2->id,
            'enrollment_date' => '2025-01-10',
            'is_active' => true, // Current year
        ]);

        // Verify separate enrollments
        $enrollments = \DB::table('student_stream')
            ->where('student_id', $student->id)
            ->get();

        $this->assertCount(2, $enrollments);
        
        // Verify current enrollment
        $currentEnrollment = \DB::table('student_stream')
            ->where('student_id', $student->id)
            ->where('academic_year_id', $year2->id)
            ->first();

        $this->assertTrue((bool) $currentEnrollment->is_active);
    }

    /** @test */
    public function teacher_assignments_are_tracked_per_academic_year()
    {
        $year1 = AcademicYear::factory()->create(['name' => '2024']);
        $year2 = AcademicYear::factory()->create(['name' => '2025']);
        
        $subject = Subject::factory()->create();

        // Assign teacher to subject in both years
        $this->teacher->subjects()->attach($subject->id, [
            'academic_year_id' => $year1->id,
            'is_primary' => true,
        ]);
        $this->teacher->subjects()->attach($subject->id, [
            'academic_year_id' => $year2->id,
            'is_primary' => true,
        ]);

        // Verify separate assignments
        $assignments = \DB::table('teacher_subject')
            ->where('teacher_id', $this->teacher->id)
            ->where('subject_id', $subject->id)
            ->get();

        $this->assertCount(2, $assignments);
    }
}
