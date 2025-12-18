<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    public function assessmentStructure()
    {
        return $this->belongsTo(AssessmentStructure::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'exam_subject');
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'exam_class', 'exam_id', 'class_id');
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function gradingScale()
    {
        return $this->belongsTo(GradingScale::class);
    }

    /**
     * Calculate and update class positions for all students in this exam
     */
    public function calculatePositions()
    {
        // Get all students with their results for this exam
        $studentAverages = \DB::table('exam_results')
            ->select('student_id', \DB::raw('AVG(marks) as average'))
            ->where('exam_id', $this->id)
            ->groupBy('student_id')
            ->orderBy('average', 'desc')
            ->get();

        $position = 1;
        foreach ($studentAverages as $studentAvg) {
            \DB::table('exam_results')
                ->where('exam_id', $this->id)
                ->where('student_id', $studentAvg->student_id)
                ->update(['position' => $position]);
            $position++;
        }
    }

    /**
     * Calculate and update stream positions for students in this exam
     */
    public function calculateStreamPositions()
    {
        // Get all streams involved in this exam
        $streams = \DB::table('exam_results')
            ->join('students', 'exam_results.student_id', '=', 'students.id')
            ->join('student_stream', 'students.id', '=', 'student_stream.student_id')
            ->where('exam_results.exam_id', $this->id)
            ->where('student_stream.academic_year_id', $this->academic_year_id)
            ->select('student_stream.stream_id')
            ->distinct()
            ->pluck('stream_id');

        foreach ($streams as $streamId) {
            // Get students in this stream with their averages
            $studentAverages = \DB::table('exam_results')
                ->join('students', 'exam_results.student_id', '=', 'students.id')
                ->join('student_stream', 'students.id', '=', 'student_stream.student_id')
                ->select('exam_results.student_id', \DB::raw('AVG(exam_results.marks) as average'))
                ->where('exam_results.exam_id', $this->id)
                ->where('student_stream.stream_id', $streamId)
                ->where('student_stream.academic_year_id', $this->academic_year_id)
                ->groupBy('exam_results.student_id')
                ->orderBy('average', 'desc')
                ->get();

            $position = 1;
            foreach ($studentAverages as $studentAvg) {
                \DB::table('exam_results')
                    ->where('exam_id', $this->id)
                    ->where('student_id', $studentAvg->student_id)
                    ->update(['stream_position' => $position]);
                $position++;
            }
        }
    }

    /**
     * Students who have fewer than $min subjects recorded for this exam.
     *
     * @param int $min
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function studentsWithInsufficientSubjects(int $min = 4)
    {
        return \App\Models\Student::whereHas('examResults', function ($q) {
            $q->where('exam_id', $this->id);
        }, '<', $min)->get();
    }

    /**
     * Students who have at least $min subjects recorded for this exam.
     */
    public function studentsWithSufficientSubjects(int $min = 4)
    {
        return \App\Models\Student::whereHas('examResults', function ($q) {
            $q->where('exam_id', $this->id);
        }, '>=', $min)->get();
    }
}
