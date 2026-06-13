

<?php $__env->startSection('page-title', 'تعديل خدمة — '.$clientService->title); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?php echo e(route('accounting.client-services.show', $clientService)); ?>" class="text-sm text-blue-600 font-semibold hover:underline">← العودة للتفاصيل</a>
        <h1 class="text-2xl font-bold mt-2">تعديل: <?php echo e($clientService->title); ?></h1>
    </div>

    <form method="POST" action="<?php echo e(route('accounting.client-services.update', $clientService)); ?>" class="bg-white border rounded-xl p-6 shadow-sm space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('accounting.client-services._form', ['clientService' => $clientService, 'prefill' => []], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700">حفظ التعديلات</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\client-services\edit.blade.php ENDPATH**/ ?>