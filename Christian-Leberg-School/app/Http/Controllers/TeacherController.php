<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Handle CSV export
        if ($request->has('export') && $request->export === 'csv') {
            return $this->export($request);
        }

        $query = Teacher::with(['user', 'subjects', 'streams']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Employment Type filter
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        // Subject filter
        if ($request->filled('subject')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }

        $teachers = $query->latest()->paginate(15);
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Export teachers to CSV
     */
    private function export(Request $request)
    {
        $query = Teacher::with(['user', 'subjects', 'streams']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('subject')) {
            $query->whereHas('subjects', function($q) use ($request) {
                $q->where('subjects.id', $request->subject);
            });
        }

        $teachers = $query->get();

        $filename = 'teachers_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($teachers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Employee Number',
                'Name',
                'Email',
                'Phone',
                'Employment Type',
                'Hire Date',
                'Subjects Count',
                'Classes Count'
            ]);

            // CSV data
            foreach ($teachers as $teacher) {
                fputcsv($file, [
                    $teacher->employee_number,
                    $teacher->user->name,
                    $teacher->user->email,
                    $teacher->phone_number ?? 'N/A',
                    ucfirst(str_replace('_', ' ', $teacher->employment_type)),
                    $teacher->hire_date ? $teacher->hire_date->format('Y-m-d') : 'N/A',
                    $teacher->subjects->count(),
                    $teacher->streams->count()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Create User
            $password = Str::random(8); // Temporary password, typically send via email
            $role = Role::where('slug', 'teacher')->firstOrFail();

            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($password), // In production, send invite link or temporary password
                'role_id' => $role->id,
            ]);

            // 2. Create Teacher Profile
            $teacherData = $request->except(['first_name', 'last_name', 'email']);
            $teacherData['user_id'] = $user->id;
            
            $teacher = Teacher::create($teacherData);
            
            // TODO: Dispatch event to send welcome email with password
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('user', 'subjects', 'streams.schoolClass');

        // Build a summary of assignments: stream -> subject, is_class_teacher
        $streamAssignments = [];
        foreach ($teacher->streams as $stream) {
            $subject = null;
            if (! empty($stream->pivot->subject_id)) {
                $subject = \App\Models\Subject::find($stream->pivot->subject_id);
            }
            $streamAssignments[$stream->id] = [
                'stream' => $stream,
                'subject' => $subject,
                'is_class_teacher' => (bool) ($stream->pivot->is_class_teacher ?? false),
            ];
        }

        return view('teachers.show', compact('teacher', 'streamAssignments'));
    }

    public function mySubjects()
    {
        $user = auth()->user();
        $teacher = $user->teacher;
        $year = \App\Models\AcademicYear::active()->first();
        
        if (!$teacher) {
            return view('teachers.my_subjects', [
                'subjects' => collect(),
                'year' => $year,
                'statistics' => null
            ]);
        }
        
        $subjects = $teacher->subjects()
            ->wherePivot('academic_year_id', $year?->id)
            ->get();
        
        // Get streams and student count for each subject
        $subjects = $subjects->map(function($subject) use ($teacher, $year) {
            // Get only students that this teacher teaches for this subject
            $teacherStudents = $teacher->getStudentsForSubject($subject->id, $year);
            $subject->students_count = $teacherStudents->count();
            $subject->students_list = $teacherStudents; // Include the actual students
            
            // Get streams where teacher teaches this specific subject
            $subject->teacher_streams = $teacher->getStreamsForSubject($subject->id, $year);
            
            return $subject;
        });
        
        // Calculate statistics based on teacher's actual data
        $statistics = [
            'total_subjects' => $subjects->count(),
            'total_students' => $subjects->sum('students_count'), // Sum of unique students across all subjects
            'total_streams' => $teacher->streams()->where('stream_teacher.academic_year_id', $year?->id)->count(),
            'active_exams' => \App\Models\Exam::where('academic_year_id', $year?->id)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count()
        ];
        
        return view('teachers.my_subjects', compact('subjects', 'year', 'statistics'));
    }

    public function myStreams()
    {
        $user = auth()->user();
        $teacher = $user->teacher;
        $year = \App\Models\AcademicYear::active()->first();
        $streams = $teacher ? $teacher->streams()->where('stream_teacher.academic_year_id', $year?->id)->with(['schoolClass','students.user'])->get() : collect();
        return view('teachers.my_streams', compact('streams'));
    }

    /**
     * Export CSV of students in a given stream who are missing results for a subject and exam.
     */
    public function exportMissingResults(Request $request)
    {
        $request->validate([
            'exam_id' => ['required', 'exists:exams,id'],
            'stream_id' => ['required', 'exists:streams,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        $user = $request->user();
        if (! $user->hasRole('teacher')) {
            abort(403);
        }

        $teacher = $user->teacher;
        $exam = \App\Models\Exam::find($request->input('exam_id'));
        $stream = \App\Models\Stream::find($request->input('stream_id'));
        $subject = \App\Models\Subject::find($request->input('subject_id'));

        // Ensure teacher is assigned to this stream (and optionally to the subject)
        $assigned = \DB::table('stream_teacher')->where('stream_id', $stream->id)->where('teacher_id', $teacher->id)->where('subject_id', $subject->id)->exists();
        if (! $assigned) abort(403);

        $year = \App\Models\AcademicYear::active()->first();

        // Students in stream for this year
        $studentIds = \DB::table('student_stream')->where('stream_id', $stream->id)->where('academic_year_id', $year->id)->where('is_active', true)->pluck('student_id');

        // Students who have results for this exam and subject
        $withResults = \DB::table('exam_results')->where('exam_id', $exam->id)->where('subject_id', $subject->id)->whereIn('student_id', $studentIds)->pluck('student_id')->all();

        $missingIds = array_diff($studentIds->toArray(), $withResults);

        $students = \App\Models\Student::with('user')->whereIn('id', $missingIds)->get();

        $filename = sprintf('missing_results_exam_%s_stream_%s_subject_%s.csv', $exam->id, $stream->id, $subject->id);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($students, $stream, $subject, $exam) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['admission_number','student_name','student_id','stream','subject','exam']);
            foreach ($students as $s) {
                fputcsv($out, [$s->admission_number, $s->user->name, $s->id, $stream->full_name, $subject->code ?? $subject->name, $exam->name]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        DB::transaction(function () use ($request, $teacher) {
            // Update User
            $teacher->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
            ]);

            // Update Teacher Profile
            $teacher->update($request->except(['first_name', 'last_name', 'email']));
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        DB::transaction(function () use ($teacher) {
            $user = $teacher->user;
            $teacher->delete(); // Soft delete teacher
            $user->delete(); // Soft delete user (if user implements SoftDeletes, which it might not, so standard delete for user or deactive)
            // Ideally we might just want to deactivate the user or soft delete if User model has SoftDeletes.
            // For now, let's keep user primarily but deactivate login?
            // Or just deleting profile logic. 
            // In many systems, we soft delete both.
        });

        return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
    }
}
