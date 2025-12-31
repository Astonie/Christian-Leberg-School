<?php

namespace App\Http\Controllers;

use App\Models\FeatureToggle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeatureToggleController extends Controller
{
    /**
     * Display the feature toggles page (Super Admin only)
     */
    public function index()
    {
        // Check if user has manage-features permission
        if (!Auth::user()->hasPermission('manage-features')) {
            abort(403, 'Unauthorized. Only Super Admins can manage features.');
        }

        $features = FeatureToggle::orderBy('category')->orderBy('sort_order')->get()->groupBy('category');
        return view('admin.features.index', compact('features'));
    }

    /**
     * Toggle a feature on/off
     */
    public function toggle(Request $request, $id)
    {
        // Check if user has manage-features permission
        if (!Auth::user()->hasPermission('manage-features')) {
            abort(403, 'Unauthorized. Only Super Admins can manage features.');
        }

        $feature = FeatureToggle::findOrFail($id);
        $feature->is_enabled = !$feature->is_enabled;
        $feature->save();

        // Clear cache
        FeatureToggle::clearCache();

        return redirect()->back()->with('success', "Feature '{$feature->name}' has been " . ($feature->is_enabled ? 'enabled' : 'disabled'));
    }

    /**
     * Update feature settings
     */
    public function update(Request $request, $id)
    {
        // Check if user has manage-features permission
        if (!Auth::user()->hasPermission('manage-features')) {
            abort(403, 'Unauthorized. Only Super Admins can manage features.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_enabled' => 'boolean',
        ]);

        $feature = FeatureToggle::findOrFail($id);
        $feature->update($request->only(['name', 'description', 'is_enabled']));

        // Clear cache
        FeatureToggle::clearCache();

        return redirect()->back()->with('success', "Feature '{$feature->name}' has been updated.");
    }

    /**
     * Bulk enable/disable features
     */
    public function bulkToggle(Request $request)
    {
        // Check if user has manage-features permission
        if (!Auth::user()->hasPermission('manage-features')) {
            abort(403, 'Unauthorized. Only Super Admins can manage features.');
        }

        $request->validate([
            'feature_ids' => 'required|array',
            'action' => 'required|in:enable,disable',
        ]);

        $isEnabled = $request->action === 'enable';
        
        FeatureToggle::whereIn('id', $request->feature_ids)
            ->update(['is_enabled' => $isEnabled]);

        // Clear cache
        FeatureToggle::clearCache();

        $message = count($request->feature_ids) . ' features have been ' . $request->action . 'd.';
        
        return redirect()->back()->with('success', $message);
    }
}
