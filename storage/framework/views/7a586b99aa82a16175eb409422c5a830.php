

<?php $__env->startSection('page-title', 'هيكل الفريق'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'هيكل الفريق',
        'subtitle' => $department->name.' — عرض التدرج الوظيفي المُعدّ من الإدارة',
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="<?php echo e(route('department-manager.dashboard')); ?>" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            لوحة القسم
        </a>
        <a href="<?php echo e(route('daily-reports.index', ['view' => 'department'])); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm" style="background:<?php echo e($themeColor); ?>">
            مراجعة التقارير اليومية
        </a>
        <a href="<?php echo e(route('department-manager.tasks.create')); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm" style="background:<?php echo e($themeColor); ?>">
            توزيع مهمة
        </a>
    </div>

    <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5 mb-6 text-sm text-blue-900">
        <p class="font-bold mb-2">دورك كرئيس قسم</p>
        <p class="leading-relaxed">الإدارة تُعيّن الموظفين وهيكل الفريق وفِرق المشاريع من صفحة القسم. أنت تستلم الفريق جاهزاً وتبدأ بـ <strong>توزيع المهام</strong> و<strong>مراجعة التقارير اليومية</strong> و<strong>متابعة التنفيذ</strong>.</p>
        <p class="mt-2 text-xs text-blue-800">لتعديل الهيكل تواصل مع الإدارة — لا يمكن تغييره من هنا.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">الموظف</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">المسمى</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">Team Lead</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">المشرف المباشر</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-4 py-3 font-semibold"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></td>
                    <td class="px-4 py-3 text-gray-600"><?php echo e($emp->position); ?></td>
                    <td class="px-4 py-3">
                        <?php if($emp->is_team_lead): ?>
                        <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">قائد فريق</span>
                        <?php else: ?> — <?php endif; ?>
                    </td>
                    <td class="px-4 py-3"><?php echo e($emp->supervisor?->name ?? ($department->manager?->user?->name ?? 'رئيس القسم')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">لا يوجد موظفون في القسم بعد.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\department-manager\team\index.blade.php ENDPATH**/ ?>