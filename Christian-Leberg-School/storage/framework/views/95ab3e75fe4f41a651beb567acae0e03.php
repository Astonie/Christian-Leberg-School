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
            <?php echo e(__('Academic Years')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            <?php if(session('success')): ?>
                <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-green-800">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e(session('success')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-red-800">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e(session('error')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('warning')): ?>
                <div class="mb-4 rounded-md bg-yellow-50 border border-yellow-200 p-4 text-yellow-800">
                    <div class="flex">
                        <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">Warning:</span> <?php echo e(session('warning')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <!-- Actions -->
            <div class="mb-4 flex justify-between items-center">
                <div>
                    <a href="<?php echo e(route('academic-years.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add New Academic Year
                    </a>
                </div>
                <div>
                    <?php if($showArchived ?? false): ?>
                        <a href="<?php echo e(route('academic-years.index')); ?>" class="text-gray-600 hover:text-gray-900 px-4 py-2 border border-gray-300 rounded">
                            ← Back to Active Years
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('academic-years.index', ['archived' => 1])); ?>" class="text-gray-600 hover:text-gray-900 px-4 py-2 border border-gray-300 rounded">
                            View Archived Years
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <?php if($years->isEmpty()): ?>
                        <div class="text-center py-8 text-gray-500">
                            <?php if($showArchived ?? false): ?>
                                <p>No archived academic years found.</p>
                            <?php else: ?>
                                <p>No academic years found. Create one to get started.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $streamCount = $year->streams()->count();
                                    $examCount = $year->exams()->count();
                                    $studentEnrollments = DB::table('student_stream')->where('academic_year_id', $year->id)->count();
                                    $hasDependencies = $streamCount > 0 || $examCount > 0 || $studentEnrollments > 0;
                                ?>
                                <tr class="<?php echo e($year->is_active ? 'bg-green-50' : ''); ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($year->name); ?></div>
                                        <?php if($hasDependencies): ?>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <?php echo e($streamCount); ?> streams, <?php echo e($examCount); ?> exams, <?php echo e($studentEnrollments); ?> enrollments
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($year->start_date->format('Y-m-d')); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($year->end_date->format('Y-m-d')); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if($year->is_active): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Inactive
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <?php if($showArchived ?? false): ?>
                                            <!-- Restore button for archived years -->
                                            <form action="<?php echo e(route('academic-years.restore', $year->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Restore this academic year?')">
                                                    Restore
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <!-- Normal actions for active years -->
                                            <a href="<?php echo e(route('academic-years.edit', $year)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                            
                                            <?php if($year->is_active): ?>
                                                <span class="text-gray-400 cursor-not-allowed" title="Cannot delete active academic year">
                                                    Delete
                                                </span>
                                            <?php elseif($hasDependencies): ?>
                                                <button 
                                                    type="button"
                                                    onclick="showDeleteWarning('<?php echo e($year->name); ?>', <?php echo e($streamCount); ?>, <?php echo e($examCount); ?>, <?php echo e($studentEnrollments); ?>)"
                                                    class="text-orange-600 hover:text-orange-900"
                                                    title="This academic year has data">
                                                    Archive
                                                </button>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('academic-years.destroy', $year)); ?>" method="POST" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Archive this academic year?')">
                                                        Archive
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Warning Modal -->
    <div id="deleteWarningModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mx-auto">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center mt-4" id="modalTitle">
                    Archive Academic Year?
                </h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500 mb-4" id="modalMessage">
                        This academic year contains important data that will be preserved.
                    </p>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 text-sm text-yellow-800">
                        <p class="font-semibold mb-2">⚠️ This action cannot be undone</p>
                        <p class="mb-1">The system will prevent deletion to protect:</p>
                        <ul class="list-disc list-inside ml-2 space-y-1" id="modalDetails">
                            <!-- Populated by JavaScript -->
                        </ul>
                    </div>
                </div>
                <div class="items-center px-4 py-3">
                    <button
                        id="closeModal"
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDeleteWarning(yearName, streams, exams, enrollments) {
            const modal = document.getElementById('deleteWarningModal');
            const message = document.getElementById('modalMessage');
            const details = document.getElementById('modalDetails');
            
            message.textContent = `Academic Year "${yearName}" contains important historical data.`;
            
            details.innerHTML = '';
            if (streams > 0) {
                details.innerHTML += `<li>${streams} stream${streams !== 1 ? 's' : ''}</li>`;
            }
            if (exams > 0) {
                details.innerHTML += `<li>${exams} exam${exams !== 1 ? 's' : ''} with results</li>`;
            }
            if (enrollments > 0) {
                details.innerHTML += `<li>${enrollments} student enrollment${enrollments !== 1 ? 's' : ''}</li>`;
            }
            
            modal.classList.remove('hidden');
        }
        
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('deleteWarningModal').classList.add('hidden');
        });
        
        // Close modal when clicking outside
        document.getElementById('deleteWarningModal').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
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
<?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/academic-years/index.blade.php ENDPATH**/ ?>