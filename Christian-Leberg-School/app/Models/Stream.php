<?php

namespace App\Models;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'class_id',
        'capacity',
        'description',
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_stream')
                    ->withPivot('academic_year_id', 'term_id', 'enrollment_date', 'is_active')
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'stream_teacher')
                    ->withPivot('subject_id', 'is_class_teacher')
                    ->withTimestamps();
    }

    public function getClassTeacherAttribute()
    {
        return $this->teachers()->wherePivot('is_class_teacher', true)->first();
    }

    public function getFullNameAttribute()
    {
        $class = $this->schoolClass?->name;
        $year = $this->academicYear?->name;
        $stream = $this->name;

        // Example: "Grade 1 A (2025)"
        return trim(sprintf('%s %s%s', $class, $stream, $year ? " ({$year})" : ''));
    }
}
