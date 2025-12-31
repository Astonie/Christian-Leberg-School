<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\AcademicYear;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_exam()
    {
        Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);

        $year = AcademicYear::create([
            'name' => '2025',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
            'is_active' => true,
        ]);

        $term = \App\Models\Term::create(['name' => 'Term 1', 'academic_year_id' => $year->id, 'start_date' => '2025-01-01', 'end_date' => '2025-04-30', 'is_active' => true]);

        $response = $this->actingAs($admin)->post(route('exams.store'), [
            'name' => 'Mid Term Exams',
            'academic_year_id' => $year->id,
            'term_id' => $term->id,
            'start_date' => '2025-05-01',
            'end_date' => '2025-05-05',
        ]);

        $response->assertRedirect(route('exams.index'));
        $this->assertDatabaseHas('exams', ['name' => 'Mid Term Exams']);
    }
}
