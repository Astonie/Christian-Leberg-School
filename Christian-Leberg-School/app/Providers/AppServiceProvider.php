<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Subject;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate to determine whether a teacher can enter scores for a specific student & subject
        Gate::define('enter-scores', function ($user, Subject $subject, Student $student) {
            if (! $user || $user->role?->slug !== 'teacher') {
                return false;
            }

            $teacher = $user->teacher;
            if (! $teacher) {
                return false;
            }

            // active academic year
            $year = AcademicYear::where('is_active', true)->first();
            if (! $year) {
                return false;
            }

            // student's active streams for the year
            $streamIds = $student->streams()->wherePivot('academic_year_id', $year->id)->wherePivot('is_active', true)->pluck('streams.id')->toArray();
            if (empty($streamIds)) {
                return false;
            }

            // teacher is assigned to that subject in one of the student's streams
            $exists = DB::table('stream_teacher')
                ->whereIn('stream_id', $streamIds)
                ->where('teacher_id', $teacher->id)
                ->where('subject_id', $subject->id)
                ->exists();

            return $exists;
        });
    }
}
