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
        return view('dashboards.admin');
    }

    public function teacher()
    {
        $user = auth()->user();
        $teacher = $user->teacher;
        $year = \App\Models\AcademicYear::active()->first();

        $subjects = collect();
        $streams = collect();
        $exams = \App\Models\Exam::latest()->get();
        $pending = [];
        $selectedExam = null;
        if (request()->query('exam_id')) {
            $selectedExam = \App\Models\Exam::find(request()->query('exam_id'));
        }
        $exam = $selectedExam ?? (\App\Models\Exam::latest()->first());

        if ($teacher && $year) {
            $subjects = $teacher->subjects()->wherePivot('academic_year_id', $year->id)->get();
            // stream_teacher pivot does not include academic_year_id; filter on streams table instead
            $streams = $teacher->streams()->where('academic_year_id', $year->id)->get();

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
        }

        return view('dashboards.teacher', compact('subjects', 'streams', 'exams', 'exam', 'pending', 'selectedExam'));
    }

    public function student()
    {
        return view('dashboards.student');
    }

    public function guardian()
    {
        return view('dashboards.guardian');
    }
}
