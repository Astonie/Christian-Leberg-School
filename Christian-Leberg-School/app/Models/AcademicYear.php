<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Term;
use App\Models\Stream;

class AcademicYear extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    
    
        /**
         * Inherit streams and teacher/subject assignments from the most recent previous academic year.
         *
         * If a previous AcademicYear is not provided, the most recent one (by id) is used.
         */
        public function inheritFromPrevious(?AcademicYear $previous = null): void
        {
            $previous = $previous ?? self::where('id', '<', $this->id)->orderBy('id', 'desc')->first();
            if (! $previous) return;

            // Clone streams
            $streamMap = [];
            foreach ($previous->streams as $s) {
                $new = $s->replicate(['id', 'created_at', 'updated_at']);
                $new->academic_year_id = $this->id;
                $new->push();
                $streamMap[$s->id] = $new->id;
            }

            // Clone teacher_subject assignments for the new academic year
            $subjectAssignments = \DB::table('teacher_subject')->where('academic_year_id', $previous->id)->get();
            foreach ($subjectAssignments as $sa) {
                if (! \DB::table('teacher_subject')->where('teacher_id', $sa->teacher_id)->where('subject_id', $sa->subject_id)->where('academic_year_id', $this->id)->exists()) {
                    \DB::table('teacher_subject')->insert([
                        'teacher_id' => $sa->teacher_id,
                        'subject_id' => $sa->subject_id,
                        'academic_year_id' => $this->id,
                        'is_primary' => $sa->is_primary,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Clone stream_teacher entries only for streams that were cloned
            if (! empty($streamMap)) {
                $oldStreamIds = array_keys($streamMap);
                $streamTeachers = \DB::table('stream_teacher')->whereIn('stream_id', $oldStreamIds)->get();
                foreach ($streamTeachers as $st) {
                    $newStreamId = $streamMap[$st->stream_id] ?? null;
                    if (! $newStreamId) continue;
                    if (! \DB::table('stream_teacher')->where('stream_id', $newStreamId)->where('teacher_id', $st->teacher_id)->where('subject_id', $st->subject_id)->exists()) {
                        \DB::table('stream_teacher')->insert([
                            'stream_id' => $newStreamId,
                            'teacher_id' => $st->teacher_id,
                            'subject_id' => $st->subject_id,
                            'is_class_teacher' => $st->is_class_teacher,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
}
