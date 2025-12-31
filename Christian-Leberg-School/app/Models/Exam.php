<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'academic_year_id',
        'term_id',
        'start_date',
        'end_date',
        'results_entry_start_date',
        'results_entry_end_date',
        'results_entry_locked',
        'is_major_exam',
        'weight_percentage',
        'assessment_structure_id',
        'description',
        'created_by',
        'assessment_type',
        'total_marks',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'results_released',        // Prevent unauthorized result release
        'results_released_at',     // Prevent timestamp manipulation
        'results_released_by',     // Prevent attribution manipulation
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'results_entry_start_date' => 'date',
        'results_entry_end_date' => 'date',
        'results_entry_locked' => 'boolean',
        'is_major_exam' => 'boolean',
        'weight_percentage' => 'decimal:2',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
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

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if exam is currently in progress (between start and end dates)
     */
    public function isInProgress()
    {
        $now = now()->startOfDay();
        return $now->greaterThanOrEqualTo($this->start_date) && 
               $now->lessThanOrEqualTo($this->end_date);
    }

    /**
     * Check if results entry is currently open
     */
    public function isResultsEntryOpen()
    {
        // If no specific entry period set, check if exam is in progress
        if (!$this->results_entry_start_date || !$this->results_entry_end_date) {
            return $this->isInProgress();
        }

        $now = now()->startOfDay();
        return $now->greaterThanOrEqualTo($this->results_entry_start_date) && 
               $now->lessThanOrEqualTo($this->results_entry_end_date);
    }

    /**
     * Check if results entry is locked (past the deadline OR manually locked).
     */
    public function isResultsEntryLocked()
    {
        // Check if manually locked
        if ($this->results_entry_locked) {
            return true;
        }

        // Check if automatically locked (past deadline)
        if (!$this->results_entry_end_date) {
            return false; // No lock if no end date set
        }

        return now()->startOfDay()->greaterThan($this->results_entry_end_date);
    }

    /**
     * Get status message for results entry
     */
    public function getResultsEntryStatus()
    {
        if ($this->isResultsEntryLocked()) {
            return [
                'status' => 'locked',
                'message' => 'Results entry period has ended',
                'color' => 'red'
            ];
        }

        if ($this->isResultsEntryOpen()) {
            return [
                'status' => 'open',
                'message' => 'Results entry is open',
                'color' => 'green'
            ];
        }

        if ($this->results_entry_start_date && now()->startOfDay()->lessThan($this->results_entry_start_date)) {
            return [
                'status' => 'pending',
                'message' => 'Results entry opens on ' . $this->results_entry_start_date->format('M d, Y'),
                'color' => 'yellow'
            ];
        }

        return [
            'status' => 'closed',
            'message' => 'Results entry not available',
            'color' => 'gray'
        ];
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
