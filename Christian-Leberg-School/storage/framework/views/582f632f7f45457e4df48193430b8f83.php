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
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Admin Dashboard</h2>
                <p class="text-sm text-gray-600 mt-1">School Analytics & Insights</p>
            </div>
            <?php if($activeYear): ?>
                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-semibold"><?php echo e($activeYear->name); ?></span>
                </div>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="container-mobile section-spacing">
        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Students -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($metrics['total_students'])); ?></p>
                <p class="text-blue-100 text-sm font-medium mb-2">Total Students</p>
                <div class="flex items-center text-xs text-blue-100">
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded"><?php echo e(number_format($metrics['active_students'])); ?> Active</span>
                </div>
            </div>

            <!-- Teachers -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($metrics['total_teachers'])); ?></p>
                <p class="text-purple-100 text-sm font-medium mb-2">Teaching Staff</p>
                <div class="flex items-center text-xs text-purple-100">
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded"><?php echo e(number_format($metrics['total_subjects'])); ?> Subjects</span>
                </div>
            </div>

            <!-- Classes -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($metrics['total_classes'])); ?></p>
                <p class="text-orange-100 text-sm font-medium mb-2">Classes</p>
                <div class="flex items-center text-xs text-orange-100">
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded">Multiple Streams</span>
                </div>
            </div>

            <!-- Active Exams -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($metrics['active_exams'])); ?></p>
                <p class="text-green-100 text-sm font-medium mb-2">Active Exams</p>
                <div class="flex items-center text-xs text-green-100">
                    <span class="bg-white bg-opacity-20 px-2 py-1 rounded">Ongoing</span>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Left Column - 2/3 width -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Gender Distribution & Attendance -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Gender Distribution -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Gender Distribution
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-700">Male Students</span>
                                    <span class="text-sm font-bold text-blue-600"><?php echo e($metrics['male_students']); ?> (<?php echo e($metrics['total_students'] > 0 ? round(($metrics['male_students'] / $metrics['total_students']) * 100, 1) : 0); ?>%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-500" style="width: <?php echo e($metrics['total_students'] > 0 ? round(($metrics['male_students'] / $metrics['total_students']) * 100, 1) : 0); ?>%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-semibold text-gray-700">Female Students</span>
                                    <span class="text-sm font-bold text-pink-600"><?php echo e($metrics['female_students']); ?> (<?php echo e($metrics['total_students'] > 0 ? round(($metrics['female_students'] / $metrics['total_students']) * 100, 1) : 0); ?>%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-pink-500 to-pink-600 h-3 rounded-full transition-all duration-500" style="width: <?php echo e($metrics['total_students'] > 0 ? round(($metrics['female_students'] / $metrics['total_students']) * 100, 1) : 0); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Overview -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Attendance (30 Days)
                        </h3>
                        <div class="text-center mb-4">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-green-100 to-green-200">
                                <span class="text-3xl font-bold text-green-600"><?php echo e($attendanceStats['rate']); ?>%</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-green-50 rounded-lg p-2">
                                <p class="text-lg font-bold text-green-600"><?php echo e($attendanceStats['present']); ?></p>
                                <p class="text-xs text-gray-600">Present</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-2">
                                <p class="text-lg font-bold text-red-600"><?php echo e($attendanceStats['absent']); ?></p>
                                <p class="text-xs text-gray-600">Absent</p>
                            </div>
                            <div class="bg-yellow-50 rounded-lg p-2">
                                <p class="text-lg font-bold text-yellow-600"><?php echo e($attendanceStats['late']); ?></p>
                                <p class="text-xs text-gray-600">Late</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Class Distribution -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Class Distribution
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 text-sm font-bold text-gray-700">Class</th>
                                        <th class="text-center py-3 px-4 text-sm font-bold text-gray-700">Streams</th>
                                        <th class="text-center py-3 px-4 text-sm font-bold text-gray-700">Students</th>
                                        <th class="text-right py-3 px-4 text-sm font-bold text-gray-700">Distribution</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php
                                        $totalStudentsInClasses = $classDistribution->sum('students');
                                    ?>
                                    <?php $__empty_1 = true; $__currentLoopData = $classDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="py-3 px-4">
                                                <span class="text-sm font-semibold text-gray-900"><?php echo e($class['name']); ?></span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800">
                                                    <?php echo e($class['streams']); ?>

                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="text-sm font-bold text-gray-900"><?php echo e($class['students']); ?></span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <div class="w-24 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: <?php echo e($totalStudentsInClasses > 0 ? round(($class['students'] / $totalStudentsInClasses) * 100, 1) : 0); ?>%"></div>
                                                    </div>
                                                    <span class="text-xs font-semibold text-gray-600 w-10 text-right"><?php echo e($totalStudentsInClasses > 0 ? round(($class['students'] / $totalStudentsInClasses) * 100, 1) : 0); ?>%</span>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="py-8 text-center text-gray-500">No class data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Exam Performance -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Recent Exam Performance
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $recentExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <h4 class="text-sm font-bold text-gray-900"><?php echo e($exam['name']); ?></h4>
                                            <p class="text-xs text-gray-500 mt-1"><?php echo e($exam['type']); ?> • <?php echo e($exam['date']); ?></p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                            <?php echo e($exam['students']); ?> Students
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-xs text-gray-600 mb-1">Average Score</p>
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full" style="width: <?php echo e($exam['avg_score']); ?>%"></div>
                                                </div>
                                                <span class="text-sm font-bold text-gray-900"><?php echo e($exam['avg_score']); ?>%</span>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-600 mb-1">Pass Rate</p>
                                            <div class="flex items-center">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full" style="width: <?php echo e($exam['pass_rate']); ?>%"></div>
                                                </div>
                                                <span class="text-sm font-bold text-gray-900"><?php echo e($exam['pass_rate']); ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-center text-gray-500 py-8">No recent exam data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - 1/3 width -->
            <div class="space-y-6">
                <!-- Top Performers -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Top Performers
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $topStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center gap-3 p-3 rounded-xl <?php echo e($index === 0 ? 'bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200' : 'bg-gray-50'); ?>">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full <?php echo e($index === 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-500' : ($index === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400' : ($index === 2 ? 'bg-gradient-to-br from-orange-300 to-orange-400' : 'bg-gray-300'))); ?> flex items-center justify-center text-white font-bold text-sm">
                                        <?php echo e($index + 1); ?>

                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate"><?php echo e($student['name']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($student['admission_no']); ?> • <?php echo e($student['exams']); ?> exams</p>
                                    </div>
                                    <div class="flex-shrink-0 text-right">
                                        <p class="text-lg font-bold text-green-600"><?php echo e($student['avg_score']); ?>%</p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-center text-gray-500 py-8 text-sm">No performance data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Teacher Workload -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Teacher Workload
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $teacherWorkload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="p-3 bg-gray-50 rounded-xl">
                                    <p class="text-sm font-bold text-gray-900 mb-2"><?php echo e($teacher['name']); ?></p>
                                    <div class="grid grid-cols-2 gap-2 text-xs">
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <?php echo e($teacher['subjects']); ?> Subjects
                                        </div>
                                        <div class="flex items-center text-gray-600">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <?php echo e($teacher['streams']); ?> Streams
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                            <span>Workload Score</span>
                                            <span class="font-bold"><?php echo e($teacher['load']); ?></span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-1.5 rounded-full" style="width: <?php echo e(min(($teacher['load'] / 20) * 100, 100)); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-center text-gray-500 py-8 text-sm">No teacher data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Quick Actions
                    </h3>
                    <div class="space-y-2">
                        <a href="<?php echo e(route('students.create')); ?>" class="block p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl text-sm font-semibold transition-all">
                            + Add New Student
                        </a>
                        <a href="<?php echo e(route('exams.create')); ?>" class="block p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl text-sm font-semibold transition-all">
                            + Create Exam
                        </a>
                        <a href="<?php echo e(route('timetables.index')); ?>" class="block p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl text-sm font-semibold transition-all">
                            📅 Manage Timetable
                        </a>
                        <a href="<?php echo e(route('attendance.index')); ?>" class="block p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl text-sm font-semibold transition-all">
                            📊 View Attendance
                        </a>
                        <a href="<?php echo e(route('users.index')); ?>" class="block p-3 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-xl text-sm font-semibold transition-all">
                            👥 Manage Users
                        </a>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/dashboards/admin.blade.php ENDPATH**/ ?>