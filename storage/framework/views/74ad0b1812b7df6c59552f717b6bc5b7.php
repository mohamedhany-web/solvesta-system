

<?php $__env->startSection('page-title', 'ميزات وتحسينات النظام'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-full">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">ميزات وتحسينات النظام</h1>
            <p class="text-gray-600">اطلب ميزة جديدة أو اقترح تحسيناً — تُنظَّم طلباتك ضمن مشاريع نظامك مع سجل توثيقي لكل طلب.</p>
        </div>
        <a href="<?php echo e(route('client.system-features.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shrink-0">+ طلب جديد</a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 text-sm font-medium"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($projects->isNotEmpty()): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <p class="text-xs font-mono text-gray-500"><?php echo e($project->reference_code); ?></p>
            <h2 class="text-lg font-bold text-gray-900 mt-1"><?php echo e($project->name); ?></h2>
            <p class="text-sm text-gray-600 mt-2 line-clamp-2"><?php echo e($project->description ?: '—'); ?></p>
            <div class="flex flex-wrap gap-2 mt-4 text-xs">
                <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 font-bold"><?php echo e($project->features_count); ?> طلب</span>
                <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700"><?php echo e($project->status_label); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b"><h2 class="font-bold text-lg">طلباتك الأخيرة</h2></div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المرجع</th>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">العنوان</th>
                    <th class="px-4 py-3 text-right">النوع</th>
                    <th class="px-4 py-3 text-right">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $recentFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs"><?php echo e($f->reference_code); ?></td>
                    <td class="px-4 py-3"><?php echo e($f->project?->name); ?></td>
                    <td class="px-4 py-3 font-semibold"><?php echo e($f->title); ?></td>
                    <td class="px-4 py-3"><?php echo e($f->type_label); ?></td>
                    <td class="px-4 py-3"><span class="text-xs font-bold px-2 py-0.5 rounded bg-slate-100"><?php echo e($f->status_label); ?></span></td>
                    <td class="px-4 py-3"><a href="<?php echo e(route('client.system-features.show', $f)); ?>" class="text-blue-600 font-bold hover:underline">عرض</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد طلبات بعد. <a href="<?php echo e(route('client.system-features.create')); ?>" class="text-blue-600 font-bold">أرسل أول طلب</a></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-portal\system-features\index.blade.php ENDPATH**/ ?>