<?php $__env->startSection('page-title', 'إدارة التصميم'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة التصميم</h1>
                <p class="text-gray-600">إدارة مشاريع التصميم والأعمال الفنية</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-design')): ?>
            <a href="<?php echo e(route('projects.create')); ?>?type=design" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                مشروع تصميم جديد
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المشاريع</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total']); ?></p>
                    <p class="text-xs text-blue-600 mt-1">جميع مشاريع التصميم</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">قيد التنفيذ</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['active']); ?></p>
                    <p class="text-xs text-orange-600 mt-1">مشاريع نشطة</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">مكتملة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['completed']); ?></p>
                    <p class="text-xs text-green-600 mt-1">تم الإنجاز بنجاح</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Designers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">المصممين</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($designers->count()); ?></p>
                    <p class="text-xs text-purple-600 mt-1">فريق التصميم</p>
                </div>
                <div class="p-4 bg-purple-50 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Design Projects Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">قائمة مشاريع التصميم (<?php echo e($projects->total()); ?>)</h3>
                <form method="GET" action="<?php echo e(route('design.index')); ?>" class="flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">جميع الحالات</option>
                        <option value="planning" <?php echo e(request('status') == 'planning' ? 'selected' : ''); ?>>تخطيط</option>
                        <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>قيد التنفيذ</option>
                        <option value="on_hold" <?php echo e(request('status') == 'on_hold' ? 'selected' : ''); ?>>معلق</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>مكتمل</option>
                    </select>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="البحث عن مشروع..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">بحث</button>
                    <?php if(request('search') || request('status')): ?>
                    <a href="<?php echo e(route('design.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">إلغاء</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم المشروع</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المصمم</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التقدم</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $totalTasks = $project->tasks()->count();
                        $completedTasks = $project->tasks()->where('status', 'completed')->count();
                        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                        
                        $statusColors = [
                            'planning' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'on_hold' => 'bg-orange-100 text-orange-800',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ];
                        
                        $statusNames = [
                            'planning' => 'تخطيط',
                            'in_progress' => 'قيد التنفيذ',
                            'on_hold' => 'معلق',
                            'completed' => 'مكتمل',
                            'cancelled' => 'ملغي',
                        ];
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($project->name); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e(Str::limit($project->description, 50)); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e($project->client->name ?? 'غير محدد'); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($project->projectManager): ?>
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center ml-2">
                                    <span class="text-xs font-medium text-white"><?php echo e(mb_substr($project->projectManager->name, 0, 1)); ?></span>
                                </div>
                                <div class="text-sm text-gray-900"><?php echo e($project->projectManager->name); ?></div>
                            </div>
                            <?php else: ?>
                            <span class="text-sm text-gray-500">غير مكلف</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 ml-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($progress); ?>%"></div>
                                </div>
                                <span class="text-xs text-gray-500"><?php echo e($progress); ?>%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColors[$project->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($statusNames[$project->status] ?? $project->status); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-design')): ?>
                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    عرض
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-design')): ?>
                                <a href="<?php echo e(route('projects.edit', $project)); ?>" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    تعديل
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                                </svg>
                                <p class="text-lg font-medium">لا توجد مشاريع تصميم</p>
                                <p class="text-sm">قم بإنشاء مشروع تصميم جديد للبدء</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($projects->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($projects->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\design\index.blade.php ENDPATH**/ ?>