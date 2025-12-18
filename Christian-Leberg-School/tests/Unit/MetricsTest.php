<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Metrics;
use Illuminate\Support\Facades\Cache;

class MetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_increment_and_get_work()
    {
        $metrics = new Metrics();

        // Ensure starting at zero
        $this->assertEquals(0, $metrics->get('test.counter'));

        $metrics->increment('test.counter');
        $this->assertEquals(1, $metrics->get('test.counter'));

        $metrics->increment('test.counter', 4);
        $this->assertEquals(5, $metrics->get('test.counter'));
    }
}
