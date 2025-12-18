<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GradingScale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class GradingScaleController extends Controller
{
    public function index()
    {
        // Be defensive: if migrations haven't been run, show an empty view with a helpful message instead of throwing a 500
        if (!Schema::hasTable('grading_scales')) {
            return view('admin.grading_scales.index', ['scales' => collect(), 'systems' => collect()])->with('warning', 'Database tables for grading scales are not present. Run migrations to enable this feature.');
        }

        $scales = GradingScale::orderBy('min_percentage', 'desc')->get();
        $systems = Schema::hasTable('grading_systems') ? \App\Models\GradingSystem::orderBy('name')->get() : collect();
        return view('admin.grading_scales.index', compact('scales', 'systems'));
    }

    public function store(\App\Http\Requests\Admin\GradingScaleRequest $request)
    {
        $data = $request->validated();

        // Support older schema names while keeping new fields
        if (array_key_exists('min_score', $data) && !array_key_exists('min_percentage', $data)) {
            $data['min_percentage'] = $data['min_score'];
        }
        if (array_key_exists('max_score', $data) && !array_key_exists('max_percentage', $data)) {
            $data['max_percentage'] = $data['max_score'];
        }
        if (array_key_exists('points', $data) && !array_key_exists('grade_point', $data)) {
            $data['grade_point'] = $data['points'];
        }

        GradingScale::create($data);
        return back()->with('success', 'Grading scale added.');
    }

    public function update(\App\Http\Requests\Admin\GradingScaleRequest $request, GradingScale $gradingScale)
    {
        $data = $request->validated();

        if (array_key_exists('min_score', $data) && !array_key_exists('min_percentage', $data)) {
            $data['min_percentage'] = $data['min_score'];
        }
        if (array_key_exists('max_score', $data) && !array_key_exists('max_percentage', $data)) {
            $data['max_percentage'] = $data['max_score'];
        }
        if (array_key_exists('points', $data) && !array_key_exists('grade_point', $data)) {
            $data['grade_point'] = $data['points'];
        }

        $gradingScale->update($data);
        return back()->with('success', 'Grading scale updated.');
    }

    public function destroy(GradingScale $gradingScale)
    {
        $gradingScale->delete();
        return back()->with('success', 'Grading scale removed.');
    }
}
