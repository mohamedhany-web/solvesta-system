<?php $__env->startSection('page-title', 'تقرير المهام'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('reports.index')); ?>" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900">تقرير المهام</h1>
            </div>
            <a href="<?php echo e(route('reports.tasks.print', request()->query())); ?>" target="_blank" class="bg-gray-800 text-white px-6 py-3 rounded-xl hover:bg-gray-900 transition-all duration-200 flex items-center shadow-lg hover:shadow-xl">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                طباعة التقرير
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المهام</p>
            <p class="text-3xl font-bold text-gray-900"><?php echo e($summary['total']); ?></p>
        </div>
        <?php $__currentLoopData = ['pending' => 'معلقة', 'in_progress' => 'قيد التنفيذ', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $statusName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-600 mb-1"><?php echo e($statusName); ?></p>
            <p class="text-3xl font-bold <?php echo e($status === 'completed' ? 'text-green-600' : 'text-gray-900'); ?>">
                <?php echo e($summary['by_status'][$status] ?? 0); ?>

            </p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <!-- Tasks Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المهمة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المشروع</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المسؤول</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأولوية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموعد النهائي</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo e($task->title); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($task->project->name ?? '-'); ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($task->assignedTo->name ?? '-'); ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?php echo e($task->priority === 'high' ? 'bg-red-100 text-red-800' : ''); ?>

                                <?php echo e($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                <?php echo e($task->priority === 'low' ? 'bg-green-100 text-green-800' : ''); ?>">
                                <?php echo e($task->priority); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?php echo e($task->status === 'completed' ? 'bg-green-100 text-green-800' : ''); ?>

                                <?php echo e($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                <?php echo e($task->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>">
                                <?php echo e($task->status); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($task->due_date ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\reports\tasks.blade.php ENDPATH**/ ?>