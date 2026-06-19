<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['task', 'teamView' => false, 'themeColor' => '#2563eb']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['task', 'teamView' => false, 'themeColor' => '#2563eb']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $priorityColors = [
        'urgent' => 'border-r-red-500',
        'high' => 'border-r-orange-500',
        'medium' => 'border-r-yellow-500',
        'low' => 'border-r-green-500',
    ];
    $priorityLabels = ['urgent' => 'Critical', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low'];
    $priorityBorder = $priorityColors[$task->priority] ?? 'border-r-gray-300';
    $activeBranch = $task->gitBranches->first();
    $openPr = $task->pullRequests->first();
    $searchText = strtolower($task->task_key.' '.$task->title.' '.($task->project->name ?? ''));
?>

<article
    class="workspace-task-card group bg-white rounded-xl border border-gray-200 border-r-4 <?php echo e($priorityBorder); ?> p-3 shadow-sm hover:shadow-md transition-all cursor-grab active:cursor-grabbing"
    draggable="true"
    data-task-id="<?php echo e($task->id); ?>"
    data-status="<?php echo e(\App\Models\Task::normalizeStatus($task->status)); ?>"
    data-priority="<?php echo e($task->priority); ?>"
    data-search="<?php echo e($searchText); ?>"
>
    <div class="flex items-start justify-between gap-2 mb-1.5">
        <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-[11px] font-mono font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;" onclick="event.stopPropagation()">
            <?php echo e($task->task_key); ?>

        </a>
        <div class="flex items-center gap-1 shrink-0">
            <?php if($task->has_blocker): ?>
                <span class="text-[9px] px-1 py-0.5 rounded bg-red-100 text-red-700 font-bold" title="عائق">🚧</span>
            <?php endif; ?>
            <?php if($task->is_overdue): ?>
                <span class="text-[9px] px-1 py-0.5 rounded bg-red-100 text-red-700 font-medium">متأخر</span>
            <?php endif; ?>
        </div>
    </div>

    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="block mb-2" onclick="event.stopPropagation()">
        <h4 class="text-sm font-semibold text-gray-900 leading-snug group-hover:opacity-80 line-clamp-2"><?php echo e($task->title); ?></h4>
    </a>

    <div class="flex flex-wrap gap-1 mb-2">
        <span class="text-[10px] px-1.5 py-0.5 rounded font-medium bg-gray-100 text-gray-600"><?php echo e($priorityLabels[$task->priority] ?? $task->priority); ?></span>
        <?php if($task->milestone): ?>
            <span class="text-[10px] px-1.5 py-0.5 rounded bg-violet-50 text-violet-700 font-medium"><?php echo e(Str::limit($task->milestone->name, 18)); ?></span>
        <?php endif; ?>
        <?php if($activeBranch): ?>
            <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-900 text-white font-mono" dir="ltr"><?php echo e(Str::limit($activeBranch->name, 16)); ?></span>
        <?php endif; ?>
        <?php if($openPr): ?>
            <span class="text-[10px] px-1.5 py-0.5 rounded bg-amber-50 text-amber-800 font-medium">PR #<?php echo e($openPr->number ?? '·'); ?></span>
        <?php endif; ?>
    </div>

    <div class="flex flex-wrap items-center gap-2 text-[11px] text-gray-500 mb-2">
        <span class="inline-flex items-center gap-1 truncate max-w-full">
            <svg class="w-3 h-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <?php echo e(Str::limit($task->project->name ?? '—', 20)); ?>

        </span>
        <?php if($task->estimated_hours): ?>
            <span class="font-medium"><?php echo e($task->estimated_hours); ?>س</span>
        <?php endif; ?>
        <?php if($task->progress_percentage > 0): ?>
            <span><?php echo e($task->progress_percentage); ?>%</span>
        <?php endif; ?>
    </div>

    <div class="flex items-center justify-between gap-2">
        <?php if($teamView && $task->assignedTo): ?>
            <div class="flex items-center gap-1.5 min-w-0">
                <div class="w-6 h-6 rounded-full text-white text-[10px] font-bold flex items-center justify-center shrink-0" style="background: <?php echo e($themeColor); ?>;">
                    <?php echo e(mb_substr($task->assignedTo->name, 0, 1)); ?>

                </div>
                <span class="text-[11px] text-gray-600 truncate"><?php echo e($task->assignedTo->name); ?></span>
            </div>
        <?php else: ?>
            <a href="<?php echo e(route('tasks.show', $task)); ?>" class="text-[10px] font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;" onclick="event.stopPropagation()">فتح المهمة ←</a>
        <?php endif; ?>
        <?php if($task->due_date): ?>
            <span class="text-[11px] shrink-0 <?php echo e($task->is_overdue ? 'text-red-600 font-bold' : 'text-gray-500'); ?>">
                <?php echo e($task->due_date->format('m/d')); ?>

            </span>
        <?php endif; ?>
    </div>
</article>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\workspace\partials\task-card.blade.php ENDPATH**/ ?>