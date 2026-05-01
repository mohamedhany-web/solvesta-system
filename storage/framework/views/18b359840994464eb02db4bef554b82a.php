

<?php $__env->startSection('page-title', 'تفاصيل المهمة'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2"><?php echo e($task->title); ?></h1>
            <p class="text-green-100">تفاصيل المهمة المخصصة لك</p>
        </div>
        <a href="<?php echo e(route('employee.tasks')); ?>" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center">
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للمهام
        </a>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل المهمة</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">الوصف</h3>
                        <p class="text-sm text-gray-900"><?php echo e($task->description ?? 'لا يوجد وصف'); ?></p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">الحالة</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php if($task->status == 'completed'): ?> bg-green-100 text-green-800
                                <?php elseif($task->status == 'in_progress'): ?> bg-blue-100 text-blue-800
                                <?php elseif($task->status == 'overdue'): ?> bg-red-100 text-red-800
                                <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                                <?php if($task->status == 'completed'): ?> مكتملة
                                <?php elseif($task->status == 'in_progress'): ?> قيد التنفيذ
                                <?php elseif($task->status == 'overdue'): ?> متأخرة
                                <?php else: ?> معلقة <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">الأولوية</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php if($task->priority == 'urgent'): ?> bg-red-100 text-red-800
                                <?php elseif($task->priority == 'high'): ?> bg-orange-100 text-orange-800
                                <?php elseif($task->priority == 'medium'): ?> bg-yellow-100 text-yellow-800
                                <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                <?php if($task->priority == 'urgent'): ?> عاجلة
                                <?php elseif($task->priority == 'high'): ?> عالية
                                <?php elseif($task->priority == 'medium'): ?> متوسطة
                                <?php elseif($task->priority == 'low'): ?> منخفضة
                                <?php else: ?> <?php echo e($task->priority); ?> <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">تاريخ الإنشاء</span>
                            <span class="text-sm text-gray-900"><?php echo e($task->created_at->format('Y/m/d H:i')); ?></span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">آخر تحديث</span>
                            <span class="text-sm text-gray-900"><?php echo e($task->updated_at->format('Y/m/d H:i')); ?></span>
                        </div>
                        
                        <?php if($task->due_date): ?>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">موعد التسليم</span>
                            <span class="text-sm text-gray-900"><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('Y/m/d H:i')); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Project Information -->
            <?php if($task->project): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">معلومات المشروع</h2>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">اسم المشروع</span>
                        <span class="text-sm text-gray-900"><?php echo e($task->project->name); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">العميل</span>
                        <span class="text-sm text-gray-900"><?php echo e($task->project->client->name ?? 'غير محدد'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">حالة المشروع</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php if($task->project->status == 'active'): ?> bg-green-100 text-green-800
                            <?php elseif($task->project->status == 'completed'): ?> bg-blue-100 text-blue-800
                            <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                            <?php echo e($task->project->status == 'active' ? 'نشط' : ($task->project->status == 'completed' ? 'مكتمل' : 'معلق')); ?>

                        </span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Task Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات المهمة</h3>
                <div class="space-y-3">
                    <?php if($task->status == 'pending'): ?>
                    <form action="<?php echo e(route('employee.tasks.update-status', $task)); ?>" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="status" value="in_progress">
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            بدء العمل
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <?php if($task->status == 'in_progress'): ?>
                    <form action="<?php echo e(route('employee.tasks.update-status', $task)); ?>" method="POST" class="w-full">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            إكمال المهمة
                        </button>
                    </form>
                    <?php endif; ?>
                    
                    <?php if($task->status == 'completed'): ?>
                    <div class="w-full flex items-center justify-center px-4 py-2 bg-gray-100 text-gray-500 rounded-lg">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        مكتملة
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Task Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">تقدم المهمة</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">الحالة الحالية</span>
                        <span class="font-medium text-gray-900">
                            <?php if($task->status == 'completed'): ?> 100%
                            <?php elseif($task->status == 'in_progress'): ?> 50%
                            <?php else: ?> 0% <?php endif; ?>
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                             style="width: <?php echo e($task->status == 'completed' ? '100' : ($task->status == 'in_progress' ? '50' : '0')); ?>%"></div>
                    </div>
                </div>
            </div>

            <!-- Task Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الجدول الزمني</h3>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-2 w-2 bg-green-500 rounded-full ml-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تم إنشاء المهمة</p>
                            <p class="text-xs text-gray-500"><?php echo e($task->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    
                    <?php if($task->status == 'in_progress' || $task->status == 'completed'): ?>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-2 w-2 bg-blue-500 rounded-full ml-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تم بدء العمل</p>
                            <p class="text-xs text-gray-500"><?php echo e($task->updated_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($task->status == 'completed'): ?>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-2 w-2 bg-green-500 rounded-full ml-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تم إكمال المهمة</p>
                            <p class="text-xs text-gray-500"><?php echo e($task->updated_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employee\tasks\show.blade.php ENDPATH**/ ?>