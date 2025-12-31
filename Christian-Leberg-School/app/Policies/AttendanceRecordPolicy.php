<?php

namespace App\Policies;

use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttendanceRecordPolicy
{
    /**
     * Determine whether the user can view any attendance records.
     * Admin, head teachers, deputy head teachers, and teachers can view attendance.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }

    /**
     * Determine whether the user can view the attendance record.
     */
    public function view(User $user, AttendanceRecord $attendanceRecord): bool
    {
        // Admin and academic managers can view any attendance record
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can view any attendance record (for collaboration and monitoring)
        if ($user->teacher) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create attendance records.
     * Teachers can create attendance for their streams/subjects.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }

    /**
     * Determine whether the user can update the attendance record.
     * Teachers can update attendance they created, managers can update any.
     */
    public function update(User $user, AttendanceRecord $attendanceRecord): bool
    {
        // Admin and academic managers can update any attendance
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can update attendance they recorded
        if ($user->teacher && $attendanceRecord->teacher_id === $user->teacher->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the attendance record.
     * Only admin and academic managers can delete attendance records.
     */
    public function delete(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->isAcademicManager();
    }

    /**
     * Determine whether the user can restore the attendance record.
     */
    public function restore(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the attendance record.
     */
    public function forceDelete(User $user, AttendanceRecord $attendanceRecord): bool
    {
        return $user->hasRole('admin');
    }
}
