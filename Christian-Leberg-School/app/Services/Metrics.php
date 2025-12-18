<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Metrics
{
    /**
     * Increment a named metric counter by one.
     *
     * Uses the cache store to persist simple counters (suitable for lightweight metric collection).
     */
    public function increment(string $name, int $by = 1): void
    {
        $key = $this->key($name);
        // Ensure key exists
        if (! Cache::has($key)) {
            Cache::put($key, 0, now()->addDays(7));
        }

        Cache::increment($key, $by);
    }

    public function get(string $name): int
    {
        return (int) Cache::get($this->key($name), 0);
    }

    protected function key(string $name): string
    {
        return "metrics:" . $name;
    }
}
