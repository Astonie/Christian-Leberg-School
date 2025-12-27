<?php

namespace App\Http\Controllers;

use App\Models\AssessmentStructure;
use App\Models\AssessmentComponent;
use App\Models\GradingSystem;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentStructureController extends Controller
{
    /**
     * Display a listing of assessment structures
     */
    public function index()
    {
        $structures = AssessmentStructure::with(['gradingSystem', 'subject', 'components'])
            ->orderBy('name')
            ->paginate(20);
        
        return view('assessment-structures.index', compact('structures'));
    }

    /**
     * Show the form for creating a new assessment structure
     */
    public function create()
    {
        $gradingSystems = GradingSystem::all();
        $subjects = Subject::orderBy('name')->get();
        
        return view('assessment-structures.create', compact('gradingSystems', 'subjects'));
    }

    /**
     * Store a newly created assessment structure
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'grading_system_id' => 'nullable|exists:grading_systems,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'version' => 'nullable|integer|min:1',
            'components' => 'required|array|min:1',
            'components.*.name' => 'required|string|max:255',
            'components.*.code' => 'required|string|max:50',
            'components.*.weight' => 'required|numeric|min:0|max:100',
            'components.*.max_score' => 'required|numeric|min:0',
            'components.*.order' => 'required|integer|min:0',
            'components.*.description' => 'nullable|string',
        ]);
        
        // Validate weights sum to 100
        $totalWeight = collect($request->components)->sum('weight');
        if (abs($totalWeight - 100) > 0.01) {
            return back()
                ->withErrors(['components' => "Component weights must sum to 100%. Current total: {$totalWeight}%"])
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $structure = AssessmentStructure::create([
                'name' => $request->name,
                'description' => $request->description,
                'grading_system_id' => $request->grading_system_id,
                'subject_id' => $request->subject_id,
                'version' => $request->version ?? 1,
            ]);
            
            foreach ($request->components as $componentData) {
                AssessmentComponent::create([
                    'assessment_structure_id' => $structure->id,
                    'name' => $componentData['name'],
                    'code' => $componentData['code'],
                    'weight' => $componentData['weight'],
                    'max_score' => $componentData['max_score'],
                    'order' => $componentData['order'],
                    'description' => $componentData['description'] ?? null,
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('assessment-structures.index')
                ->with('success', 'Assessment structure created successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to create assessment structure: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified assessment structure
     */
    public function show(AssessmentStructure $assessmentStructure)
    {
        $assessmentStructure->load(['gradingSystem', 'subject', 'components' => function($q) {
            $q->orderBy('order');
        }]);
        
        return view('assessment-structures.show', compact('assessmentStructure'));
    }

    /**
     * Show the form for editing the specified assessment structure
     */
    public function edit(AssessmentStructure $assessmentStructure)
    {
        $assessmentStructure->load(['components' => function($q) {
            $q->orderBy('order');
        }]);
        
        $gradingSystems = GradingSystem::all();
        $subjects = Subject::orderBy('name')->get();
        
        return view('assessment-structures.edit', compact('assessmentStructure', 'gradingSystems', 'subjects'));
    }

    /**
     * Update the specified assessment structure
     */
    public function update(Request $request, AssessmentStructure $assessmentStructure)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'grading_system_id' => 'nullable|exists:grading_systems,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'version' => 'nullable|integer|min:1',
            'components' => 'required|array|min:1',
            'components.*.name' => 'required|string|max:255',
            'components.*.code' => 'required|string|max:50',
            'components.*.weight' => 'required|numeric|min:0|max:100',
            'components.*.max_score' => 'required|numeric|min:0',
            'components.*.order' => 'required|integer|min:0',
            'components.*.description' => 'nullable|string',
        ]);
        
        // Validate weights sum to 100
        $totalWeight = collect($request->components)->sum('weight');
        if (abs($totalWeight - 100) > 0.01) {
            return back()
                ->withErrors(['components' => "Component weights must sum to 100%. Current total: {$totalWeight}%"])
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $assessmentStructure->update([
                'name' => $request->name,
                'description' => $request->description,
                'grading_system_id' => $request->grading_system_id,
                'subject_id' => $request->subject_id,
                'version' => $request->version ?? 1,
            ]);
            
            // Delete existing components
            $assessmentStructure->components()->delete();
            
            // Create new components
            foreach ($request->components as $componentData) {
                AssessmentComponent::create([
                    'assessment_structure_id' => $assessmentStructure->id,
                    'name' => $componentData['name'],
                    'code' => $componentData['code'],
                    'weight' => $componentData['weight'],
                    'max_score' => $componentData['max_score'],
                    'order' => $componentData['order'],
                    'description' => $componentData['description'] ?? null,
                ]);
            }
            
            DB::commit();
            
            return redirect()
                ->route('assessment-structures.index')
                ->with('success', 'Assessment structure updated successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withErrors(['error' => 'Failed to update assessment structure: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Remove the specified assessment structure
     */
    public function destroy(AssessmentStructure $assessmentStructure)
    {
        try {
            $assessmentStructure->delete();
            
            return redirect()
                ->route('assessment-structures.index')
                ->with('success', 'Assessment structure deleted successfully!');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Failed to delete assessment structure: ' . $e->getMessage()]);
        }
    }
}
