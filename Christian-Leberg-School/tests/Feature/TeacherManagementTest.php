<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup Roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Teacher', 'slug' => 'teacher']);
        
        $this->admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
    }

    public function test_admin_can_view_teachers_list()
    {
        $this->withoutExceptionHandling();
        // Create a teacher
        $role = Role::where('slug', 'teacher')->first();
        $user = User::factory()->create(['role_id' => $role->id, 'name' => 'John Doe']);
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_number' => 'TCH001',
            'hire_date' => now(),
            'phone_number' => '1234567890',
            'qualification' => 'B.Ed',
            'employment_type' => 'full-time',
        ]);

        $response = $this->actingAs($this->admin)->get(route('teachers.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_teacher()
    {
        $response = $this->actingAs($this->admin)->post(route('teachers.store'), [
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john.smith@school.com',
            'employee_number' => 'TCH001',
            'hire_date' => '2023-01-01',
            'qualification' => 'B.Ed',
            'specialization' => 'Mathematics',
            'phone_number' => '1234567890',
            'address' => '123 School Lane',
            'employment_type' => 'full-time',
        ]);

        $response->assertRedirect(route('teachers.index'));
        
        $this->assertDatabaseHas('users', ['email' => 'john.smith@school.com']);
        $this->assertDatabaseHas('teachers', ['employee_number' => 'TCH001']);
        
        $user = User::where('email', 'john.smith@school.com')->first();
        $this->assertNotNull($user->teacher);
        $this->assertEquals('TCH001', $user->teacher->employee_number);
    }

    public function test_admin_can_update_teacher()
    {
        // Create a teacher first
        $role = Role::where('slug', 'teacher')->first();
        $user = User::factory()->create(['role_id' => $role->id, 'name' => 'Jane Doe']);
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'employee_number' => 'TCH002',
            'hire_date' => now(),
            'phone_number' => '0987654321',
            'qualification' => 'B.Ed',
            'employment_type' => 'contract',
        ]);

        $response = $this->actingAs($this->admin)->put(route('teachers.update', $teacher), [
            'first_name' => 'Jane',
            'last_name' => 'Doe Updated',
            'email' => $user->email,
            'employee_number' => 'TCH002',
            'hire_date' => '2023-01-01',
            'phone_number' => '0987654321',
            'qualification' => 'M.Ed',
            'employment_type' => 'part-time', // Changed
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('teachers.index'));
        
        $this->assertDatabaseHas('users', ['name' => 'Jane Doe Updated']);
        $this->assertDatabaseHas('teachers', ['employment_type' => 'part-time']);
    }
}
