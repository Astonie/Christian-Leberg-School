<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stories = Story::latest()->paginate(10);
        return view('stories', compact('stories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stories_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        Story::create($validated);

        return redirect()->route('stories.index')->with('success', 'Story created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Story $story)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Story $story)
    {
        return view('stories_edit', compact('story'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Story $story)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'quote' => 'required|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $story->update($validated);

        return redirect()->route('stories.index')->with('success', 'Story updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Story $story)
    {
        $story->delete();

        return redirect()->route('stories.index')->with('success', 'Story deleted successfully.');
    }
}
