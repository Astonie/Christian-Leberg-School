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
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Exams & Assessments</h2>
                <p class="text-sm text-gray-600 mt-2">Manage examinations and track student performance</p>
            </div>
            <a href="<?php echo e(route('exams.create')); ?>" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create Exam
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="container-mobile section-spacing">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($stats['total'])); ?></p>
                <p class="text-blue-100 text-sm font-medium">Total Exams</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($stats['current_year'])); ?></p>
                <p class="text-green-100 text-sm font-medium">Current Year</p>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($stats['active'])); ?></p>
                <p class="text-orange-100 text-sm font-medium">Active Now</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div class="p-3 bg-white bg-opacity-20 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold mb-1"><?php echo e(number_format($stats['archived'])); ?></p>
                <p class="text-purple-100 text-sm font-medium">Archived</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filter Exams
                </h3>
            </div>
            <form method="GET" action="<?php echo e(route('exams.index')); ?>" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                            placeholder="Search exam name..."
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <!-- Academic Year -->
                    <div>
                        <label for="academic_year" class="block text-sm font-semibold text-gray-700 mb-2">Academic Year</label>
                        <select name="academic_year" id="academic_year" 
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">All Years</option>
                            <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year->id); ?>" <?php echo e(request('academic_year') == $year->id ? 'selected' : ''); ?>>
                                    <?php echo e($year->name); ?> <?php echo e($year->is_active ? '(Active)' : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Term -->
                    <div>
                        <label for="term" class="block text-sm font-semibold text-gray-700 mb-2">Term</label>
                        <select name="term" id="term" 
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">All Terms</option>
                            <?php $__currentLoopData = $terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($term->id); ?>" <?php echo e(request('term') == $term->id ? 'selected' : ''); ?>>
                                    <?php echo e($term->name); ?> (<?php echo e($term->academicYear->name); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Exam Type -->
                    <div>
                        <label for="exam_type" class="block text-sm font-semibold text-gray-700 mb-2">Exam Type</label>
                        <select name="exam_type" id="exam_type" 
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">All Types</option>
                            <?php $__currentLoopData = $examTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type->id); ?>" <?php echo e(request('exam_type') == $type->id ? 'selected' : ''); ?>>
                                    <?php echo e($type->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" 
                            class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            <option value="">All Status</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-md hover:shadow-lg transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Apply Filters
                    </button>
                    <a href="<?php echo e(route('exams.index')); ?>" class="inline-flex items-center px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-semibold transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Clear Filters
                    </a>

                    <!-- Archive Toggle -->
                    <div class="ml-auto flex items-center">
                        <label for="archived" class="flex items-center cursor-pointer">
                            <input type="checkbox" name="archived" id="archived" value="1" 
                                <?php echo e(request('archived') == '1' ? 'checked' : ''); ?>

                                onchange="this.form.submit()"
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                            <span class="ml-2 text-sm font-semibold text-gray-700">Show Archived Only</span>
                        </label>
                    </div>
                </div>

                <?php if(request()->hasAny(['search', 'academic_year', 'term', 'exam_type', 'status', 'archived'])): ?>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-semibold text-gray-700">Active Filters:</span>
                            <?php if(request('search')): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    Search: <?php echo e(request('search')); ?>

                                </span>
                            <?php endif; ?>
                            <?php if(request('academic_year')): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                    Year: <?php echo e($academicYears->find(request('academic_year'))->name); ?>

                                </span>
                            <?php endif; ?>
                            <?php if(request('term')): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">
                                    Term: <?php echo e($terms->find(request('term'))->name); ?>

                                </span>
                            <?php endif; ?>
                            <?php if(request('exam_type')): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-orange-100 text-orange-800 text-xs font-semibold rounded-full">
                                    Type: <?php echo e($examTypes->find(request('exam_type'))->name); ?>

                                </span>
                            <?php endif; ?>
                            <?php if(request('status')): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                                    Status: <?php echo e(ucfirst(request('status'))); ?>

                                </span>
                            <?php endif; ?>
                            <?php if(request('archived') == '1'): ?>
                                <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                    Archived Only
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        <?php if($exams->isEmpty()): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No exams found</h3>
                <p class="text-gray-600 mb-6">
                    <?php if(request()->hasAny(['search', 'academic_year', 'term', 'exam_type', 'status', 'archived'])): ?>
                        No exams match your current filters. Try adjusting your search criteria.
                    <?php else: ?>
                        Create your first exam to start tracking student performance and generating reports.
                    <?php endif; ?>
                </p>
                <div class="flex items-center justify-center gap-3">
                    <?php if(request()->hasAny(['search', 'academic_year', 'term', 'exam_type', 'status', 'archived'])): ?>
                        <a href="<?php echo e(route('exams.index')); ?>" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold transition-all">
                            Clear Filters
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('exams.create')); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create Exam
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Exams Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto scrollbar-thin">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Exam Details</th>
                                <th class="hidden md:table-cell px-3 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Academic Year</th>
                                <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Term</th>
                                <th class="hidden lg:table-cell px-3 sm:px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Type</th>
                                <th class="px-3 sm:px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider whitespace-nowrap">Period</th>
                                <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 sm:h-10 sm:w-10 flex items-center justify-center bg-blue-100 rounded-lg">
                                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-2 sm:ml-4">
                                                <div class="text-xs sm:text-sm font-bold text-gray-900"><?php echo e($exam->name); ?></div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <span class="md:hidden"><?php echo e($exam->academicYear->name ?? 'N/A'); ?></span>
                                                    <span class="hidden sm:inline"><?php echo e($exam->created_at->diffForHumans()); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="hidden md:table-cell px-3 sm:px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-gray-900"><?php echo e($exam->academicYear->name ?? 'N/A'); ?></span>
                                        <?php if($exam->academicYear && $exam->academicYear->is_active): ?>
                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="hidden lg:table-cell px-3 sm:px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-800">
                                            <?php echo e($exam->term->name); ?>

                                        </span>
                                    </td>
                                    <td class="hidden lg:table-cell px-3 sm:px-6 py-4 whitespace-nowrap">
                                        <?php if($exam->examType): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-orange-100 text-orange-800">
                                                <?php echo e($exam->examType->name); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <div class="text-xs sm:text-sm font-semibold text-gray-900"><?php echo e($exam->start_date->format('M d')); ?> - <?php echo e($exam->end_date->format('M d, Y')); ?></div>
                                        <div class="text-xs text-gray-500 hidden sm:block"><?php echo e($exam->start_date->diffInDays($exam->end_date) + 1); ?> days</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex flex-col gap-1">
                                            <?php if($exam->status === 'active'): ?>
                                                <span class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="4"/>
                                                    </svg>
                                                    <span class="hidden sm:inline">Active</span>
                                                    <span class="sm:hidden">✓</span>
                                                </span>
                                            <?php elseif($exam->status === 'inactive'): ?>
                                                <span class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="4"/>
                                                    </svg>
                                                    <span class="hidden sm:inline">Inactive</span>
                                                    <span class="sm:hidden">✕</span>
                                                </span>
                                            <?php elseif($exam->end_date < now()): ?>
                                                <span class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="4"/>
                                                    </svg>
                                                    <span class="hidden sm:inline">Completed</span>
                                                    <span class="sm:hidden">●</span>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if($exam->results_released): ?>
                                                <span class="inline-flex items-center px-2 sm:px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="4"/>
                                                    </svg>
                                                    <span class="hidden sm:inline">Results Released</span>
                                                    <span class="sm:hidden">📋</span>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-1 sm:gap-2">
                                            <?php if(request('archived') === '1'): ?>
                                                <!-- Archived exams - show restore button -->
                                                <?php if(auth()->user()->hasRole('admin')): ?>
                                                    <form action="<?php echo e(route('exams.restore', $exam->id)); ?>" method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to restore this exam? Status will be changed to active.');">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-2 sm:px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-xs font-semibold transition-colors">
                                                            <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Restore</span>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <!-- Active exams - show view and archive buttons -->
                                                <?php if(auth()->user()->hasRole('admin')): ?>
                                                    <?php if($exam->results_released): ?>
                                                        <form action="<?php echo e(route('exams.withdraw_results', $exam)); ?>" method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to withdraw these results? Students and guardians will no longer be able to access them.');"
                                                              class="inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" 
                                                                    class="inline-flex items-center px-2 sm:px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 rounded-lg text-xs font-semibold transition-colors">
                                                                <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                                                </svg>
                                                                <span class="hidden sm:inline">Withdraw</span>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <form action="<?php echo e(route('exams.release_results', $exam)); ?>" method="POST" 
                                                              onsubmit="return confirm('Are you sure you want to release these results? Students and guardians will be able to access them.');"
                                                              class="inline">
                                                            <?php echo csrf_field(); ?>
                                                            <button type="submit" 
                                                                    class="inline-flex items-center px-2 sm:px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-xs font-semibold transition-colors">
                                                                <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                <span class="hidden sm:inline">Release</span>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                
                                                <a href="<?php echo e(route('exams.show', $exam)); ?>" 
                                                   class="inline-flex items-center px-2 sm:px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg text-xs font-semibold transition-colors">
                                                    <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">View</span>
                                                </a>
                                                <?php if(auth()->user()->hasRole('admin')): ?>
                                                    <form action="<?php echo e(route('exams.destroy', $exam)); ?>" method="POST" 
                                                          onsubmit="return confirm('Are you sure you want to archive this exam? Status will be changed to inactive.');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-2 sm:px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-xs font-semibold transition-colors">
                                                            <svg class="w-4 h-4 sm:mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Archive</span>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($exams->hasPages()): ?>
                    <div class="px-3 sm:px-6 py-4 border-t border-gray-200">
                        <?php echo e($exams->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/exams/index.blade.php ENDPATH**/ ?>