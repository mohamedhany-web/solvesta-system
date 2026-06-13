

<?php $__env->startSection('page-title', 'الجودة والاختبارات'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">الجودة والاختبارات</h1>
                <p class="text-gray-600">إدارة الاختبارات وضمان الجودة</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-qa')): ?>
            <a href="<?php echo e(route('qa.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                اختبار جديد
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
        <!-- Total Tests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الاختبارات</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total']); ?></p>
                    <p class="text-xs text-blue-600 mt-1">جميع الاختبارات المسجلة</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Passed Tests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الاختبارات الناجحة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['passed']); ?></p>
                    <p class="text-xs text-green-600 mt-1"><?php echo e($stats['pass_rate']); ?>% معدل النجاح</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Failed Tests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الاختبارات الفاشلة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['failed']); ?></p>
                    <p class="text-xs text-red-600 mt-1">يحتاج للإصلاح</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Tests -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">اختبارات معلقة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['pending']); ?></p>
                    <p class="text-xs text-yellow-600 mt-1">في انتظار التنفيذ</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-xl">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- QA Tests Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">قائمة الاختبارات (<?php echo e($tests->total()); ?>)</h3>
                <form method="GET" action="<?php echo e(route('qa.index')); ?>" class="flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">جميع الحالات</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>معلق</option>
                        <option value="running" <?php echo e(request('status') == 'running' ? 'selected' : ''); ?>>قيد التنفيذ</option>
                        <option value="passed" <?php echo e(request('status') == 'passed' ? 'selected' : ''); ?>>نجح</option>
                        <option value="failed" <?php echo e(request('status') == 'failed' ? 'selected' : ''); ?>>فشل</option>
                        <option value="skipped" <?php echo e(request('status') == 'skipped' ? 'selected' : ''); ?>>تم التخطي</option>
                    </select>
                    <select name="type" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">جميع الأنواع</option>
                        <option value="unit" <?php echo e(request('type') == 'unit' ? 'selected' : ''); ?>>وحدة</option>
                        <option value="integration" <?php echo e(request('type') == 'integration' ? 'selected' : ''); ?>>تكامل</option>
                        <option value="functional" <?php echo e(request('type') == 'functional' ? 'selected' : ''); ?>>وظيفي</option>
                        <option value="performance" <?php echo e(request('type') == 'performance' ? 'selected' : ''); ?>>أداء</option>
                        <option value="security" <?php echo e(request('type') == 'security' ? 'selected' : ''); ?>>أمان</option>
                        <option value="usability" <?php echo e(request('type') == 'usability' ? 'selected' : ''); ?>>سهولة استخدام</option>
                    </select>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="البحث عن اختبار..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">بحث</button>
                    <?php if(request('search') || request('status') || request('type')): ?>
                    <a href="<?php echo e(route('qa.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">إلغاء</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الاختبار</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الاختبار</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المشروع</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النوع</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المكلف</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'running' => 'bg-blue-100 text-blue-800',
                            'passed' => 'bg-green-100 text-green-800',
                            'failed' => 'bg-red-100 text-red-800',
                            'skipped' => 'bg-gray-100 text-gray-800',
                        ];
                        
                        $typeColors = [
                            'unit' => 'bg-blue-100 text-blue-800',
                            'integration' => 'bg-purple-100 text-purple-800',
                            'functional' => 'bg-green-100 text-green-800',
                            'performance' => 'bg-orange-100 text-orange-800',
                            'security' => 'bg-red-100 text-red-800',
                            'usability' => 'bg-pink-100 text-pink-800',
                        ];
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($test->test_number); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($test->created_at->diffForHumans()); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e(Str::limit($test->name, 40)); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e(Str::limit($test->description, 50)); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e($test->project->name ?? 'عام'); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($typeColors[$test->type] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($test->type_name); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColors[$test->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($test->status_name); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if($test->assignedTo): ?>
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center ml-2">
                                    <span class="text-xs font-medium text-white"><?php echo e(mb_substr($test->assignedTo->name, 0, 1)); ?></span>
                                </div>
                                <div class="text-sm text-gray-900"><?php echo e($test->assignedTo->name); ?></div>
                            </div>
                            <?php else: ?>
                            <span class="text-sm text-gray-500">غير مكلف</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('qa.show', $test)); ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    عرض
                                </a>
                                <a href="<?php echo e(route('qa.edit', $test)); ?>" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    تعديل
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-lg font-medium">لا توجد اختبارات</p>
                                <p class="text-sm">قم بإنشاء اختبار جديد للبدء</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($tests->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($tests->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\qa\index.blade.php ENDPATH**/ ?>