<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Stream;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAssignmentController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->paginate(20);
        $activeYear = AcademicYear::active()->first();
        
        return view('teachers.assignments.index', compact('teachers', 'activeYear'));
    }

    public function edit(Teacher $teacher)
    {
        $activeYear = AcademicYear::active()->first();
        
        // Get all subjects
        $subjects = Subject::orderBy('name')->get();
        
        // Get all streams for active year
        $streams = Stream::with('schoolClass')
            ->where('academic_year_id', $activeYear?->id)
            ->orderBy('name')
            ->get();
        
        // Get teacher's current assignments
        $assignedSubjects = $teacher->subjects()
            ->wherePivot('academic_year_id', $activeYear?->id)
            ->get()
            ->pluck('id')
            ->toArray();
        
        $assignedStreams = $teacher->streams()
            ->where('academic_year_id', $activeYear?->id)
            ->get()
            ->pluck('id')
            ->toArray();
        
        return view('teachers.assignments.edit', compact(
            'teacher',
            'subjects',
            'streams',
            'assignedSubjects',
            'assignedStreams',
            'activeYear'
        ));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
            'streams' => 'nullable|array',
            'streams.*' => 'exists:streams,id',
        ]);

        $activeYear = AcademicYear::active()->first();

        if (!$activeYear) {
            return back()->with('error', 'No active academic year found.');
        }

        DB::beginTransaction();
        
        try {
            // Sync subjects
            $subjectData = [];
            if ($request->has('subjects')) {
                foreach ($request->subjects as $subjectId) {
                    $subjectData[$subjectId] = [
                        'academic_year_id' => $activeYear->id,
                        'is_primary' => false,
                    ];
                }
            }
            
            // Remove existing assignments for this year and add new ones
            $teacher->subjects()
                ->wherePivot('academic_year_id', $activeYear->id)
                ->detach();
            
            if (!empty($subjectData)) {
                $teacher->subjects()->attach($subjectData);
            }

            // Sync streams
            $streamData = [];
            if ($request->has('streams')) {
                foreach ($request->streams as $streamId) {
                    $streamData[$streamId] = [
                        'academic_year_id' => $activeYear->id,
                    ];
                }
            }
            
            // Remove existing stream assignments for this year and add new ones
            $teacher->streams()
                ->wherePivot('academic_year_id', $activeYear->id)
                ->detach();
            
            if (!empty($streamData)) {
                $teacher->streams()->attach($streamData);
            }

            DB::commit();

            return redirect()
                ->route('teachers.assignments.index')
                ->with('success', 'Teacher assignments updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update assignments: ' . $e->getMessage());
        }
    }

    public function bulkAssign()
    {
        $activeYear = AcademicYear::active()->first();
        $teachers = Teacher::with('user')->orderBy('created_at', 'desc')->get();
        $subjects = Subject::orderBy('name')->get();
        $streams = Stream::with('schoolClass')
            ->where('academic_year_id', $activeYear?->id)
            ->orderBy('name')
            ->get();
        
        return view('teachers.assignments.bulk', compact('teachers', 'subjects', 'streams', 'activeYear'));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'assignments' => 'required|array',
            'assignments.*.teacher_id' => 'required|exists:teachers,id',
            'assignments.*.subjects' => 'nullable|array',
            'assignments.*.subjects.*' => 'exists:subjects,id',
            'assignments.*.streams' => 'nullable|array',
            'assignments.*.streams.*' => 'exists:streams,id',
        ]);

        $activeYear = AcademicYear::active()->first();

        if (!$activeYear) {
            return back()->with('error', 'No active academic year found.');
        }

        DB::beginTransaction();
        
        try {
            foreach ($request->assignments as $assignment) {
                $teacher = Teacher::findOrFail($assignment['teacher_id']);
                
                // Clear existing assignments for this year
                $teacher->subjects()->wherePivot('academic_year_id', $activeYear->id)->detach();
                $teacher->streams()->wherePivot('academic_year_id', $activeYear->id)->detach();
                
                // Assign subjects
                if (!empty($assignment['subjects'])) {
                    $subjectData = [];
                    foreach ($assignment['subjects'] as $subjectId) {
                        $subjectData[$subjectId] = [
                            'academic_year_id' => $activeYear->id,
                            'is_primary' => false,
                        ];
                    }
                    $teacher->subjects()->attach($subjectData);
                }
                
                // Assign streams
                if (!empty($assignment['streams'])) {
                    $streamData = [];
                    foreach ($assignment['streams'] as $streamId) {
                        $streamData[$streamId] = [
                            'academic_year_id' => $activeYear->id,
                        ];
                    }
                    $teacher->streams()->attach($streamData);
                }
            }

            DB::commit();

            return redirect()
                ->route('teachers.assignments.index')
                ->with('success', 'Bulk assignments completed successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process bulk assignments: ' . $e->getMessage());
        }
    }
}
