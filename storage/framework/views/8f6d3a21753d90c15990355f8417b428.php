<?php $__env->startSection('page-title', 'إدارة المشاريع'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $statusLabels = [
        'planning' => 'تخطيط',
        'in_progress' => 'قيد التنفيذ',
        'on_hold' => 'معلق',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
    ];
    $statusColors = [
        'planning' => 'bg-amber-100 text-amber-900',
        'in_progress' => 'bg-blue-100 text-blue-900',
        'on_hold' => 'bg-orange-100 text-orange-900',
        'completed' => 'bg-emerald-100 text-emerald-900',
        'cancelled' => 'bg-red-100 text-red-900',
    ];
    $priorityLabels = [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'عالية',
        'urgent' => 'عاجلة',
    ];
    $cardColors = ['#2563eb', '#059669', '#7c3aed', '#d97706', '#db2777', '#0891b2'];
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'إدارة المشاريع',
        'subtitle' => 'تتبع المشاريع، التقدم، الفريق، والربط مع PMO والمالية',
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-projects')): ?>
        <a href="<?php echo e(route('projects.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            مشروع جديد
        </a>
        <?php endif; ?>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = [
            ['إجمالي المشاريع', $stats['total'], $themeColor],
            ['نشطة', $stats['active'], '#059669'],
            ['مكتملة', $stats['completed'], '#7c3aed'],
            ['معلقة', $stats['on_hold'], '#d97706'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500"><?php echo e($label); ?></p>
            <p class="text-3xl font-bold mt-1" style="color: <?php echo e($color); ?>;"><?php echo e($val); ?></p>
            <?php if($label === 'نشطة' && $stats['total'] > 0): ?>
                <p class="text-xs text-gray-400 mt-1"><?php echo e($stats['percentage_active']); ?>% من الإجمالي</p>
            <?php elseif($label === 'مكتملة' && $stats['total'] > 0): ?>
                <p class="text-xs text-gray-400 mt-1"><?php echo e($stats['percentage_completed']); ?>% من الإجمالي</p>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if($featuredProjects->count() > 0): ?>
    <div class="mb-6">
        <h2 class="text-sm font-bold text-gray-600 mb-3">أحدث المشاريع</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            <?php $__currentLoopData = $featuredProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $color = $cardColors[$index % count($cardColors)];
                $totalTasks = $project->tasks->count();
                $completedTasks = $project->tasks->where('status', 'completed')->count();
                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : ($project->progress_percentage ?? 0);
            ?>
            <a href="<?php echo e(route('projects.show', $project)); ?>" class="block bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md hover:border-blue-200 transition-all">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center text-white font-bold shrink-0" style="background: <?php echo e($color); ?>;">
                            <?php echo e(mb_substr($project->name, 0, 1)); ?>

                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-gray-900 truncate"><?php echo e($project->name); ?></h3>
                            <p class="text-xs text-gray-500 truncate"><?php echo e($project->client->name ?? 'بدون عميل'); ?></p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded-full shrink-0 <?php echo e($statusColors[$project->status] ?? 'bg-gray-100 text-gray-700'); ?>">
                        <?php echo e($statusLabels[$project->status] ?? $project->status); ?>

                    </span>
                </div>
                <p class="text-sm text-gray-600 line-clamp-2 mb-3"><?php echo e($project->description ?: 'لا يوجد وصف'); ?></p>
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
                    <span><?php echo e($completedTasks); ?>/<?php echo e($totalTasks); ?> مهام</span>
                    <span class="font-bold"><?php echo e($progress); ?>%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="width: <?php echo e($progress); ?>%; background: <?php echo e($color); ?>;"></div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <form method="GET" action="<?php echo e(route('projects.index')); ?>" class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
        <div class="flex-1 min-w-[12rem]">
            <label class="text-xs font-bold text-gray-600 block mb-1">بحث</label>
            <input name="search" value="<?php echo e(request('search')); ?>" placeholder="اسم المشروع أو الوصف..."
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">القسم</label>
            <select name="department_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($department->id); ?>" <?php if(request('department_id') == $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">العميل</label>
            <select name="client_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($client->id); ?>" <?php if(request('client_id') == $client->id): echo 'selected'; endif; ?>><?php echo e($client->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الحالة</label>
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <option value="">الكل</option>
                <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if(request('status') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الأولوية</label>
            <select name="priority" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <option value="">الكل</option>
                <?php $__currentLoopData = $priorityLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($value); ?>" <?php if(request('priority') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-800">تصفية</button>
        <?php if(request()->hasAny(['search', 'status', 'priority', 'client_id', 'department_id'])): ?>
        <a href="<?php echo e(route('projects.index')); ?>" class="border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">إعادة تعيين</a>
        <?php endif; ?>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-2">
            <h2 class="font-bold text-gray-900">جميع المشاريع <span class="text-gray-400 font-normal text-sm">(<?php echo e($projects->total()); ?>)</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المشروع</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">العميل</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">قائد الفريق</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الأولوية</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التقدم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">البداية</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalTasks = $project->tasks()->count();
                        $completedTasks = $project->tasks()->where('status', 'completed')->count();
                        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : ($project->progress_percentage ?? 0);
                    ?>
                    <tr class="hover:bg-blue-50/40 transition-colors align-middle">
                        <td class="px-4 py-3">
                            <a href="<?php echo e(route('projects.show', $project)); ?>" class="font-bold text-gray-900 hover:text-blue-700"><?php echo e($project->name); ?></a>
                            <?php if($project->description): ?>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1"><?php echo e($project->description); ?></p>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($project->client): ?>
                                <a href="<?php echo e(route('clients.show', $project->client)); ?>" class="text-blue-600 hover:underline"><?php echo e($project->client->name); ?></a>
                            <?php else: ?>
                                <span class="text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-gray-700"><?php echo e($project->department->name ?? '—'); ?></td>
                        <td class="px-4 py-3">
                            <?php if($project->projectManager): ?>
                                <span class="text-gray-700"><?php echo e($project->projectManager->name); ?></span>
                            <?php else: ?>
                                <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-900 px-2 py-0.5 text-xs font-bold">بانتظار رئيس القسم</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold text-gray-600"><?php echo e($priorityLabels[$project->priority] ?? $project->priority); ?></span>
                        </td>
                        <td class="px-4 py-3 min-w-[7rem]">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: <?php echo e($progress); ?>%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8"><?php echo e($progress); ?>%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                            <?php echo e($project->start_date ? $project->start_date->format('Y/m/d') : '—'); ?>

                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full <?php echo e($statusColors[$project->status] ?? 'bg-gray-100 text-gray-700'); ?>">
                                <?php echo e($statusLabels[$project->status] ?? $project->status); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="text-xs font-bold text-blue-600 hover:underline">عرض</a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-projects')): ?>
                                <a href="<?php echo e(route('projects.edit', $project)); ?>" class="text-xs font-bold text-gray-600 hover:underline">تعديل</a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-projects')): ?>
                                <form action="<?php echo e(route('projects.destroy', $project)); ?>" method="POST" class="inline" onsubmit="return confirm('حذف المشروع «<?php echo e($project->name); ?>»؟');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-xs font-bold text-red-600 hover:underline">حذف</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا توجد مشاريع</p>
                            <p class="text-sm"><?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-projects')): ?><a href="<?php echo e(route('projects.create')); ?>" class="text-blue-600 font-semibold hover:underline">أنشئ مشروعاً جديداً</a> للبدء.<?php else: ?> لا توجد مشاريع مرتبطة بك حالياً.<?php endif; ?></p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($projects->hasPages()): ?>
        <div class="px-4 py-3 border-t border-gray-100"><?php echo e($projects->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\projects\index.blade.php ENDPATH**/ ?>