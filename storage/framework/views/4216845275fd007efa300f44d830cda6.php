

<?php $__env->startSection('page-title', 'بوابة العميل'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-full">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 sm:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3zm8 0c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20v-1a5 5 0 0110 0v1M14 20v-1a5 5 0 0110 0v1" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1 truncate">بوابة العميل</h1>
                        <p class="text-gray-600 text-sm sm:text-base">مرحباً <?php echo e($client->name); ?></p>
                    </div>
                </div>
                <div class="text-sm text-gray-700 bg-white/60 rounded-lg px-4 py-2 border border-blue-100">
                    <span class="font-semibold">الشركة:</span> <?php echo e($client->company ?? '—'); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">المشاريع</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($projectsCount); ?></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg class="w-7 h-7 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.projects')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض المشاريع →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">تذاكر الدعم (مفتوحة)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($ticketsOpenCount); ?></p>
                </div>
                <div class="p-3 bg-amber-100 rounded-xl">
                    <svg class="w-7 h-7 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414a2 2 0 00-2.828 0L7 11.343V15h3.657l7.707-7.707a2 2 0 000-2.828z" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.support.tickets')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض التذاكر →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">فواتير (عادية)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($invoicesCount); ?></p>
                    <p class="text-xs text-gray-500 mt-1">متبقي: <?php echo e(number_format($invoicesUnpaidAmount)); ?></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-xl">
                    <svg class="w-7 h-7 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.invoices')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض الفواتير →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">فواتير (مالية)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($financialInvoicesCount); ?></p>
                    <p class="text-xs text-gray-500 mt-1">متبقي: <?php echo e(number_format($financialInvoicesUnpaidAmount)); ?></p>
                </div>
                <div class="p-3 bg-purple-100 rounded-xl">
                    <svg class="w-7 h-7 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.invoices')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض الفواتير →</a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">بيانات العميل</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">الاسم</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->name); ?></div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">البريد</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->email ?? '—'); ?></div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">الهاتف</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->phone ?? '—'); ?></div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">العنوان</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->address ?? '—'); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-portal\dashboard.blade.php ENDPATH**/ ?>