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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight"><?php echo e($exam->name); ?></h2>
                <p class="text-sm text-gray-600 mt-2"><?php echo e($exam->academicYear->name); ?> - <?php echo e($exam->term->name); ?></p>
            </div>
            <div class="flex gap-2">
                <?php if(auth()->user()->hasRole('admin')): ?>
                    <a href="<?php echo e(route('exams.edit', $exam)); ?>" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white border-2 border-indigo-600 rounded-lg text-sm font-semibold hover:from-indigo-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Exam
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('exams.index')); ?>" class="inline-flex items-center px-4 py-2.5 bg-white border-2 border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Exams
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="container-mobile section-spacing">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Students -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($totalStudents)); ?></p>
                <p class="text-blue-100 text-sm font-medium">Total Students</p>
            </div>

            <!-- Total Subjects -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e($totalSubjects); ?></p>
                <p class="text-green-100 text-sm font-medium">Subjects</p>
            </div>

            <!-- Total Classes -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e($totalClasses); ?></p>
                <p class="text-purple-100 text-sm font-medium">Classes</p>
            </div>

            <!-- Completion Progress -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e($completionPercentage); ?>%</p>
                <p class="text-orange-100 text-sm font-medium">Completion</p>
                <div class="mt-2 bg-white bg-opacity-20 rounded-full h-2">
                    <div class="bg-white rounded-full h-2 transition-all duration-500" style="width: <?php echo e($completionPercentage); ?>%"></div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Exam Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                        <h3 class="text-lg font-bold text-blue-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Examination Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Academic Year</p>
                                <p class="text-lg font-bold text-gray-900"><?php echo e($exam->academicYear->name); ?></p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Term</p>
                                <p class="text-lg font-bold text-gray-900"><?php echo e($exam->term->name); ?></p>
                                <?php if($exam->term->is_active): ?>
                                    <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active Term</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Start Date</p>
                                <p class="text-lg font-bold text-gray-900"><?php echo e($exam->start_date->format('M d, Y')); ?></p>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">End Date</p>
                                <p class="text-lg font-bold text-gray-900"><?php echo e($exam->end_date->format('M d, Y')); ?></p>
                            </div>
                            <?php if($exam->examType): ?>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Exam Type</p>
                                    <p class="text-lg font-bold text-gray-900"><?php echo e($exam->examType->name); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if($exam->gradingScale): ?>
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-1">Grading Scale</p>
                                    <p class="text-lg font-bold text-gray-900"><?php echo e($exam->gradingScale->name); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if($exam->description): ?>
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Description</p>
                                <p class="text-gray-700 leading-relaxed"><?php echo e($exam->description); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Subjects -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                        <h3 class="text-lg font-bold text-green-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Subjects Included (<?php echo e($totalSubjects); ?>)
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if($exam->subjects->isEmpty()): ?>
                            <p class="text-gray-500 text-center py-8">No subjects have been added to this exam yet.</p>
                        <?php else: ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <?php $__currentLoopData = $exam->subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-4 bg-green-50 border-2 border-green-200 rounded-xl hover:shadow-md transition-all">
                                        <div>
                                            <p class="font-bold text-gray-900"><?php echo e($subject->name); ?></p>
                                            <p class="text-sm text-gray-600"><?php echo e($subject->code); ?></p>
                                        </div>
                                        <?php if(auth()->user()->hasRole('admin') || (auth()->user()->hasRole('teacher') && auth()->user()->teacher->subjects->contains($subject->id))): ?>
                                            <a href="<?php echo e(route('exams.results.create_for_subject', [$exam, $subject])); ?>" 
                                               class="px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                                Enter Marks
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Classes/Forms -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-4 border-b border-purple-200">
                        <h3 class="text-lg font-bold text-purple-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Classes Taking This Exam (<?php echo e($totalClasses); ?>)
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if($exam->classes->isEmpty()): ?>
                            <p class="text-gray-500 text-center py-8">No classes have been assigned to this exam yet.</p>
                        <?php else: ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                <?php $__currentLoopData = $exam->classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="p-4 bg-purple-50 border-2 border-purple-200 rounded-xl hover:shadow-md transition-all">
                                        <p class="font-bold text-gray-900 text-lg mb-2"><?php echo e($class->name); ?></p>
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <?php echo e($class->streams->sum(function($stream) use ($exam) {
                                                return $stream->students()->wherePivot('academic_year_id', $exam->academic_year_id)->wherePivot('is_active', true)->count();
                                            })); ?> students
                                        </div>
                                        <a href="<?php echo e(route('exams.class.report', [$exam, $class])); ?>" 
                                           class="block w-full text-center px-3 py-2 bg-purple-600 text-white text-sm font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                                            View Report
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-indigo-200">
                        <h3 class="text-lg font-bold text-indigo-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="<?php echo e(route('exams.results.index', $exam)); ?>" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl font-semibold hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg">
                            <span>View All Results</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>

                        <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('teacher')): ?>
                            <a href="<?php echo e(route('exams.results.create', $exam)); ?>" 
                               class="flex items-center justify-between w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg">
                                <span>Enter Results</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </a>
                        <?php endif; ?>

                        <a href="<?php echo e(route('exams.report', $exam)); ?>" 
                           class="flex items-center justify-between w-full px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl font-semibold hover:from-purple-600 hover:to-purple-700 transition-all shadow-md hover:shadow-lg">
                            <span>View Report</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </a>

                        <?php if(auth()->user()->hasRole('admin')): ?>
                            <a href="<?php echo e(route('exam-marks.import')); ?>" 
                               class="flex items-center justify-between w-full px-4 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-xl font-semibold hover:from-orange-600 hover:to-orange-700 transition-all shadow-md hover:shadow-lg">
                                <span>Import Results</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Results Progress -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-teal-50 to-teal-100 px-6 py-4 border-b border-teal-200">
                        <h3 class="text-lg font-bold text-teal-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Results Progress
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-700">Students with Results</span>
                                    <span class="text-sm font-bold text-teal-700"><?php echo e($resultsEntered); ?> / <?php echo e($totalStudents); ?></span>
                                </div>
                                <div class="bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-teal-400 to-teal-600 rounded-full h-3 transition-all duration-500" 
                                         style="width: <?php echo e($totalStudents > 0 ? round(($resultsEntered / $totalStudents) * 100) : 0); ?>%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-700">Total Results Entered</span>
                                    <span class="text-sm font-bold text-blue-700"><?php echo e($actualResults); ?> / <?php echo e($expectedResults); ?></span>
                                </div>
                                <div class="bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-full h-3 transition-all duration-500" 
                                         style="width: <?php echo e($completionPercentage); ?>%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-xs text-gray-600 mb-2 font-semibold uppercase tracking-wide">Expected Results</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($expectedResults)); ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($totalStudents); ?> students × <?php echo e($totalSubjects); ?> subjects</p>
                        </div>
                    </div>
                </div>

                <!-- Top Performers -->
                <?php if($topPerformers->isNotEmpty()): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-4 border-b border-yellow-200">
                            <h3 class="text-lg font-bold text-yellow-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Top 5 Performers
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <?php $__currentLoopData = $topPerformers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                        <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm
                                            <?php if($index === 0): ?> bg-yellow-400 text-yellow-900
                                            <?php elseif($index === 1): ?> bg-gray-300 text-gray-900
                                            <?php elseif($index === 2): ?> bg-orange-300 text-orange-900
                                            <?php else: ?> bg-blue-100 text-blue-900
                                            <?php endif; ?>">
                                            <?php echo e($index + 1); ?>

                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-900 truncate"><?php echo e($result->student->user->name); ?></p>
                                            <p class="text-xs text-gray-500">Avg: <?php echo e(number_format($result->average, 1)); ?>%</p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/exams/show.blade.php ENDPATH**/ ?>