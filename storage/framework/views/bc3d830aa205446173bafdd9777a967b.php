

<?php $__env->startSection('page-title', 'تقارير الأقسام'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'تقارير الأقسام',
        'subtitle' => 'متابعة التقارير المرفوعة من مديري الأقسام',
        'icon' => 'doc',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="<?php echo e(route('admin.department-oversight.index')); ?>"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            لوحة متابعة الأقسام
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['إجمالي التقارير', $stats['total'], $themeColor],
            ['مُرسلة', $stats['submitted'], '#059669'],
            ['مسودات', $stats['draft'], '#d97706'],
            ['هذا الشهر', $stats['this_month'], '#7c3aed'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500"><?php echo e($label); ?></p>
            <p class="text-3xl font-bold mt-1" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">القسم</label>
            <select name="department_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($dept->id); ?>" <?php if(request('department_id') == $dept->id): echo 'selected'; endif; ?>><?php echo e($dept->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الحالة</label>
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>مسودة</option>
                <option value="submitted" <?php if(request('status') === 'submitted'): echo 'selected'; endif; ?>>مُرسل</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold">تصفية</button>
        <?php if(request()->hasAny(['department_id', 'status'])): ?>
        <a href="<?php echo e(route('admin.department-reports.index')); ?>" class="border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50">إعادة تعيين</a>
        <?php endif; ?>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">قائمة التقارير <span class="text-gray-400 font-normal text-sm">(<?php echo e($reports->total()); ?>)</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التاريخ</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المدير</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المشروع</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الفترة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-blue-50/40 transition-colors align-middle">
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                            <div><?php echo e($report->created_at?->format('Y/m/d')); ?></div>
                            <div class="text-gray-400"><?php echo e($report->created_at?->format('H:i')); ?></div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-gray-900"><?php echo e($report->department?->name ?? '—'); ?></span>
                            <?php if($report->summary): ?>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1"><?php echo e(Str::limit($report->summary, 60)); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs"><?php echo e($report->department?->manager?->user?->name ?? '—'); ?></td>
                        <td class="px-4 py-3 text-gray-700 text-xs"><?php echo e($report->project?->name ?? '—'); ?></td>
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                            <?php if($report->period_start || $report->period_end): ?>
                                <?php echo e($report->period_start?->format('Y/m/d') ?? '—'); ?>

                                <span class="text-gray-300">→</span>
                                <?php echo e($report->period_end?->format('Y/m/d') ?? '—'); ?>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-full <?php echo e($report->status === 'submitted' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600'); ?>">
                                <?php echo e($report->status === 'submitted' ? 'مُرسل' : 'مسودة'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <a href="<?php echo e(route('admin.department-reports.show', $report)); ?>" class="text-xs font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;">عرض</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا توجد تقارير</p>
                            <p class="text-sm">ستظهر هنا عند رفع مديري الأقسام لتقاريرهم.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($reports->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($reports->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\department-reports\admin\index.blade.php ENDPATH**/ ?>