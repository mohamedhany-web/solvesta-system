<div class="space-y-5">
    
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-gray-900">التقرير اليومي</h3>
            <?php if($todayReport): ?>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">مُسجّل ✓</span>
            <?php else: ?>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">مطلوب</span>
            <?php endif; ?>
        </div>
        <div class="p-4">
            <?php if($todayReport): ?>
                <p class="text-xs text-gray-600 line-clamp-2"><?php echo e($todayReport->work_summary); ?></p>
                <p class="text-xs text-gray-400 mt-2"><?php echo e($todayReport->hours_worked); ?> ساعة اليوم</p>
            <?php else: ?>
                <p class="text-xs text-gray-500 mb-3">لم تُسجّل ساعات عملك اليوم بعد.</p>
            <?php endif; ?>
            <a href="<?php echo e(route('daily-reports.index')); ?>" class="block w-full text-center py-2 rounded-xl text-white text-xs font-bold" style="background: <?php echo e($themeColor); ?>;">
                <?php echo e($todayReport ? 'عرض التقارير' : 'اكتب تقرير اليوم'); ?>

            </a>
            <p class="text-[10px] text-gray-400 mt-2 text-center">هذا الأسبوع: <strong class="text-gray-700"><?php echo e($weekHours); ?>س</strong></p>
        </div>
    </div>

    
    <?php if($focusTasks->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm text-gray-900">🎯 تركيز اليوم</h3>
            <p class="text-[10px] text-gray-500">عاجلة أو تستحق خلال 3 أيام</p>
        </div>
        <ul class="divide-y divide-gray-50">
            <?php $__currentLoopData = $focusTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="px-4 py-3 hover:bg-gray-50/80 transition">
                <a href="<?php echo e(route('tasks.show', $ft)); ?>" class="block">
                    <p class="text-[10px] font-mono font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e($ft->task_key); ?></p>
                    <p class="text-xs font-semibold text-gray-900 mt-0.5 line-clamp-2"><?php echo e($ft->title); ?></p>
                    <p class="text-[10px] text-gray-500 mt-1"><?php echo e($ft->project->name ?? ''); ?> · <?php echo e(\App\Models\Task::statusLabelAr($ft->status)); ?></p>
                </a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    
    <?php if($blockerTasks->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-red-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-red-100 bg-red-50/50">
            <h3 class="font-bold text-sm text-red-800">🚧 عوائق (<?php echo e($stats['blockers']); ?>)</h3>
        </div>
        <ul class="divide-y divide-red-50">
            <?php $__currentLoopData = $blockerTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="px-4 py-3">
                <a href="<?php echo e(route('tasks.show', $bt)); ?>" class="text-xs font-semibold text-gray-900 hover:underline"><?php echo e($bt->title); ?></a>
                <?php if($bt->blocker_description): ?>
                <p class="text-[10px] text-red-600 mt-1 line-clamp-2"><?php echo e($bt->blocker_description); ?></p>
                <?php endif; ?>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-dev-workflow')): ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-github-integration')): ?>
    <?php echo $__env->make('dev-workflow.partials.git-access-requests', [
        'accessRequests' => $accessRequests,
        'pendingCount' => $pendingAccessCount,
        'themeColor' => $themeColor,
        'compact' => true,
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
    <?php echo $__env->make('dev-workflow.partials.git-identity-form', ['themeColor' => $themeColor], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-sm">فروعي النشطة</h3>
            <a href="<?php echo e(route('dev-workflow.index')); ?>" class="text-[10px] font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;">الكل</a>
        </div>
        <ul class="divide-y divide-gray-50 text-xs">
            <?php $__empty_1 = true; $__currentLoopData = $myBranches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="px-4 py-3">
                <code class="text-[10px] font-bold text-gray-800 block truncate" dir="ltr"><?php echo e($branch->name); ?></code>
                <p class="text-[10px] text-gray-500 mt-1"><?php echo e($branch->repository?->project?->name); ?></p>
                <?php if($branch->task): ?>
                <a href="<?php echo e(route('tasks.show', $branch->task)); ?>" class="text-[10px] hover:underline" style="color: <?php echo e($themeColor); ?>;"><?php echo e(Str::limit($branch->task->title, 30)); ?></a>
                <?php endif; ?>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="px-4 py-6 text-center text-gray-400 text-[11px]">لا فروع — أنشئ فرعاً من صفحة المهمة</li>
            <?php endif; ?>
        </ul>
    </div>

    
    <?php if($myPullRequests->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">Pull Requests الخاصة بي</h3>
        </div>
        <ul class="divide-y divide-gray-50">
            <?php $__currentLoopData = $myPullRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="px-4 py-3">
                <a href="<?php echo e(route('dev-workflow.pull-requests.show', $pr)); ?>" class="text-xs font-semibold hover:underline line-clamp-2" style="color: <?php echo e($themeColor); ?>;"><?php echo e($pr->title); ?></a>
                <span class="text-[10px] mt-1 inline-block px-1.5 py-0.5 rounded <?php echo e($pr->statusBadgeClass()); ?>"><?php echo e($pr->statusLabelAr()); ?></span>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('review-code')): ?>
    <?php if($prsToReview->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-amber-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-amber-100 bg-amber-50/50">
            <h3 class="font-bold text-sm text-amber-900">مراجعة مطلوبة</h3>
        </div>
        <ul class="divide-y divide-amber-50">
            <?php $__currentLoopData = $prsToReview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="px-4 py-3">
                <a href="<?php echo e(route('dev-workflow.pull-requests.show', $pr)); ?>" class="text-xs font-semibold text-gray-900 hover:underline"><?php echo e(Str::limit($pr->title, 40)); ?></a>
                <p class="text-[10px] text-gray-500 mt-1"><?php echo e($pr->author?->name); ?></p>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    <?php if($projectRepos->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">مستودعات المشاريع</h3>
        </div>
        <ul class="divide-y divide-gray-50">
            <?php $__currentLoopData = $projectRepos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="px-4 py-3">
                <p class="text-xs font-bold text-gray-900"><?php echo e($repo->project?->name); ?></p>
                <p class="text-[10px] font-mono text-gray-600 mt-0.5" dir="ltr"><?php echo e($repo->fullName()); ?></p>
                <button type="button" onclick="navigator.clipboard.writeText('git clone <?php echo e($repo->cloneUrl()); ?>');this.textContent='تم النسخ!';"
                        class="mt-2 text-[10px] font-bold px-2 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
                    نسخ git clone
                </button>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    <?php if($recentActivity->isNotEmpty()): ?>
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">آخر النشاط</h3>
        </div>
        <ul class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
            <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="px-4 py-2.5">
                <p class="text-[11px] text-gray-800 line-clamp-2"><?php echo e($act->comment); ?></p>
                <p class="text-[10px] text-gray-400 mt-0.5"><?php echo e($act->user?->name); ?> · <?php echo e($act->created_at?->diffForHumans()); ?></p>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    
    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <h3 class="font-bold text-sm text-gray-900 mb-3">اختصارات</h3>
        <div class="grid grid-cols-2 gap-2">
            <a href="<?php echo e(route('tasks.index')); ?>" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">المهام</a>
            <a href="<?php echo e(route('projects.index')); ?>" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">المشاريع</a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-dev-workflow')): ?>
            <a href="<?php echo e(route('dev-workflow.index')); ?>" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">Git / PR</a>
            <?php endif; ?>
            <a href="<?php echo e(route('messages.index')); ?>" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">الرسائل</a>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\workspace\partials\sidebar.blade.php ENDPATH**/ ?>