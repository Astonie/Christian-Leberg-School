<?php

namespace App\Http\Controllers;

use App\Models\Term;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class TermController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // If this term is set to active, deactivate all other terms in the same academic year
        if ($request->is_active) {
            Term::where('academic_year_id', $data['academic_year_id'])
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $term = Term::create($data);

        return back()->with('success', 'Term created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Term $term)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // If this term is set to active, deactivate all other terms in the same academic year
        if ($request->is_active) {
            Term::where('academic_year_id', $term->academic_year_id)
                ->where('id', '!=', $term->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $term->update($data);

        return back()->with('success', 'Term updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
        // Check if term has exams
        if ($term->exams()->count() > 0) {
            return back()->with('error', 'Cannot delete term with existing exams. Please delete or reassign the exams first.');
        }

        $term->delete();

        return back()->with('success', 'Term deleted successfully.');
    }
}
