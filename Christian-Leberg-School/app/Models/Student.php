<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'admission_number',
        'admission_date',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
    ];

    /**
     * The attributes that are not mass assignable.
     * Protects sensitive fields from mass assignment attacks.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'results_access_blocked',   // Prevent unauthorized results blocking
        'blocked_at',                // Prevent timestamp manipulation
        'blocked_by',                // Prevent blocking attribution manipulation
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth' => 'date',
        'results_access_blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    /**
     * Generate a unique admission number
     * Format: YEAR-XXXX (e.g., 2025-0001)
     */
    public static function generateAdmissionNumber(): string
    {
        $year = now()->year;
        $prefix = $year . '-';
        
        // Get the last admission number for this year
        $lastStudent = self::where('admission_number', 'like', $prefix . '%')
            ->orderBy('admission_number', 'desc')
            ->first();
        
        if ($lastStudent) {
            // Extract the numeric part and increment
            $lastNumber = (int) substr($lastStudent->admission_number, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            // First student of this year
            $newNumber = 1;
        }
        
        // Format with leading zeros (4 digits)
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guardians()
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
                    ->withPivot('is_primary_contact', 'can_pickup')
                    ->withTimestamps();
    }

    public function streams()
    {
        return $this->belongsToMany(Stream::class, 'student_stream')
                    ->withPivot('academic_year_id', 'term_id', 'enrollment_date', 'is_active')
                    ->withTimestamps();
    }

    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }
    
    public function studentScores()
    {
        return $this->hasMany(StudentScore::class);
    }

    public function activeStreams()
    {
        // Order by the pivot enrollment_date descending so the most recent active stream is first
        return $this->streams()->wherePivot('is_active', true)->orderBy('student_stream.enrollment_date', 'desc');
    }

    public function getCurrentStreamAttribute()
    {
        // When accessed as property, activeStreams returns a Collection; ensure we get the first model
        $streams = $this->activeStreams()->get();
        return $streams->first();
    }

    public function getCurrentClassAttribute()
    {
        return $this->currentStream?->schoolClass;
    }

    public function attendanceRecords()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function blockedBy()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    /**
     * Check if student can access exam results
     */
    public function canAccessResults(Exam $exam = null): bool
    {
        // Check if student is blocked from all results
        if ($this->results_access_blocked) {
            return false;
        }

        // If specific exam provided, check if that exam's results are released
        if ($exam && !$exam->results_released) {
            return false;
        }

        return true;
    }
}
