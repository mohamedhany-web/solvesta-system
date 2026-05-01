<?php $__env->startSection('page-title', 'إدارة المصروفات'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إدارة المصروفات</h1>
                <p class="text-gray-600">تتبع وإدارة المصروفات والنفقات</p>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-finance')): ?>
            <a href="<?php echo e(route('expenses.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                مصروف جديد
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
        <!-- Total Expenses -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المصروفات</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total']); ?></p>
                    <p class="text-xs text-blue-600 mt-1">جميع المصروفات</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المبلغ</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e(number_format($stats['total_amount'], 2)); ?></p>
                    <p class="text-xs text-purple-600 mt-1">ج.م</p>
                </div>
                <div class="p-4 bg-purple-50 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Approval -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">في انتظار الموافقة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['pending']); ?></p>
                    <p class="text-xs text-yellow-600 mt-1">يحتاج للمراجعة</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-xl">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">موافق عليها</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['approved']); ?></p>
                    <p class="text-xs text-green-600 mt-1">تمت الموافقة</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">قائمة المصروفات (<?php echo e($expenses->total()); ?>)</h3>
                <form method="GET" action="<?php echo e(route('expenses.index')); ?>" class="flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">جميع الحالات</option>
                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>معلق</option>
                        <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>موافق عليه</option>
                        <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>مرفوض</option>
                    </select>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="البحث..." class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">بحث</button>
                    <?php if(request('search') || request('status')): ?>
                    <a href="<?php echo e(route('expenses.index')); ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">إلغاء</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم المصروف</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفئة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                        ];
                        
                        $statusNames = [
                            'pending' => 'معلق',
                            'approved' => 'موافق عليه',
                            'rejected' => 'مرفوض',
                        ];
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($expense->expense_number); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($expense->created_at->diffForHumans()); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e(Str::limit($expense->description, 50)); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900"><?php echo e($expense->expense_category); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?php echo e(number_format($expense->amount, 2)); ?> ج.م</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500"><?php echo e($expense->expense_date->format('Y/m/d')); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($statusColors[$expense->status] ?? 'bg-gray-100 text-gray-800'); ?>">
                                <?php echo e($statusNames[$expense->status] ?? $expense->status); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('expenses.show', $expense)); ?>" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    عرض
                                </a>
                                <a href="<?php echo e(route('expenses.edit', $expense)); ?>" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition-colors duration-200">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-lg font-medium">لا توجد مصروفات</p>
                                <p class="text-sm">قم بإضافة مصروف جديد للبدء</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($expenses->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($expenses->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\expenses\index.blade.php ENDPATH**/ ?>