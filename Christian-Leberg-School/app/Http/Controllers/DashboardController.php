<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('dashboard.admin');
        } elseif ($user->hasRole('head-teacher') || $user->hasRole('deputy-head-teacher')) {
            return redirect()->route('dashboard.admin'); // Use admin dashboard for academic managers
        } elseif ($user->hasRole('teacher')) {
            return redirect()->route('dashboard.teacher');
        } elseif ($user->hasRole('student')) {
            return redirect()->route('dashboard.student');
        } elseif ($user->hasRole('guardian')) {
            return redirect()->route('dashboard.guardian');
        }

        return view('dashboard', ['user' => $user]);
    }

    public function admin()
    {
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        
        // Key Metrics
        $metrics = [
            'total_students' => \App\Models\Student::count(),
            'total_teachers' => \App\Models\Teacher::count(),
            'total_classes' => \App\Models\SchoolClass::count(),
            'total_subjects' => \App\Models\Subject::count(),
            'active_students' => 0,
            'male_students' => \App\Models\Student::where('gender', 'male')->count(),
            'female_students' => \App\Models\Student::where('gender', 'female')->count(),
            'active_exams' => \App\Models\Exam::where('end_date', '>=', now())->count(),
        ];
        
        // Active students in current academic year
        if ($activeYear) {
            $metrics['active_students'] = DB::table('student_stream')
                ->where('academic_year_id', $activeYear->id)
                ->where('is_active', true)
                ->distinct('student_id')
                ->count('student_id');
        }
        
        // Enrollment Trends (last 6 months) - SQLite compatible
        $enrollmentTrends = DB::table('students')
            ->select(DB::raw("strftime('%Y-%m', created_at) as month"), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');
        
        // Attendance Overview (last 30 days)
        $attendanceStats = [
            'total_records' => 0,
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'rate' => 0
        ];
        
        $recentAttendance = \App\Models\AttendanceRecord::where('date', '>=', now()->subDays(30))->get();
        if ($recentAttendance->count() > 0) {
            $attendanceStats['total_records'] = $recentAttendance->count();
            $attendanceStats['present'] = $recentAttendance->where('status', 'present')->count();
            $attendanceStats['absent'] = $recentAttendance->where('status', 'absent')->count();
            $attendanceStats['late'] = $recentAttendance->where('status', 'late')->count();
            $attendanceStats['rate'] = round(($attendanceStats['present'] / $attendanceStats['total_records']) * 100, 1);
        }
        
        // Class Distribution
        $classDistribution = \App\Models\SchoolClass::withCount(['streams' => function($q) use ($activeYear) {
            if ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            }
        }])->get()->map(function($class) use ($activeYear) {
            $studentCount = 0;
            if ($activeYear) {
                $studentCount = DB::table('student_stream')
                    ->join('streams', 'student_stream.stream_id', '=', 'streams.id')
                    ->where('streams.class_id', $class->id)
                    ->where('student_stream.academic_year_id', $activeYear->id)
                    ->where('student_stream.is_active', true)
                    ->distinct('student_stream.student_id')
                    ->count('student_stream.student_id');
            }
            return [
                'name' => $class->name,
                'streams' => $class->streams_count,
                'students' => $studentCount
            ];
        });
        
        // Recent Exam Performance (latest 3 exams)
        $recentExams = \App\Models\Exam::with('examType')
            ->latest('start_date')
            ->take(3)
            ->get()
            ->map(function($exam) {
                $results = \App\Models\ExamResult::where('exam_id', $exam->id)->get();
                $totalStudents = $results->pluck('student_id')->unique()->count();
                $avgScore = $results->avg('marks');
                $passRate = $results->where('marks', '>=', 50)->count();
                
                return [
                    'name' => $exam->name,
                    'type' => $exam->examType->name ?? 'N/A',
                    'date' => \Carbon\Carbon::parse($exam->start_date)->format('M d, Y'),
                    'students' => $totalStudents,
                    'avg_score' => round($avgScore, 1),
                    'pass_rate' => $totalStudents > 0 ? round(($passRate / ($totalStudents * $results->pluck('subject_id')->unique()->count())) * 100, 1) : 0
                ];
            });
        
        // Top Performing Students (current year)
        $topStudents = collect();
        if ($activeYear) {
            $studentScores = \App\Models\ExamResult::whereHas('exam', function($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->select('student_id', DB::raw('AVG(marks) as avg_marks'), DB::raw('COUNT(*) as exam_count'))
            ->groupBy('student_id')
            ->having('exam_count', '>=', 3)
            ->orderByDesc('avg_marks')
            ->take(5)
            ->get();
            
            $topStudents = $studentScores->map(function($score) {
                $student = \App\Models\Student::with('user')->find($score->student_id);
                return [
                    'name' => $student->user->name ?? 'N/A',
                    'admission_no' => $student->admission_number,
                    'avg_score' => round($score->avg_marks, 1),
                    'exams' => $score->exam_count
                ];
            });
        }
        
        // Teacher Workload
        $teacherWorkload = \App\Models\Teacher::with('user')
            ->get()
            ->map(function($teacher) use ($activeYear) {
                $subjectCount = 0;
                $streamCount = 0;
                
                if ($activeYear) {
                    $subjectCount = $teacher->subjects()->wherePivot('academic_year_id', $activeYear->id)->count();
                    $streamCount = $teacher->streams()->where('stream_teacher.academic_year_id', $activeYear->id)->count();
                }
                
                return [
                    'name' => $teacher->user->name ?? 'N/A',
                    'subjects' => $subjectCount,
                    'streams' => $streamCount,
                    'load' => $subjectCount * $streamCount
                ];
            })
            ->sortByDesc('load')
            ->take(5);
        
        return view('dashboards.admin', compact(
            'metrics',
            'enrollmentTrends',
            'attendanceStats',
            'classDistribution',
            'recentExams',
            'topStudents',
            'teacherWorkload',
            'activeYear'
        ));
    }

    public function teacher()
    {
        $user = auth()->user();
        $teacher = $user->teacher;
        $year = \App\Models\AcademicYear::active()->first();

        $subjects = collect();
        $streams = collect();
        $allExams = collect();
        $relevantExams = collect();
        $pending = [];
        $selectedExam = null;
        if (request()->query('exam_id')) {
            $selectedExam = \App\Models\Exam::find(request()->query('exam_id'));
        }

        if ($teacher && $year) {
            $subjects = $teacher->subjects()->wherePivot('academic_year_id', $year->id)->get();
            // Use table prefix to avoid ambiguous column name error
            $streams = $teacher->streams()->where('stream_teacher.academic_year_id', $year->id)->get();
            
            // Get exams relevant to this teacher with open results entry period
            $teacherSubjectIds = $subjects->pluck('id')->toArray();
            $teacherClassIds = $streams->pluck('class_id')->unique()->toArray();
            
            if (!empty($teacherSubjectIds) && !empty($teacherClassIds)) {
                $relevantExams = \App\Models\Exam::with(['academicYear', 'term', 'examType', 'subjects', 'classes'])
                    ->where('academic_year_id', $year->id)
                    // Show exams where results entry is currently open
                    ->where(function($q) {
                        $q->where(function($q2) {
                            // Has specific entry period and is within that period
                            $q2->whereNotNull('results_entry_start_date')
                               ->whereNotNull('results_entry_end_date')
                               ->where('results_entry_start_date', '<=', now())
                               ->where('results_entry_end_date', '>=', now()->startOfDay());
                        })->orWhere(function($q2) {
                            // No specific entry period, use exam dates
                            $q2->whereNull('results_entry_start_date')
                               ->whereNull('results_entry_end_date')
                               ->where('start_date', '<=', now())
                               ->where('end_date', '>=', now());
                        });
                    })
                    ->whereHas('subjects', function($q) use ($teacherSubjectIds) {
                        $q->whereIn('subjects.id', $teacherSubjectIds);
                    })
                    ->whereHas('classes', function($q) use ($teacherClassIds) {
                        $q->whereIn('classes.id', $teacherClassIds);
                    })
                    ->latest('start_date')
                    ->get();
            }
            
            // All exams for dropdown (for backward compatibility)
            $allExams = \App\Models\Exam::where('academic_year_id', $year->id)->latest()->get();
        }
        
        $exam = $selectedExam ?? $relevantExams->first() ?? $allExams->first();

        // Compute pending (students without a result) per stream per subject for the selected exam using batched queries
        if ($exam && $streams->count() && $subjects->count()) {
            $streamIds = $streams->pluck('id')->all();

            // total students per stream (active enrollments in this academic year)
            $studentCounts = DB::table('student_stream')
                ->select('stream_id', DB::raw('COUNT(student_id) as total'))
                ->whereIn('stream_id', $streamIds)
                ->where('academic_year_id', $year->id)
                ->where('is_active', true)
                ->groupBy('stream_id')
                ->pluck('total', 'stream_id')
                ->toArray();

            // results per stream per subject for this exam (join via student_stream to associate student -> stream)
            $results = DB::table('exam_results as er')
                ->select('st.stream_id', 'er.subject_id', DB::raw('COUNT(er.id) as results_count'))
                ->join('student_stream as st', function ($join) use ($year) {
                    $join->on('er.student_id', '=', 'st.student_id')
                         ->where('st.academic_year_id', $year->id)
                         ->where('st.is_active', true);
                })
                ->where('er.exam_id', $exam->id)
                ->whereIn('st.stream_id', $streamIds)
                ->groupBy('st.stream_id', 'er.subject_id')
                ->get();

            // Build pending map quickly in memory
            foreach ($results as $r) {
                $total = $studentCounts[$r->stream_id] ?? 0;
                $pendingCount = max(0, $total - (int) $r->results_count);
                if ($pendingCount > 0) {
                    $pending[$r->stream_id][ $r->subject_id ] = $pendingCount;
                }
            }
        }

        return view('dashboards.teacher', compact('subjects', 'streams', 'allExams', 'relevantExams', 'exam', 'pending', 'selectedExam'));
    }

    public function student()
    {
        return view('dashboards.student');
    }

    public function guardian()
    {
        $user = auth()->user();
        $guardian = $user->guardian;
        
        if (!$guardian) {
            return view('dashboards.guardian', [
                'students' => collect(),
                'statistics' => [],
                'announcements' => collect(),
                'attendanceAlerts' => [],
                'upcomingEvents' => collect(),
                'recentResults' => collect(),
                'academicOverview' => []
            ]);
        }
        
        // Get guardian's children
        $students = $guardian->students()->with(['user', 'streams' => function($q) {
            $activeYear = \App\Models\AcademicYear::active()->first();
            if ($activeYear) {
                $q->wherePivot('academic_year_id', $activeYear->id)
                  ->wherePivot('is_active', true)
                  ->with('schoolClass');
            }
        }])->get();
        
        $activeYear = \App\Models\AcademicYear::active()->first();
        
        // Get announcements relevant to guardians (recent 5)
        $guardianRole = \App\Models\Role::where('slug', 'guardian')->first();
        $announcements = \App\Models\Announcement::published()
            ->whereHas('roles', function($q) use ($guardianRole) {
                $q->where('role_id', $guardianRole->id);
            })
            ->latest('created_at')
            ->take(5)
            ->get();
        
        // Get upcoming exams (next 30 days)
        $upcomingExams = \App\Models\Exam::where('start_date', '>=', now())
            ->where('start_date', '<=', now()->addDays(30))
            ->where('academic_year_id', $activeYear->id ?? null)
            ->with(['term', 'examType'])
            ->orderBy('start_date')
            ->take(5)
            ->get();
        
        // Calculate comprehensive statistics for each child
        $statistics = [];
        $attendanceAlerts = [];
        $recentResults = collect();
        $academicOverview = [
            'total_children' => $students->count(),
            'excellent_performers' => 0,
            'needs_attention' => 0,
            'attendance_concerns' => 0
        ];
        
        foreach ($students as $student) {
            $stats = [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'admission_number' => $student->admission_number,
                'current_class' => $student->streams->first()->schoolClass->name ?? 'N/A',
                'attendance_rate' => 0,
                'recent_exams' => 0,
                'average_grade' => 'N/A',
                'grade_numeric' => 0,
                'trend' => 'stable', // up, down, stable
                'absent_days' => 0,
                'late_days' => 0,
                'position' => null,
                'total_students' => 0
            ];
            
            if ($activeYear) {
                // Calculate attendance rate (last 30 days)
                $startDate = now()->subDays(30);
                $attendanceRecords = \App\Models\AttendanceRecord::where('student_id', $student->id)
                    ->whereDate('date', '>=', $startDate)
                    ->get();
                
                if ($attendanceRecords->count() > 0) {
                    $presentCount = $attendanceRecords->where('status', 'present')->count();
                    $absentCount = $attendanceRecords->where('status', 'absent')->count();
                    $lateCount = $attendanceRecords->where('status', 'late')->count();
                    
                    $stats['attendance_rate'] = round(($presentCount / $attendanceRecords->count()) * 100, 1);
                    $stats['absent_days'] = $absentCount;
                    $stats['late_days'] = $lateCount;
                    
                    // Check for attendance concerns
                    if ($stats['attendance_rate'] < 80) {
                        $attendanceAlerts[] = [
                            'student' => $student,
                            'rate' => $stats['attendance_rate'],
                            'absent_days' => $absentCount,
                            'severity' => $stats['attendance_rate'] < 70 ? 'critical' : 'warning'
                        ];
                        $academicOverview['attendance_concerns']++;
                    }
                }
                
                // Get recent exam results (last 3 exams)
                $studentResults = \App\Models\ExamResult::where('student_id', $student->id)
                    ->whereHas('exam', function($q) use ($activeYear) {
                        $q->where('academic_year_id', $activeYear->id)
                          ->where('results_released', true);
                    })
                    ->with(['exam.term', 'exam.examType', 'subject'])
                    ->latest('created_at')
                    ->take(20)
                    ->get();
                
                $stats['recent_exams'] = $studentResults->count();
                
                if ($studentResults->count() > 0) {
                    $avgMarks = $studentResults->avg('marks');
                    $stats['average_grade'] = round($avgMarks, 1);
                    $stats['grade_numeric'] = round($avgMarks, 1);
                    
                    // Add to recent results collection
                    $latestExamResults = $studentResults->groupBy('exam_id')->take(1);
                    foreach ($latestExamResults as $examId => $results) {
                        $exam = $results->first()->exam;
                        $recentResults->push([
                            'student' => $student,
                            'exam' => $exam,
                            'average' => round($results->avg('marks'), 1),
                            'subjects_count' => $results->count(),
                            'date' => $exam->start_date
                        ]);
                    }
                    
                    // Determine performance trend (compare recent vs older results)
                    if ($studentResults->count() >= 4) {
                        $recent = $studentResults->take(ceil($studentResults->count() / 2))->avg('marks');
                        $older = $studentResults->skip(ceil($studentResults->count() / 2))->avg('marks');
                        
                        if ($recent > $older + 5) {
                            $stats['trend'] = 'up';
                        } elseif ($recent < $older - 5) {
                            $stats['trend'] = 'down';
                        }
                    }
                    
                    // Categorize performance
                    if ($avgMarks >= 75) {
                        $academicOverview['excellent_performers']++;
                    } elseif ($avgMarks < 50) {
                        $academicOverview['needs_attention']++;
                    }
                    
                    // Get class ranking for latest exam
                    $latestExam = \App\Models\Exam::where('academic_year_id', $activeYear->id)
                        ->where('results_released', true)
                        ->latest('start_date')
                        ->first();
                    
                    if ($latestExam) {
                        // Get student's average for this exam
                        $studentAvg = \App\Models\ExamResult::where('student_id', $student->id)
                            ->where('exam_id', $latestExam->id)
                            ->avg('marks');
                        
                        // Get all students' averages
                        $allAverages = \App\Models\ExamResult::where('exam_id', $latestExam->id)
                            ->select('student_id', DB::raw('AVG(marks) as avg_marks'))
                            ->groupBy('student_id')
                            ->orderByDesc('avg_marks')
                            ->get();
                        
                        $position = $allAverages->search(function($item) use ($student) {
                            return $item->student_id == $student->id;
                        });
                        
                        if ($position !== false) {
                            $stats['position'] = $position + 1;
                            $stats['total_students'] = $allAverages->count();
                        }
                    }
                }
            }
            
            $statistics[] = $stats;
        }
        
        // Sort recent results by date
        $recentResults = $recentResults->sortByDesc('date')->take(5);
        
        return view('dashboards.guardian', compact(
            'students', 
            'statistics', 
            'announcements', 
            'attendanceAlerts', 
            'upcomingExams', 
            'recentResults',
            'academicOverview'
        ));
    }
}
