<?php

if (!function_exists('setting')) {
    /**
     * Get a setting value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @return \App\Models\Setting
     */
    function set_setting($key, $value, $type = 'text', $group = 'general')
    {
        return \App\Models\Setting::set($key, $value, $type, $group);
    }
}

if (!function_exists('feature_enabled')) {
    /**
     * Check if a feature is enabled
     *
     * @param string $feature
     * @return bool
     */
    function feature_enabled($feature)
    {
        return \App\Models\FeatureToggle::isEnabled($feature);
    }
}
