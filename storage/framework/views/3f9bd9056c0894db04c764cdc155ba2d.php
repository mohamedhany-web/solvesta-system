<?php $__env->startSection('page-title', 'KPI — أداء الموظفين'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'KPI الموظفين',
        'subtitle' => 'التزام · إنجاز المهام · جودة · تقييم Team Lead',
        'icon' => 'chart',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-between gap-3 mb-6">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="year" class="border rounded-xl px-3 py-2 text-sm">
                <?php for($y = now()->year; $y >= now()->year - 2; $y--): ?>
                    <option value="<?php echo e($y); ?>" <?php if($year==$y): echo 'selected'; endif; ?>><?php echo e($y); ?></option>
                <?php endfor; ?>
            </select>
            <select name="month" class="border rounded-xl px-3 py-2 text-sm">
                <?php for($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo e($m); ?>" <?php if($month==$m): echo 'selected'; endif; ?>><?php echo e($m); ?></option>
                <?php endfor; ?>
            </select>
            <button class="px-4 py-2 rounded-xl text-white text-sm font-bold" style="background:<?php echo e($themeColor); ?>;">تصفية</button>
        </form>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-employees')): ?>
        <form method="POST" action="<?php echo e(route('kpi.recalculate')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="year" value="<?php echo e($year); ?>">
            <input type="hidden" name="month" value="<?php echo e($month); ?>">
            <button class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm">إعادة حساب الكل</button>
        </form>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['متوسط KPI', $stats['avg_score'].'%', $themeColor],
            ['ممتاز (90+)', $stats['excellent'], '#059669'],
            ['يحتاج تحسين', $stats['needs_improvement'], '#dc2626'],
            ['موظفون', $stats['employees'], '#6b7280'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$l,$v,$c]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border p-4 text-center">
            <p class="text-xs text-gray-500 font-tajawal"><?php echo e($l); ?></p>
            <p class="text-2xl font-bold" style="color:<?php echo e($c); ?>;"><?php echo e($v); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background:<?php echo e($themeColor); ?>08;">
                <tr>
                    <th class="px-4 py-3 text-right">الموظف</th>
                    <th class="px-4 py-3 text-right">الدور</th>
                    <th class="px-4 py-3 text-right">التزام</th>
                    <th class="px-4 py-3 text-right">المهام</th>
                    <th class="px-4 py-3 text-right">الجودة</th>
                    <th class="px-4 py-3 text-right">TL</th>
                    <th class="px-4 py-3 text-right">الإجمالي</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold"><?php echo e($p->user?->name); ?></td>
                    <td class="px-4 py-3 text-xs"><?php echo e($p->role_template); ?></td>
                    <td class="px-4 py-3"><?php echo e($p->adherence_score); ?>%</td>
                    <td class="px-4 py-3"><?php echo e($p->task_completion_score); ?>%</td>
                    <td class="px-4 py-3"><?php echo e($p->quality_score); ?>%</td>
                    <td class="px-4 py-3"><?php echo e($p->team_lead_rating ?? '—'); ?></td>
                    <td class="px-4 py-3">
                        <span class="font-bold" style="color:<?php echo e($themeColor); ?>;"><?php echo e($p->total_score); ?>%</span>
                        <span class="text-xs text-gray-500">(<?php echo e($p->grade_label); ?>)</span>
                    </td>
                    <td class="px-4 py-3"><a href="<?php echo e(route('kpi.show', $p->user)); ?>?year=<?php echo e($year); ?>&month=<?php echo e($month); ?>" class="font-bold text-blue-600">تفاصيل</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">لا توجد بيانات KPI — اضغط «إعادة حساب الكل».</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3"><?php echo e($periods->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\kpi\index.blade.php ENDPATH**/ ?>