<?php echo $__env->make('partials.erp-page-header', [
    'title' => 'PMO — '.$project->name,
    'subtitle' => 'Milestones · توزيع العمل · متابعة التقدم',
    'icon' => 'briefcase',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>

<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-5">
        <h2 class="text-lg font-bold font-tajawal">مراحل المشروع (Milestones)</h2>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-projects')): ?>
            <?php if($project->milestones->isEmpty()): ?>
            <form method="POST" action="<?php echo e(route('pmo.projects.seed-milestones', $project)); ?>"><?php echo csrf_field(); ?>
                <button class="px-4 py-2 rounded-xl text-white text-sm font-bold"
                        style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                    إنشاء المراحل الافتراضية (4 Phases)
                </button>
            </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if($project->milestones->isEmpty()): ?>
        <p class="text-sm text-gray-500 font-tajawal">لم تُنشأ مراحل بعد. ابدأ بقالب Phase 1–4 (UI/UX → Backend → Frontend → Testing).</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php $__currentLoopData = $project->milestones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $milestone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border rounded-xl p-4 <?php echo e($milestone->is_overdue ? 'border-red-200 bg-red-50/30' : 'border-gray-200'); ?>">
                <div class="flex flex-wrap justify-between gap-2 mb-3">
                    <div>
                        <h3 class="font-bold text-gray-900"><?php echo e($milestone->name); ?></h3>
                        <p class="text-xs text-gray-500"><?php echo e($milestone->phase_label); ?> · <?php echo e($milestone->status_label); ?>

                            <?php if($milestone->due_date): ?> · حتى <?php echo e($milestone->due_date->format('Y/m/d')); ?><?php endif; ?>
                        </p>
                    </div>
                    <div class="text-left">
                        <span class="text-lg font-bold" style="color:<?php echo e($themeColor); ?>;"><?php echo e($milestone->progress_percentage); ?>%</span>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full mb-3">
                    <div class="h-full rounded-full transition-all" style="width:<?php echo e($milestone->progress_percentage); ?>%; background:<?php echo e($themeColor); ?>;"></div>
                </div>

                <?php if($milestone->tasks->count()): ?>
                <ul class="text-sm space-y-2 mb-3 font-tajawal">
                    <?php $__currentLoopData = $milestone->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex flex-wrap justify-between gap-2 py-2 px-3 bg-gray-50 rounded-lg">
                        <span>
                            <?php echo e($task->title); ?>

                            <?php if($task->has_blocker): ?><span class="text-red-600 text-xs font-bold">⛔ Blocker</span><?php endif; ?>
                        </span>
                        <span class="text-xs text-gray-500"><?php echo e($task->assignedTo?->name); ?> · <?php echo e($task->estimated_hours); ?>س</span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-projects')): ?>
                <details class="text-sm">
                    <summary class="cursor-pointer font-bold text-blue-600 mb-2">+ توزيع مهمة على هذه المرحلة</summary>
                    <form method="POST" action="<?php echo e(route('pmo.projects.assign-task', $project)); ?>" class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2 p-3 bg-gray-50 rounded-xl">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="milestone_id" value="<?php echo e($milestone->id); ?>">
                        <input name="title" required placeholder="عنوان المهمة" class="border rounded-lg px-3 py-2 md:col-span-2">
                        <select name="assigned_to" required class="border rounded-lg px-3 py-2">
                            <option value="">اختر الموظف</option>
                            <?php $__currentLoopData = $teamUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u->id); ?>"><?php echo e($u->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <select name="specialization" class="border rounded-lg px-3 py-2">
                            <option value="backend">Backend</option>
                            <option value="frontend">Frontend</option>
                            <option value="ui_ux">UI/UX</option>
                            <option value="qa">QA</option>
                            <option value="design">Design</option>
                        </select>
                        <input type="number" step="0.5" name="estimated_hours" placeholder="ساعات تقديرية" class="border rounded-lg px-3 py-2">
                        <input type="date" name="due_date" class="border rounded-lg px-3 py-2">
                        <button class="md:col-span-2 py-2 rounded-lg text-white font-bold" style="background:<?php echo e($themeColor); ?>;">توزيع المهمة</button>
                    </form>
                </details>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<?php if(($stats['blockers_count'] ?? 0) > 0): ?>
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 text-sm font-tajawal">
    <strong class="text-red-800">⚠ <?php echo e($stats['blockers_count']); ?> Blocker(s) مفتوحة</strong> في هذا المشروع — راجع لوحة PMO أو حلّها من المهام.
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\projects\partials\pmo-panel.blade.php ENDPATH**/ ?>