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
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('admin.grading_systems.index')); ?>" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <?php echo e($system->name); ?>

                </h2>
                <?php if($system->is_active): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Active System
                    </span>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- System Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">System Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?php echo e($system->name); ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-mono"><?php echo e($system->slug); ?></dd>
                    </div>
                    <?php if($system->description): ?>
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900"><?php echo e($system->description); ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

            <!-- Grading Scale Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Grading Scale</h3>
                        <p class="text-sm text-gray-500 mt-1"><?php echo e($system->scales->count()); ?> grades defined</p>
                    </div>
                    <button onclick="openAddScaleModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Grade
                    </button>
                </div>

                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $system->scales->sortBy('order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="flex items-center justify-center w-14 h-14 bg-indigo-100 rounded-xl">
                                    <span class="text-lg font-bold text-indigo-700"><?php echo e($scale->code); ?></span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-base font-semibold text-gray-900"><?php echo e($scale->label); ?></p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium"><?php echo e($scale->min_score); ?>%</span> - 
                                        <span class="font-medium"><?php echo e($scale->max_score); ?>%</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <?php if($scale->points): ?>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-indigo-600"><?php echo e($scale->points); ?></p>
                                        <p class="text-xs text-gray-500">points</p>
                                    </div>
                                <?php endif; ?>
                                <div class="flex gap-2">
                                    <button onclick="editScale(<?php echo e($scale->id); ?>, '<?php echo e($scale->code); ?>', '<?php echo e($scale->label); ?>', <?php echo e($scale->min_score); ?>, <?php echo e($scale->max_score); ?>, <?php echo e($scale->points); ?>, <?php echo e($scale->order ?? 0); ?>)" 
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <form action="<?php echo e(route('admin.grading_scales.destroy', $scale)); ?>" method="POST" onsubmit="return confirm('Delete this grade scale?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-lg font-medium mb-2">No grades defined yet</p>
                            <p class="text-sm mb-4">Add grade scales to complete this grading system</p>
                            <button onclick="openAddScaleModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add First Grade
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Example Usage Card -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">How it works</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <p>When this system is active, all exam results will automatically be graded according to these scales. For example:</p>
                            <ul class="list-disc list-inside mt-2 space-y-1">
                                <?php
                                    $examples = $system->scales->sortBy('order')->take(3);
                                ?>
                                <?php $__currentLoopData = $examples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>A student scoring <?php echo e($scale->min_score); ?>%-<?php echo e($scale->max_score); ?>% will receive grade <strong><?php echo e($scale->code); ?></strong> (<?php echo e($scale->label); ?>)</li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Scale Modal -->
    <div id="scaleModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4">
            <form id="scaleForm" method="POST" action="<?php echo e(route('admin.grading_scales.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="grading_system_id" value="<?php echo e($system->id); ?>">
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Add Grade Scale</h3>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grade Code *</label>
                            <input type="text" name="code" id="code" required 
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="e.g., A, B+, 1">
                            <p class="mt-1 text-xs text-gray-500">The grade displayed (e.g., A, B+, 1, 2)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Label *</label>
                            <input type="text" name="label" id="label" required 
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="e.g., Excellent">
                            <p class="mt-1 text-xs text-gray-500">Description of the grade</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Score (%) *</label>
                            <input type="number" name="min_score" id="min_score" required step="0.01" min="0" max="100"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Lowest mark for this grade</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Score (%) *</label>
                            <input type="number" name="max_score" id="max_score" required step="0.01" min="0" max="100"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Highest mark for this grade</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Points *</label>
                            <input type="number" name="points" id="points" required step="0.01"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Point value for calculations</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order *</label>
                            <input type="number" name="order" id="order" required min="1"
                                class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Display order (1 = first)</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-xl">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">
                        <span id="submitText">Add Grade</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddScaleModal() {
            document.getElementById('scaleModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Add Grade Scale';
            document.getElementById('submitText').textContent = 'Add Grade';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('scaleForm').action = '<?php echo e(route("admin.grading_scales.store")); ?>';
            document.getElementById('scaleForm').reset();
        }

        function editScale(id, code, label, minScore, maxScore, points, order) {
            document.getElementById('scaleModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Edit Grade Scale';
            document.getElementById('submitText').textContent = 'Update Grade';
            document.getElementById('code').value = code;
            document.getElementById('label').value = label;
            document.getElementById('min_score').value = minScore;
            document.getElementById('max_score').value = maxScore;
            document.getElementById('points').value = points;
            document.getElementById('order').value = order;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('scaleForm').action = `/admin/grading-scales/${id}`;
        }

        function closeModal() {
            document.getElementById('scaleModal').classList.add('hidden');
        }
    </script>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/admin/grading_systems/show.blade.php ENDPATH**/ ?>