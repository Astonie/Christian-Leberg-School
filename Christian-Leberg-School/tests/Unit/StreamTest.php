<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;

class StreamTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_name_accessor_includes_class_and_year()
    {
        $year = AcademicYear::create(['name' => '2025', 'start_date' => '2025-01-01', 'end_date' => '2025-12-31', 'is_active' => true]);
        $class = SchoolClass::create(['name' => 'Grade 2', 'level' => 2]);
        $stream = Stream::create(['name' => 'A', 'class_id' => $class->id, 'academic_year_id' => $year->id]);

        $this->assertEquals('Grade 2 A (2025)', $stream->full_name);
    }
}
