

<?php $__env->startSection('title', $page->meta_title ?? $page->title); ?>
<?php $__env->startSection('meta_description', $page->meta_description ?? $page->excerpt); ?>
<?php $__env->startSection('meta_keywords', $page->meta_keywords); ?>

<?php $__env->startPush('meta'); ?>
    <?php if($page->og_image): ?>
        <meta property="og:image" content="<?php echo e(Storage::url($page->og_image)); ?>">
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold"><?php echo e($page->title); ?></h1>
        <?php if($page->excerpt): ?>
            <p class="text-xl mt-4"><?php echo e($page->excerpt); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Page Content -->
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if($page->featured_image): ?>
            <div class="mb-8">
                <img src="<?php echo e(Storage::url($page->featured_image)); ?>" alt="<?php echo e($page->title); ?>" class="w-full h-96 object-cover rounded-lg shadow-lg">
            </div>
        <?php endif; ?>

        <div class="prose prose-lg max-w-none">
            <?php echo nl2br(e($page->content)); ?>

        </div>

        <div class="mt-8 pt-8 border-t text-sm text-gray-500">
            <p>Last updated: <?php echo e($page->updated_at->format('F d, Y')); ?></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\THINKPAD -T15\Christian-Leberg-School\resources\views/website/page.blade.php ENDPATH**/ ?>