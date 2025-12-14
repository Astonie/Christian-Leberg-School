<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Student;
use App\Models\Guardian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuardianManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Roles
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        Role::create(['name' => 'Guardian', 'slug' => 'guardian']);
        Role::create(['name' => 'Student', 'slug' => 'student']);

        // Create Admin
        $this->admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
    }

    public function test_admin_can_view_guardians_index()
    {
        $response = $this->actingAs($this->admin)->get(route('guardians.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_guardian_and_link_student()
    {
        $studentUser = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        $student = Student::create([
            'user_id' => $studentUser->id,
            'admission_number' => 'STU001',
            'admission_date' => now(),
            'date_of_birth' => now()->subYears(10),
            'gender' => 'female'
        ]);

        $response = $this->actingAs($this->admin)->post(route('guardians.store'), [
            'name' => 'John Doe',
            'email' => 'john@guardian.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone_number' => '1234567890',
            'relationship' => 'Father',
            'address' => '123 Main St',
            'student_ids' => [$student->id]
        ]);

        $response->assertRedirect(route('guardians.index'));
        
        $this->assertDatabaseHas('users', ['email' => 'john@guardian.com']);
        $this->assertDatabaseHas('guardians', ['phone_number' => '1234567890']);
        
        $guardian = Guardian::where('phone_number', '1234567890')->first();
        $this->assertTrue($guardian->students->contains($student->id));
    }
}
