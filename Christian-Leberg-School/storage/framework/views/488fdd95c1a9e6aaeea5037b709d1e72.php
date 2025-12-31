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
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enter Exam Results</h2>
                <p class="text-sm text-gray-600 mt-1"><?php echo e($exam->name); ?> - <?php echo e($exam->academicYear->name ?? 'N/A'); ?></p>
            </div>
            <a href="<?php echo e(route('exams.show', $exam)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Exam
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8" x-data="{
        showImport: false,
        autoCalculateGrades: true,
        showGradePreview: false,
        previewMarks: 0,
        previewGrade: '',
        calculateGrade(marks) {
            if (marks >= 80) return 'A';
            if (marks >= 70) return 'B';
            if (marks >= 60) return 'C';
            if (marks >= 50) return 'D';
            return 'E';
        },
        updatePreview(marks) {
            this.previewMarks = marks;
            this.previewGrade = this.calculateGrade(marks);
            this.showGradePreview = marks !== '';
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

            <!-- Class Filter Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex-1">
                        <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Filter Students by Class</label>
                        <select id="class_id" name="class_id" 
                                onchange="var url = new URL(window.location); url.searchParams.set('class_id', this.value); if(this.value === '') url.searchParams.delete('class_id'); window.location = url.toString();" 
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                            <option value="">All Classes</option>
                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($class->id); ?>" <?php echo e((isset($classId) && $classId == $class->id) ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button @click="showImport = true" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Import CSV
                        </button>
                        <a href="<?php echo e(route('exams.results.index', $exam)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View Results
                        </a>
                    </div>
                </div>
            </div>

            <!-- Results Entry Form -->
            <form method="POST" action="<?php echo e(route('exams.results.store', $exam)); ?>">
                <?php echo csrf_field(); ?>
                
                <?php if($selectedSubjectId): ?>
                    <input type="hidden" name="subject_id" value="<?php echo e($selectedSubjectId); ?>">
                <?php endif; ?>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Enter Results</h3>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($students->count()); ?> students found</p>
                            </div>
                            
                            <!-- Grade Preview -->
                            <div x-show="showGradePreview" x-cloak class="flex items-center gap-3 px-4 py-2 bg-gray-50 rounded-lg border border-gray-200">
                                <span class="text-sm text-gray-600">Preview:</span>
                                <span class="text-2xl font-bold" x-text="previewMarks + '%'"></span>
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg font-bold text-lg"
                                      :class="{
                                          'bg-green-100 text-green-800': previewMarks >= 80,
                                          'bg-blue-100 text-blue-800': previewMarks >= 70 && previewMarks < 80,
                                          'bg-yellow-100 text-yellow-800': previewMarks >= 60 && previewMarks < 70,
                                          'bg-orange-100 text-orange-800': previewMarks >= 50 && previewMarks < 60,
                                          'bg-red-100 text-red-800': previewMarks < 50
                                      }"
                                      x-text="previewGrade"></span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="subject_filter" class="block text-sm font-medium text-gray-700 mb-2">Select Subject <span class="text-red-500">*</span></label>
                            <select id="subject_filter" 
                                    onchange="var url = new URL(window.location); url.searchParams.set('subject_id', this.value); if(this.value === '') url.searchParams.delete('subject_id'); window.location = url.toString();" 
                                    class="w-full md:w-1/2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                <option value="">Choose subject...</option>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>" <?php echo e(($selectedSubjectId ?? '') == $subject->id ? 'selected' : ''); ?>>
                                        <?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <?php if(session('conflicts')): ?>
                            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-6 h-6 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-yellow-800 mb-2">⚠️ Existing Marks Detected</h4>
                                        <p class="text-sm text-yellow-700 mb-3">The following students already have marks entered. To change them, please edit the marks individually or confirm below to overwrite:</p>
                                        <div class="space-y-2 mb-4">
                                            <?php $__currentLoopData = session('conflicts'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conflict): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="bg-white rounded p-3 text-sm">
                                                    <span class="font-semibold text-gray-900"><?php echo e($conflict['student_name']); ?></span>
                                                    <span class="text-gray-600">- Current: <span class="font-bold"><?php echo e($conflict['existing_marks']); ?>%</span></span>
                                                    <span class="text-gray-600">→ New: <span class="font-bold text-yellow-700"><?php echo e($conflict['new_marks']); ?>%</span></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif($selectedSubjectId && $existingResults->count() > 0): ?>
                            <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-blue-800"><?php echo e($existingResults->count()); ?> student(s) already have marks entered</h4>
                                        <p class="text-sm text-blue-700 mt-1">Students with existing marks are highlighted in blue. You can edit their marks directly. Updating existing marks will replace the previous values.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if($students->isEmpty()): ?>
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Students Found</h3>
                            <p class="text-gray-500 mb-6">No students are enrolled for this exam's academic year.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Student</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Current Marks</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Marks (0-100)</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Grade Preview</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $existingMark = $existingResults[$student->id] ?? null;
                                            $hasExisting = $existingMark && $selectedSubjectId;
                                            $currentMarks = old("results.{$loop->index}.marks", $existingMark->marks ?? '');
                                        ?>
                                        <tr class="hover:bg-gray-50 transition-colors <?php echo e($hasExisting ? 'bg-blue-50' : ''); ?>" x-data="{ marks: '<?php echo e($currentMarks); ?>', grade: '<?php echo e($existingMark->grade ?? ''); ?>' }">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-gray-100 rounded-lg">
                                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900"><?php echo e($student->user->name); ?></div>
                                                        <div class="text-xs text-gray-500"><?php echo e($student->admission_number); ?></div>
                                                        <?php if($hasExisting): ?>
                                                            <div class="text-xs text-blue-600 font-semibold mt-1">✓ Already graded</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <?php if($hasExisting): ?>
                                                    <div class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-bold">
                                                        <?php echo e($existingMark->marks); ?>%
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-400 text-sm">—</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4">
                                                <input type="hidden" name="results[<?php echo e($loop->index); ?>][student_id]" value="<?php echo e($student->id); ?>">
                                                <?php if(session('conflicts')): ?>
                                                    <input type="hidden" name="results[<?php echo e($loop->index); ?>][force_update]" value="1">
                                                <?php endif; ?>
                                                <input type="number" 
                                                       name="results[<?php echo e($loop->index); ?>][marks]" 
                                                       x-model="marks"
                                                       @input="grade = calculateGrade($event.target.value); updatePreview($event.target.value)"
                                                       value="<?php echo e(old("results.{$loop->index}.marks", $existingMark->marks ?? '')); ?>"
                                                       class="w-32 mx-auto block px-4 py-2 text-center border rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent <?php echo e($hasExisting ? 'border-blue-300 bg-blue-50' : 'border-gray-300'); ?>" 
                                                       min="0" 
                                                       max="100"
                                                       step="0.01"
                                                       placeholder="0">
                                                <?php $__errorArgs = ["results.{$loop->index}.marks"];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <div class="text-sm text-red-600 mt-1 text-center"><?php echo e($message); ?></div>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span x-show="marks !== ''" 
                                                      class="inline-flex items-center justify-center w-10 h-10 rounded-lg font-bold text-lg transition-colors"
                                                      :class="{
                                                          'bg-green-100 text-green-800': marks >= 80,
                                                          'bg-blue-100 text-blue-800': marks >= 70 && marks < 80,
                                                          'bg-yellow-100 text-yellow-800': marks >= 60 && marks < 70,
                                                          'bg-orange-100 text-orange-800': marks >= 50 && marks < 60,
                                                          'bg-red-100 text-red-800': marks < 50 && marks !== ''
                                                      }"
                                                      x-text="grade"></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Submit Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-600">
                                    <?php if(session('conflicts')): ?>
                                        <span class="font-medium text-yellow-700">⚠️ Warning:</span> Click "Confirm & Overwrite" to update existing marks.
                                    <?php elseif($selectedSubjectId && $existingResults->count() > 0): ?>
                                        <span class="font-medium text-blue-700">✏️ Edit Mode:</span> You can update existing marks. Leave blank to skip a student.
                                    <?php else: ?>
                                        <span class="font-medium">Note:</span> Leave marks blank to skip a student. Grades are auto-calculated.
                                    <?php endif; ?>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <a href="<?php echo e(route('exams.show', $exam)); ?>" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                        Cancel
                                    </a>
                                    <?php if(session('conflicts')): ?>
                                        <button type="submit" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition-colors flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            Confirm & Overwrite All
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="px-6 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg transition-colors">
                                            Save All Results
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- CSV Import Modal -->
        <div x-show="showImport" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showImport" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showImport = false"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75">
                </div>

                <!-- Modal content -->
                <div x-show="showImport"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Import Results from CSV</h3>
                            <button @click="showImport = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-sm text-blue-800">
                                    <p class="font-medium mb-1">CSV Format Requirements</p>
                                    <p>Your CSV file must have these columns: <strong>admission_number, subject_code (or subject_id), marks, remarks</strong></p>
                                    <p class="mt-2">You can also select a subject below to apply it to all rows instead of using subject_code in the CSV.</p>
                                </div>
                            </div>
                        </div>

                        <form action="<?php echo e(route('exams.results.import', $exam)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Subject (optional)</label>
                                    <select name="subject_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-400 focus:border-transparent">
                                        <option value="">(use subject_code column in CSV)</option>
                                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?> (<?php echo e($subject->code); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">CSV File <span class="text-red-500">*</span></label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-gray-800 hover:text-gray-900 focus-within:outline-none">
                                                    <span>Upload a file</span>
                                                    <input id="file-upload" name="file" type="file" accept=".csv,text/csv" required class="sr-only">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">CSV up to 10MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                                <a href="<?php echo e(route('exams.results.sample_csv', $exam)); ?>" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download Sample CSV
                                </a>

                                <div class="flex items-center space-x-3">
                                    <button type="button" @click="showImport = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                                        Import Results
                                    </button>
                                </div>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/exams/results/create.blade.php ENDPATH**/ ?>