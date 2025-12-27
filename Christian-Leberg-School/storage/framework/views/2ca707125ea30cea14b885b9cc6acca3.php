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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Assessment Structure: <?php echo e($assessmentStructure->name); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if($errors->any()): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc list-inside">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="<?php echo e(route('assessment-structures.update', $assessmentStructure)); ?>" id="assessmentForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Basic Information -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold mb-4">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                                    <input type="text" name="name" id="name" value="<?php echo e(old('name', $assessmentStructure->name)); ?>" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="version" class="block text-sm font-medium text-gray-700">Version</label>
                                    <input type="number" name="version" id="version" value="<?php echo e(old('version', $assessmentStructure->version)); ?>" min="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject (Optional)</label>
                                    <select name="subject_id" id="subject_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">All Subjects</option>
                                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id', $assessmentStructure->subject_id) == $subject->id ? 'selected' : ''); ?>>
                                                <?php echo e($subject->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div>
                                    <label for="grading_system_id" class="block text-sm font-medium text-gray-700">Grading System</label>
                                    <select name="grading_system_id" id="grading_system_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Default</option>
                                        <?php $__currentLoopData = $gradingSystems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $system): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($system->id); ?>" <?php echo e(old('grading_system_id', $assessmentStructure->grading_system_id) == $system->id ? 'selected' : ''); ?>>
                                                <?php echo e($system->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo e(old('description', $assessmentStructure->description)); ?></textarea>
                            </div>
                        </div>

                        <!-- Assessment Components -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Assessment Components</h3>
                                <div class="flex items-center space-x-2">
                                    <select id="template_select" onchange="loadTemplate(this.value)" 
                                            class="text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Load Template...</option>
                                        <option value="kcse_standard">KCSE Standard (CAT 20% + MID 30% + EXAM 50%)</option>
                                        <option value="coursework_heavy">Coursework Heavy (5 components)</option>
                                        <option value="exam_focused">Exam Focused (CA 30% + EXAM 70%)</option>
                                    </select>
                                    <button type="button" onclick="addComponent()" 
                                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm inline-flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Component
                                    </button>
                                </div>
                            </div>

                            <div id="componentsContainer" class="space-y-4">
                                <!-- Components will be loaded here -->
                            </div>

                            <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="h-6 w-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <span class="font-semibold text-gray-700">Total Weight:</span>
                                            <span id="totalWeight" class="ml-2 text-2xl font-bold">0%</span>
                                        </div>
                                    </div>
                                    <div id="weightWarning" class="hidden flex items-center text-red-600 font-medium">
                                        <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        Must equal 100%
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="<?php echo e(route('assessment-structures.index')); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Structure
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let componentIndex = 0;
        const componentTemplates = {
            'kcse_standard': [
                { name: 'Continuous Assessment Tests', code: 'CAT', weight: 20, max_score: 20, description: 'Regular class tests throughout the term' },
                { name: 'Mid-Term Examination', code: 'MID', weight: 30, max_score: 30, description: 'Mid-term examination assessment' },
                { name: 'End Term Examination', code: 'EXAM', weight: 50, max_score: 50, description: 'Final term examination' }
            ],
            'coursework_heavy': [
                { name: 'Homework Assignments', code: 'HW', weight: 15, max_score: 15, description: 'Regular homework assignments' },
                { name: 'Class Participation', code: 'PART', weight: 10, max_score: 10, description: 'Active class participation' },
                { name: 'Project Work', code: 'PROJ', weight: 25, max_score: 25, description: 'Term project assessment' },
                { name: 'Tests & Quizzes', code: 'TEST', weight: 20, max_score: 20, description: 'Regular tests and quizzes' },
                { name: 'Final Examination', code: 'EXAM', weight: 30, max_score: 30, description: 'End of term examination' }
            ],
            'exam_focused': [
                { name: 'Continuous Assessment', code: 'CA', weight: 30, max_score: 30, description: 'Overall continuous assessment' },
                { name: 'Final Examination', code: 'EXAM', weight: 70, max_score: 70, description: 'Final examination' }
            ]
        };

        // Load existing components
        const existingComponents = <?php echo json_encode($assessmentStructure->components, 15, 512) ?>;

        function loadTemplate(templateName) {
            if (!templateName) return;
            
            if (!confirm('This will replace all current components. Continue?')) {
                document.getElementById('template_select').value = '';
                return;
            }
            
            // Clear existing components
            document.getElementById('componentsContainer').innerHTML = '';
            componentIndex = 0;
            
            // Load template
            const template = componentTemplates[templateName];
            template.forEach(comp => {
                addComponent(comp);
            });
            
            // Reset select
            document.getElementById('template_select').value = '';
            calculateTotalWeight();
        }

        function addComponent(data = null) {
            const container = document.getElementById('componentsContainer');
            const componentHtml = `
                <div class="border-2 border-gray-300 rounded-lg p-5 component-item bg-white hover:border-blue-400 transition" data-index="${componentIndex}">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2">
                            <div class="cursor-move handle bg-gray-200 px-2 py-1 rounded">
                                <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-semibold text-gray-900">Component ${componentIndex + 1}</h4>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="duplicateComponent(this)" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                            <button type="button" onclick="removeComponent(this)" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                <svg class="h-5 w-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name *</label>
                            <input type="text" name="components[${componentIndex}][name]" value="${data?.name || ''}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Code *</label>
                            <input type="text" name="components[${componentIndex}][code]" value="${data?.code || ''}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Weight (%) *</label>
                            <div class="relative mt-1">
                                <input type="number" name="components[${componentIndex}][weight]" value="${data?.weight || ''}" min="0" max="100" step="0.1" required
                                    class="weight-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-8"
                                    onchange="calculateTotalWeight()" oninput="updateWeightPreview(this)">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">%</span>
                                </div>
                            </div>
                            <div class="mt-1">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="weight-preview bg-blue-600 h-2 rounded-full transition-all" style="width: ${data?.weight || 0}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max Score *</label>
                            <input type="number" name="components[${componentIndex}][max_score]" value="${data?.max_score || ''}" min="0" step="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Display Order *</label>
                            <input type="number" name="components[${componentIndex}][order]" value="${data?.order !== undefined ? data.order : componentIndex}" min="0" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="components[${componentIndex}][description]" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">${data?.description || ''}</textarea>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', componentHtml);
            componentIndex++;
        }

        function duplicateComponent(button) {
            const component = button.closest('.component-item');
            const inputs = component.querySelectorAll('input, textarea');
            const data = {};
            
            inputs.forEach(input => {
                const name = input.name.match(/\[(\w+)\]$/)?.[1];
                if (name) data[name] = input.value;
            });
            
            addComponent(data);
            calculateTotalWeight();
        }

        function removeComponent(button) {
            if (document.querySelectorAll('.component-item').length === 1) {
                alert('You must have at least one component.');
                return;
            }
            button.closest('.component-item').remove();
            calculateTotalWeight();
        }

        function updateWeightPreview(input) {
            const preview = input.closest('div').nextElementSibling.querySelector('.weight-preview');
            preview.style.width = (input.value || 0) + '%';
        }

        function calculateTotalWeight() {
            const weightInputs = document.querySelectorAll('.weight-input');
            let total = 0;
            
            weightInputs.forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            
            const totalDisplay = document.getElementById('totalWeight');
            totalDisplay.textContent = total.toFixed(1) + '%';
            
            const warning = document.getElementById('weightWarning');
            const submitBtn = document.querySelector('button[type="submit"]');
            
            if (Math.abs(total - 100) > 0.01 && total > 0) {
                warning.classList.remove('hidden');
                totalDisplay.classList.add('text-red-600');
                totalDisplay.classList.remove('text-green-600');
                submitBtn.disabled = false;
            } else if (total === 100) {
                warning.classList.add('hidden');
                totalDisplay.classList.remove('text-red-600');
                totalDisplay.classList.add('text-green-600');
                submitBtn.disabled = false;
            } else {
                warning.classList.add('hidden');
                totalDisplay.classList.remove('text-red-600', 'text-green-600');
                submitBtn.disabled = false;
            }
        }

        // Load existing components on page load
        document.addEventListener('DOMContentLoaded', function() {
            existingComponents.forEach(comp => {
                addComponent(comp);
            });
            calculateTotalWeight();
        });
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/assessment-structures/edit.blade.php ENDPATH**/ ?>