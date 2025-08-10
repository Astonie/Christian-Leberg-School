<?php

namespace App\Http\Controllers;

use App\Models\Involvement;
use Illuminate\Http\Request;

class InvolvementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $involvements = Involvement::latest()->paginate(10);
        return view('involvements', compact('involvements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('involvements_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:donate,volunteer,partner',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'cta_label' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
        ]);
        Involvement::create($validated);
        return redirect()->route('involvements.index')->with('success', 'Involvement created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Involvement $involvement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Involvement $involvement)
    {
        return view('involvements_edit', compact('involvement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Involvement $involvement)
    {
        $validated = $request->validate([
            'type' => 'required|in:donate,volunteer,partner',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
            'cta_label' => 'nullable|string|max:255',
            'cta_link' => 'nullable|string|max:255',
        ]);
        $involvement->update($validated);
        return redirect()->route('involvements.index')->with('success', 'Involvement updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Involvement $involvement)
    {
        $involvement->delete();
        return redirect()->route('involvements.index')->with('success', 'Involvement deleted successfully.');
    }
}
