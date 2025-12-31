<?php

namespace App\Policies;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExamResultPolicy
{
    /**
     * Determine whether the user can view any exam results.
     * Admin, head teachers, deputy head teachers, and teachers can view results.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher', 'teacher']);
    }

    /**
     * Determine whether the user can view the exam result.
     */
    public function view(User $user, ExamResult $examResult): bool
    {
        // Admin and academic managers can view any result
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can view results (they need to see results for analysis and reporting)
        if ($user->hasRole('teacher')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create exam results.
     * Teachers can create results for exams they're authorized to enter.
     * Note: Timing checks (locked/open) are handled in the controller, not here.
     */
    public function create(User $user, Exam $exam): bool
    {
        // Admin and academic managers can always create results
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can create results for subjects they teach
        if ($user->teacher) {
            $subjects = $user->teacher->subjects()
                ->wherePivot('academic_year_id', $exam->academic_year_id)
                ->exists();
            
            return $subjects;
        }

        return false;
    }

    /**
     * Determine whether the user can update the exam result.
     */
    public function update(User $user, ExamResult $examResult): bool
    {
        $exam = $examResult->exam;

        // Check if results entry is locked
        if ($exam->isResultsEntryLocked()) {
            return false;
        }

        // Admin and academic managers can always update results
        if ($user->isAcademicManager()) {
            return true;
        }

        // Teachers can update results for subjects they teach
        if ($user->teacher) {
            return $user->teacher->teachesSubjectInYear(
                $examResult->subject_id,
                $exam->academic_year_id
            );
        }

        return false;
    }

    /**
     * Determine whether the user can delete the exam result.
     * Only admin and academic managers can delete results.
     */
    public function delete(User $user, ExamResult $examResult): bool
    {
        $exam = $examResult->exam;

        // Can't delete if results entry is locked
        if ($exam->isResultsEntryLocked()) {
            return false;
        }

        return $user->isAcademicManager();
    }

    /**
     * Determine whether the user can restore the exam result.
     */
    public function restore(User $user, ExamResult $examResult): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the exam result.
     */
    public function forceDelete(User $user, ExamResult $examResult): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can enter results for a specific subject in an exam.
     */
    public function enterForSubject(User $user, Exam $exam, int $subjectId): bool
    {
        // Admin and academic managers can enter for any subject (bypass lock and time checks)
        if ($user->isAcademicManager()) {
            return true;
        }

        // For teachers, check if results entry is open and not locked
        if (!$exam->isResultsEntryOpen() || $exam->isResultsEntryLocked()) {
            return false;
        }

        // Teachers can only enter for subjects they teach
        if ($user->teacher) {
            return $user->teacher->teachesSubjectInYear($subjectId, $exam->academic_year_id);
        }

        return false;
    }
}
