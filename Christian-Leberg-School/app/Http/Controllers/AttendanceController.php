<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Stream;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Http\Requests\StoreAttendanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        // For simplicity, allowed for Admin and Teacher
        $streams = Stream::with('schoolClass')->get();
        return view('attendance.index', compact('streams'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'stream_id' => 'required|exists:streams,id',
            'date' => 'required|date',
        ]);

        $stream = Stream::with('schoolClass')->findOrFail($request->stream_id);
        $date = $request->date;

        // Fetch students in this stream
        // We assume students have a current_stream or via pivot. 
        // Using the activeStreams relation defined in Student model or simpler pivot query.
        $students = $stream->students()->wherePivot('is_active', true)->get();

        // Check for existing attendance to pre-fill
        $existingAttendance = AttendanceRecord::where('stream_id', $stream->id)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        return view('attendance.create', compact('stream', 'date', 'students', 'existingAttendance'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $data = $request->validated();
        $streamId = $data['stream_id'];
        $date = $data['date'];
        $teacherId = Auth::user()->hasRole('teacher') ? Auth::user()->profile->id : null;

        foreach ($data['attendance'] as $record) {
            AttendanceRecord::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'date' => $date,
                ],
                [
                    'stream_id' => $streamId,
                    'teacher_id' => $teacherId,
                    'status' => $record['status'],
                    'remarks' => $record['remarks'] ?? null,
                ]
            );
        }

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance recorded successfully for ' . $date);
    }
}
