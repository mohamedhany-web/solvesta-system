

<?php $__env->startSection('page-title', 'تقرير قسم'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $statusBadge = $report->status === 'submitted' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600';
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'تقرير: ' . ($report->department?->name ?? 'قسم'),
        'subtitle' => ($report->project?->name ?? 'بدون مشروع') . ' — ' . ($report->department?->manager?->user?->name ?? 'بدون مدير'),
        'icon' => 'doc',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <?php if($report->project): ?>
        <a href="<?php echo e(route('projects.show', $report->project)); ?>"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            عرض المشروع
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('admin.department-reports.index', ['department_id' => $report->department_id])); ?>"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            تقارير القسم
        </a>
        <a href="<?php echo e(route('admin.department-reports.index')); ?>"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            كل التقارير
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="font-bold text-lg">الملخص</h2>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo e($statusBadge); ?>">
                        <?php echo e($report->status === 'submitted' ? 'مُرسل' : 'مسودة'); ?>

                    </span>
                </div>
                <div class="p-6 text-gray-800 whitespace-pre-wrap leading-relaxed text-sm">
                    <?php echo e($report->summary ?: '— لا يوجد ملخص —'); ?>

                </div>
            </div>

            <?php if(is_array($report->kpis) && count($report->kpis)): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">مؤشرات الأداء (KPIs)</h2>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php $__currentLoopData = $report->kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                        <dt class="text-xs font-bold text-gray-500 mb-1"><?php echo e($key); ?></dt>
                        <dd class="text-xl font-bold text-gray-900"><?php echo e(is_scalar($value) ? $value : json_encode($value, JSON_UNESCAPED_UNICODE)); ?></dd>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">معلومات التقرير</h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-3 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">القسم</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($report->department?->name ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">مدير القسم</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($report->department?->manager?->user?->name ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">المشروع</dt>
                            <dd class="font-semibold text-gray-900"><?php echo e($report->project?->name ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">أنشأه</dt>
                            <dd><?php echo e($report->creator?->name ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">فترة التقرير</dt>
                            <dd>
                                <?php if($report->period_start || $report->period_end): ?>
                                    <?php echo e($report->period_start?->format('Y/m/d') ?? '—'); ?>

                                    →
                                    <?php echo e($report->period_end?->format('Y/m/d') ?? '—'); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">تاريخ الإنشاء</dt>
                            <dd><?php echo e($report->created_at?->format('Y/m/d H:i') ?? '—'); ?></dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">تاريخ الإرسال</dt>
                            <dd><?php echo e($report->submitted_at?->format('Y/m/d H:i') ?? '—'); ?></dd>
                        </div>
                    </dl>
                </div>
            </div>

            <?php if(is_array($report->attachments) && count($report->attachments)): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">المرفقات <span class="text-gray-400 font-normal text-sm">(<?php echo e(count($report->attachments)); ?>)</span></h2>
                </div>
                <div class="p-4 space-y-2">
                    <?php $__currentLoopData = $report->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a class="flex items-center gap-3 rounded-xl border border-gray-200 px-4 py-3 text-sm hover:bg-gray-50 transition"
                       href="<?php echo e(asset('storage/' . ($file['path'] ?? ''))); ?>"
                       target="_blank" rel="noopener">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg shrink-0" style="background: <?php echo e($themeColor); ?>15; color: <?php echo e($themeColor); ?>;">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        </span>
                        <span class="font-semibold text-gray-800 truncate"><?php echo e($file['name'] ?? 'ملف'); ?></span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\department-reports\admin\show.blade.php ENDPATH**/ ?>