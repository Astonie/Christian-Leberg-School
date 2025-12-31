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
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">My Assessments</h2>
                <p class="text-sm text-gray-600 mt-2">Create and manage your own tests, quizzes, assignments, and other assessments</p>
            </div>
            <a href="<?php echo e(route('teacher-assessments.create')); ?>" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Assessment
            </a>
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

        <!-- Info Banner -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-bold text-blue-800 mb-1">Create Your Own Assessments</h4>
                    <p class="text-sm text-blue-700">
                        Create tests, quizzes, assignments, practicals, and more for your subjects. Set their weight contribution to final grades and manage deadlines independently.
                    </p>
                </div>
            </div>
        </div>

        <?php if($assessments->isNotEmpty()): ?>
            <!-- Assessments Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-200">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1"><?php echo e($assessment->name); ?></h3>
                                    <?php if($assessment->subjects->first()): ?>
                                        <p class="text-sm text-blue-700 font-medium"><?php echo e($assessment->subjects->first()->name); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php
                                    $status = $assessment->getResultsEntryStatus();
                                ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                    <?php if($status['status'] === 'open'): ?> bg-green-100 text-green-800
                                    <?php elseif($status['status'] === 'locked'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800
                                    <?php endif; ?>">
                                    <?php if($status['status'] === 'open'): ?> 🟢 Open
                                    <?php elseif($status['status'] === 'locked'): ?> 🔒 Locked
                                    <?php else: ?> ⚪ Closed
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 font-medium">Term</p>
                                    <p class="text-gray-900 font-semibold"><?php echo e($assessment->term->name); ?></p>
                                </div>
                                <?php if($assessment->weight_percentage): ?>
                                    <div>
                                        <p class="text-gray-500 font-medium">Weight</p>
                                        <p class="text-blue-700 font-bold"><?php echo e($assessment->weight_percentage); ?>%</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 font-medium">Assessment Date</p>
                                    <p class="text-gray-900"><?php echo e($assessment->start_date->format('M d, Y')); ?></p>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium">Entry Deadline</p>
                                    <p class="text-gray-900"><?php echo e($assessment->results_entry_end_date->format('M d, Y')); ?></p>
                                </div>
                            </div>

                            <?php if($assessment->classes->isNotEmpty()): ?>
                                <div>
                                    <p class="text-gray-500 font-medium text-sm mb-2">Classes</p>
                                    <div class="flex flex-wrap gap-2">
                                        <?php $__currentLoopData = $assessment->classes->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded">
                                                <?php echo e($class->name); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($assessment->classes->count() > 3): ?>
                                            <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded">
                                                +<?php echo e($assessment->classes->count() - 3); ?> more
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="pt-4 border-t border-gray-200 flex items-center gap-2">
                                <a href="<?php echo e(route('teacher-assessments.show', $assessment)); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                <?php if(!$assessment->isResultsEntryLocked()): ?>
                                    <a href="<?php echo e(route('exams.results.create', $assessment)); ?>" class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Enter Marks
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                <?php echo e($assessments->links()); ?>

            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-12 text-center">
                <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Assessments Yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    You haven't created any assessments yet. Create tests, quizzes, assignments, and other assessments for your subjects.
                </p>
                <a href="<?php echo e(route('teacher-assessments.create')); ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Your First Assessment
                </a>
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/teacher-assessments/index.blade.php ENDPATH**/ ?>