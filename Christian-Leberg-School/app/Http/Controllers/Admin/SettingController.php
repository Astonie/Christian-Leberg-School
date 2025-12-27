<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.cms.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                if ($setting->type === 'image' && $request->hasFile("settings.{$key}")) {
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    $value = $request->file("settings.{$key}")->store('cms/settings', 'public');
                }
                
                $setting->update(['value' => is_array($value) ? json_encode($value) : $value]);
            } else {
                Setting::create([
                    'key' => $key,
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'type' => 'text',
                    'group' => 'general',
                ]);
            }
        }

        return redirect()->route('admin.cms.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}
