<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use App\Models\AcademicYear;
use App\Http\Requests\StoreStreamRequest;
use App\Http\Requests\UpdateStreamRequest;
use Illuminate\Http\Request;
use App\Services\Metrics;

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

        try {
            // Check for bulk creation
            if ($request->has('bulk_names') && !empty($request->input('bulk_names'))) {
                $names = array_map('trim', explode(',', $request->input('bulk_names')));
                $capacity = $request->input('bulk_capacity');
                $classId = $request->input('class_id');
                $createdCount = 0;

                foreach ($names as $name) {
                    if (empty($name)) {
                        continue;
                    }

                    // Check if stream already exists
                    $exists = Stream::where('class_id', $classId)
                        ->where('academic_year_id', $academicYear->id)
                        ->where('name', $name)
                        ->exists();

                    if (!$exists) {
                        Stream::create([
                            'name' => $name,
                            'class_id' => $classId,
                            'academic_year_id' => $academicYear->id,
                            'capacity' => $capacity ?: null,
                        ]);
                        $createdCount++;
                    }
                }

                if ($createdCount > 0) {
                    return redirect()->route('classes.show', ['class' => $classId])
                        ->with('success', "{$createdCount} stream(s) created successfully.");
                } else {
                    return back()->with('warning', 'All streams already exist.');
                }
            }

            // Single stream creation
            $stream = Stream::create([
                'name' => $request->name,
                'class_id' => $request->class_id,
                'academic_year_id' => $academicYear->id,
                'capacity' => $request->capacity,
            ]);
        } catch (\Exception $e) {
                $context = ['error' => $e->getMessage(), 'payload' => $request->validated(), 'user_id' => $request->user()?->id, 'route' => request()->route()?->getName(), 'ip' => request()->ip()];
                \Log::error('Failed to create stream', $context);
                // increment metric for stream create failures (guard fallback)
                try {
                    app(Metrics::class)->increment('stream.create.failures');
                } catch (\Throwable $me) {
                    \Log::warning('Metrics increment failed', ['error' => $me->getMessage()]);
                }

                return back()->withInput()->with('error', 'Unable to create stream. Please check the inputs and try again.');
            }

        // Redirect to the class page so the newly created stream is visible
        $classId = $request->input('class_id');
        if ($classId) {
            return redirect()->route('classes.show', ['class' => $classId])->with('success', 'Stream created successfully.');
        }

        return back()->with('success', 'Stream created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStreamRequest $request, Stream $stream)
    {
        $data = $request->validated();
        
        // Update stream name and capacity if provided
        if ($request->has('name') || $request->has('capacity')) {
            $updateData = [];
            if ($request->has('name')) {
                $updateData['name'] = $request->input('name');
            }
            if ($request->filled('capacity')) {
                $updateData['capacity'] = $request->input('capacity');
            } elseif ($request->has('capacity') && $request->input('capacity') === null) {
                $updateData['capacity'] = null;
            }
            
            $stream->update($updateData);
        }

        // Handle Class Teacher assignment
        if ($request->has('class_teacher_id')) {
            // Remove existing class teacher
            \Illuminate\Support\Facades\DB::table('stream_teacher')
                ->where('stream_id', $stream->id)
                ->where('is_class_teacher', true)
                ->update(['is_class_teacher' => false]);
            
            try {
                if ($request->class_teacher_id) {
                    // Check if teacher is already attached
                    $existing = $stream->teachers()->where('teachers.id', $request->class_teacher_id)->first();

                    if ($existing) {
                        // Update pivot
                        $stream->teachers()->updateExistingPivot($request->class_teacher_id, [
                            'is_class_teacher' => true,
                            'academic_year_id' => $stream->academic_year_id
                        ]);
                    } else {
                        // Attach; subject_id is nullable so we can attach without it
                        $stream->teachers()->attach($request->class_teacher_id, [
                            'is_class_teacher' => true,
                            'academic_year_id' => $stream->academic_year_id
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $context = ['stream_id' => $stream->id, 'teacher_id' => $request->class_teacher_id, 'error' => $e->getMessage(), 'user_id' => $request->user()?->id, 'route' => request()->route()?->getName(), 'ip' => request()->ip()];
                \Log::error('Failed to assign class teacher to stream', $context);
                try {
                    app(Metrics::class)->increment('stream.assign_teacher.failures');
                } catch (\Throwable $me) {
                    \Log::warning('Metrics increment failed', ['error' => $me->getMessage()]);
                }
                return back()->withInput()->with('error', 'Unable to assign class teacher. Please try again.');
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

    /**
     * Create the stream record. Extracted for testability.
     */
    protected function performCreate(array $data)
    {
        return Stream::create($data);
    }
}
