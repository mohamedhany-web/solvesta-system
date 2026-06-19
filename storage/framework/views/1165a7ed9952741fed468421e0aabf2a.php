

<?php $__env->startSection('page-title', 'مساحة عملي'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
?>
<div class="w-full max-w-full font-tajawal" id="workspace-app">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'مساحة عمل المطوّر',
        'subtitle' => ($teamView ? 'عرض الفريق' : 'مهامي') . ' · Kanban · Git · تقارير · ' . now()->translatedFormat('l j F'),
        'icon' => 'workspace',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="flex flex-wrap gap-2 mb-6 -mt-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-dev-workflow')): ?>
        <a href="<?php echo e(route('dev-workflow.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-90 transition" style="background: <?php echo e($themeColor); ?>;">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
            بيئة التطوير
        </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-github-integration')): ?>
        <a href="<?php echo e(route('github.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-bold shadow-sm hover:bg-gray-800">
            تكامل GitHub
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('daily-reports.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">
            التقرير اليومي
            <?php if (! ($todayReport)): ?><span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span><?php endif; ?>
        </a>
        <a href="<?php echo e(route('tasks.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">قائمة المهام</a>
        <a href="<?php echo e(route('projects.index')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">المشاريع</a>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-dev-workflow')): ?>
    <?php if(!\App\Services\GitHubSettings::isConfigured()): ?>
    <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 flex flex-wrap items-center gap-2">
        <strong>GitHub غير مربوط.</strong>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-github-integration')): ?>
        <a href="<?php echo e(route('github.index')); ?>" class="font-bold underline">اربط من صفحة تكامل GitHub</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-8 gap-3 mb-6">
        <?php $__currentLoopData = [
            ['مفتوحة', $stats['open'], $themeColor, 'مهام نشطة'],
            ['تنفيذ', $stats['in_progress'], '#2563eb', 'In Progress'],
            ['Code Review', $stats['code_review'], '#7c3aed', 'مراجعة كود'],
            ['QA', $stats['qa_testing'], '#0891b2', 'اختبار'],
            ['مكتملة', $stats['done'], '#059669', 'Done'],
            ['متأخرة', $stats['overdue'], '#dc2626', 'تحتاج انتباه'],
            ['عوائق', $stats['blockers'], '#ea580c', 'Blockers'],
            ['ساعات', $stats['estimated_hours'].'س', '#d97706', 'متوقعة'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $color, $sub]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-[10px] text-gray-500 font-medium"><?php echo e($label); ?></p>
            <p class="text-xl font-bold mt-0.5" style="color: <?php echo e($color); ?>;"><?php echo e($value); ?></p>
            <p class="text-[9px] text-gray-400 mt-0.5 truncate"><?php echo e($sub); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-dev-workflow')): ?>
    <?php if(!empty($devStats)): ?>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <?php $__currentLoopData = [
            ['مهام Git', $devStats['assigned'] ?? 0, 'تنفيذ'],
            ['فروع', $devStats['active_branches'] ?? 0, 'نشطة'],
            ['PR مفتوحة', $devStats['open_prs'] ?? 0, 'مراجعة'],
            ['موافق', $devStats['approved_prs'] ?? 0, 'دمج'],
            ['QA', $devStats['in_review'] ?? 0, 'اختبار'],
            ['مُدمجة', $devStats['merged_this_week'] ?? 0, 'هذا الأسبوع'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $sub]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-gray-900 rounded-2xl p-3 text-white shadow-sm">
            <p class="text-[10px] text-gray-400"><?php echo e($label); ?></p>
            <p class="text-xl font-bold"><?php echo e($val); ?></p>
            <p class="text-[9px] text-gray-500"><?php echo e($sub); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    
    <div class="mb-6 bg-white border border-gray-200 rounded-2xl p-4 shadow-sm hidden md:block">
        <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold text-gray-600">
            <?php $__currentLoopData = \App\Models\Task::workflowStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($i > 0): ?><span class="text-gray-300">→</span><?php endif; ?>
                <span class="px-2.5 py-1 rounded-lg bg-gray-50 border border-gray-100"><?php echo e(\App\Models\Task::statusLabelAr($status)); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        <div class="xl:col-span-8 min-w-0">
            <?php echo $__env->make('workspace.partials.filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <?php if($viewMode === 'list'): ?>
            
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">المهمة</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">المشروع</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">Epic</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">الحالة</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">الأولوية</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">التسليم</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="workspace-list-body">
                            <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="workspace-list-row hover:bg-gray-50/80" data-search="<?php echo e(strtolower($task->task_key.' '.$task->title)); ?>" data-priority="<?php echo e($task->priority); ?>">
                                <td class="px-4 py-3">
                                    <p class="text-[11px] font-mono font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e($task->task_key); ?></p>
                                    <p class="font-semibold text-gray-900 text-xs mt-0.5"><?php echo e($task->title); ?></p>
                                    <?php if($task->has_blocker): ?><span class="text-[10px] text-red-600">🚧 عائق</span><?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600"><?php echo e($task->project->name ?? '—'); ?></td>
                                <td class="px-4 py-3 text-xs text-violet-700"><?php echo e($task->milestone->name ?? '—'); ?></td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100"><?php echo e($task->status_label_ar); ?></span>
                                </td>
                                <td class="px-4 py-3 text-xs capitalize"><?php echo e($task->priority); ?></td>
                                <td class="px-4 py-3 text-xs <?php echo e($task->is_overdue ? 'text-red-600 font-bold' : 'text-gray-500'); ?>">
                                    <?php echo e($task->due_date?->format('Y-m-d') ?? '—'); ?>

                                </td>
                                <td class="px-4 py-3">
                                    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-xs font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;">فتح</a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" class="px-4 py-12 text-center text-gray-500 text-sm">لا مهام — تحقق من الفلاتر أو انتظر تعيين مهام من Team Lead</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            
            <div class="overflow-x-auto pb-4 -mx-1 px-1" id="workspace-kanban">
                <div class="flex gap-3 min-w-max xl:min-w-0 xl:grid xl:grid-cols-7 xl:gap-2">
                    <?php $__currentLoopData = \App\Models\Task::workflowStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $styles = \App\Models\Task::columnStyles()[$status];
                            $columnTasks = $board[$status] ?? collect();
                        ?>
                        <div class="workspace-column w-[260px] xl:w-auto flex-shrink-0 flex flex-col max-h-[calc(100vh-280px)] min-h-[380px]" data-status="<?php echo e($status); ?>">
                            <div class="rounded-t-xl px-3 py-2 <?php echo e($styles['header']); ?> flex items-center justify-between sticky top-0 z-10 border border-b-0 border-gray-200/50">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="w-2 h-2 rounded-full <?php echo e($styles['dot']); ?> shrink-0"></span>
                                    <span class="text-xs font-bold truncate"><?php echo e(\App\Models\Task::statusLabelAr($status)); ?></span>
                                </div>
                                <span class="text-[10px] font-bold bg-white/70 rounded-full px-1.5 py-0.5 column-count shrink-0"><?php echo e($columnTasks->count()); ?></span>
                            </div>
                            <div class="workspace-dropzone flex-1 bg-white border border-gray-200 rounded-b-xl p-2 space-y-2 overflow-y-auto transition-colors min-h-[120px]"
                                 data-status="<?php echo e($status); ?>">
                                <?php $__empty_1 = true; $__currentLoopData = $columnTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php echo $__env->make('workspace.partials.task-card', ['task' => $task, 'teamView' => $teamView, 'themeColor' => $themeColor], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="empty-state text-center py-6 text-gray-300 text-[11px]">
                                        <p>فارغ</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="xl:col-span-4">
            <?php echo $__env->make('workspace.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <p id="workspace-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden px-5 py-2.5 rounded-xl text-sm font-bold shadow-xl"></p>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    let draggedCard = null;

    document.querySelectorAll('.workspace-task-card').forEach(card => {
        card.addEventListener('dragstart', e => {
            draggedCard = card;
            e.dataTransfer.effectAllowed = 'move';
            card.classList.add('opacity-50', 'ring-2', 'ring-blue-300');
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('opacity-50', 'ring-2', 'ring-blue-300');
            draggedCard = null;
            document.querySelectorAll('.workspace-dropzone').forEach(z => z.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50/50'));
        });
    });

    document.querySelectorAll('.workspace-dropzone').forEach(zone => {
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50/50'); });
        zone.addEventListener('dragleave', e => {
            if (!zone.contains(e.relatedTarget)) zone.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50/50');
        });
        zone.addEventListener('drop', async e => {
            e.preventDefault();
            zone.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50/50');
            if (!draggedCard) return;
            const newStatus = zone.dataset.status;
            const oldStatus = draggedCard.dataset.status;
            const taskId = draggedCard.dataset.taskId;
            if (newStatus === oldStatus) return;
            const empty = zone.querySelector('.empty-state');
            if (empty) empty.remove();
            zone.prepend(draggedCard);
            draggedCard.dataset.status = newStatus;
            updateColumnCounts();
            try {
                const res = await fetch(`<?php echo e(url('workspace/tasks')); ?>/${taskId}/status`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ status: newStatus }),
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'فشل التحديث');
                showToast(data.message, 'success');
            } catch (err) {
                showToast(err.message || 'تعذر تحديث الحالة', 'error');
                setTimeout(() => location.reload(), 1500);
            }
        });
    });

    function updateColumnCounts() {
        document.querySelectorAll('.workspace-column').forEach(col => {
            const count = col.querySelectorAll('.workspace-task-card:not([style*="display: none"])').length;
            const badge = col.querySelector('.column-count');
            if (badge) badge.textContent = count;
            const zone = col.querySelector('.workspace-dropzone');
            const visible = col.querySelectorAll('.workspace-task-card:not([style*="display: none"])').length;
            if (visible === 0 && zone && !zone.querySelector('.empty-state')) {
                zone.innerHTML = '<div class="empty-state text-center py-6 text-gray-300 text-[11px]"><p>فارغ</p></div>';
            }
        });
    }

    const searchInput = document.getElementById('workspace-search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('.workspace-task-card').forEach(card => {
                const match = !q || (card.dataset.search || '').includes(q);
                card.style.display = match ? '' : 'none';
            });
            document.querySelectorAll('.workspace-list-row').forEach(row => {
                const match = !q || (row.dataset.search || '').includes(q);
                row.style.display = match ? '' : 'none';
            });
            updateColumnCounts();
        });
    }

    function showToast(msg, type) {
        const el = document.getElementById('workspace-toast');
        if (!el) return;
        el.textContent = msg;
        el.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-2.5 rounded-xl text-sm font-bold shadow-xl ' +
            (type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white');
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 2800);
    }
})();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\workspace\index.blade.php ENDPATH**/ ?>