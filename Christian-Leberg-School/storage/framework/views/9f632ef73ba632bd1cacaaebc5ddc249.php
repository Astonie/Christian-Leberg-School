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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                    <?php echo e(__('Teacher Dashboard')); ?>

                </h2>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Manage your classes, attendance, and academic records</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
            
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
                <div class="p-4 sm:p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-xl sm:text-2xl font-bold mb-1 sm:mb-2 truncate">Welcome, <?php echo e(auth()->user()->name); ?></h3>
                            <p class="text-sm sm:text-base text-blue-100">Access your teaching resources and manage student performance</p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-24 h-24 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Navigation -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                
                <!-- Academic Records -->
                <a href="<?php echo e(route('exam-results.entry')); ?>" class="bg-white rounded-xl shadow-sm border-2 border-blue-200 hover:border-blue-400 hover:shadow-lg transition-all duration-200 p-5 sm:p-6 group active:scale-95">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2.5 sm:p-3 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Academic Records</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Record and manage exam results</p>
                </a>
                
                <!-- Attendance -->
                <a href="<?php echo e(route('attendance.index')); ?>" class="bg-white rounded-xl shadow-sm border-2 border-green-200 hover:border-green-400 hover:shadow-lg transition-all duration-200 p-5 sm:p-6 group active:scale-95">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2.5 sm:p-3 bg-green-100 rounded-lg group-hover:bg-green-600 transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-400 group-hover:text-green-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">Daily Attendance</h3>
                    <p class="text-xs sm:text-sm text-gray-600">Mark and view attendance records</p>
                </a>

                <!-- My Students -->
                <a href="<?php echo e(route('students.index')); ?>" class="bg-white rounded-xl shadow-sm border-2 border-purple-200 hover:border-purple-400 hover:shadow-lg transition-all duration-200 p-5 sm:p-6 group active:scale-95">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2.5 sm:p-3 bg-purple-100 rounded-lg group-hover:bg-purple-600 transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">My Students</h3>
                    <p class="text-xs sm:text-sm text-gray-600">View and manage your students</p>
                </a>

                <!-- My Subjects -->
                <a href="<?php echo e(route('teacher.subjects')); ?>" class="bg-white rounded-xl shadow-sm border-2 border-orange-200 hover:border-orange-400 hover:shadow-lg transition-all duration-200 p-5 sm:p-6 group active:scale-95">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <div class="p-2.5 sm:p-3 bg-orange-100 rounded-lg group-hover:bg-orange-600 transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-400 group-hover:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1">My Subjects</h3>
                    <p class="text-xs sm:text-sm text-gray-600">View subjects and streams</p>
                </a>
            </div>

            <!-- Active Relevant Exams Section -->
            <?php if($relevantExams->isNotEmpty()): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-green-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-bold text-green-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                My Active Exams
                            </h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-200 text-green-800">
                                <?php echo e($relevantExams->count()); ?> Active
                            </span>
                        </div>
                        <p class="text-sm text-green-700 mt-1">Exams matching your subjects and assigned classes</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php $__currentLoopData = $relevantExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relevantExam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow bg-gradient-to-r from-white to-gray-50">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h4 class="text-base font-bold text-gray-900"><?php echo e($relevantExam->name); ?></h4>
                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-800">
                                                    <?php echo e($relevantExam->term->name ?? 'N/A'); ?>

                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <?php echo e($relevantExam->examType->name ?? 'N/A'); ?>

                                                </span>
                                                <span class="text-xs text-gray-600">
                                                    <?php echo e($relevantExam->start_date->format('M d')); ?> - <?php echo e($relevantExam->end_date->format('M d, Y')); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <?php if($relevantExam->end_date >= now()): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="4"/>
                                                </svg>
                                                Active
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-600 mb-1">Your Subjects in this Exam:</p>
                                            <div class="flex flex-wrap gap-1">
                                                <?php
                                                    $teacherSubjects = $subjects->pluck('id');
                                                    $examSubjects = $relevantExam->subjects->whereIn('id', $teacherSubjects);
                                                ?>
                                                <?php $__currentLoopData = $examSubjects->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                        <?php echo e($subject->code); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($examSubjects->count() > 3): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        +<?php echo e($examSubjects->count() - 3); ?> more
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <p class="text-xs font-semibold text-gray-600 mb-1">Your Classes in this Exam:</p>
                                            <div class="flex flex-wrap gap-1">
                                                <?php
                                                    $teacherClasses = $streams->pluck('class_id')->unique();
                                                    $examClasses = $relevantExam->classes->whereIn('id', $teacherClasses);
                                                ?>
                                                <?php $__currentLoopData = $examClasses->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        <?php echo e($class->name); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($examClasses->count() > 3): ?>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        +<?php echo e($examClasses->count() - 3); ?> more
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2">
                                        <a href="<?php echo e(route('exams.show', $relevantExam)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Details
                                        </a>
                                        <a href="<?php echo e(route('exam-results.entry')); ?>?exam_id=<?php echo e($relevantExam->id); ?>" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Enter Results
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Active Exams</h3>
                    <p class="text-gray-600 mb-4">There are currently no active exams that match your assigned subjects and classes.</p>
                    <a href="<?php echo e(route('exams.index')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                        View All Exams
                    </a>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                
                <!-- My Subjects Overview -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                        <h3 class="text-lg font-bold text-orange-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            My Subjects
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if($subjects->isEmpty()): ?>
                            <p class="text-sm text-gray-600">No subjects assigned for the active academic year.</p>
                        <?php else: ?>
                            <ul class="space-y-2">
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-100">
                                        <div>
                                            <span class="font-semibold text-gray-900"><?php echo e($subject->name); ?></span>
                                            <span class="text-gray-500 text-sm ml-2">(<?php echo e($subject->code); ?>)</span>
                                        </div>
                                        <a href="<?php echo e(route('exam-results.entry')); ?>?subject_id=<?php echo e($subject->id); ?>" class="text-blue-600 hover:text-blue-800">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <a href="<?php echo e(route('teacher.subjects')); ?>" class="mt-4 block text-center text-blue-600 hover:text-blue-800 font-medium">
                                View All Details →
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- My Streams -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 px-6 py-4 border-b border-indigo-200">
                        <h3 class="text-lg font-bold text-indigo-900 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            My Classes/Streams
                        </h3>
                    </div>
                    <div class="p-6">
                        <?php if($streams->isEmpty()): ?>
                            <p class="text-sm text-gray-600">Not assigned to any streams for the active academic year.</p>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $streams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stream): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border-2 border-indigo-100 rounded-lg p-4 hover:border-indigo-300 transition-all">
                                        <div class="flex items-start justify-between mb-2">
                                            <div>
                                                <div class="font-bold text-gray-900"><?php echo e($stream->schoolClass->name ?? 'Class'); ?> - <?php echo e($stream->name); ?></div>
                                                <?php if($stream->pivot->is_class_teacher): ?>
                                                    <span class="inline-block mt-1 px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded">Class Teacher</span>
                                                <?php endif; ?>
                                            </div>
                                            <a href="<?php echo e(route('students.index')); ?>?stream_id=<?php echo e($stream->id); ?>" class="text-blue-600 hover:text-blue-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        
                                        <?php
                                            $assignedSubjectId = $stream->pivot->subject_id ?? null;
                                            $assignedSubject = $assignedSubjectId ? \App\Models\Subject::find($assignedSubjectId) : null;
                                            $pendingAssigned = $assignedSubject ? ($pending[$stream->id][$assignedSubject->id] ?? 0) : 0;
                                        ?>

                                        <?php if($assignedSubject): ?>
                                            <div class="text-sm text-gray-600 mb-2">
                                                <span class="font-medium">Subject:</span> <?php echo e($assignedSubject->name); ?>

                                                <?php if($pendingAssigned): ?>
                                                    <span class="ml-2 inline-block bg-red-100 text-red-800 text-xs px-2 rounded-full"><?php echo e($pendingAssigned); ?> pending</span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if($relevantExams->isNotEmpty() && $exam): ?>
                                                <div class="flex gap-2">
                                                    <a href="<?php echo e(route('exams.results.create_for_subject', ['exam' => $exam->id, 'subject' => $assignedSubject->id])); ?>?stream_id=<?php echo e($stream->id); ?>" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                        Enter Results
                                                    </a>
                                                    <a href="<?php echo e(route('attendance.index')); ?>?stream_id=<?php echo e($stream->id); ?>" class="flex-1 text-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                                        Attendance
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="flex gap-2">
                                                    <a href="<?php echo e(route('attendance.index')); ?>?stream_id=<?php echo e($stream->id); ?>" class="flex-1 text-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                                        Attendance
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Enter Results -->
                <?php if($relevantExams->isNotEmpty()): ?>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                            <h3 class="text-lg font-bold text-blue-900 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Quick Results Entry
                            </h3>
                        </div>
                        <div class="p-6">
                            <form id="enter-results-form" onsubmit="return false;" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Examination</label>
                                    <select id="er-exam" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <?php $__currentLoopData = $relevantExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relevantExam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($relevantExam->id); ?>"><?php echo e($relevantExam->name); ?> (<?php echo e($relevantExam->term->name); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                    <select id="er-subject" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Stream/Class</label>
                                    <select id="er-stream" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <?php $__currentLoopData = $streams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stream): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($stream->id); ?>"><?php echo e($stream->schoolClass->name ?? 'Class'); ?> - <?php echo e($stream->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                
                                <button id="er-go" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                                    Enter Results →
                                </button>
                            </form>
                            <script>
                                document.getElementById('er-go').addEventListener('click', function () {
                                    var exam = document.getElementById('er-exam').value;
                                    var subject = document.getElementById('er-subject').value;
                                    var stream = document.getElementById('er-stream').value;
                                    if (!exam || !subject) return;
                                    // Redirect to the teacher-specific create page for the exam and subject, include stream_id query
                                    window.location = '/exams/' + exam + '/subjects/' + subject + '/results/create?stream_id=' + stream;
                                });
                            </script>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>

            <!-- Additional Resources Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Quick Actions & Resources
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="<?php echo e(route('exam-results.entry')); ?>" class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all group">
                            <div class="p-2 bg-blue-600 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">Marks Entry Grid</div>
                                <div class="text-sm text-gray-600">Simplified bulk entry interface</div>
                            </div>
                        </a>

                        <?php if(isset($exam) && $exam): ?>
                            <a href="<?php echo e(route('exams.results.index', $exam)); ?>" class="flex items-center p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 hover:border-green-300 transition-all group">
                                <div class="p-2 bg-green-600 rounded-lg mr-4">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">View All Results</div>
                                    <div class="text-sm text-gray-600">Browse recorded marks</div>
                                </div>
                            </a>

                            <a href="<?php echo e(route('exams.results.export', $exam)); ?>" class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 hover:border-purple-300 transition-all group">
                                <div class="p-2 bg-purple-600 rounded-lg mr-4">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Export Results</div>
                                    <div class="text-sm text-gray-600">Download as CSV</div>
                                </div>
                            </a>
                        <?php endif; ?>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/dashboards/teacher.blade.php ENDPATH**/ ?>