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
     * Admin, head teachers, deputy head teachers can view all exams.
     * Teachers can only view exams for subjects they teach.
     */
    public function view(User $user, Exam $exam): bool
    {
        // Admin and academic managers can view any exam
        if ($user->hasAnyRole(['admin', 'head-teacher', 'deputy-head-teacher'])) {
            return true;
        }
        
        // Teachers can only view exams for subjects they teach
        if ($user->hasRole('teacher') && $user->teacher) {
            $teacherSubjectIds = $user->teacher->subjects()->pluck('subjects.id')->toArray();
            
            // Check if exam has any subjects the teacher teaches
            $hasTeachingSubject = $exam->subjects()->whereIn('subjects.id', $teacherSubjectIds)->exists();
            
            if ($hasTeachingSubject) {
                return true;
            }
            
            // Also check if teacher is assigned to any streams taking this exam
            $teacherStreamIds = $user->teacher->streams()->pluck('streams.id')->toArray();
            $examClassIds = $exam->classes()->pluck('classes.id')->toArray();
            
            // Check if any of teacher's streams are in the exam classes
            if (!empty($teacherStreamIds) && !empty($examClassIds)) {
                $hasAssignedStream = \App\Models\Stream::whereIn('id', $teacherStreamIds)
                    ->whereIn('class_id', $examClassIds)
                    ->exists();
                    
                if ($hasAssignedStream) {
                    return true;
                }
            }
            
            return false;
        }
        
        return false;
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
