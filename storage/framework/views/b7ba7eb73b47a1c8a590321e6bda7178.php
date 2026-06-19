

<?php $__env->startSection('page-title', 'طلبات التوظيف'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'طلبات التوظيف',
        'subtitle' => 'الوظيفة: ' . $jobPosting->title,
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="<?php echo e(route('website.careers.show', $jobPosting->slug)); ?>" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            رابط التقديم
        </a>
        <a href="<?php echo e(route('job-postings.show', $jobPosting)); ?>"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            تفاصيل الوظيفة
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        <?php $__currentLoopData = [
            ['الإجمالي', $appStats['total'], $themeColor],
            ['جديدة', $appStats['pending'], '#3b82f6'],
            ['قيد المراجعة', $appStats['reviewing'], '#d97706'],
            ['قائمة مختصرة', $appStats['shortlisted'], '#7c3aed'],
            ['تم التوظيف', $appStats['hired'], '#059669'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm text-center">
            <p class="text-[10px] text-gray-500"><?php echo e($label); ?></p>
            <p class="text-2xl font-bold mt-0.5" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">قائمة المتقدمين <span class="text-gray-400 font-normal text-sm">(<?php echo e($applications->total()); ?>)</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المتقدم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التواصل</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">السيرة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التاريخ</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $appBadge = match($a->status) {
                            'hired' => 'bg-emerald-100 text-emerald-800',
                            'shortlisted' => 'bg-violet-100 text-violet-800',
                            'reviewing' => 'bg-amber-100 text-amber-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            default => 'bg-blue-100 text-blue-800',
                        };
                    ?>
                    <tr class="hover:bg-blue-50/40 transition-colors align-top">
                        <td class="px-4 py-3">
                            <div class="font-bold text-gray-900"><?php echo e($a->full_name); ?></div>
                            <?php if($a->message): ?>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo e(Str::limit($a->message, 80)); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            <div><?php echo e($a->email); ?></div>
                            <div class="text-gray-400"><?php echo e($a->phone ?? '—'); ?></div>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($a->cv_path): ?>
                                <a class="inline-flex items-center gap-1 text-xs font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;"
                                   href="<?php echo e(asset('storage/'.$a->cv_path)); ?>" target="_blank" rel="noopener">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    تحميل CV
                                </a>
                            <?php else: ?>
                                <span class="text-gray-400 text-xs">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-full <?php echo e($appBadge); ?>"><?php echo e($a->statusLabelAr()); ?></span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap"><?php echo e($a->created_at->format('Y/m/d')); ?></td>
                        <td class="px-4 py-3">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-jobs')): ?>
                            <form method="POST" action="<?php echo e(route('job-postings.applications.status', $a)); ?>" class="flex flex-wrap items-center gap-2">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <select name="status" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs min-w-[7rem]">
                                    <?php
                                        $statusLabels = [
                                            'pending' => 'جديد',
                                            'reviewing' => 'قيد المراجعة',
                                            'shortlisted' => 'قائمة مختصرة',
                                            'rejected' => 'مرفوض',
                                            'hired' => 'تم التوظيف',
                                        ];
                                    ?>
                                    <?php $__currentLoopData = \App\Models\JobApplication::STATUSES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($st); ?>" <?php if($a->status === $st): echo 'selected'; endif; ?>><?php echo e($statusLabels[$st] ?? $st); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-xs font-bold hover:bg-black">حفظ</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if($a->message): ?>
                    <tr class="bg-gray-50/80">
                        <td colspan="6" class="px-4 py-3 text-xs text-gray-700 whitespace-pre-wrap border-b border-gray-100">
                            <span class="font-bold text-gray-500">رسالة المتقدم:</span> <?php echo e($a->message); ?>

                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا توجد طلبات بعد</p>
                            <a href="<?php echo e(route('website.careers.show', $jobPosting->slug)); ?>" target="_blank" rel="noopener"
                               class="font-semibold hover:underline" style="color: <?php echo e($themeColor); ?>;">رابط التقديم على الموقع</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($applications->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($applications->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\job-postings\applications.blade.php ENDPATH**/ ?>