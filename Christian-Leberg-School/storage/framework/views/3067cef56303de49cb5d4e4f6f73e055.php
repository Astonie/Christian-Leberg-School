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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Grading Scales Management</h2>
            <button onclick="document.getElementById('addScaleForm').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Grading Scale
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Warning Messages -->
            <?php if(session('success')): ?>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-green-800 font-medium"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('warning')): ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium"><?php echo e(session('warning')); ?></span>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <?php
                $totalScales = $scales->count();
                $systemsCount = $scales->pluck('grading_system_id')->unique()->count();
                $avgPoints = $scales->avg('grade_point') ?? 0;
            ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Scales</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($totalScales); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Grading Systems</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($systemsCount); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Average Points</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e(number_format($avgPoints, 1)); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grading Scales Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Current Grading Scales</h3>
                    <p class="text-sm text-gray-600 mt-1">Manage and configure grading scales for your institution</p>
                </div>

                <?php if($scales->count() > 0): ?>
                    <?php
                        $groupedScales = $scales->groupBy('grading_system_id');
                    ?>

                    <?php $__currentLoopData = $groupedScales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $systemId => $systemScales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $system = $systemScales->first()->gradingSystem;
                        ?>
                        
                        <div class="border-b border-gray-200 last:border-0">
                            <div class="bg-gray-50 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900"><?php echo e($system?->name ?? 'Default System'); ?></h4>
                                        <p class="text-sm text-gray-600 mt-1"><?php echo e($systemScales->count()); ?> grade levels configured</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-800">
                                        <?php echo e($system?->type ?? 'Standard'); ?>

                                    </span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Range</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Label</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        <?php $__currentLoopData = $systemScales->sortByDesc('min_percentage'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg font-bold text-lg
                                                        <?php if(($scale->min_percentage ?? 0) >= 80): ?> bg-green-100 text-green-800
                                                        <?php elseif(($scale->min_percentage ?? 0) >= 70): ?> bg-blue-100 text-blue-800
                                                        <?php elseif(($scale->min_percentage ?? 0) >= 60): ?> bg-yellow-100 text-yellow-800
                                                        <?php elseif(($scale->min_percentage ?? 0) >= 50): ?> bg-orange-100 text-orange-800
                                                        <?php else: ?> bg-red-100 text-red-800
                                                        <?php endif; ?>">
                                                        <?php echo e($scale->code ?? substr($scale->label, 0, 1)); ?>

                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="flex items-center justify-center">
                                                        <span class="text-sm font-semibold text-gray-900"><?php echo e($scale->min_percentage ?? $scale->min_score); ?>%</span>
                                                        <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                        </svg>
                                                        <span class="text-sm font-semibold text-gray-900"><?php echo e($scale->max_percentage ?? $scale->max_score); ?>%</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-900"><?php echo e($scale->label); ?></span>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="text-sm text-gray-600"><?php echo e($scale->remark); ?></span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-900">
                                                        <?php echo e($scale->grade_point ?? $scale->points); ?>

                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <button onclick="editScale(<?php echo e($scale->id); ?>, '<?php echo e($scale->min_percentage ?? $scale->min_score); ?>', '<?php echo e($scale->max_percentage ?? $scale->max_score); ?>', '<?php echo e($scale->label); ?>', '<?php echo e($scale->remark); ?>', '<?php echo e($scale->grade_point ?? $scale->points); ?>')" class="text-gray-600 hover:text-gray-900 font-medium">
                                                            Edit
                                                        </button>
                                                        <form action="<?php echo e(route('admin.grading_scales.destroy', $scale)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this grading scale?')">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Grading Scales Found</h3>
                        <p class="text-gray-500 mb-6">Get started by adding your first grading scale below.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Add/Edit Grading Scale Form -->
            <div id="addScaleForm" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Grading Scale</h3>
                
                <form action="<?php echo e(route('admin.grading_scales.store')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php if($systems->count()): ?>
                            <div>
                                <label for="grading_system_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Grading System <span class="text-red-500">*</span>
                                </label>
                                <select id="grading_system_id" name="grading_system_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                    <option value="">Select System</option>
                                    <?php $__currentLoopData = $systems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $system): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($system->id); ?>"><?php echo e($system->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <div>
                            <label for="min_percentage" class="block text-sm font-medium text-gray-700 mb-1">
                                Minimum Percentage <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="min_percentage" name="min_percentage" step="0.01" min="0" max="100" required class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                            </div>
                        </div>

                        <div>
                            <label for="max_percentage" class="block text-sm font-medium text-gray-700 mb-1">
                                Maximum Percentage <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="max_percentage" name="max_percentage" step="0.01" min="0" max="100" required class="w-full pl-3 pr-8 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="100">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                            </div>
                        </div>

                        <div>
                            <label for="label" class="block text-sm font-medium text-gray-700 mb-1">
                                Grade Label <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="label" name="label" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="e.g., A, B+, Excellent">
                        </div>

                        <div>
                            <label for="remark" class="block text-sm font-medium text-gray-700 mb-1">
                                Remark <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="remark" name="remark" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="e.g., Excellent, Good">
                        </div>

                        <div>
                            <label for="grade_point" class="block text-sm font-medium text-gray-700 mb-1">
                                Grade Points <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="grade_point" name="grade_point" step="0.01" min="0" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="e.g., 4.0">
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="reset" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            Reset
                        </button>
                        <button type="submit" class="px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                            Add Grading Scale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function editScale(id, min, max, label, remark, points) {
            // Scroll to form
            document.getElementById('addScaleForm').scrollIntoView({behavior: 'smooth'});
            
            // Populate form fields
            document.getElementById('min_percentage').value = min;
            document.getElementById('max_percentage').value = max;
            document.getElementById('label').value = label;
            document.getElementById('remark').value = remark;
            document.getElementById('grade_point').value = points;
            
            // Change form title
            document.querySelector('#addScaleForm h3').textContent = 'Edit Grading Scale';
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
<?php endif; ?><?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/admin/grading_scales/index.blade.php ENDPATH**/ ?>