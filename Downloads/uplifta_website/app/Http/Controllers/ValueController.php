<?php

namespace App\Http\Controllers;

use App\Models\Value;
use Illuminate\Http\Request;

class ValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $values = Value::latest()->paginate(10);
        return view('values', compact('values'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('values_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:mission,vision,value,impact',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);
        Value::create($validated);
        return redirect()->route('values.index')->with('success', 'Value created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Value $value)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Value $value)
    {
        return view('values_edit', compact('value'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Value $value)
    {
        $validated = $request->validate([
            'type' => 'required|in:mission,vision,value,impact',
            'title' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'required|string',
        ]);
        $value->update($validated);
        return redirect()->route('values.index')->with('success', 'Value updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Value $value)
    {
        $value->delete();
        return redirect()->route('values.index')->with('success', 'Value deleted successfully.');
    }
}
