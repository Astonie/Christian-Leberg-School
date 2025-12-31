<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Report Card - <?php echo e($student->user->name); ?></h2>
            <a href="<?php echo e(route('exams.student-report.pdf', [$exam, $student])); ?>" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download PDF
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Report Card Container -->
            <div class="bg-white rounded-xl shadow-lg border-2 border-gray-200 overflow-hidden">
                
                <!-- Header with School Info -->
                <div class="bg-gradient-to-r from-blue-600 to-cyan-600 text-white p-8">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold mb-2"><?php echo e(config('app.name')); ?></h1>
                            <p class="text-blue-100">Academic Excellence & Character Development</p>
                        </div>
                        <?php if(file_exists(public_path('images/school_logo.png'))): ?>
                            <img src="<?php echo e(asset('images/school_logo.png')); ?>" alt="Logo" class="w-20 h-20 bg-white rounded-lg p-2">
                        <?php endif; ?>
                    </div>
                    <div class="mt-6 bg-white/10 rounded-lg p-4">
                        <h2 class="text-xl font-semibold"><?php echo e($exam->name); ?> - <?php echo e($exam->term->name); ?></h2>
                        <p class="text-sm text-blue-100"><?php echo e($exam->academicYear->name); ?> | <?php echo e($exam->start_date->format('M d')); ?> - <?php echo e($exam->end_date->format('M d, Y')); ?></p>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Student Information -->
                    <div class="grid grid-cols-2 gap-6 mb-8 bg-gray-50 rounded-lg p-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Student Name</h3>
                            <p class="text-lg font-bold text-gray-900"><?php echo e($student->user->name); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Admission Number</h3>
                            <p class="text-lg font-bold text-gray-900"><?php echo e($student->admission_number); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Class & Stream</h3>
                            <p class="text-lg font-bold text-gray-900">
                                <?php echo e(optional($student->currentStream->schoolClass ?? null)->name ?? '-'); ?> - 
                                <?php echo e(optional($student->currentStream ?? null)->name ?? '-'); ?>

                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Academic Year</h3>
                            <p class="text-lg font-bold text-gray-900"><?php echo e($exam->academicYear->name); ?></p>
                        </div>
                    </div>

                    <!-- Subjects Performance Table -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Subject Performance</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="bg-gray-100 border-b-2 border-gray-300">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Subject</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Marks</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Grade</th>
                                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Points</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Remark</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php
                                        $totalMarks = 0;
                                        $totalPoints = 0;
                                        $subjectCount = 0;
                                        $gradingSystem = \App\Models\GradingSystem::where('is_active', true)->with('scales')->first();
                                    ?>
                                    <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $totalMarks += $result->marks;
                                            $totalPoints += $result->points ?? 0;
                                            $subjectCount++;
                                            
                                            // Find remark from grading system
                                            $remark = 'Average';
                                            if ($gradingSystem) {
                                                foreach ($gradingSystem->scales as $scale) {
                                                    if ($result->marks >= $scale->min_score && $result->marks <= $scale->max_score) {
                                                        $remark = $scale->label;
                                                        break;
                                                    }
                                                }
                                            }
                                        ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-sm font-medium text-gray-900"><?php echo e($result->subject->name); ?></td>
                                            <td class="px-4 py-3 text-center text-sm font-bold text-gray-900"><?php echo e($result->marks); ?></td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                                    <?php if($result->marks >= 70): ?> bg-green-100 text-green-800
                                                    <?php elseif($result->marks >= 60): ?> bg-blue-100 text-blue-800
                                                    <?php elseif($result->marks >= 50): ?> bg-yellow-100 text-yellow-800
                                                    <?php else: ?> bg-red-100 text-red-800
                                                    <?php endif; ?>">
                                                    <?php echo e($result->grade); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center text-sm font-semibold text-gray-700"><?php echo e($result->points ?? '-'); ?></td>
                                            <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($remark); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                                    <tr>
                                        <td class="px-4 py-4 text-sm font-bold text-gray-900">TOTAL</td>
                                        <td class="px-4 py-4 text-center text-lg font-bold text-blue-600"><?php echo e($totalMarks); ?></td>
                                        <td colspan="2" class="px-4 py-4 text-center text-sm font-semibold text-gray-700">Total Points: <?php echo e($totalPoints); ?></td>
                                        <td class="px-4 py-4"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Performance Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6 border border-blue-200">
                            <h4 class="text-sm font-medium text-blue-700 mb-2">Average Score</h4>
                            <p class="text-3xl font-bold text-blue-900"><?php echo e($subjectCount > 0 ? number_format($totalMarks / $subjectCount, 1) : '0'); ?>%</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6 border border-green-200">
                            <h4 class="text-sm font-medium text-green-700 mb-2">Mean Grade</h4>
                            <?php
                                $meanPoints = $subjectCount > 0 ? $totalPoints / $subjectCount : 0;
                                $meanGrade = 'E';
                                if ($gradingSystem) {
                                    foreach ($gradingSystem->scales->sortBy('order') as $scale) {
                                        if ($meanPoints >= ($scale->points ?? 0)) {
                                            $meanGrade = $scale->code;
                                            break;
                                        }
                                    }
                                }
                            ?>
                            <p class="text-3xl font-bold text-green-900"><?php echo e($meanGrade); ?></p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6 border border-purple-200">
                            <h4 class="text-sm font-medium text-purple-700 mb-2">Total Points</h4>
                            <p class="text-3xl font-bold text-purple-900"><?php echo e($totalPoints); ?></p>
                        </div>
                    </div>

                    <!-- Position Information -->
                    <?php
                        $firstResult = $results->first();
                    ?>
                    <?php if($firstResult && ($firstResult->position || $firstResult->stream_position)): ?>
                        <div class="grid grid-cols-2 gap-6 mb-8">
                            <?php if($firstResult->stream_position): ?>
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-lg">
                                    <h4 class="text-sm font-semibold text-yellow-800 mb-2">Stream Position</h4>
                                    <p class="text-4xl font-bold text-yellow-900"><?php echo e($firstResult->stream_position); ?></p>
                                    <p class="text-sm text-yellow-700 mt-1">out of stream</p>
                                </div>
                            <?php endif; ?>
                            <?php if($firstResult->position): ?>
                                <div class="bg-indigo-50 border-l-4 border-indigo-400 p-6 rounded-r-lg">
                                    <h4 class="text-sm font-semibold text-indigo-800 mb-2">Overall Position</h4>
                                    <p class="text-4xl font-bold text-indigo-900"><?php echo e($firstResult->position); ?></p>
                                    <p class="text-sm text-indigo-700 mt-1">out of class</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Grading Scale Reference -->
                    <?php if($gradingSystem): ?>
                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <h4 class="text-sm font-semibold text-gray-700 mb-4">Grading Scale: <?php echo e($gradingSystem->name); ?></h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                <?php $__currentLoopData = $gradingSystem->scales->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="text-center p-3 bg-white rounded-lg border border-gray-200">
                                        <div class="text-lg font-bold text-gray-900"><?php echo e($scale->code); ?></div>
                                        <div class="text-xs text-gray-600"><?php echo e($scale->min_score); ?>-<?php echo e($scale->max_score); ?>%</div>
                                        <div class="text-xs text-gray-500 mt-1"><?php echo e($scale->label); ?></div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Signatures -->
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t-2 border-gray-200">
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="text-sm font-semibold text-gray-700">Class Teacher</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="text-sm font-semibold text-gray-700">Principal</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="border-t-2 border-gray-400 pt-2 mt-16">
                                <p class="text-sm font-semibold text-gray-700">Date</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/exams/student_report.blade.php ENDPATH**/ ?>