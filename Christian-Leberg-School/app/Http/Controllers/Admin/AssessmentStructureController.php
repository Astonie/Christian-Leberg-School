<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentStructure;
use Illuminate\Support\Facades\Schema;

class AssessmentStructureController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('assessment_structures')) {
            return view('admin.assessment_structures.index', ['structures' => collect()])->with('warning', 'Assessment structures table not present. Run migrations to enable this feature.');
        }

        $structures = AssessmentStructure::with(['gradingSystem', 'subject', 'components'])->orderBy('name')->get();
        return view('admin.assessment_structures.index', compact('structures'));
    }
}
