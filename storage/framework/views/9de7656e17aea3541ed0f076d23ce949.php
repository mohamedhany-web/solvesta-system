<?php $__env->startSection('page-title', 'Leads — توليد العملاء'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'Leads',
        'subtitle' => 'توليد العملاء المحتملين — Business Development → Sales',
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-sales')): ?>
        <a href="<?php echo e(route('leads.create')); ?>" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:shadow-xl transition"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">+ Lead جديد</a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['جديد', $stats['new'], $themeColor],
            ['مؤهل', $stats['qualified'], '#2563eb'],
            ['تحوّل لمبيعات', $stats['converted'], '#059669'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 text-center hover:shadow-xl transition-all duration-300">
            <p class="text-gray-500 text-sm font-tajawal"><?php echo e($label); ?></p>
            <p class="text-3xl font-bold font-tajawal mt-1" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
        <input name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث..." class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
        <select name="status" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
            <option value="">كل الحالات</option>
            <?php $__currentLoopData = ['new','contacted','qualified','converted','lost','on_hold']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($st); ?>" <?php if(request('status')===$st): echo 'selected'; endif; ?>><?php echo e((new \App\Models\Lead(['status'=>$st]))->status_label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="px-5 py-2.5 rounded-xl text-white text-sm font-bold"
                style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">تصفية</button>
    </form>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background: <?php echo e($themeColor); ?>08;">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">المرجع</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الاسم</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الشركة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الخدمة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">المصدر</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">الحالة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono text-xs text-gray-500"><?php echo e($lead->reference_code); ?></td>
                    <td class="px-4 py-3 font-semibold text-gray-900"><?php echo e($lead->name); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($lead->company ?? '—'); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($lead->service_interest ?? '—'); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($lead->source_label); ?></td>
                    <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-gray-100"><?php echo e($lead->status_label); ?></span></td>
                    <td class="px-4 py-3"><a href="<?php echo e(route('leads.show', $lead)); ?>" class="font-bold" style="color: <?php echo e($themeColor); ?>;">فتح</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7" class="px-4 py-16 text-center text-gray-500">لا توجد Leads بعد.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3 border-t"><?php echo e($leads->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\leads\index.blade.php ENDPATH**/ ?>