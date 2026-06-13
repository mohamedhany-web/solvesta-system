<?php $__env->startSection('page-title', 'إدارة المهام'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">إدارة المهام</h1>
                <p class="text-sm sm:text-base text-gray-600">تتبع وإدارة مهام المشاريع والموظفين</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-tasks')): ?>
            <a href="<?php echo e(route('tasks.create')); ?>" class="bg-blue-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                مهمة جديدة
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المهام</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total'] ?? 0); ?></p>
                    <p class="text-xs text-blue-600 mt-1">جميع المهام</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">مهام مكتملة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['completed'] ?? 0); ?></p>
                    <p class="text-xs text-green-600 mt-1"><?php echo e(($stats['total'] ?? 0) > 0 ? round((($stats['completed'] ?? 0) / ($stats['total'] ?? 1)) * 100, 1) : 0); ?>% من إجمالي المهام</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">قيد التنفيذ</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['in_progress'] ?? 0); ?></p>
                    <p class="text-xs text-orange-600 mt-1"><?php echo e(($stats['total'] ?? 0) > 0 ? round((($stats['in_progress'] ?? 0) / ($stats['total'] ?? 1)) * 100, 1) : 0); ?>% من إجمالي المهام</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">مهام معلقة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['pending'] ?? 0); ?></p>
                    <p class="text-xs text-red-600 mt-1">يحتاج للمراجعة</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">قائمة المهام</h3>
                <div class="flex items-center gap-2">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>جميع الحالات</option>
                        <option>مكتمل</option>
                        <option>قيد التنفيذ</option>
                        <option>معلق</option>
                    </select>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>جميع المشاريع</option>
                        <option>موقع الشركة</option>
                        <option>تطبيق الجوال</option>
                        <option>نظام إدارة</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">اسم المهمة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">المشروع</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">المسؤول</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">الأولوية</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">التقدم</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">الحالة</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 w-1/6">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($task->title); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e(Str::limit($task->description, 50)); ?></div>
                        </td>
                        <td class="px-6 py-4 w-1/6">
                            <?php if($task->project): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo e($task->project->name); ?>

                            </span>
                            <?php else: ?>
                            <span class="text-sm text-gray-500">لا يوجد مشروع</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 w-1/6">
                            <?php if($task->assignedTo): ?>
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center ml-2">
                                    <span class="text-xs font-medium text-white"><?php echo e(substr($task->assignedTo->name, 0, 1)); ?></span>
                                </div>
                                <div class="text-sm text-gray-900"><?php echo e($task->assignedTo->name); ?></div>
                            </div>
                            <?php else: ?>
                            <span class="text-sm text-gray-500">غير محدد</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 w-1/6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?php if($task->priority == 'high'): ?> bg-red-100 text-red-800
                                <?php elseif($task->priority == 'medium'): ?> bg-yellow-100 text-yellow-800
                                <?php elseif($task->priority == 'low'): ?> bg-green-100 text-green-800
                                <?php else: ?> bg-gray-100 text-gray-800
                                <?php endif; ?>">
                                <?php if($task->priority == 'high'): ?> عالية
                                <?php elseif($task->priority == 'medium'): ?> متوسطة
                                <?php elseif($task->priority == 'low'): ?> منخفضة
                                <?php else: ?> <?php echo e($task->priority); ?>

                                <?php endif; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 w-1/6">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 ml-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo e($task->progress_percentage ?? 0); ?>%"></div>
                                </div>
                                <span class="text-xs text-gray-500"><?php echo e($task->progress_percentage ?? 0); ?>%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 w-1/6">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    <?php if($task->status == 'completed'): ?> bg-green-100 text-green-800
                                    <?php elseif($task->status == 'in_progress'): ?> bg-orange-100 text-orange-800
                                    <?php elseif($task->status == 'pending'): ?> bg-yellow-100 text-yellow-800
                                    <?php elseif($task->status == 'on_hold'): ?> bg-red-100 text-red-800
                                    <?php else: ?> bg-gray-100 text-gray-800
                                    <?php endif; ?>">
                                    <?php if($task->status == 'completed'): ?> مكتمل
                                    <?php elseif($task->status == 'in_progress'): ?> قيد التنفيذ
                                    <?php elseif($task->status == 'pending'): ?> معلق
                                    <?php elseif($task->status == 'on_hold'): ?> متوقف
                                    <?php else: ?> <?php echo e($task->status); ?>

                                    <?php endif; ?>
                                </span>
                                <div class="flex gap-1">
                                    <a href="<?php echo e(route('tasks.show', $task)); ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        عرض
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-tasks', $task)): ?>
                                    <a href="<?php echo e(route('tasks.edit', $task)); ?>" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مهام</h3>
                                <p class="text-gray-500 mb-4">ابدأ بإنشاء مهمة جديدة لإدارة مشاريعك</p>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-tasks')): ?>
                                <a href="<?php echo e(route('tasks.create')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    إضافة مهمة جديدة
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\tasks\index.blade.php ENDPATH**/ ?>