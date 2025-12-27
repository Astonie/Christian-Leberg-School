<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Exam;
use App\Models\User;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\AcademicYear;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\StudentService; // Assuming we will create this
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Student::with(['user', 'streams.schoolClass']);
        $baseQuery = Student::query(); // For stats calculation
        $teacherStreamIds = [];
        $availableClasses = SchoolClass::all();

        // If a teacher is viewing, limit to students in the teacher's assigned streams for the active academic year
        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            $year = AcademicYear::active()->first();
            if ($teacher && $year) {
                // Get teacher's stream IDs
                $teacherStreamIds = $teacher->streams()->where('stream_teacher.academic_year_id', $year->id)->pluck('streams.id')->all();
                
                if (!empty($teacherStreamIds)) {
                    // Apply filter to main query
                    $query->whereHas('streams', function ($q) use ($teacherStreamIds, $year) {
                        $q->whereIn('streams.id', $teacherStreamIds)
                          ->where('student_stream.academic_year_id', $year->id)
                          ->where('student_stream.is_active', true);
                    })
                    // Eager load only the relevant streams for this teacher and year
                    ->with(['streams' => function($q) use ($teacherStreamIds, $year) {
                        $q->whereIn('streams.id', $teacherStreamIds)
                          ->where('student_stream.academic_year_id', $year->id)
                          ->where('student_stream.is_active', true);
                    }]);
                    
                    // Apply same filter to base query for stats
                    $baseQuery->whereHas('streams', function ($q) use ($teacherStreamIds, $year) {
                        $q->whereIn('streams.id', $teacherStreamIds)
                          ->where('student_stream.academic_year_id', $year->id)
                          ->where('student_stream.is_active', true);
                    });
                    
                    // Get only classes that the teacher teaches
                    $availableClasses = SchoolClass::whereHas('streams', function($q) use ($teacherStreamIds) {
                        $q->whereIn('streams.id', $teacherStreamIds);
                    })->get();
                } else {
                    // No stream assignment -> return empty
                    $query->whereRaw('1 = 0');
                    $baseQuery->whereRaw('1 = 0');
                    $availableClasses = collect();
                }
            } else {
                // No teacher profile or no active year -> return empty
                $query->whereRaw('1 = 0');
                $baseQuery->whereRaw('1 = 0');
                $availableClasses = collect();
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admission_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Class filter
        if ($request->filled('class')) {
            $query->whereHas('activeStreams.schoolClass', function($q) use ($request) {
                $q->where('classes.id', $request->class);
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Export functionality
        if ($request->filled('export')) {
            return $this->export($request, $query);
        }

        // Calculate stats based on role
        $stats = [
            'total' => $baseQuery->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'male' => (clone $baseQuery)->where('gender', 'male')->count(),
            'female' => (clone $baseQuery)->where('gender', 'female')->count(),
        ];

        $students = $query->latest()->paginate(15);
        $exams = Exam::latest()->get();
        $selectedExam = $request->query('exam') ? Exam::find($request->query('exam')) : Exam::latest()->first();
        
        return view('students.index', compact('students', 'exams', 'selectedExam', 'stats', 'availableClasses'));
    }

    /**
     * Export students data
     */
    private function export(Request $request, $query)
    {
        $students = $query->get();
        $filename = 'students_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Admission No', 'Name', 'Email', 'Gender', 'Class', 'Stream', 'Status', 'Admission Date', 'Date of Birth']);

            foreach ($students as $student) {
                fputcsv($file, [
                    $student->admission_number,
                    $student->user->name,
                    $student->user->email,
                    $student->gender,
                    $student->current_stream ? $student->current_stream->schoolClass->name : 'N/A',
                    $student->current_stream ? $student->current_stream->name : 'N/A',
                    $student->status,
                    $student->admission_date->format('Y-m-d'),
                    $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '',
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
        $classes = SchoolClass::with('streams')->get();
        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Create User
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make('password'), // Default password or generated
                'role_id' => Role::where('slug', 'student')->first()->id,
                'is_active' => true,
            ]);

            // Create Student Profile
            $student = Student::create([
                'user_id' => $user->id,
                'admission_number' => $request->admission_number,
                'admission_date' => $request->admission_date,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'address' => $request->address,
                'medical_conditions' => $request->medical_conditions,
                'status' => 'active',
            ]);

            // Assign to Stream if provided
            if ($request->stream_id) {
                $academicYear = AcademicYear::active()->first();
                if ($academicYear) {
                    $student->streams()->attach($request->stream_id, [
                        'academic_year_id' => $academicYear->id,
                        'enrollment_date' => now(),
                        'is_active' => true,
                    ]);
                }
            }
        });

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Student $student)
    {
        $user = $request->user();

        // If teacher, ensure the student is in one of their assigned streams for the active academic year
        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            $year = AcademicYear::active()->first();
            $inStream = $student->streams()->wherePivot('academic_year_id', $year?->id)->where('student_stream.is_active', true)->whereIn('streams.id', $teacher->streams()->where('stream_teacher.academic_year_id', $year?->id)->pluck('streams.id')->all())->exists();
            if (! $inStream) {
                abort(403);
            }
        }

        $student->load(['user', 'guardians.user', 'streams.schoolClass', 'attendanceRecords', 'examResults.exam', 'examResults.subject']);
        $exams = Exam::latest()->get();
        $selectedExam = $request->query('exam') ? Exam::find($request->query('exam')) : Exam::latest()->first();
        return view('students.show', compact('student', 'exams', 'selectedExam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $student->load('user', 'activeStreams');
        $classes = SchoolClass::with('streams')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        DB::transaction(function () use ($request, $student) {
            // Update User
            $student->user->update([
                'name' => $request->first_name . ' ' . $request->last_name,
                'email' => $request->email, // Email update might need validation against other users
            ]);

            // Update Student Profile
            $student->update($request->only([
                'admission_number', 'admission_date', 'date_of_birth', 
                'gender', 'nationality', 'address', 'medical_conditions', 'status'
            ]));

            // Update Stream if changed
            if ($request->has('stream_id') && $request->stream_id != $student->current_stream?->id) {
                // Deactivate current stream
                $student->streams()->wherePivot('is_active', true)->update(['is_active' => false]);
                
                // Assign new stream
                if ($request->stream_id) {
                    $academicYear = AcademicYear::active()->first();
                    $student->streams()->attach($request->stream_id, [
                        'academic_year_id' => $academicYear ? $academicYear->id : 1, // Fallback or handle error
                        'enrollment_date' => now(),
                        'is_active' => true,
                    ]);
                }
            }
        });

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->user->delete(); // Soft delete user
        $student->delete(); // Soft delete profile
        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    /**
     * Store a guardian for this student
     */
    public function storeGuardian(Request $request, Student $student)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'phone_number' => ['required', 'string', 'max:20'],
            'relationship' => ['required', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'is_primary_contact' => ['nullable', 'boolean'],
            'can_pickup' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $student) {
            // Create User account for guardian
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password ?? \Illuminate\Support\Str::random(12)),
                'role_id' => Role::where('slug', 'guardian')->first()->id,
                'is_active' => true,
            ]);

            // Create Guardian profile
            $guardian = \App\Models\Guardian::create([
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'relationship' => $request->relationship,
                'address' => $request->address,
                'occupation' => $request->occupation,
            ]);

            // Attach guardian to student with pivot data
            $student->guardians()->attach($guardian->id, [
                'is_primary_contact' => $request->boolean('is_primary_contact', false),
                'can_pickup' => $request->boolean('can_pickup', true),
            ]);
        });

        return redirect()->route('students.show', $student)->with('success', 'Guardian added successfully.');
    }
}
