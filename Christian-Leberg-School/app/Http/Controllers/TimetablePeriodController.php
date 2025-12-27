<?php

namespace App\Http\Controllers;

use App\Models\TimetablePeriod;
use Illuminate\Http\Request;

class TimetablePeriodController extends Controller
{
    /**
     * Display periods list
     */
    public function index()
    {
        $periods = TimetablePeriod::orderBy('order')->get();
        return view('timetable-periods.index', compact('periods'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('timetable-periods.create');
    }

    /**
     * Store new period
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'order' => 'required|integer|min:0',
            'is_break' => 'boolean',
            'is_active' => 'boolean',
        ]);

        TimetablePeriod::create($validated);

        return redirect()->route('timetable-periods.index')->with('success', 'Period created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(TimetablePeriod $timetablePeriod)
    {
        return view('timetable-periods.edit', compact('timetablePeriod'));
    }

    /**
     * Update period
     */
    public function update(Request $request, TimetablePeriod $timetablePeriod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'order' => 'required|integer|min:0',
            'is_break' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $timetablePeriod->update($validated);

        return redirect()->route('timetable-periods.index')->with('success', 'Period updated successfully.');
    }

    /**
     * Delete period
     */
    public function destroy(TimetablePeriod $timetablePeriod)
    {
        if ($timetablePeriod->timetableEntries()->count() > 0) {
            return back()->with('error', 'Cannot delete period with existing timetable entries.');
        }

        $timetablePeriod->delete();
        return back()->with('success', 'Period deleted successfully.');
    }
}
