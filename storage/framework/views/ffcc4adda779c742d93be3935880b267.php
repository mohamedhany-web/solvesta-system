<?php $__env->startSection('page-title', 'التقارير اليومية'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $view = $view ?? 'my';
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'التقارير اليومية',
        'subtitle' => 'التسلسل: موظف → Team Lead → رئيس القسم → الإدارة العليا',
        'icon' => 'doc',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-between gap-3 mb-6 -mt-2">
        <div class="flex flex-wrap gap-2">
            <a href="<?php echo e(route('daily-reports.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-sm hover:opacity-95"
               style="background: <?php echo e($themeColor); ?>;">+ تقرير اليوم</a>

            <?php if($caps['team_lead']): ?>
            <a href="<?php echo e(route('daily-reports.index', ['view' => 'team'])); ?>"
               class="px-4 py-2.5 rounded-xl border text-sm font-bold <?php echo e($view === 'team' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white border-gray-200'); ?>">
                مراجعة Team Lead
                <?php if($stats['pending_team']): ?><span class="mr-1 rounded-full bg-red-500 text-white px-1.5 text-xs"><?php echo e($stats['pending_team']); ?></span><?php endif; ?>
            </a>
            <?php endif; ?>
            <?php if($caps['dept_head']): ?>
            <a href="<?php echo e(route('daily-reports.index', ['view' => 'department'])); ?>"
               class="px-4 py-2.5 rounded-xl border text-sm font-bold <?php echo e($view === 'department' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white border-gray-200'); ?>">
                مراجعة القسم
                <?php if($stats['pending_dept']): ?><span class="mr-1 rounded-full bg-red-500 text-white px-1.5 text-xs"><?php echo e($stats['pending_dept']); ?></span><?php endif; ?>
            </a>
            <?php endif; ?>
            <?php if($caps['executive']): ?>
            <a href="<?php echo e(route('daily-reports.index', ['view' => 'executive'])); ?>"
               class="px-4 py-2.5 rounded-xl border text-sm font-bold <?php echo e($view === 'executive' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white border-gray-200'); ?>">
                الإدارة العليا
                <?php if($stats['pending_executive']): ?><span class="mr-1 rounded-full bg-red-500 text-white px-1.5 text-xs"><?php echo e($stats['pending_executive']); ?></span><?php endif; ?>
            </a>
            <?php endif; ?>
            <?php if($view !== 'my'): ?>
            <a href="<?php echo e(route('daily-reports.index')); ?>" class="px-4 py-2.5 rounded-xl border bg-white text-sm font-semibold text-gray-600">تقاريري</a>
            <?php endif; ?>
        </div>
        <div class="flex gap-4 text-sm">
            <span>ساعات الأسبوع: <strong style="color:<?php echo e($themeColor); ?>"><?php echo e($stats['week_hours']); ?></strong></span>
            <?php if($stats['open_blockers']): ?><span class="text-red-600 font-bold"><?php echo e($stats['open_blockers']); ?> blocker</span><?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">التاريخ</th>
                    <?php if($view !== 'my'): ?><th class="px-4 py-3 text-right font-bold text-gray-600">الموظف</th><?php endif; ?>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">المشروع</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">الساعات</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">Blocker</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">إجراء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $reviewLevel = match(true) {
                        $view === 'executive' => 'executive',
                        $view === 'department' => 'dept_head',
                        $view === 'team' => 'team_lead',
                        default => null,
                    };
                ?>
                <tr class="hover:bg-gray-50/80">
                    <td class="px-4 py-3"><?php echo e($r->report_date->format('Y/m/d')); ?></td>
                    <?php if($view !== 'my'): ?><td class="px-4 py-3 font-semibold"><?php echo e($r->user?->name); ?></td><?php endif; ?>
                    <td class="px-4 py-3"><?php echo e($r->project?->name ?? '—'); ?></td>
                    <td class="px-4 py-3 font-bold"><?php echo e($r->hours_worked); ?></td>
                    <td class="px-4 py-3"><?php if($r->has_blocker): ?><span class="text-red-600 text-xs font-bold">نعم</span><?php else: ?> — <?php endif; ?></td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-semibold px-2 py-1 rounded-lg bg-slate-100 text-slate-700">
                            <?php echo e($statusLabels[$r->review_status] ?? $r->review_status); ?>

                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <?php if($reviewLevel && $r->review_status !== 'closed'): ?>
                        <form method="POST" action="<?php echo e(route('daily-reports.review', $r)); ?>" class="inline-flex items-center gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="level" value="<?php echo e($reviewLevel); ?>">
                            <input type="text" name="notes" placeholder="ملاحظة..." class="border border-gray-200 rounded-lg px-2 py-1 text-xs w-28">
                            <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg text-white" style="background:<?php echo e($themeColor); ?>">مراجعة</button>
                        </form>
                        <?php elseif($r->review_status === 'closed'): ?>
                        <span class="text-emerald-600 text-xs font-bold">✓ مكتمل</span>
                        <?php else: ?>
                        —
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if($r->work_summary): ?>
                <tr class="bg-gray-50/50">
                    <td colspan="<?php echo e($view !== 'my' ? 7 : 6); ?>" class="px-4 py-2 text-xs text-gray-600 whitespace-pre-wrap"><?php echo e(Str::limit($r->work_summary, 300)); ?></td>
                </tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="<?php echo e($view !== 'my' ? 7 : 6); ?>" class="px-4 py-12 text-center text-gray-500">لا توجد تقارير.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3"><?php echo e($reports->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\daily-reports\index.blade.php ENDPATH**/ ?>