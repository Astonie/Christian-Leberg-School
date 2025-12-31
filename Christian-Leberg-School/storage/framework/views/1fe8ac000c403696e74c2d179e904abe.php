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
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Exam Results Management</h2>
                <p class="text-sm text-gray-600 mt-1"><?php echo e($exam->name); ?> - <?php echo e($exam->academicYear->name ?? 'N/A'); ?></p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="<?php echo e(route('exams.results.create', $exam)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Enter Results
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8" x-data="{
        selectedSubject: '<?php echo e($selectedSubject ?? ''); ?>',
        selectedClass: '<?php echo e($selectedClass ?? ''); ?>',
        showBulkEdit: false,
        selectedResults: [],
        bulkMarks: '',
        filter() {
            let url = new URL(window.location.href);
            if (this.selectedSubject) url.searchParams.set('subject_id', this.selectedSubject);
            else url.searchParams.delete('subject_id');
            if (this.selectedClass) url.searchParams.set('class_id', this.selectedClass);
            else url.searchParams.delete('class_id');
            window.location = url.toString();
        },
        toggleAll(checked) {
            if (checked) {
                this.selectedResults = Array.from(document.querySelectorAll('input[name=result_ids]')).map(cb => parseInt(cb.value));
            } else {
                this.selectedResults = [];
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success/Error Messages -->
            <?php if (isset($component)) { $__componentOriginalc5711d836f933e61eafca8928e9a27a5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc5711d836f933e61eafca8928e9a27a5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.alerts','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('alerts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $attributes = $__attributesOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__attributesOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc5711d836f933e61eafca8928e9a27a5)): ?>
<?php $component = $__componentOriginalc5711d836f933e61eafca8928e9a27a5; ?>
<?php unset($__componentOriginalc5711d836f933e61eafca8928e9a27a5); ?>
<?php endif; ?>

            <!-- Statistics Cards -->
            <?php
                $totalResults = $results->count();
                $studentsWithResults = $results->pluck('student_id')->unique()->count();
                $subjectsWithResults = $results->pluck('subject_id')->unique()->count();
                $averageScore = $results->avg('marks');
                $highestScore = $results->max('marks');
                $lowestScore = $results->min('marks');
            ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Results</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($totalResults); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Average Score</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e(number_format($averageScore, 1)); ?>%</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Students</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($studentsWithResults); ?></p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Subjects</p>
                            <p class="text-3xl font-bold text-gray-900 mt-1"><?php echo e($subjectsWithResults); ?></p>
                        </div>
                        <div class="bg-gray-100 rounded-full p-3">
                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div>
                            <label for="filter_subject" class="block text-xs font-medium text-gray-700 mb-1">Filter by Subject</label>
                            <select x-model="selectedSubject" @change="filter()" id="filter_subject" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                <option value="">All Subjects</option>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div>
                            <label for="filter_class" class="block text-xs font-medium text-gray-700 mb-1">Filter by Class</label>
                            <select x-model="selectedClass" @change="filter()" id="filter_class" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                <option value="">All Classes</option>
                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <form method="GET" action="<?php echo e(route('exams.results.export', $exam)); ?>" class="inline">
                            <input type="hidden" name="subject_id" :value="selectedSubject">
                            <input type="hidden" name="class_id" :value="selectedClass">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export CSV
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Results Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Examination Results</h3>
                            <p class="text-sm text-gray-600 mt-1">View and manage student exam results</p>
                        </div>
                        
                        <template x-if="selectedResults.length > 0">
                            <button @click="showBulkEdit = true" class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Bulk Edit (<span x-text="selectedResults.length"></span>)
                            </button>
                        </template>
                    </div>
                </div>

                <?php if($results->isEmpty()): ?>
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Results Found</h3>
                        <p class="text-gray-500 mb-6">No examination results have been entered yet.</p>
                        <a href="<?php echo e(route('exams.results.create', $exam)); ?>" class="inline-flex items-center px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Enter First Results
                        </a>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left">
                                        <input type="checkbox" @change="toggleAll($event.target.checked)" class="rounded border-gray-300 text-gray-800 focus:ring-gray-400">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <input type="checkbox" name="result_ids" value="<?php echo e($result->id); ?>" x-model="selectedResults" class="rounded border-gray-300 text-gray-800 focus:ring-gray-400">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-100 rounded-lg">
                                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900"><?php echo e($result->student->user->name); ?></div>
                                                    <div class="text-xs text-gray-500"><?php echo e($result->student->admission_number); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-800">
                                                <?php echo e($result->subject->name); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div x-data="{ editing: false, marks: <?php echo e($result->marks); ?> }">
                                                <span x-show="!editing" @click="editing = true" class="text-lg font-bold cursor-pointer
                                                    <?php if($result->marks >= 80): ?> text-green-600
                                                    <?php elseif($result->marks >= 70): ?> text-blue-600
                                                    <?php elseif($result->marks >= 60): ?> text-yellow-600
                                                    <?php elseif($result->marks >= 50): ?> text-orange-600
                                                    <?php else: ?> text-red-600
                                                    <?php endif; ?>">
                                                    <?php echo e($result->marks); ?>%
                                                </span>
                                                <form x-show="editing" @submit.prevent="$el.submit()" action="<?php echo e(route('exam-results.update', $result)); ?>" method="POST" class="inline-flex items-center gap-2">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <input type="number" name="marks" x-model="marks" min="0" max="100" class="w-20 px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400" @blur="editing = false">
                                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg font-bold text-lg
                                                <?php if($result->marks >= 80): ?> bg-green-100 text-green-800
                                                <?php elseif($result->marks >= 70): ?> bg-blue-100 text-blue-800
                                                <?php elseif($result->marks >= 60): ?> bg-yellow-100 text-yellow-800
                                                <?php elseif($result->marks >= 50): ?> bg-orange-100 text-orange-800
                                                <?php else: ?> bg-red-100 text-red-800
                                                <?php endif; ?>">
                                                <?php echo e($result->grade); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="<?php echo e(route('students.show', $result->student)); ?>" class="text-gray-600 hover:text-gray-900 font-medium">
                                                View Student
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bulk Edit Modal -->
        <div x-show="showBulkEdit" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showBulkEdit" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showBulkEdit = false"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75">
                </div>

                <!-- Modal content -->
                <div x-show="showBulkEdit"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Bulk Edit Results</h3>
                            <button @click="showBulkEdit = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mb-6">Update marks for <span x-text="selectedResults.length"></span> selected result(s)</p>
                        
                        <form action="<?php echo e(route('exams.results.bulk_update', $exam)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="result_ids" :value="JSON.stringify(selectedResults)">
                            <template x-for="id in selectedResults" :key="id">
                                <input type="hidden" name="result_ids[]" :value="id">
                            </template>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">New Marks</label>
                                    <input type="number" x-model="bulkMarks" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent" placeholder="Enter marks (0-100)">
                                </div>
                                
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                        <div class="text-sm text-yellow-800">
                                            <p class="font-medium">Warning</p>
                                            <p>This will update all selected results with the same marks value. This action cannot be undone.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                                <button type="button" @click="showBulkEdit = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" class="px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition-colors">
                                    Update Results
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/exams/results/index.blade.php ENDPATH**/ ?>