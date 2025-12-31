<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FeatureToggle extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'is_enabled',
        'category',
        'sort_order',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Check if a feature is enabled
     */
    public static function isEnabled(string $key): bool
    {
        return Cache::remember("feature.{$key}", 3600, function () use ($key) {
            $feature = self::where('key', $key)->first();
            return $feature ? $feature->is_enabled : false;
        });
    }

    /**
     * Enable a feature
     */
    public static function enable(string $key): bool
    {
        $feature = self::where('key', $key)->first();
        if ($feature) {
            $feature->update(['is_enabled' => true]);
            Cache::forget("feature.{$key}");
            return true;
        }
        return false;
    }

    /**
     * Disable a feature
     */
    public static function disable(string $key): bool
    {
        $feature = self::where('key', $key)->first();
        if ($feature) {
            $feature->update(['is_enabled' => false]);
            Cache::forget("feature.{$key}");
            return true;
        }
        return false;
    }

    /**
     * Get all features grouped by category
     */
    public static function getGroupedFeatures(): array
    {
        return Cache::remember('features.grouped', 3600, function () {
            return self::orderBy('category')->orderBy('sort_order')->get()->groupBy('category')->toArray();
        });
    }

    /**
     * Clear all feature cache
     */
    public static function clearCache(): void
    {
        $features = self::all();
        foreach ($features as $feature) {
            Cache::forget("feature.{$feature->key}");
        }
        Cache::forget('features.grouped');
    }
}
