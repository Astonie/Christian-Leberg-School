<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
    public function index()
    {
        $students = Student::with(['user', 'activeStreams.class'])->latest()->paginate(10);
        return view('students.index', compact('students'));
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
    public function show(Student $student)
    {
        $student->load(['user', 'guardians.user', 'streams.class', 'attendanceRecords', 'examMarks.exam']);
        return view('students.show', compact('student'));
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
}
