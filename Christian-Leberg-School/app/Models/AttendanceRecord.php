<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'student_id',
        'stream_id',
        'subject_id',
        'date',
        'status',
        'remarks',
    ];

    /**
     * The attributes that are not mass assignable.
     * Prevents attribution manipulation.
     *
     * @var array<string>
     */
    protected $guarded = [
        'id',
        'teacher_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
