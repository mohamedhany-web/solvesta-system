<?php $__env->startSection('page-title', 'التقارير اليومية'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'التقارير اليومية',
        'subtitle' => 'ماذا عملت؟ كم ساعة؟ هل يوجد Blocker؟',
        'icon' => 'doc',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-between gap-3 mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="<?php echo e(route('daily-reports.create')); ?>" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm"
               style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">+ تقرير اليوم</a>
            <?php if($canReview): ?>
            <a href="<?php echo e(route('daily-reports.index', ['view' => 'team'])); ?>" class="px-4 py-2.5 rounded-xl border bg-white text-sm font-bold">مراجعة الفريق</a>
            <?php endif; ?>
        </div>
        <div class="flex gap-4 text-sm font-tajawal">
            <span>ساعات الأسبوع: <strong style="color:<?php echo e($themeColor); ?>"><?php echo e($stats['week_hours']); ?></strong></span>
            <?php if($stats['open_blockers']): ?><span class="text-red-600 font-bold"><?php echo e($stats['open_blockers']); ?> blocker</span><?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background: <?php echo e($themeColor); ?>08;">
                <tr>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                    <?php if($canReview && request('view')==='team'): ?><th class="px-4 py-3 text-right">الموظف</th><?php endif; ?>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">الساعات</th>
                    <th class="px-4 py-3 text-right">Blocker</th>
                    <th class="px-4 py-3 text-right">مراجعة</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><?php echo e($r->report_date->format('Y/m/d')); ?></td>
                    <?php if($canReview && request('view')==='team'): ?><td class="px-4 py-3"><?php echo e($r->user?->name); ?></td><?php endif; ?>
                    <td class="px-4 py-3"><?php echo e($r->project?->name ?? '—'); ?></td>
                    <td class="px-4 py-3 font-bold"><?php echo e($r->hours_worked); ?></td>
                    <td class="px-4 py-3">
                        <?php if($r->has_blocker): ?><span class="text-red-600 text-xs font-bold">نعم</span><?php else: ?> — <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php if($r->reviewed_at): ?>
                            <span class="text-emerald-600 text-xs">✓</span>
                        <?php elseif($canReview && request('view')==='team'): ?>
                        <form method="POST" action="<?php echo e(route('daily-reports.review', $r)); ?>" class="inline"><?php echo csrf_field(); ?>
                            <button class="text-xs font-bold" style="color:<?php echo e($themeColor); ?>;">مراجعة</button>
                        </form>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if($r->work_summary): ?>
                <tr class="bg-gray-50/50"><td colspan="6" class="px-4 py-2 text-xs text-gray-600 whitespace-pre-wrap"><?php echo e(Str::limit($r->work_summary, 200)); ?></td></tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد تقارير.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3"><?php echo e($reports->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\daily-reports\index.blade.php ENDPATH**/ ?>