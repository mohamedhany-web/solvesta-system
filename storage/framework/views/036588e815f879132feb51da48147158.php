<?php $__env->startSection('page-title', 'PMO — إدارة التنفيذ'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full px-2 sm:px-0">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'PMO',
        'subtitle' => 'Milestones · توزيع المهام · Blockers · متابعة التنفيذ',
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="<?php echo e(route('daily-reports.create')); ?>" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">+ تقرير يومي</a>
        <a href="<?php echo e(route('daily-reports.index')); ?>" class="px-5 py-2.5 rounded-xl border bg-white font-bold text-sm hover:shadow-md">التقارير اليومية</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['مشاريع نشطة', $stats['active_projects'], $themeColor],
            ['مراحل متأخرة', $stats['overdue_milestones'], '#dc2626'],
            ['Blockers مفتوحة', $stats['open_blockers'], '#d97706'],
            ['تقارير اليوم', $stats['reports_today'], '#2563eb'],
            ['بانتظار مراجعة', $stats['unreviewed_reports'], '#7c3aed'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500 font-tajawal"><?php echo e($label); ?></p>
            <p class="text-2xl font-bold font-tajawal mt-1" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h2 class="font-bold text-red-700 mb-4 font-tajawal">Blockers نشطة</h2>
            <ul class="space-y-3 text-sm font-tajawal">
                <?php $__empty_1 = true; $__currentLoopData = $blockers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="flex justify-between gap-2">
                        <strong><?php echo e($task->title); ?></strong>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-projects')): ?>
                        <form method="POST" action="<?php echo e(route('pmo.tasks.resolve-blocker', $task)); ?>"><?php echo csrf_field(); ?>
                            <button class="text-xs text-emerald-700 font-bold">حلّ ✓</button>
                        </form>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-gray-600 mt-1"><?php echo e($task->project?->name); ?> — <?php echo e($task->assignedTo?->name); ?></p>
                    <?php if($task->blocker_description): ?><p class="text-xs text-red-800 mt-1"><?php echo e($task->blocker_description); ?></p><?php endif; ?>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="text-gray-500">لا توجد عوائق مفتوحة.</li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h2 class="font-bold text-amber-700 mb-4 font-tajawal">مراحل متأخرة</h2>
            <ul class="space-y-3 text-sm font-tajawal">
                <?php $__empty_1 = true; $__currentLoopData = $overdueMilestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <li class="p-3 rounded-xl bg-amber-50 border border-amber-100 flex justify-between">
                    <div>
                        <strong><?php echo e($m->name); ?></strong>
                        <p class="text-xs text-gray-600"><?php echo e($m->project?->name); ?></p>
                    </div>
                    <span class="text-xs text-red-700 font-bold"><?php echo e($m->due_date?->format('Y/m/d')); ?></span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="text-gray-500">لا توجد مراحل متأخرة.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b font-bold font-tajawal" style="background: <?php echo e($themeColor); ?>08;">المشاريع تحت الإدارة</div>
        <table class="w-full text-sm font-tajawal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">المراحل</th>
                    <th class="px-4 py-3 text-right">التقدم</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold"><?php echo e($p->name); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($p->client?->name); ?></td>
                    <td class="px-4 py-3"><?php echo e($p->milestones->count()); ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full max-w-[80px]">
                                <div class="h-full rounded-full" style="width:<?php echo e($p->progress_percentage ?? 0); ?>%; background:<?php echo e($themeColor); ?>;"></div>
                            </div>
                            <span><?php echo e($p->progress_percentage ?? 0); ?>%</span>
                        </div>
                    </td>
                    <td class="px-4 py-3"><a href="<?php echo e(route('projects.show', $p)); ?>" class="font-bold" style="color:<?php echo e($themeColor); ?>;">فتح</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">لا توجد مشاريع نشطة.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="px-4 py-3"><?php echo e($projects->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\pmo\index.blade.php ENDPATH**/ ?>