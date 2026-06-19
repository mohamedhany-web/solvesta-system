<?php $__env->startSection('page-title', 'إضافة قسم جديد'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'إضافة قسم جديد',
        'subtitle' => 'تعريف قسم جديد مع صلاحيات السايدبار وملف الموظف الافتراضي',
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="<?php echo e(route('departments.index')); ?>" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            كل الأقسام
        </a>
    </div>

    <?php if($errors->any()): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-bold mb-1">يرجى تصحيح الأخطاء:</p>
        <ul class="list-disc list-inside"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <div class="max-w-4xl">
        <?php echo $__env->make('departments.partials.form', [
            'action' => route('departments.store'),
            'method' => 'POST',
            'submitLabel' => 'إنشاء القسم',
            'cancelUrl' => route('departments.index'),
            'department' => $department,
            'employees' => $employees,
            'parentDepartments' => $parentDepartments,
            'themeColor' => $themeColor,
        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\departments\create.blade.php ENDPATH**/ ?>