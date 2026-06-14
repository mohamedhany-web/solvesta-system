

<?php $__env->startSection('page-title', 'التوظيف والوظائف'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'التوظيف والوظائف',
        'subtitle' => 'إدارة الوظائف المعروضة على الموقع العام وطلبات التقديم',
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="<?php echo e(route('website.careers')); ?>" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
            صفحة التوظيف العامة
        </a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-jobs')): ?>
        <a href="<?php echo e(route('job-postings.create')); ?>"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            وظيفة جديدة
        </a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['إجمالي الوظائف', $stats['total'], $themeColor],
            ['منشورة', $stats['published'], '#059669'],
            ['مسودات', $stats['draft'], '#d97706'],
            ['طلبات التقديم', $stats['applications'], '#7c3aed'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500"><?php echo e($label); ?></p>
            <p class="text-3xl font-bold mt-1" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
        <div class="flex-1 min-w-[12rem]">
            <label class="text-xs font-bold text-gray-600 block mb-1">بحث</label>
            <input name="search" value="<?php echo e(request('search')); ?>" placeholder="المسمى أو الملخص..."
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الحالة</label>
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>مسودة</option>
                <option value="published" <?php if(request('status') === 'published'): echo 'selected'; endif; ?>>منشورة</option>
                <option value="closed" <?php if(request('status') === 'closed'): echo 'selected'; endif; ?>>مغلقة</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold">تصفية</button>
        <?php if(request()->hasAny(['search', 'status'])): ?>
        <a href="<?php echo e(route('job-postings.index')); ?>" class="border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50">إعادة تعيين</a>
        <?php endif; ?>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">قائمة الوظائف <span class="text-gray-400 font-normal text-sm">(<?php echo e($jobs->total()); ?>)</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الوظيفة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم / النوع</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الطلبات</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التحديث</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $badge = match($job->status) {
                            'published' => 'bg-emerald-100 text-emerald-800',
                            'closed' => 'bg-gray-100 text-gray-600',
                            default => 'bg-amber-100 text-amber-800',
                        };
                    ?>
                    <tr class="hover:bg-blue-50/40 transition-colors align-middle">
                        <td class="px-4 py-3">
                            <a href="<?php echo e(route('job-postings.show', $job)); ?>" class="font-bold text-gray-900 hover:underline" style="color: inherit;"><?php echo e($job->title); ?></a>
                            <?php if($job->is_featured): ?>
                                <span class="inline-block ms-1 text-[10px] font-bold px-1.5 py-0.5 rounded bg-orange-100 text-orange-700">مميزة</span>
                            <?php endif; ?>
                            <?php if($job->summary): ?>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1"><?php echo e($job->summary); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            <div><?php echo e($job->department?->name ?? '—'); ?></div>
                            <div class="text-gray-400"><?php echo e($job->employmentTypeLabel()); ?></div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-full <?php echo e($badge); ?>"><?php echo e($job->statusLabelAr()); ?></span>
                        </td>
                        <td class="px-4 py-3">
                            <a href="<?php echo e(route('job-postings.applications', $job)); ?>" class="font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;"><?php echo e($job->applications_count); ?></a>
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap"><?php echo e($job->updated_at->format('Y/m/d')); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('job-postings.show', $job)); ?>" class="text-xs font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;">عرض</a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-jobs')): ?>
                                <a href="<?php echo e(route('job-postings.edit', $job)); ?>" class="text-xs font-bold text-gray-600 hover:underline">تعديل</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا توجد وظائف</p>
                            <p class="text-sm mb-3">أنشئ وظيفة واضبط حالتها على «منشورة» لتظهر في الموقع.</p>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-jobs')): ?>
                            <a href="<?php echo e(route('job-postings.create')); ?>" class="font-semibold hover:underline" style="color: <?php echo e($themeColor); ?>;">إنشاء وظيفة جديدة</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($jobs->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($jobs->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\job-postings\index.blade.php ENDPATH**/ ?>