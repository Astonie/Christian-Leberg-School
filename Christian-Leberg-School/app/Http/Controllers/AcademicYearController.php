<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showArchived = $request->get('archived', false);
        
        if ($showArchived) {
            $years = AcademicYear::onlyTrashed()->latest('start_date')->get();
        } else {
            $years = AcademicYear::latest('start_date')->get();
        }
        
        return view('academic-years.index', compact('years', 'showArchived'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcademicYearRequest $request)
    {
        // If this year is set to active, deactivate all others
        if ($request->is_active) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        $data = $request->validated();
        $inherit = isset($data['inherit_previous']) ? (bool) $data['inherit_previous'] : false;
        unset($data['inherit_previous']);

        $year = AcademicYear::create($data);

        // Optionally inherit streams and teacher assignments from previous year
        if ($inherit) {
            $year->inheritFromPrevious();
        }

        return redirect()->route('academic-years.index')->with('success', 'Academic Year created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear)
    {
        // Maybe show terms or stats here
        return redirect()->route('academic-years.edit', $academicYear);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear)
    {
        $academicYear->load('terms');
        return view('academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear)
    {
        // If this year is set to active, deactivate all others
        if ($request->is_active) {
            AcademicYear::where('id', '!=', $academicYear->id)->where('is_active', true)->update(['is_active' => false]);
        }

        $academicYear->update($request->validated());

        return redirect()->route('academic-years.index')->with('success', 'Academic Year updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear)
    {
        // Prevent deletion of active academic year
        if ($academicYear->is_active) {
            return redirect()->route('academic-years.index')
                ->with('error', 'Cannot delete the active academic year. Please deactivate it first.');
        }

        // Check for critical dependencies
        $streamCount = $academicYear->streams()->count();
        $examCount = $academicYear->exams()->count();
        $termCount = $academicYear->terms()->count();
        
        // Count student enrollments for this year
        $studentEnrollments = \DB::table('student_stream')
            ->where('academic_year_id', $academicYear->id)
            ->count();

        if ($streamCount > 0 || $examCount > 0 || $studentEnrollments > 0) {
            return redirect()->route('academic-years.index')
                ->with('warning', "Cannot delete academic year '{$academicYear->name}'. It has {$streamCount} streams, {$examCount} exams, and {$studentEnrollments} student enrollments. This data will be permanently lost. Use soft delete (archive) instead.");
        }

        // Soft delete (archive) the academic year
        $academicYear->delete();

        return redirect()->route('academic-years.index')
            ->with('success', "Academic year '{$academicYear->name}' has been archived.");
    }

    /**
     * Restore a soft-deleted academic year.
     */
    public function restore($id)
    {
        $academicYear = AcademicYear::withTrashed()->findOrFail($id);
        
        if (!$academicYear->trashed()) {
            return redirect()->route('academic-years.index')
                ->with('info', 'Academic year is not archived.');
        }

        $academicYear->restore();

        return redirect()->route('academic-years.index')
            ->with('success', "Academic year '{$academicYear->name}' has been restored.");
    }
}
