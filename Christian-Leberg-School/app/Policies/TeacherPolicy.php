<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeacherPolicy
{
    /**
     * Determine whether the user can view any teachers.
     * Admin, head teachers, deputy head teachers, and teachers can view teachers list.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }

    /**
     * Determine whether the user can view the teacher.
     */
    public function view(User $user, Teacher $teacher): bool
    {
        // Admin and academic managers can view any teacher
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can view their own profile and other teachers
        if ($user->teacher) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create teachers.
     * Admin and head-teacher can create teachers.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'head-teacher']);
    }

    /**
     * Determine whether the user can update the teacher.
     * Admin and head-teacher can update teachers.
     */
    public function update(User $user, Teacher $teacher): bool
    {
        return $user->hasRole(['admin', 'head-teacher']);
    }

    /**
     * Determine whether the user can delete the teacher.
     * Admin and head-teacher can delete teachers.
     */
    public function delete(User $user, Teacher $teacher): bool
    {
        return $user->hasRole(['admin', 'head-teacher']);
    }

    /**
     * Determine whether the user can restore the teacher.
     * Only admin can restore deleted teachers.
     */
    public function restore(User $user, Teacher $teacher): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the teacher.
     * Only admin can force delete teachers.
     */
    public function forceDelete(User $user, Teacher $teacher): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can assign subjects to teacher.
     * Only admin, head teachers, and deputy head teachers can assign subjects.
     */
    public function assignSubjects(User $user, Teacher $teacher): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can assign streams to teacher.
     * Only admin, head teachers, and deputy head teachers can assign streams.
     */
    public function assignStreams(User $user, Teacher $teacher): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }
}
