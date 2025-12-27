<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Stream;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Http\Requests\StoreAttendanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // If teacher, only show streams and subjects they are assigned to
        if ($user->hasRole('teacher') && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $activeYear = AcademicYear::active()->first();
            
            if (!$teacher || !$activeYear) {
                $streams = collect();
                $subjects = collect();
            } else {
                $streams = $teacher->streams()
                    ->where('streams.academic_year_id', $activeYear->id)
                    ->with('schoolClass')
                    ->get();
                    
                $subjects = $teacher->subjects()
                    ->wherePivot('academic_year_id', $activeYear->id)
                    ->get();
            }
        } else {
            // Admin can see all streams and subjects
            $streams = Stream::with('schoolClass')->get();
            $subjects = Subject::all();
        }
        
        return view('attendance.index', compact('streams', 'subjects'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'stream_id' => 'required|exists:streams,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
        ]);

        $stream = Stream::with('schoolClass')->findOrFail($request->stream_id);
        $subject = Subject::findOrFail($request->subject_id);
        $date = $request->date;
        
        // If teacher, verify they are assigned to this stream and teach this subject
        if ($user->hasRole('teacher') && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $activeYear = AcademicYear::active()->first();
            
            if (!$teacher || !$activeYear) {
                abort(403, 'No active academic year found.');
            }
            
            $isAssigned = $teacher->streams()
                ->where('streams.id', $stream->id)
                ->where('stream_teacher.academic_year_id', $activeYear->id)
                ->exists();
            
            if (!$isAssigned) {
                abort(403, 'You are not assigned to this stream.');
            }
            
            // Verify teacher teaches this subject
            $teachesSubject = $teacher->subjects()
                ->where('subjects.id', $subject->id)
                ->wherePivot('academic_year_id', $activeYear->id)
                ->exists();
                
            if (!$teachesSubject) {
                abort(403, 'You do not teach this subject.');
            }
        }

        // Fetch students in this stream
        $students = $stream->students()->wherePivot('is_active', true)->get();

        // Check for existing attendance to pre-fill
        $existingAttendance = AttendanceRecord::where('stream_id', $stream->id)
            ->where('subject_id', $subject->id)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        return view('attendance.create', compact('stream', 'subject', 'date', 'students', 'existingAttendance'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $streamId = $data['stream_id'];
        $subjectId = $data['subject_id'];
        $date = $data['date'];
        
        // Verify stream and subject access for teachers
        if ($user->hasRole('teacher') && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $activeYear = AcademicYear::active()->first();
            
            if (!$teacher || !$activeYear) {
                abort(403, 'No active academic year found.');
            }
            
            $isAssigned = $teacher->streams()
                ->where('streams.id', $streamId)
                ->where('stream_teacher.academic_year_id', $activeYear->id)
                ->exists();
            
            if (!$isAssigned) {
                abort(403, 'You are not assigned to this stream.');
            }
            
            // Verify teacher teaches this subject
            $teachesSubject = $teacher->subjects()
                ->where('subjects.id', $subjectId)
                ->wherePivot('academic_year_id', $activeYear->id)
                ->exists();
                
            if (!$teachesSubject) {
                abort(403, 'You do not teach this subject.');
            }
            
            $teacherId = $teacher->id;
        } else {
            $teacherId = null;
        }

        try {
            DB::beginTransaction();
            
            $updatedCount = 0;
            $createdCount = 0;
            
            foreach ($data['attendance'] as $record) {
                $attendance = AttendanceRecord::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'subject_id' => $subjectId,
                        'date' => $date,
                    ],
                    [
                        'stream_id' => $streamId,
                        'teacher_id' => $teacherId,
                        'status' => $record['status'],
                        'remarks' => $record['remarks'] ?? null,
                    ]
                );
                
                if ($attendance->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }
            
            DB::commit();
            
            $message = "Attendance recorded successfully for " . \Carbon\Carbon::parse($date)->format('M d, Y');
            if ($updatedCount > 0) {
                $message .= " ($updatedCount record(s) updated, $createdCount new)";
            }
            
            return redirect()->route('attendance.index')
                ->with('success', $message);
                
        } catch (QueryException $e) {
            DB::rollBack();
            
            // Check if it's a unique constraint violation
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'UNIQUE constraint failed') !== false) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Attendance has already been marked for some students in this subject on this date. Please check the attendance records and try again.');
            }
            
            // Log other database errors
            \Log::error('Attendance Store Error', [
                'error' => $e->getMessage(),
                'stream_id' => $streamId,
                'subject_id' => $subjectId,
                'date' => $date
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while saving attendance records. Please try again.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Unexpected Attendance Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An unexpected error occurred. Please contact the administrator if this persists.');
        }
    }

    public function reports(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $streamId = $request->input('stream_id');
        $subjectId = $request->input('subject_id');
        
        // Build query based on user role
        // Use whereDate for proper date comparison since date column is datetime
        $query = AttendanceRecord::with(['student.user', 'stream.schoolClass', 'subject', 'teacher.user'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate);
        
        $teacherStreamIds = [];
        $teacherSubjectIds = [];
        
        if ($user->hasRole('teacher') && !$user->hasRole('admin')) {
            $teacher = $user->teacher;
            $activeYear = AcademicYear::active()->first();
            
            if (!$teacher || !$activeYear) {
                $attendanceRecords = collect();
                $streams = collect();
                $subjects = collect();
                $stats = ['total' => 0, 'present' => 0, 'absent' => 0, 'late' => 0, 'excused' => 0];
                
                return view('attendance.reports', compact('attendanceRecords', 'streams', 'subjects', 'stats', 'startDate', 'endDate', 'streamId', 'subjectId'));
            } else {
                // Filter by teacher's streams and subjects
                $teacherStreamIds = $teacher->streams()
                    ->where('stream_teacher.academic_year_id', $activeYear->id)
                    ->pluck('streams.id')->toArray();
                
                $teacherSubjectIds = $teacher->subjects()
                    ->wherePivot('academic_year_id', $activeYear->id)
                    ->pluck('subjects.id')->toArray();
                
                // Only show records for teacher's assigned streams and subjects
                if (!empty($teacherStreamIds) && !empty($teacherSubjectIds)) {
                    $query->whereIn('stream_id', $teacherStreamIds)
                          ->whereIn('subject_id', $teacherSubjectIds);
                }
                
                $streams = $teacher->streams()
                    ->where('streams.academic_year_id', $activeYear->id)
                    ->with('schoolClass')
                    ->get();
                    
                $subjects = $teacher->subjects()
                    ->wherePivot('academic_year_id', $activeYear->id)
                    ->get();
            }
        } else {
            // Admin sees all
            $streams = Stream::with('schoolClass')->get();
            $subjects = Subject::all();
        }
        
        // Apply additional filters
        if ($streamId) {
            $query->where('stream_id', $streamId);
        }
        
        if ($subjectId) {
            $query->where('subject_id', $subjectId);
        }
        
        $attendanceRecords = $query->orderBy('date', 'desc')
            ->orderBy('stream_id')
            ->orderBy('subject_id')
            ->paginate(50);
        
        // Calculate statistics with proper filtering
        $statsQuery = AttendanceRecord::query()
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate);
        
        // Apply teacher filters to stats if non-admin teacher
        if (!empty($teacherStreamIds) && !empty($teacherSubjectIds)) {
            $statsQuery->whereIn('stream_id', $teacherStreamIds)
                      ->whereIn('subject_id', $teacherSubjectIds);
        }
        
        // Apply user-selected filters to stats
        if ($streamId) {
            $statsQuery->where('stream_id', $streamId);
        }
        
        if ($subjectId) {
            $statsQuery->where('subject_id', $subjectId);
        }
        
        $stats = [
            'total' => $attendanceRecords->total(),
            'present' => (clone $statsQuery)->where('status', 'present')->count(),
            'absent' => (clone $statsQuery)->where('status', 'absent')->count(),
            'late' => (clone $statsQuery)->where('status', 'late')->count(),
            'excused' => (clone $statsQuery)->where('status', 'excused')->count(),
        ];
        
        return view('attendance.reports', compact('attendanceRecords', 'streams', 'subjects', 'stats', 'startDate', 'endDate', 'streamId', 'subjectId'));
    }

    public function guardianReport($studentId = null)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('guardian')) {
            abort(403, 'Access denied.');
        }
        
        $guardian = $user->guardian;
        
        if (!$guardian) {
            abort(404, 'Guardian profile not found.');
        }
        
        // Get guardian's children
        $students = $guardian->students;
        
        if ($students->isEmpty()) {
            return view('attendance.guardian-report', [
                'students' => $students,
                'selectedStudent' => null,
                'attendanceData' => [],
                'missingRecords' => []
            ]);
        }
        
        // If no student selected, default to first child
        if (!$studentId) {
            $studentId = $students->first()->id;
        }
        
        // Verify the student belongs to this guardian
        $selectedStudent = $students->firstWhere('id', $studentId);
        
        if (!$selectedStudent) {
            abort(403, 'You do not have access to this student.');
        }
        
        // Get active academic year
        $activeYear = AcademicYear::active()->first();
        
        if (!$activeYear) {
            return view('attendance.guardian-report', [
                'students' => $students,
                'selectedStudent' => $selectedStudent,
                'attendanceData' => [],
                'missingRecords' => []
            ]);
        }
        
        // Get student's active streams
        $studentStreams = $selectedStudent->streams()
            ->wherePivot('academic_year_id', $activeYear->id)
            ->wherePivot('is_active', true)
            ->with('schoolClass')
            ->get();
        
        // Get attendance records for the last 30 days
        $startDate = now()->subDays(30);
        $endDate = now();
        
        $attendanceRecords = AttendanceRecord::where('student_id', $selectedStudent->id)
            ->whereDate('date', '>=', $startDate->format('Y-m-d'))
            ->whereDate('date', '<=', $endDate->format('Y-m-d'))
            ->with(['subject', 'stream', 'teacher.user'])
            ->orderBy('date', 'desc')
            ->get();
        
        // Group by subject
        $attendanceData = $attendanceRecords->groupBy('subject_id')->map(function($records, $subjectId) {
            $subject = $records->first()->subject;
            return [
                'subject' => $subject,
                'records' => $records,
                'total' => $records->count(),
                'present' => $records->where('status', 'present')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
                'excused' => $records->where('status', 'excused')->count(),
                'attendance_rate' => $records->count() > 0 
                    ? round(($records->where('status', 'present')->count() / $records->count()) * 100, 1) 
                    : 0
            ];
        });
        
        // Check for missing attendance records
        // Get all subjects the student should have attendance for
        $allSubjects = Subject::all();
        $daysInPeriod = $endDate->diffInDays($startDate);
        
        $missingRecords = [];
        foreach ($allSubjects as $subject) {
            $recordCount = $attendanceRecords->where('subject_id', $subject->id)->count();
            
            // If no records in the last 30 days, flag as missing
            if ($recordCount === 0) {
                $missingRecords[] = [
                    'subject' => $subject,
                    'message' => 'No attendance records in the last 30 days'
                ];
            }
        }
        
        return view('attendance.guardian-report', compact('students', 'selectedStudent', 'attendanceData', 'missingRecords'));
    }
}
