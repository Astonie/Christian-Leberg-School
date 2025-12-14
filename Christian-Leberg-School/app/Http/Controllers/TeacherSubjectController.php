<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class TeacherSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Teacher $teacher)
    {
        $teacher->load('subjects');
        $subjects = Subject::whereNotIn('id', $teacher->subjects->pluck('id'))->get();
        // We might want to filter subjects available for assignment (not already assigned)
        
        return view('teachers.subjects', compact('teacher', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Teacher $teacher)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'is_primary' => 'boolean',
        ]);

        $academicYear = AcademicYear::active()->first();
        if (!$academicYear) {
            return back()->with('error', 'No active academic year found.');
        }

        // Check if already assigned
        if ($teacher->subjects()->where('subject_id', $request->subject_id)->exists()) {
             return back()->with('error', 'Subject already assigned to this teacher.');
        }

        $teacher->subjects()->attach($request->subject_id, [
            'academic_year_id' => $academicYear->id,
            'is_primary' => $request->boolean('is_primary', false),
        ]);

        return back()->with('success', 'Subject assigned successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher, Subject $subject)
    {
        $teacher->subjects()->detach($subject->id);
        return back()->with('success', 'Subject removed successfully.');
    }
}
