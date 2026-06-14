<?php $__env->startSection('page-title', $partner->name); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => $partner->name,
        'subtitle' => $partner->reference_code.' · '.$partner->company,
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="flex gap-2 mb-6">
        <a href="<?php echo e(route('bd.index')); ?>" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold">← BD</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border p-6 font-tajawal text-sm space-y-2">
            <p><strong>النوع:</strong> <?php echo e($partner->partner_type_label); ?></p>
            <p><strong>البريد:</strong> <?php echo e($partner->email ?? '—'); ?></p>
            <p><strong>الهاتف:</strong> <?php echo e($partner->phone ?? '—'); ?></p>
            <p><strong>مسند إلى:</strong> <?php echo e($partner->assignee?->name); ?></p>
            <?php if($partner->notes): ?><p class="mt-4 p-3 bg-gray-50 rounded-xl"><?php echo e($partner->notes); ?></p><?php endif; ?>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border p-6">
            <h3 class="font-bold mb-3">الفرص المرتبطة</h3>
            <ul class="space-y-2 text-sm">
                <?php $__empty_1 = true; $__currentLoopData = $partner->opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="p-2 bg-gray-50 rounded-lg flex justify-between">
                    <span><?php echo e($o->title); ?></span>
                    <span class="text-xs"><?php echo e($o->status_label); ?></span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="text-gray-500">لا توجد فرص بعد.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\bd\partners\show.blade.php ENDPATH**/ ?>