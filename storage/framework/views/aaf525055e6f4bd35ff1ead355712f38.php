

<?php $__env->startSection('page-title', 'مراجعة Pull Request'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => $pr->title,
        'subtitle' => $pr->repository?->fullName() . ' · ' . $pr->source_branch . ' → ' . $pr->target_branch,
        'icon' => 'doc',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <?php if($pr->pr_url): ?>
        <a href="<?php echo e($pr->pr_url); ?>" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, #24292f 0%, #444 100%);">
            فتح على GitHub
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('dev-workflow.index')); ?>" class="border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">لوحة التطوير</a>
        <?php if($pr->task): ?>
        <a href="<?php echo e(route('tasks.show', $pr->task)); ?>" class="border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">المهمة</a>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80 flex justify-between items-center">
                    <h2 class="font-bold">الوصف</h2>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo e($pr->statusBadgeClass()); ?>"><?php echo e($pr->statusLabelAr()); ?></span>
                </div>
                <div class="p-6 text-sm text-gray-800 whitespace-pre-wrap leading-relaxed"><?php echo e($pr->description ?: '—'); ?></div>
            </div>

            <?php if($pr->review_notes): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold">ملاحظات المراجعة</h2>
                </div>
                <div class="p-6 text-sm text-gray-800 whitespace-pre-wrap"><?php echo e($pr->review_notes); ?></div>
            </div>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('review-code')): ?>
            <?php if($pr->isPendingReview() || $pr->status === 'approved'): ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
                <h2 class="font-bold mb-4">إجراء المراجعة</h2>
                <form method="POST" action="<?php echo e(route('dev-workflow.pull-requests.review', $pr)); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1">ملاحظات</label>
                        <textarea name="review_notes" rows="4" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm"><?php echo e(old('review_notes')); ?></textarea>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <button type="submit" name="action" value="approve" class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold">موافقة</button>
                        <button type="submit" name="action" value="request_changes" class="px-4 py-2 rounded-xl bg-amber-500 text-white text-sm font-bold">طلب تعديلات</button>
                        <?php if($pr->status === 'approved'): ?>
                        <button type="submit" name="action" value="merge" class="px-4 py-2 rounded-xl text-white text-sm font-bold" style="background: <?php echo e($themeColor); ?>;">تسجيل دمج</button>
                        <?php endif; ?>
                        <button type="submit" name="action" value="close" class="px-4 py-2 rounded-xl border border-gray-300 text-sm font-semibold">إغلاق</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="xl:col-span-4">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">المشروع</span><span class="font-bold"><?php echo e($pr->repository?->project?->name); ?></span></div>
                <div class="flex justify-between"><span class="text-gray-500">المؤلف</span><span><?php echo e($pr->author?->name); ?></span></div>
                <div class="flex justify-between"><span class="text-gray-500">المراجع</span><span><?php echo e($pr->reviewer?->name ?? '—'); ?></span></div>
                <?php if($pr->number): ?><div class="flex justify-between"><span class="text-gray-500">PR #</span><span><?php echo e($pr->number); ?></span></div><?php endif; ?>
                <div class="flex justify-between"><span class="text-gray-500">أُنشئ</span><span><?php echo e($pr->created_at?->format('Y/m/d H:i')); ?></span></div>
                <?php if($pr->reviewed_at): ?><div class="flex justify-between"><span class="text-gray-500">رُوجع</span><span><?php echo e($pr->reviewed_at->format('Y/m/d H:i')); ?></span></div><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\dev-workflow\pull-request-show.blade.php ENDPATH**/ ?>