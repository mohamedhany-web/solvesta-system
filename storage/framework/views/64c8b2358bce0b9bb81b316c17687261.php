<?php $__env->startSection('page-title', 'التقارير المالية — CEO'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'التقارير المالية',
        'subtitle' => 'إيرادات · مستحقات · مصروفات · ربحية المشاريع',
        'icon' => 'chart',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap gap-3 mb-6">
        <a href="<?php echo e(route('executive.dashboard')); ?>" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold hover:shadow-md">← لوحة CEO</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <?php $__currentLoopData = [
            ['إيرادات محصّلة', $summary['paid_revenue'], '#059669'],
            ['مستحقات', $summary['outstanding'], '#d97706'],
            ['فواتير تسليم معلّقة', $summary['delivery_pending_count'], '#2563eb'],
            ['ربح تقديري', $summary['estimated_profit'], $themeColor],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 text-center">
            <p class="text-xs text-gray-500 font-tajawal"><?php echo e($label); ?></p>
            <p class="text-2xl font-bold font-tajawal mt-1" style="color: <?php echo e($color); ?>;">
                <?php echo e(is_numeric($val) && $label !== 'فواتير تسليم معلّقة' ? number_format($val) : $val); ?>

                <?php if(is_numeric($val) && $label !== 'فواتير تسليم معلّقة'): ?> <span class="text-sm">ج.م</span> <?php endif; ?>
            </p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
        <div class="rounded-2xl p-5 border shadow-lg" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>10 0%, white 100%); border-color: <?php echo e($themeColor); ?>25;">
            <h2 class="font-bold mb-3 font-tajawal">تفصيل المصروفات</h2>
            <ul class="space-y-2 text-sm font-tajawal">
                <li class="flex justify-between"><span>مصروفات مرتبطة بمشاريع</span><strong class="text-red-700"><?php echo e(number_format($summary['project_expenses'])); ?></strong></li>
                <li class="flex justify-between"><span>مصروفات عامة</span><strong><?php echo e(number_format($summary['general_expenses'])); ?></strong></li>
                <li class="flex justify-between border-t pt-2"><span>مستحقات فواتير التسليم</span><strong class="text-amber-700"><?php echo e(number_format($summary['delivery_outstanding'])); ?></strong></li>
            </ul>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <h2 class="font-bold mb-3 font-tajawal">معادلة الربح</h2>
            <p class="text-sm text-gray-600 font-tajawal leading-relaxed">
                الربح التقديري = الإيرادات المحصّلة − مصروفات المشاريع − المصروفات العامة
            </p>
            <p class="text-3xl font-bold mt-4 font-tajawal" style="color: <?php echo e($summary['estimated_profit'] >= 0 ? $themeColor : '#dc2626'); ?>;">
                <?php echo e(number_format($summary['estimated_profit'])); ?> ج.م
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-8">
        <div class="px-5 py-4 border-b font-bold font-tajawal" style="background: <?php echo e($themeColor); ?>08;">ربحية المشاريع</div>
        <table class="w-full text-sm font-tajawal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">محصّل</th>
                    <th class="px-4 py-3 text-right">مصروفات</th>
                    <th class="px-4 py-3 text-right">ربح</th>
                    <th class="px-4 py-3 text-right">هامش</th>
                    <th class="px-4 py-3 text-right">مستحقات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $summary['project_rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="<?php echo e(route('projects.show', $row['project'])); ?>" class="font-bold" style="color:<?php echo e($themeColor); ?>;"><?php echo e($row['project']->name); ?></a>
                    </td>
                    <td class="px-4 py-3 text-emerald-700"><?php echo e(number_format($row['revenue_paid'])); ?></td>
                    <td class="px-4 py-3 text-red-700"><?php echo e(number_format($row['expenses'])); ?></td>
                    <td class="px-4 py-3 font-bold"><?php echo e(number_format($row['profit'])); ?></td>
                    <td class="px-4 py-3"><?php echo e($row['margin_percent']); ?>%</td>
                    <td class="px-4 py-3 text-amber-700"><?php echo e(number_format($row['outstanding'])); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد بيانات مشاريع.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
        <h2 class="font-bold mb-4 font-tajawal">آخر فواتير التسليم</h2>
        <ul class="space-y-2 text-sm font-tajawal">
            <?php $__empty_1 = true; $__currentLoopData = $summary['recent_delivery_invoices']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="flex justify-between py-2 border-b border-gray-50">
                <a href="<?php echo e(route('invoices.show', $inv)); ?>" class="text-blue-600 font-semibold"><?php echo e($inv->invoice_number); ?></a>
                <span><?php echo e($inv->client?->name); ?> — <?php echo e($inv->project?->name); ?></span>
                <strong><?php echo e(number_format($inv->total_amount)); ?> (<?php echo e($inv->status); ?>)</strong>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="text-gray-500">لم تُصدر فواتير تسليم بعد.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\executive\finance.blade.php ENDPATH**/ ?>