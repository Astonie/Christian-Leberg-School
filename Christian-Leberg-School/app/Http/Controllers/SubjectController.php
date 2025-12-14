<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::latest()->get();
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        Subject::create($request->validated());

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        // Could show stats, or which classes take this subject
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        // Check dependencies (teachers, exams)
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
