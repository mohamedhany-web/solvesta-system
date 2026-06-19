

<?php $__env->startSection('page-title', 'بيئة التطوير'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $githubConfigured = \App\Services\GitHubSettings::isConfigured();
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'بيئة التطوير',
        'subtitle' => 'المهام → الفروع → Pull Request → مراجعة → QA → نشر',
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(!$githubConfigured): ?>
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        <strong>GitHub غير مربوط.</strong>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-github-integration')): ?>
        <a href="<?php echo e(route('github.index')); ?>" class="font-bold underline mr-1">اربط GitHub من الإعدادات</a>
        <?php else: ?>
        اطلب من المسؤول ربط GitHub من صفحة تكامل GitHub.
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-github-integration')): ?>
    <?php echo $__env->make('dev-workflow.partials.git-access-requests', [
        'accessRequests' => $accessRequests,
        'pendingCount' => $pendingAccessCount,
        'themeColor' => $themeColor,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
    <?php echo $__env->make('dev-workflow.partials.git-identity-form', ['themeColor' => $themeColor], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 mb-8">
        <?php $__currentLoopData = [
            ['مهام نشطة', $stats['assigned'], $themeColor, 'استلام وتنفيذ'],
            ['فروع نشطة', $stats['active_branches'], '#2563eb', 'feature/* bugfix/*'],
            ['PR مفتوحة', $stats['open_prs'], '#d97706', 'بانتظار المراجعة'],
            ['موافق عليها', $stats['approved_prs'], '#7c3aed', 'جاهزة للدمج'],
            ['قيد QA', $stats['in_review'], '#0891b2', 'مهام review'],
            ['مُدمجة هذا الأسبوع', $stats['merged_this_week'], '#059669', 'main'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color, $sub]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
            <p class="text-[10px] text-gray-500"><?php echo e($label); ?></p>
            <p class="text-2xl font-bold mt-0.5" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
            <p class="text-[10px] text-gray-400 mt-1"><?php echo e($sub); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mb-8 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h2 class="font-bold text-gray-900 mb-4 text-sm">مسار العمل الاحترافي</h2>
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <?php $__currentLoopData = ['Task من PM/Lead', 'فرع Git', 'Commits', 'Pull Request', 'Code Review', 'QA', 'Deploy']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 font-semibold"><?php echo e($step); ?></span>
            <?php if(!$loop->last): ?><span class="text-gray-300">→</span><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-7">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-bold">مراجعة الكود — PRs بانتظار الإجراء</h2>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('review-code')): ?>
                    <span class="text-xs text-gray-500"><?php echo e($pendingReviews->count()); ?> معلّقة</span>
                    <?php endif; ?>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $pendingReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-5 py-4 hover:bg-blue-50/30 transition">
                        <div class="flex justify-between gap-3 items-start">
                            <div class="min-w-0">
                                <a href="<?php echo e(route('dev-workflow.pull-requests.show', $pr)); ?>" class="font-bold text-sm hover:underline" style="color: <?php echo e($themeColor); ?>;">
                                    <?php echo e($pr->title); ?>

                                </a>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?php echo e($pr->repository?->project?->name); ?> · <?php echo e($pr->source_branch); ?> → <?php echo e($pr->target_branch); ?>

                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">بواسطة <?php echo e($pr->author?->name); ?> · <?php echo e($pr->created_at?->diffForHumans()); ?></p>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded-full <?php echo e($pr->statusBadgeClass()); ?> shrink-0"><?php echo e($pr->statusLabelAr()); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-5 py-10 text-center text-gray-500 text-sm">لا توجد Pull Requests بانتظار المراجعة.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="xl:col-span-5 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-sm">فروع نشطة</h2>
                </div>
                <ul class="divide-y divide-gray-100 text-sm">
                    <?php $__empty_1 = true; $__currentLoopData = $recentBranches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="px-5 py-3">
                        <code class="text-xs font-bold text-gray-800" dir="ltr"><?php echo e($branch->name); ?></code>
                        <p class="text-xs text-gray-500 mt-1"><?php echo e($branch->repository?->project?->name); ?> · <?php echo e($branch->creator?->name); ?></p>
                        <?php if($branch->task): ?>
                        <a href="<?php echo e(route('tasks.show', $branch->task)); ?>" class="text-xs hover:underline" style="color: <?php echo e($themeColor); ?>;"><?php echo e(Str::limit($branch->task->title, 40)); ?></a>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="px-5 py-8 text-center text-gray-500 text-xs">لا توجد فروع نشطة.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-sm">آخر PRs مُدمجة</h2>
                </div>
                <ul class="divide-y divide-gray-100 text-sm">
                    <?php $__empty_1 = true; $__currentLoopData = $recentMerged; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="px-5 py-3 flex justify-between gap-2">
                        <span class="truncate"><?php echo e($pr->title); ?></span>
                        <span class="text-xs text-gray-400 shrink-0"><?php echo e($pr->merged_at?->format('m/d')); ?></span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="px-5 py-8 text-center text-gray-500 text-xs">لا دمج حديث.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\dev-workflow\index.blade.php ENDPATH**/ ?>