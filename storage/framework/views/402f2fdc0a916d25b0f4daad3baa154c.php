

<?php $__env->startSection('page-title', 'تسجيل خدمة ما بعد البيع'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="<?php echo e(route('accounting.client-services.index')); ?>" class="text-sm text-blue-600 font-semibold hover:underline">← العودة للقائمة</a>
        <h1 class="text-2xl font-bold mt-2">تسجيل خدمة / اشتراك شهري</h1>
        <p class="text-gray-600 text-sm">بعد التفعيل، يُصدر النظام فاتورة شهرية تلقائياً ويُبلّغ العميل في بوابته.</p>
    </div>

    <form method="POST" action="<?php echo e(route('accounting.client-services.store')); ?>" class="bg-white border rounded-xl p-6 shadow-sm space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('accounting.client-services._form', ['prefill' => $prefill], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700">حفظ الخدمة</button>
            <a href="<?php echo e(route('accounting.client-services.index')); ?>" class="px-6 py-3 border rounded-xl font-semibold text-gray-700">إلغاء</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\client-services\create.blade.php ENDPATH**/ ?>