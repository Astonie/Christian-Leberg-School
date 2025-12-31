<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\Teacher;
use App\Models\Role;
use App\Policies\AttendanceRecordPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceRecordPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected AttendanceRecordPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new AttendanceRecordPolicy();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    /** @test */
    public function admin_can_view_any_attendance_records()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($admin));
    }

    /** @test */
    public function head_teacher_can_view_any_attendance_records()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($headTeacher));
    }

    /** @test */
    public function teacher_can_view_any_attendance_records()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertTrue($this->policy->viewAny($teacher));
    }

    /** @test */
    public function student_cannot_view_any_attendance_records()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($student));
    }

    /** @test */
    public function guardian_cannot_view_any_attendance_records()
    {
        $guardian = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        
        $this->assertFalse($this->policy->viewAny($guardian));
    }

    /** @test */
    public function admin_can_view_specific_record()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $record = AttendanceRecord::factory()->create();
        
        $this->assertTrue($this->policy->view($admin, $record));
    }

    /** @test */
    public function teacher_can_view_their_own_record()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $record = AttendanceRecord::factory()->create(['teacher_id' => $teacher->id]);
        
        $this->assertTrue($this->policy->view($teacherUser, $record));
    }

    /** @test */
    public function teacher_can_view_other_teachers_records()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $otherTeacher = Teacher::factory()->create();
        $record = AttendanceRecord::factory()->create(['teacher_id' => $otherTeacher->id]);
        
        $this->assertTrue($this->policy->view($teacherUser, $record));
    }

    /** @test */
    public function admin_can_create_attendance_records()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        
        $this->assertTrue($this->policy->create($admin));
    }

    /** @test */
    public function head_teacher_can_create_attendance_records()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        
        $this->assertTrue($this->policy->create($headTeacher));
    }

    /** @test */
    public function teacher_can_create_attendance_records()
    {
        $teacher = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        
        $this->assertTrue($this->policy->create($teacher));
    }

    /** @test */
    public function student_cannot_create_attendance_records()
    {
        $student = User::factory()->create(['role_id' => Role::where('slug', 'student')->first()->id]);
        
        $this->assertFalse($this->policy->create($student));
    }

    /** @test */
    public function guardian_cannot_create_attendance_records()
    {
        $guardian = User::factory()->create(['role_id' => Role::where('slug', 'guardian')->first()->id]);
        
        $this->assertFalse($this->policy->create($guardian));
    }

    /** @test */
    public function admin_can_update_any_attendance_record()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $record = AttendanceRecord::factory()->create();
        
        $this->assertTrue($this->policy->update($admin, $record));
    }

    /** @test */
    public function head_teacher_can_update_any_attendance_record()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $record = AttendanceRecord::factory()->create();
        
        $this->assertTrue($this->policy->update($headTeacher, $record));
    }

    /** @test */
    public function teacher_can_update_own_attendance_record()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $record = AttendanceRecord::factory()->create(['teacher_id' => $teacher->id]);
        
        $this->assertTrue($this->policy->update($teacherUser, $record));
    }

    /** @test */
    public function teacher_cannot_update_other_teachers_records()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $otherTeacher = Teacher::factory()->create();
        $record = AttendanceRecord::factory()->create(['teacher_id' => $otherTeacher->id]);
        
        $this->assertFalse($this->policy->update($teacherUser, $record));
    }

    /** @test */
    public function admin_can_delete_attendance_records()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $record = AttendanceRecord::factory()->create();
        
        $this->assertTrue($this->policy->delete($admin, $record));
    }

    /** @test */
    public function head_teacher_can_delete_attendance_records()
    {
        $headTeacher = User::factory()->create(['role_id' => Role::where('slug', 'head-teacher')->first()->id]);
        $record = AttendanceRecord::factory()->create();
        
        $this->assertTrue($this->policy->delete($headTeacher, $record));
    }

    /** @test */
    public function teacher_cannot_delete_attendance_records()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $record = AttendanceRecord::factory()->create(['teacher_id' => $teacher->id]);
        
        $this->assertFalse($this->policy->delete($teacherUser, $record));
    }

    /** @test */
    public function admin_can_restore_attendance_records()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $record = AttendanceRecord::factory()->create();
        $record->delete();
        
        $this->assertTrue($this->policy->restore($admin, $record));
    }

    /** @test */
    public function teacher_cannot_restore_attendance_records()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $record = AttendanceRecord::factory()->create(['teacher_id' => $teacher->id]);
        $record->delete();
        
        $this->assertFalse($this->policy->restore($teacherUser, $record));
    }

    /** @test */
    public function admin_can_force_delete_attendance_records()
    {
        $admin = User::factory()->create(['role_id' => Role::where('slug', 'admin')->first()->id]);
        $record = AttendanceRecord::factory()->create();
        
        $this->assertTrue($this->policy->forceDelete($admin, $record));
    }

    /** @test */
    public function teacher_cannot_force_delete_attendance_records()
    {
        $teacherUser = User::factory()->create(['role_id' => Role::where('slug', 'teacher')->first()->id]);
        $teacher = Teacher::factory()->create(['user_id' => $teacherUser->id]);
        $record = AttendanceRecord::factory()->create(['teacher_id' => $teacher->id]);
        
        $this->assertFalse($this->policy->forceDelete($teacherUser, $record));
    }
}
