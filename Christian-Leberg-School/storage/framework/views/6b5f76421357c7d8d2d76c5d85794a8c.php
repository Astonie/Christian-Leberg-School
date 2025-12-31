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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Teachers Management</h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Teacher::class)): ?>
                <a href="<?php echo e(route('teachers.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg shadow-sm transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Teacher
                </a>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ view: '<?php echo e(request('view', 'cards')); ?>' }">
            
            <!-- Search & Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" action="<?php echo e(route('teachers.index')); ?>" class="space-y-4">
                    <input type="hidden" name="view" x-model="view">
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <input type="text" id="search" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, email or employee number..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Employment Type Filter -->
                        <div>
                            <label for="employment_type" class="block text-sm font-medium text-gray-700 mb-1">Employment Type</label>
                            <select id="employment_type" name="employment_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400">
                                <option value="">All Types</option>
                                <option value="full-time" <?php echo e(request('employment_type') == 'full-time' ? 'selected' : ''); ?>>Full Time</option>
                                <option value="part-time" <?php echo e(request('employment_type') == 'part-time' ? 'selected' : ''); ?>>Part Time</option>
                                <option value="contract" <?php echo e(request('employment_type') == 'contract' ? 'selected' : ''); ?>>Contract</option>
                            </select>
                        </div>

                        <!-- Subject Filter -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <select id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400">
                                <option value="">All Subjects</option>
                                <?php $__currentLoopData = \App\Models\Subject::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>" <?php echo e(request('subject') == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Apply Filters
                            </button>
                            <a href="<?php echo e(route('teachers.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                Clear
                            </a>
                        </div>

                        <div class="flex items-center space-x-2">
                            <!-- View Switcher -->
                            <div class="inline-flex rounded-lg border border-gray-300 bg-white">
                                <button type="button" @click="view = 'cards'" :class="view === 'cards' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="inline-flex items-center px-3 py-2 rounded-l-lg font-medium transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    Cards
                                </button>
                                <button type="button" @click="view = 'table'" :class="view === 'table' ? 'bg-gray-800 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'" class="inline-flex items-center px-3 py-2 rounded-r-lg font-medium transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    Table
                                </button>
                            </div>

                            <button type="button" onclick="exportTeachers('csv')" class="inline-flex items-center px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Teachers</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e(\App\Models\Teacher::count()); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Full Time</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e(\App\Models\Teacher::where('employment_type', 'full-time')->count()); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Part Time</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e(\App\Models\Teacher::where('employment_type', 'part_time')->count()); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Contract</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e(\App\Models\Teacher::where('employment_type', 'contract')->count()); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teachers Content -->
            <?php if($teachers->isEmpty()): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Teachers Found</h3>
                    <p class="text-gray-500 mb-6">Get started by adding your first teacher.</p>
                    <a href="<?php echo e(route('teachers.create')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Teacher
                    </a>
                </div>
            <?php else: ?>
                <!-- Cards View -->
                <div x-show="view === 'cards'" x-transition>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                                <!-- Card Header -->
                                <div class="bg-gray-700 p-4 rounded-t-xl">
                                    <div class="flex items-center space-x-3">
                                        <!-- Avatar -->
                                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-gray-700 font-bold text-xl shadow-md">
                                            <?php echo e(strtoupper(substr($teacher->user->name, 0, 2))); ?>

                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-white font-semibold text-lg truncate"><?php echo e($teacher->user->name); ?></h3>
                                            <p class="text-gray-200 text-sm"><?php echo e($teacher->employee_number); ?></p>
                                        </div>
                                        <!-- Employment Type Badge -->
                                        <div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                                <?php if($teacher->employment_type === 'full_time'): ?> bg-green-100 text-green-800
                                                <?php elseif($teacher->employment_type === 'part_time'): ?> bg-blue-100 text-blue-800
                                                <?php else: ?> bg-orange-100 text-orange-800
                                                <?php endif; ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $teacher->employment_type))); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-4 space-y-3">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate"><?php echo e($teacher->user->email); ?></span>
                                    </div>

                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span><?php echo e($teacher->phone_number ?? 'N/A'); ?></span>
                                    </div>

                                    <?php if($teacher->hire_date): ?>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>Joined: <?php echo e($teacher->hire_date->format('M d, Y')); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php
                                        $subjectsCount = $teacher->subjects()->count();
                                        $streamsCount = $teacher->streams()->count();
                                    ?>

                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">Subjects</p>
                                            <p class="text-lg font-bold text-gray-900"><?php echo e($subjectsCount); ?></p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500">Classes</p>
                                            <p class="text-lg font-bold text-gray-900"><?php echo e($streamsCount); ?></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Footer -->
                                <div class="border-t border-gray-100 px-4 py-3 bg-gray-50 rounded-b-xl">
                                    <div class="flex items-center justify-between space-x-2">
                                        <a href="<?php echo e(route('teachers.show', $teacher)); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>

                                        <a href="<?php echo e(route('teachers.edit', $teacher)); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition-colors text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>

                                        <button onclick="confirmDelete(<?php echo e($teacher->id); ?>)" class="inline-flex items-center justify-center px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 font-medium rounded-lg transition-colors text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        <form id="delete-form-<?php echo e($teacher->id); ?>" action="<?php echo e(route('teachers.destroy', $teacher)); ?>" method="POST" class="hidden">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Table View -->
                <div x-show="view === 'table'" x-transition>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employment Type</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Classes</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-bold text-sm mr-3">
                                                        <?php echo e(strtoupper(substr($teacher->user->name, 0, 2))); ?>

                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900"><?php echo e($teacher->user->name); ?></div>
                                                        <div class="text-sm text-gray-500"><?php echo e($teacher->user->email); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($teacher->employee_number); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo e($teacher->phone_number ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                                    <?php if($teacher->employment_type === 'full_time'): ?> bg-green-100 text-green-800
                                                    <?php elseif($teacher->employment_type === 'part_time'): ?> bg-blue-100 text-blue-800
                                                    <?php else: ?> bg-orange-100 text-orange-800
                                                    <?php endif; ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $teacher->employment_type))); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-purple-600">
                                                <?php echo e($teacher->subjects()->count()); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-semibold text-indigo-600">
                                                <?php echo e($teacher->streams()->count()); ?>

                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="<?php echo e(route('teachers.show', $teacher)); ?>" class="text-gray-600 hover:text-gray-900 font-medium">View</a>
                                                    <a href="<?php echo e(route('teachers.edit', $teacher)); ?>" class="text-gray-600 hover:text-gray-900 font-medium">Edit</a>
                                                    <button onclick="confirmDelete(<?php echo e($teacher->id); ?>)" class="text-red-600 hover:text-red-900 font-medium">Delete</button>
                                                    <form id="delete-form-<?php echo e($teacher->id); ?>" action="<?php echo e(route('teachers.destroy', $teacher)); ?>" method="POST" class="hidden">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mt-6">
                    <?php echo e($teachers->appends(request()->except('page'))->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function confirmDelete(teacherId) {
            if (confirm('Are you sure you want to delete this teacher? This action cannot be undone.')) {
                document.getElementById('delete-form-' + teacherId).submit();
            }
        }

        function exportTeachers(format) {
            const params = new URLSearchParams(window.location.search);
            params.set('export', format);
            window.location.href = '<?php echo e(route("teachers.index")); ?>?' + params.toString();
        }
    </script>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/teachers/index.blade.php ENDPATH**/ ?>