<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\AcademicYear;
use App\Http\Requests\StoreStreamRequest;
use App\Http\Requests\UpdateStreamRequest;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStreamRequest $request)
    {
        $academicYear = AcademicYear::active()->first();
        if (!$academicYear) {
            return back()->with('error', 'No active academic year found. Please create one first.');
        }

        Stream::create(array_merge($request->validated(), ['academic_year_id' => $academicYear->id]));

        return back()->with('success', 'Stream created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStreamRequest $request, Stream $stream)
    {
        $data = $request->validated();
        $stream->update(\Illuminate\Support\Arr::except($data, ['class_teacher_id']));

        // Handle Class Teacher assignment
        if ($request->has('class_teacher_id')) {
            // Remove existing class teacher
            // We use standard DB query or newPivotStatement to ensure we target pivot table
             \Illuminate\Support\Facades\DB::table('stream_teacher')
                ->where('stream_id', $stream->id)
                ->where('is_class_teacher', true)
                ->update(['is_class_teacher' => false]);
            
            if ($request->class_teacher_id) {
                // Check if teacher is already attached
                $existing = $stream->teachers()->where('teacher_id', $request->class_teacher_id)->first();
                
                if ($existing) {
                    // Update pivot
                    $stream->teachers()->updateExistingPivot($request->class_teacher_id, ['is_class_teacher' => true]);
                } else {
                    // Attach
                    // Make sure to populate subject_id if needed, but it is nullable now.
                    $stream->teachers()->attach($request->class_teacher_id, ['is_class_teacher' => true]);
                }
            }
        }

        return back()->with('success', 'Stream updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stream $stream)
    {
        if ($stream->students()->wherePivot('is_active', true)->exists()) {
            return back()->with('error', 'Cannot delete stream with active students.');
        }

        $stream->delete();

        return back()->with('success', 'Stream deleted successfully.');
    }
}
