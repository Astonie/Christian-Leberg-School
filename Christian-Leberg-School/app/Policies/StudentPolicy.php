<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any students.
     * Admin, head teachers, deputy head teachers, and teachers can view students.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }

    /**
     * Determine whether the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        // Admin and academic managers can view any student
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can view students in their streams
        if ($user->teacher) {
            $studentStreamIds = $student->streams()->pluck('streams.id')->toArray();
            $teacherStreamIds = $user->teacher->streams()->pluck('streams.id')->toArray();
            return !empty(array_intersect($studentStreamIds, $teacherStreamIds));
        }

        // Guardians can view their own students
        if ($user->guardian) {
            return $user->guardian->students()->where('students.id', $student->id)->exists();
        }

        // Students can view their own profile
        if ($user->student) {
            return $user->student->id === $student->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create students.
     * Only admin, head teachers, and deputy head teachers can create students.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can update the student.
     * Only admin, head teachers, and deputy head teachers can update students.
     */
    public function update(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can delete the student.
     * Only admin, head teachers, and deputy head teachers can delete students.
     */
    public function delete(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can restore the student.
     * Only admin can restore deleted students.
     */
    public function restore(User $user, Student $student): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the student.
     * Only admin can force delete students.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can block/unblock student results access.
     * Only admin, head teachers, and deputy head teachers can manage results access.
     */
    public function manageResultsAccess(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can import students.
     * Only admin, head teachers, and deputy head teachers can import students.
     */
    public function import(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can add guardians to a student.
     * Only admin, head teachers, and deputy head teachers can add guardians.
     */
    public function addGuardian(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can assign student to stream.
     * Only admin, head teachers, and deputy head teachers can assign students to streams.
     */
    public function assignToStream(User $user, Student $student): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can view student results.
     */
    public function viewResults(User $user, Student $student): bool
    {
        // Admin and academic managers can view any student results
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can view results of students in their streams
        if ($user->teacher) {
            $studentStreamIds = $student->streams()->pluck('streams.id')->toArray();
            $teacherStreamIds = $user->teacher->streams()->pluck('streams.id')->toArray();
            return !empty(array_intersect($studentStreamIds, $teacherStreamIds));
        }

        // Guardians can view their own students' results
        if ($user->guardian) {
            return $user->guardian->students()->where('students.id', $student->id)->exists();
        }

        // Students can view their own results
        if ($user->student) {
            return $user->student->id === $student->id;
        }

        return false;
    }
}
