<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExamPolicy
{
    /**
     * Determine whether the user can view any exams.
     * Admin, head teachers, deputy head teachers, and teachers can view exams.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }

    /**
     * Determine whether the user can view the exam.
     * Admin, head teachers, deputy head teachers, and teachers can view exams.
     */
    public function view(User $user, Exam $exam): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }

    /**
     * Determine whether the user can create exams.
     * Only admin, head teachers, and deputy head teachers can create exams.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can update the exam.
     * Only admin, head teachers, and deputy head teachers can update exams.
     */
    public function update(User $user, Exam $exam): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can delete the exam.
     * Only admin, head teachers, and deputy head teachers can delete exams.
     */
    public function delete(User $user, Exam $exam): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can restore the exam.
     * Only admin can restore deleted exams.
     */
    public function restore(User $user, Exam $exam): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the exam.
     * Only admin can force delete exams.
     */
    public function forceDelete(User $user, Exam $exam): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can release exam results.
     * Only admin, head teachers, and deputy head teachers can release results.
     */
    public function releaseResults(User $user, Exam $exam): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can withdraw exam results.
     * Only admin, head teachers, and deputy head teachers can withdraw results.
     */
    public function withdrawResults(User $user, Exam $exam): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can manage student access to results.
     * Only admin, head teachers, and deputy head teachers can manage access.
     */
    public function manageStudentAccess(User $user, Exam $exam): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher']);
    }

    /**
     * Determine whether the user can view exam reports.
     * Admin, head teachers, deputy head teachers, and teachers can view reports.
     */
    public function viewReports(User $user, Exam $exam): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }
}
