<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TimetableEntry;
use App\Models\TimetablePeriod;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing timetable entries
        TimetableEntry::truncate();

        // Get the active academic year (2025)
        $academicYear = AcademicYear::where('name', '2025')->first();
        if (!$academicYear) {
            $this->command->error('No academic year found. Please run AcademicSeeder first.');
            return;
        }

        // Get the first term
        $term = Term::where('academic_year_id', $academicYear->id)->first();
        if (!$term) {
            $this->command->error('No term found. Please create terms first.');
            return;
        }

        // Get all teaching periods (exclude breaks)
        $periods = TimetablePeriod::where('is_break', false)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        if ($periods->isEmpty()) {
            $this->command->error('No teaching periods found. Please run TimetablePeriodSeeder first.');
            return;
        }

        // Get all subjects
        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            $this->command->error('No subjects found. Please run AcademicSeeder first.');
            return;
        }

        // Get all teachers
        $teachers = Teacher::all();
        if ($teachers->isEmpty()) {
            $this->command->error('No teachers found. Please seed teachers first.');
            return;
        }

        // Get all classes with their streams
        $classes = SchoolClass::with(['streams' => function ($query) use ($academicYear) {
            $query->where('academic_year_id', $academicYear->id);
        }])->get();

        if ($classes->isEmpty()) {
            $this->command->error('No classes found. Please run AcademicSeeder first.');
            return;
        }

        // Subject-Teacher mapping (distribute subjects among teachers)
        $subjectTeacherMap = $this->mapSubjectsToTeachers($subjects, $teachers);

        // Days of the week (Monday to Friday)
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        $entriesCreated = 0;
        $conflicts = 0;

        // For each class and stream, create a weekly timetable
        foreach ($classes as $class) {
            foreach ($class->streams as $stream) {
                $this->command->info("Creating timetable for {$class->name} - Stream {$stream->name}...");

                // Create a balanced schedule for the week
                $weekSchedule = $this->generateWeekSchedule($subjects, $periods, $daysOfWeek);

                foreach ($daysOfWeek as $day) {
                    foreach ($weekSchedule[$day] as $periodId => $subjectId) {
                        // Get the teacher for this subject
                        $teacherId = $subjectTeacherMap[$subjectId] ?? $teachers->random()->id;

                        // Check for conflicts before creating entry
                        $teacherAvailable = TimetableEntry::isTeacherAvailable(
                            $academicYear->id,
                            $term->id,
                            $teacherId,
                            $periodId,
                            $day
                        );

                        $classAvailable = TimetableEntry::isClassAvailable(
                            $academicYear->id,
                            $term->id,
                            $class->id,
                            $stream->id,
                            $periodId,
                            $day
                        );

                        if ($teacherAvailable && $classAvailable) {
                            try {
                                TimetableEntry::create([
                                    'academic_year_id' => $academicYear->id,
                                    'term_id' => $term->id,
                                    'class_id' => $class->id,
                                    'stream_id' => $stream->id,
                                    'subject_id' => $subjectId,
                                    'teacher_id' => $teacherId,
                                    'period_id' => $periodId,
                                    'day_of_week' => $day,
                                    'room' => "Room " . rand(1, 20),
                                ]);
                                $entriesCreated++;
                            } catch (\Exception $e) {
                                $conflicts++;
                                $this->command->warn("Conflict: {$e->getMessage()}");
                            }
                        } else {
                            $conflicts++;
                            if (!$teacherAvailable) {
                                $this->command->warn("Teacher conflict on $day period $periodId");
                            }
                            if (!$classAvailable) {
                                $this->command->warn("Class conflict on $day period $periodId");
                            }
                        }
                    }
                }
            }
        }

        $this->command->info("✅ Timetable seeding completed!");
        $this->command->info("📊 Entries created: $entriesCreated");
        if ($conflicts > 0) {
            $this->command->warn("⚠️  Conflicts avoided: $conflicts");
        }
    }

    /**
     * Map subjects to teachers (each teacher gets 1-2 subjects)
     */
    private function mapSubjectsToTeachers($subjects, $teachers): array
    {
        $map = [];
        $teacherIndex = 0;
        $teachersArray = $teachers->toArray();

        foreach ($subjects as $subject) {
            // Assign each subject to a teacher
            $map[$subject->id] = $teachersArray[$teacherIndex % count($teachersArray)]['id'];
            $teacherIndex++;
        }

        return $map;
    }

    /**
     * Generate a balanced weekly schedule
     * Ensures each subject appears multiple times throughout the week
     */
    private function generateWeekSchedule($subjects, $periods, $days): array
    {
        $schedule = [];
        $subjectIds = $subjects->pluck('id')->toArray();
        $periodIds = $periods->pluck('id')->toArray();

        // Calculate how many periods are available per week
        $totalPeriods = count($days) * count($periodIds);
        
        // Calculate how many times each subject should appear
        $periodsPerSubject = floor($totalPeriods / count($subjectIds));
        
        // Create a pool of subject IDs with repetitions
        $subjectPool = [];
        foreach ($subjectIds as $subjectId) {
            for ($i = 0; $i < $periodsPerSubject; $i++) {
                $subjectPool[] = $subjectId;
            }
        }
        
        // Fill remaining slots with random subjects
        $remaining = $totalPeriods - count($subjectPool);
        for ($i = 0; $i < $remaining; $i++) {
            $subjectPool[] = $subjectIds[array_rand($subjectIds)];
        }
        
        // Shuffle the subject pool
        shuffle($subjectPool);
        
        // Distribute subjects across the week
        $index = 0;
        foreach ($days as $day) {
            $schedule[$day] = [];
            foreach ($periodIds as $periodId) {
                $schedule[$day][$periodId] = $subjectPool[$index];
                $index++;
            }
        }

        return $schedule;
    }
}
