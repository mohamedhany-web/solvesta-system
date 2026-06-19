<?php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $repository = $task->project?->activeRepository();
    $branches = $task->gitBranches()->with('repository')->latest()->get();
    $pullRequests = $task->pullRequests()->with('author')->latest()->get();
    $suggestedBranch = app(\App\Services\DevWorkflowService::class)->suggestBranchName($task, 'feature');
?>
<?php if($repository && (auth()->user()->can('create-git-branches') || $branches->isNotEmpty())): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/80">
        <h3 class="font-bold text-gray-900 text-sm">سير عمل Git</h3>
        <p class="text-xs text-gray-500 mt-0.5"><?php echo e($repository->fullName()); ?></p>
    </div>
    <div class="p-5 space-y-4">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-git-branches')): ?>
        <?php if(!$branches->where('status', 'active')->count()): ?>
        <form method="POST" action="<?php echo e(route('dev-workflow.branches.store', $task)); ?>" class="space-y-3">
            <?php echo csrf_field(); ?>
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-1">نوع الفرع</label>
                <select name="branch_type" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">
                    <option value="feature">feature — ميزة جديدة</option>
                    <option value="bugfix">bugfix — إصلاح</option>
                    <option value="hotfix">hotfix — عاجل</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-1">اسم الفرع (اختياري)</label>
                <input name="branch_name" value="<?php echo e($suggestedBranch); ?>" dir="ltr"
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs font-mono">
            </div>
            <button type="submit" class="w-full py-2 rounded-xl text-white text-sm font-bold" style="background: <?php echo e($themeColor); ?>;">
                إنشاء فرع للمهمة
            </button>
        </form>
        <?php endif; ?>
        <?php endif; ?>

        <?php $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="rounded-xl border border-gray-200 p-3 text-sm">
            <div class="flex justify-between items-start gap-2">
                <code class="text-xs font-bold break-all" dir="ltr"><?php echo e($branch->name); ?></code>
                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-gray-100"><?php echo e($branch->statusLabelAr()); ?></span>
            </div>
            <?php if($branch->githubUrl()): ?>
            <a href="<?php echo e($branch->githubUrl()); ?>" target="_blank" rel="noopener" class="text-xs mt-2 inline-block hover:underline" style="color: <?php echo e($themeColor); ?>;">عرض على GitHub</a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-pull-requests')): ?>
            <?php if($branch->status === 'active' && !$branch->pullRequests()->whereNotIn('status', ['closed'])->exists()): ?>
            <form method="POST" action="<?php echo e(route('dev-workflow.pull-requests.store', $branch)); ?>" class="mt-3">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full py-1.5 rounded-lg border border-gray-300 text-xs font-bold hover:bg-gray-50">
                    فتح Pull Request
                </button>
            </form>
            <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php $__currentLoopData = $pullRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('dev-workflow.pull-requests.show', $pr)); ?>" class="block rounded-xl border border-blue-100 bg-blue-50/50 p-3 hover:bg-blue-50 transition">
            <p class="text-xs font-bold text-gray-900"><?php echo e(Str::limit($pr->title, 50)); ?></p>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full mt-1 inline-block <?php echo e($pr->statusBadgeClass()); ?>"><?php echo e($pr->statusLabelAr()); ?></span>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="text-[10px] text-gray-500 leading-relaxed border-t border-gray-100 pt-3">
            <strong>الخطوات:</strong> فرع → commit & push → PR → مراجعة Team Lead → QA → نشر
        </div>
    </div>
</div>
<?php elseif($task->project && auth()->user()->can('manage-project-repos')): ?>
<p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl p-3">
    اربط مستودع GitHub من <a href="<?php echo e(route('projects.show', $task->project)); ?>" class="font-bold underline">صفحة المشروع</a> لتفعيل سير العمل.
</p>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\dev-workflow\partials\task-panel.blade.php ENDPATH**/ ?>