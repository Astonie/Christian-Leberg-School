<?php

namespace App\Http\Controllers;

use App\Models\TimetableEntry;
use App\Models\TimetablePeriod;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{
    /**
     * Display timetable view with filters
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::active()->first();
        $user = auth()->user();
        
        $academicYearId = $request->get('academic_year_id', $activeYear->id ?? null);
        $termId = $request->get('term_id');
        $classId = $request->get('class_id');
        $streamId = $request->get('stream_id');
        $teacherId = $request->get('teacher_id');
        
        // If user is a teacher, automatically filter to their timetable and set view type
        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            if ($teacher) {
                $teacherId = $teacher->id;
                $viewType = 'teacher';
            }
        } else {
            $viewType = $request->get('view_type', 'class'); // class or teacher
        }

        // Get filter options
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $terms = Term::orderBy('order')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $streams = Stream::orderBy('name')->get();
        
        // For teachers, only show themselves in the teacher dropdown
        if ($user->hasRole('teacher')) {
            $teachers = Teacher::with('user')->where('id', $teacher->id ?? null)->get();
        } else {
            $teachers = Teacher::with('user')->get()->sortBy('user.name');
        }
        
        $periods = TimetablePeriod::active()->get();

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        // Build query based on view type
        $query = TimetableEntry::with(['period', 'subject', 'teacher.user', 'schoolClass', 'stream'])
            ->where('academic_year_id', $academicYearId);

        if ($termId) {
            $query->where('term_id', $termId);
        }

        if ($viewType === 'class' && $classId) {
            $query->where('class_id', $classId);
            if ($streamId) {
                $query->where('stream_id', $streamId);
            }
        } elseif ($viewType === 'teacher' && $teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $timetableEntries = $query->get();

        // Organize entries into a grid
        $timetable = [];
        foreach ($days as $day) {
            $timetable[$day] = [];
            foreach ($periods as $period) {
                $entry = $timetableEntries->first(function ($item) use ($day, $period) {
                    return $item->day_of_week === $day && $item->period_id === $period->id;
                });
                $timetable[$day][$period->id] = $entry;
            }
        }

        return view('timetables.index', compact(
            'timetable',
            'periods',
            'days',
            'academicYears',
            'terms',
            'classes',
            'streams',
            'teachers',
            'academicYearId',
            'termId',
            'classId',
            'streamId',
            'teacherId',
            'viewType'
        ));
    }

    /**
     * Show form to create/edit timetable entry
     */
    public function create(Request $request)
    {
        $activeYear = AcademicYear::active()->first();
        
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $terms = Term::orderBy('order')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $streams = Stream::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get()->sortBy('user.name');
        $periods = TimetablePeriod::teaching()->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        // Pre-fill from query params if provided
        $preselected = [
            'academic_year_id' => $request->get('academic_year_id', $activeYear->id ?? null),
            'term_id' => $request->get('term_id'),
            'class_id' => $request->get('class_id'),
            'stream_id' => $request->get('stream_id'),
            'day_of_week' => $request->get('day_of_week'),
            'period_id' => $request->get('period_id'),
        ];

        return view('timetables.create', compact(
            'academicYears',
            'terms',
            'classes',
            'streams',
            'subjects',
            'teachers',
            'periods',
            'days',
            'preselected'
        ));
    }

    /**
     * Store new timetable entry
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'nullable|exists:terms,id',
            'class_id' => 'required|exists:classes,id',
            'stream_id' => 'nullable|exists:streams,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'period_id' => 'required|exists:timetable_periods,id',
            'day_of_week' => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'room' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for conflicts
        $teacherAvailable = TimetableEntry::isTeacherAvailable(
            $validated['teacher_id'],
            $validated['period_id'],
            $validated['day_of_week'],
            $validated['academic_year_id'],
            $validated['term_id'] ?? null
        );

        $classAvailable = TimetableEntry::isClassAvailable(
            $validated['class_id'],
            $validated['stream_id'] ?? null,
            $validated['period_id'],
            $validated['day_of_week'],
            $validated['academic_year_id'],
            $validated['term_id'] ?? null
        );

        if (!$teacherAvailable) {
            return back()->withInput()->with('error', 'Teacher is already scheduled for another class at this time.');
        }

        if (!$classAvailable) {
            return back()->withInput()->with('error', 'This class/stream already has a lesson scheduled at this time.');
        }

        try {
            TimetableEntry::create($validated);
            
            return redirect()->route('timetables.index', [
                'academic_year_id' => $validated['academic_year_id'],
                'term_id' => $validated['term_id'],
                'class_id' => $validated['class_id'],
                'stream_id' => $validated['stream_id'],
            ])->with('success', 'Timetable entry created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create timetable entry: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit(TimetableEntry $timetable)
    {
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $terms = Term::orderBy('order')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $streams = Stream::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::with('user')->get()->sortBy('user.name');
        $periods = TimetablePeriod::teaching()->get();
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('timetables.edit', compact(
            'timetable',
            'academicYears',
            'terms',
            'classes',
            'streams',
            'subjects',
            'teachers',
            'periods',
            'days'
        ));
    }

    /**
     * Update timetable entry
     */
    public function update(Request $request, TimetableEntry $timetable)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'nullable|exists:terms,id',
            'class_id' => 'required|exists:classes,id',
            'stream_id' => 'nullable|exists:streams,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'period_id' => 'required|exists:timetable_periods,id',
            'day_of_week' => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'room' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for conflicts (excluding current entry)
        $teacherAvailable = TimetableEntry::isTeacherAvailable(
            $validated['teacher_id'],
            $validated['period_id'],
            $validated['day_of_week'],
            $validated['academic_year_id'],
            $validated['term_id'] ?? null,
            $timetable->id
        );

        $classAvailable = TimetableEntry::isClassAvailable(
            $validated['class_id'],
            $validated['stream_id'] ?? null,
            $validated['period_id'],
            $validated['day_of_week'],
            $validated['academic_year_id'],
            $validated['term_id'] ?? null,
            $timetable->id
        );

        if (!$teacherAvailable) {
            return back()->withInput()->with('error', 'Teacher is already scheduled for another class at this time.');
        }

        if (!$classAvailable) {
            return back()->withInput()->with('error', 'This class/stream already has a lesson scheduled at this time.');
        }

        try {
            $timetable->update($validated);
            
            return redirect()->route('timetables.index', [
                'academic_year_id' => $validated['academic_year_id'],
                'term_id' => $validated['term_id'],
                'class_id' => $validated['class_id'],
                'stream_id' => $validated['stream_id'],
            ])->with('success', 'Timetable entry updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update timetable entry: ' . $e->getMessage());
        }
    }

    /**
     * Delete timetable entry
     */
    public function destroy(TimetableEntry $timetable)
    {
        try {
            $timetable->delete();
            return back()->with('success', 'Timetable entry deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete timetable entry: ' . $e->getMessage());
        }
    }

    /**
     * Bulk create/update timetable entries
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'term_id' => 'nullable|exists:terms,id',
            'entries' => 'required|array',
            'entries.*.class_id' => 'required|exists:classes,id',
            'entries.*.stream_id' => 'nullable|exists:streams,id',
            'entries.*.subject_id' => 'required|exists:subjects,id',
            'entries.*.teacher_id' => 'required|exists:teachers,id',
            'entries.*.period_id' => 'required|exists:timetable_periods,id',
            'entries.*.day_of_week' => ['required', Rule::in(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])],
            'entries.*.room' => 'nullable|string|max:100',
        ]);

        $conflicts = [];
        $created = 0;

        DB::beginTransaction();
        try {
            foreach ($validated['entries'] as $index => $entry) {
                $entry['academic_year_id'] = $validated['academic_year_id'];
                $entry['term_id'] = $validated['term_id'] ?? null;

                // Check conflicts
                $teacherAvailable = TimetableEntry::isTeacherAvailable(
                    $entry['teacher_id'],
                    $entry['period_id'],
                    $entry['day_of_week'],
                    $entry['academic_year_id'],
                    $entry['term_id']
                );

                $classAvailable = TimetableEntry::isClassAvailable(
                    $entry['class_id'],
                    $entry['stream_id'] ?? null,
                    $entry['period_id'],
                    $entry['day_of_week'],
                    $entry['academic_year_id'],
                    $entry['term_id']
                );

                if (!$teacherAvailable || !$classAvailable) {
                    $conflicts[] = "Entry #" . ($index + 1) . ": " . (!$teacherAvailable ? "Teacher conflict" : "Class conflict");
                    continue;
                }

                TimetableEntry::create($entry);
                $created++;
            }

            if (empty($conflicts)) {
                DB::commit();
                return back()->with('success', "Successfully created {$created} timetable entries.");
            } else {
                DB::rollBack();
                return back()->with('warning', "Created {$created} entries. Skipped " . count($conflicts) . " due to conflicts: " . implode(', ', $conflicts));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Bulk operation failed: ' . $e->getMessage());
        }
    }
}
