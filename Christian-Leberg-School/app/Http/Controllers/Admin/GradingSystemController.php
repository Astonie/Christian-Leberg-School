<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradingSystem;
use App\Http\Requests\Admin\GradingSystemRequest;
use Illuminate\Http\Request;

class GradingSystemController extends Controller
{
    public function index()
    {
        if (!\Illuminate\Support\Facades\Schema::hasTable('grading_systems')) {
            return view('admin.grading_systems.index', ['systems' => collect()])->with('warning', 'Database tables for grading systems are not present. Run migrations to enable this feature.');
        }

        $systems = GradingSystem::with('scales')->orderBy('name')->get();
        return view('admin.grading_systems.index', compact('systems'));
    }

    public function show($id)
    {
        $system = GradingSystem::with('scales')->findOrFail($id);
        return view('admin.grading_systems.show', compact('system'));
    }

    public function store(GradingSystemRequest $request)
    {
        GradingSystem::create($request->validated());
        return back()->with('success', 'Grading system created.');
    }

    public function activate($id)
    {
        // Deactivate all other systems
        GradingSystem::query()->update(['is_active' => false]);
        
        // Activate this one
        $system = GradingSystem::findOrFail($id);
        $system->update(['is_active' => true]);
        
        return back()->with('success', 'Grading system activated.');
    }

    public function update(GradingSystemRequest $request, GradingSystem $gradingSystem)
    {
        $gradingSystem->update($request->validated());
        return back()->with('success', 'Grading system updated.');
    }

    public function destroy(GradingSystem $gradingSystem)
    {
        if ($gradingSystem->is_active) {
            return back()->with('error', 'Cannot delete the active grading system.');
        }
        
        $gradingSystem->delete();
        return back()->with('success', 'Grading system removed.');
    }
}
