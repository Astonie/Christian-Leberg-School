<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Teacher;
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
    public function show(SchoolClass $schoolClass) // Route model binding will look for 'school_class' but parameter is 'schoolClass' if we name route param 'class' it conflicts. Standard resource route uses 'school_class'
    {
        $schoolClass->load(['streams.academicYear', 'streams.classTeacher']); 
        $teachers = Teacher::with('user')->get();
        return view('classes.show', compact('schoolClass', 'teachers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SchoolClass $schoolClass)
    {
        return view('classes.edit', compact('schoolClass'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSchoolClassRequest $request, SchoolClass $schoolClass)
    {
        $schoolClass->fill($request->validated());
        $schoolClass->save();

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolClass $schoolClass)
    {
        if ($schoolClass->streams()->count() > 0) {
            return back()->with('error', 'Cannot delete class with existing streams.');
        }
        
        $schoolClass->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
