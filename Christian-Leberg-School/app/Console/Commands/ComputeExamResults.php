<?php

namespace App\Console\Commands;

use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Console\Command;

class ComputeExamResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:compute-results {exam? : The exam ID} {--all : Compute for all exams with assessment structures}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compute exam results from component scores using GradingEngine';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            $exams = Exam::whereNotNull('assessment_structure_id')->get();
            $this->info("Computing results for {$exams->count()} exams with assessment structures...");
            
            foreach ($exams as $exam) {
                $this->computeExam($exam);
            }
        } elseif ($examId = $this->argument('exam')) {
            $exam = Exam::findOrFail($examId);
            $this->computeExam($exam);
        } else {
            $this->error('Please specify an exam ID or use --all flag');
            return 1;
        }
        
        $this->info('Computation complete!');
        return 0;
    }
    
    protected function computeExam(Exam $exam)
    {
        if (!$exam->assessment_structure_id) {
            $this->warn("Exam {$exam->id} ({$exam->name}) has no assessment structure. Skipping.");
            return;
        }
        
        $this->info("Computing results for: {$exam->name}");
        
        $results = ExamResult::where('exam_id', $exam->id)->get();
        $computed = 0;
        $failed = 0;
        
        foreach ($results as $result) {
            if ($result->computeFromComponents()) {
                $result->save();
                $computed++;
            } else {
                $failed++;
            }
        }
        
        $this->line("  ✓ Computed: {$computed} | ✗ Failed: {$failed}");
    }
}

