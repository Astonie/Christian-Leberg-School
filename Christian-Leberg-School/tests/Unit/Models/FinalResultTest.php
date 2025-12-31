<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\FinalResult;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FinalResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_student()
    {
        $student = Student::factory()->create();
        $result = FinalResult::factory()->create(['student_id' => $student->id]);

        $this->assertInstanceOf(Student::class, $result->student);
        $this->assertEquals($student->id, $result->student->id);
    }

    /** @test */
    public function it_casts_percentage_as_float()
    {
        $result = FinalResult::factory()->create(['percentage' => 85.75]);

        $this->assertIsFloat($result->percentage);
        $this->assertEquals(85.75, $result->percentage);
    }

    /** @test */
    public function it_casts_points_as_float()
    {
        $result = FinalResult::factory()->create(['points' => 3.5]);

        $this->assertIsFloat($result->points);
        $this->assertEquals(3.5, $result->points);
    }

    /** @test */
    public function it_casts_breakdown_as_array()
    {
        $breakdown = [
            'total_marks' => 500,
            'obtained_marks' => 450,
            'subjects' => [
                ['name' => 'Math', 'marks' => 90],
                ['name' => 'Science', 'marks' => 85],
            ],
        ];

        $result = FinalResult::factory()->create(['breakdown' => $breakdown]);

        $this->assertIsArray($result->breakdown);
        $this->assertEquals(500, $result->breakdown['total_marks']);
        $this->assertCount(2, $result->breakdown['subjects']);
    }

    /** @test */
    public function it_casts_is_published_as_boolean()
    {
        $result = FinalResult::factory()->create(['is_published' => true]);

        $this->assertIsBool($result->is_published);
        $this->assertTrue($result->is_published);
    }

    /** @test */
    public function it_can_be_published_or_unpublished()
    {
        $published = FinalResult::factory()->published()->create();
        $unpublished = FinalResult::factory()->unpublished()->create();

        $this->assertTrue($published->is_published);
        $this->assertFalse($unpublished->is_published);
    }

    /** @test */
    public function it_has_guarded_attributes()
    {
        $result = new FinalResult();

        $this->assertEquals([], $result->getGuarded());
    }

    /** @test */
    public function it_stores_grade_information()
    {
        $result = FinalResult::factory()->create([
            'percentage' => 85.5,
            'grade_code' => 'A',
            'grade_label' => 'Excellent',
            'points' => 4.0,
        ]);

        $this->assertEquals('A', $result->grade_code);
        $this->assertEquals('Excellent', $result->grade_label);
        $this->assertEquals(85.5, $result->percentage);
        $this->assertEquals(4.0, $result->points);
    }
}
