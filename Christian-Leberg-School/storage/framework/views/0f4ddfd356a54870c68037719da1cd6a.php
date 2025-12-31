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
        <div class="flex items-center">
            <a href="<?php echo e(route('teacher-assessments.index')); ?>" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Create Assessment</h2>
                <p class="text-sm text-gray-600 mt-1">Create a test, quiz, assignment, or other assessment for your students</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="container-mobile section-spacing">
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

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8">
                <form action="<?php echo e(route('teacher-assessments.store')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <!-- Academic Year & Term -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="academic_year_id" class="block text-sm font-semibold text-gray-700 mb-2">Academic Year <span class="text-red-500">*</span></label>
                            <select id="academic_year_id" name="academic_year_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Academic Year</option>
                                <?php $__currentLoopData = $academicYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>" <?php echo e(old('academic_year_id') == $year->id ? 'selected' : ''); ?>>
                                        <?php echo e($year->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['academic_year_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="term_id" class="block text-sm font-semibold text-gray-700 mb-2">Term <span class="text-red-500">*</span></label>
                            <select id="term_id" name="term_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Term</option>
                                <?php $__currentLoopData = $terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($term->id); ?>" <?php echo e(old('term_id') == $term->id ? 'selected' : ''); ?>>
                                        <?php echo e($term->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['term_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Assessment Name & Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required placeholder="e.g., Mid-Term Quiz, Chapter 5 Test" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="assessment_type" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Type <span class="text-red-500">*</span></label>
                            <select id="assessment_type" name="assessment_type" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Type</option>
                                <option value="test" <?php echo e(old('assessment_type') == 'test' ? 'selected' : ''); ?>>Test</option>
                                <option value="quiz" <?php echo e(old('assessment_type') == 'quiz' ? 'selected' : ''); ?>>Quiz</option>
                                <option value="assignment" <?php echo e(old('assessment_type') == 'assignment' ? 'selected' : ''); ?>>Assignment</option>
                                <option value="practical" <?php echo e(old('assessment_type') == 'practical' ? 'selected' : ''); ?>>Practical</option>
                                <option value="project" <?php echo e(old('assessment_type') == 'project' ? 'selected' : ''); ?>>Project</option>
                                <option value="presentation" <?php echo e(old('assessment_type') == 'presentation' ? 'selected' : ''); ?>>Presentation</option>
                                <option value="classwork" <?php echo e(old('assessment_type') == 'classwork' ? 'selected' : ''); ?>>Classwork</option>
                                <option value="homework" <?php echo e(old('assessment_type') == 'homework' ? 'selected' : ''); ?>>Homework</option>
                            </select>
                            <?php $__errorArgs = ['assessment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Subject & Weight -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="subject_id" class="block text-sm font-semibold text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                            <select id="subject_id" name="subject_id" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select Subject</option>
                                <?php $__currentLoopData = $teacherSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id') == $subject->id ? 'selected' : ''); ?>>
                                        <?php echo e($subject->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-gray-500 mt-1">Only subjects you teach are listed</p>
                        </div>

                        <div>
                            <label for="weight_percentage" class="block text-sm font-semibold text-gray-700 mb-2">Weight (%) <span class="text-red-500">*</span></label>
                            <input type="number" id="weight_percentage" name="weight_percentage" value="<?php echo e(old('weight_percentage', 10)); ?>" required min="5" max="100" step="0.01" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <?php $__errorArgs = ['weight_percentage'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-gray-500 mt-1">Contribution to final grade (5-100%)</p>
                        </div>
                    </div>

                    <!-- Classes -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Classes <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <?php $__currentLoopData = $teacherClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center space-x-3 cursor-pointer hover:bg-white p-3 rounded-lg transition-colors">
                                    <input type="checkbox" name="classes[]" value="<?php echo e($class->id); ?>" <?php echo e((is_array(old('classes')) && in_array($class->id, old('classes'))) ? 'checked' : ''); ?> class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-medium text-gray-700"><?php echo e($class->name); ?></span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['classes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Assessment Date & Deadline -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">Assessment Date <span class="text-red-500">*</span></label>
                            <input type="date" id="start_date" name="start_date" value="<?php echo e(old('start_date')); ?>" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-gray-500 mt-1">When will this assessment take place?</p>
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="<?php echo e(old('end_date')); ?>" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <p class="text-xs text-gray-500 mt-1">Optional: for multi-day assessments</p>
                        </div>
                    </div>

                    <!-- Results Entry Period -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Results Entry Period</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="results_entry_start_date" class="block text-sm font-semibold text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                                <input type="date" id="results_entry_start_date" name="results_entry_start_date" value="<?php echo e(old('results_entry_start_date')); ?>" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <?php $__errorArgs = ['results_entry_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="results_entry_end_date" class="block text-sm font-semibold text-gray-700 mb-2">End Date <span class="text-red-500">*</span></label>
                                <input type="date" id="results_entry_end_date" name="results_entry_end_date" value="<?php echo e(old('results_entry_end_date')); ?>" required class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                <?php $__errorArgs = ['results_entry_end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-yellow-800 mb-1">Entry Period Controls</p>
                                    <p class="text-sm text-yellow-700">
                                        After the end date, the system will automatically lock mark entry. Make sure to set a reasonable deadline for completing grading.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Marks (Optional) -->
                    <div>
                        <label for="total_marks" class="block text-sm font-semibold text-gray-700 mb-2">Total Marks (Optional)</label>
                        <input type="number" id="total_marks" name="total_marks" value="<?php echo e(old('total_marks')); ?>" min="1" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                        <?php $__errorArgs = ['total_marks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <p class="text-xs text-gray-500 mt-1">Leave blank if not applicable</p>
                    </div>

                    <!-- Description (Optional) -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description (Optional)</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Additional notes or instructions for this assessment"><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                        <a href="<?php echo e(route('teacher-assessments.index')); ?>" class="px-6 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-semibold transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                            Create Assessment
                        </button>
                    </div>
                </form>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/teacher-assessments/create.blade.php ENDPATH**/ ?>