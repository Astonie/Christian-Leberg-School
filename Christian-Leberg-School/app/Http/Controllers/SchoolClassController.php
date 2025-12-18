<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = SchoolClass::withCount('streams')->orderBy('level')->get();
        return view('classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSchoolClassRequest $request)
    {
        SchoolClass::create($request->validated());

        return redirect()->route('classes.index')->with('success', 'Class created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) // Changed from SchoolClass $schoolClass to match route binding
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $user = request()->user();
        $activeYear = AcademicYear::active()->first();

        // If a teacher is viewing, ensure they are assigned to at least one stream in this class for the active academic year
        if ($user->hasRole('teacher')) {
            $teacher = $user->teacher;
            Log::info('SchoolClassController@show accessed by teacher', ['teacher_id' => $teacher?->id, 'active_year' => $activeYear?->id, 'class_id' => $schoolClass->id]);
            if (! $teacher || ! $activeYear) {
                Log::warning('Teacher or active year missing in SchoolClassController@show', ['teacher' => $teacher?->id ?? null, 'year' => $activeYear?->id ?? null]);
                abort(403);
            }

            $teachesInClass = $teacher->streams()->where('academic_year_id', $activeYear->id)->where('class_id', $schoolClass->id)->exists();
            Log::info('SchoolClassController@show teacher->streams check', ['teachesInClass' => $teachesInClass]);
            if (! $teachesInClass) {
                Log::warning('Teacher not assigned to any stream in this class', ['teacher_id' => $teacher->id, 'class_id' => $schoolClass->id, 'year' => $activeYear->id]);
                abort(403);
            }
        }

        // Only display streams for the active academic year on the class management page
        if ($activeYear) {
            $schoolClass->load(['streams' => function ($query) use ($activeYear) {
                $query->where('academic_year_id', $activeYear->id)->with('academicYear');
            }]);
        } else {
            $schoolClass->setRelation('streams', collect());
        }
        
        $teachers = Teacher::with('user')->get();

        $hasActiveYear = (bool) $activeYear;

        return view('classes.show', compact('schoolClass', 'teachers', 'hasActiveYear', 'activeYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schoolClass = SchoolClass::findOrFail($id);
        return view('classes.edit', compact('schoolClass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolClassRequest $request, $id)
    {
        $schoolClass = SchoolClass::findOrFail($id);
        $schoolClass->fill($request->validated());
        $schoolClass->save();

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schoolClass = SchoolClass::findOrFail($id);
        
        if ($schoolClass->streams()->count() > 0) {
            return back()->with('error', 'Cannot delete class with existing streams.');
        }
        
        $schoolClass->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }

    /**
     * Teacher-scoped view for a class (only accessible to teachers assigned to one of its streams)
     */
    public function teacherShow(SchoolClass $schoolClass)
    {
        $user = request()->user();
        if (! $user || ! $user->hasRole('teacher')) {
            abort(403);
        }

        $teacher = $user->teacher;
        $activeYear = AcademicYear::active()->first();
        if (! $teacher || ! $activeYear) {
            abort(403);
        }

        $teachesInClass = $teacher->streams()->where('academic_year_id', $activeYear->id)->where('class_id', $schoolClass->id)->exists();
        if (! $teachesInClass) {
            abort(403);
        }

        $schoolClass->load(['streams.academicYear']); 
        if ($activeYear) {
            $schoolClass->setRelation('streams', $schoolClass->streams()->where('academic_year_id', $activeYear->id)->get());
        } else {
            $schoolClass->setRelation('streams', collect());
        }
        $teachers = Teacher::with('user')->get();

        $hasActiveYear = (bool) $activeYear;

        return view('classes.show', compact('schoolClass', 'teachers', 'hasActiveYear', 'activeYear'));
    }
}
