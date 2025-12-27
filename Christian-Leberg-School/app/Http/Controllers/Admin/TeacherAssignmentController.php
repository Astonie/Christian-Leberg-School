<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Stream;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherAssignmentController extends Controller
{
    public function edit(Teacher $teacher)
    {
        $highlightStream = request()->query('highlight_stream');
        $year = AcademicYear::active()->first();
        $subjects = Subject::all();
        $streams = Stream::where('academic_year_id', $year?->id)->get();

        // current assignments for teacher
        $assigned = DB::table('stream_teacher')->where('teacher_id', $teacher->id)->pluck('subject_id', 'stream_id')->toArray();
        $classTeachers = DB::table('stream_teacher')->where('teacher_id', $teacher->id)->where('is_class_teacher', true)->pluck('is_class_teacher', 'stream_id')->toArray();

        return view('admin.teachers.assignments', compact('teacher', 'subjects', 'streams', 'assigned', 'classTeachers', 'year', 'highlightStream'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $year = AcademicYear::active()->first();

        if (!$year) {
            return back()->with('error', 'No active academic year found.');
        }

        $data = $request->validate([
            'assignments' => ['nullable', 'array'], // assignments[stream_id] = subject_id
            'class_teacher' => ['nullable', 'array'], // class_teacher[stream_id] = 1
        ]);

        $streams = Stream::where('academic_year_id', $year->id)->pluck('id')->all();

        DB::transaction(function () use ($teacher, $data, $streams, $year) {
            // Remove existing assignments for teacher in these streams for this academic year
            DB::table('stream_teacher')
                ->where('teacher_id', $teacher->id)
                ->whereIn('stream_id', $streams)
                ->where('academic_year_id', $year->id)
                ->delete();

            $assignments = $data['assignments'] ?? [];
            $classTeachers = $data['class_teacher'] ?? [];

            foreach ($assignments as $streamId => $subjectId) {
                if (! $subjectId) continue;
                DB::table('stream_teacher')->insert([
                    'stream_id' => $streamId,
                    'teacher_id' => $teacher->id,
                    'subject_id' => $subjectId,
                    'is_class_teacher' => isset($classTeachers[$streamId]) && $classTeachers[$streamId] ? true : false,
                    'academic_year_id' => $year->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        return redirect()->route('teachers.show', $teacher)->with('success', 'Assignments updated.');
    }
}
