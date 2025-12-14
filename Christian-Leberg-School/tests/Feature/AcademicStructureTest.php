<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicStructureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        
        $this->admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
    }

    // --- Academic Years ---
    public function test_admin_can_manage_academic_years()
    {
        // Create
        $response = $this->actingAs($this->admin)->post(route('academic-years.store'), [
            'name' => '2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);
        $response->assertRedirect(route('academic-years.index'));
        $this->assertDatabaseHas('academic_years', ['name' => '2025', 'is_active' => true]);

        // Update
        $year = AcademicYear::first();
        $response = $this->actingAs($this->admin)->put(route('academic-years.update', $year), [
            'name' => '2025-Updated',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('academic_years', ['name' => '2025-Updated']);
    }

    // --- Classes ---
    public function test_admin_can_manage_classes()
    {
        // Create
        $response = $this->actingAs($this->admin)->post(route('classes.store'), [
            'name' => 'Form 1',
            'level' => 1,
            'description' => 'Freshers',
        ]);
        $response->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('classes', ['name' => 'Form 1']);

        // Update
        $class = SchoolClass::first();
        $response = $this->actingAs($this->admin)->put(route('classes.update', $class), [
            'name' => 'Form 1 East',
            'level' => 1,
        ]);
        if (session('errors')) {
            file_put_contents(base_path('test_errors_class.log'), print_r(session('errors')->all(), true));
        }
        $this->assertDatabaseHas('classes', ['name' => 'Form 1 East']);
    }

    // --- Streams ---
    public function test_admin_can_manage_streams()
    {
        // Setup Year and Class
        $year = AcademicYear::create([
            'name' => '2025', 
            'start_date' => '2025-01-01', 
            'end_date' => '2025-12-31', 
            'is_active' => true
        ]);
        $class = SchoolClass::create(['name' => 'Form 1', 'level' => 1]);

        // Create Stream
        $response = $this->actingAs($this->admin)->post(route('streams.store'), [
            'class_id' => $class->id,
            'name' => 'Blue',
            'capacity' => 40,
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('streams', ['name' => 'Blue', 'class_id' => $class->id]);

        // Update Stream
        $stream = Stream::first();
        $response = $this->actingAs($this->admin)->put(route('streams.update', $stream), [
            'name' => 'Red',
            'capacity' => 45,
        ]);
        $this->assertDatabaseHas('streams', ['name' => 'Red']);
    }

    // --- Subjects ---
    public function test_admin_can_manage_subjects()
    {
        // Create
        $response = $this->actingAs($this->admin)->post(route('subjects.store'), [
            'name' => 'Mathematics',
            'code' => 'MATH101',
            'description' => 'Core Math',
        ]);
        $response->assertRedirect(route('subjects.index'));
        $this->assertDatabaseHas('subjects', ['code' => 'MATH101']);

        // Update
        $subject = Subject::first();
        $response = $this->actingAs($this->admin)->put(route('subjects.update', $subject), [
            'name' => 'Advanced Mathematics',
            'code' => 'MATH102',
        ]);
        $this->assertDatabaseHas('subjects', ['name' => 'Advanced Mathematics']);
    }
}
