<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Role;
use App\Models\User;
use App\Models\SchoolClass;

class CreateStreamNoAcademicYearTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_create_stream_without_active_academic_year()
    {
        $adminRole = Role::where('slug','admin')->first() ?? Role::create(['name'=>'Admin','slug'=>'admin']);
        $admin = User::factory()->create(['role_id' => $adminRole->id]);

        $class = SchoolClass::create(['name' => 'Grade NoYear', 'level' => 1]);

        $response = $this->actingAs($admin)->post(route('streams.store'), ['class_id' => $class->id, 'name' => 'D', 'capacity' => 30]);
        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('streams', ['name' => 'D', 'class_id' => $class->id]);
    }
}
