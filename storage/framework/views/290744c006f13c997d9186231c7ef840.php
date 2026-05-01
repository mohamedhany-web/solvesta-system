<?php $__env->startSection('page-title', 'لوحة التحكم المالية'); ?>

<style>
.text-shadow-lg {
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3), 0 2px 4px rgba(0, 0, 0, 0.2);
}
.font-black {
    font-weight: 900 !important;
}
</style>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">لوحة التحكم المالية</h1>
                <p class="text-gray-600">نظرة شاملة على الوضع المالي للشركة - <?php echo e(\Carbon\Carbon::now()->locale('ar')->translatedFormat('F Y')); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('accounting.reports.index')); ?>" class="bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    التقارير المالية
                </a>
                <a href="<?php echo e(route('accounting.journal-entries.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    قيد محاسبي جديد
                </a>
            </div>
        </div>
    </div>

    <!-- Financial Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Assets -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-md border border-green-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="p-2 bg-green-500 rounded-lg ml-3">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-green-900">إجمالي الأصول</p>
                    </div>
                    <p class="text-2xl font-bold text-green-900 mb-1"><?php echo e(number_format($totalAssets)); ?></p>
                    <p class="text-xs font-medium text-green-700">ج.م</p>
                </div>
            </div>
        </div>

        <!-- Total Liabilities -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-md border border-orange-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="p-2 bg-orange-500 rounded-lg ml-3">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-orange-900">إجمالي الخصوم</p>
                    </div>
                    <p class="text-2xl font-bold text-orange-900 mb-1"><?php echo e(number_format($totalLiabilities)); ?></p>
                    <p class="text-xs font-medium text-orange-700">ج.م</p>
                </div>
            </div>
        </div>

        <!-- Net Income -->
        <div class="bg-gradient-to-br <?php echo e($netIncome >= 0 ? 'from-emerald-50 to-emerald-100 border-emerald-200' : 'from-red-50 to-red-100 border-red-200'); ?> rounded-xl shadow-md border p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="p-2 <?php echo e($netIncome >= 0 ? 'bg-emerald-500' : 'bg-red-500'); ?> rounded-lg ml-3">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($netIncome >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6'); ?>" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold <?php echo e($netIncome >= 0 ? 'text-emerald-900' : 'text-red-900'); ?>">صافي الدخل</p>
                    </div>
                    <p class="text-2xl font-bold <?php echo e($netIncome >= 0 ? 'text-emerald-900' : 'text-red-900'); ?> mb-1"><?php echo e(number_format(abs($netIncome))); ?></p>
                    <p class="text-xs font-medium <?php echo e($netIncome >= 0 ? 'text-emerald-700' : 'text-red-700'); ?>"><?php echo e($netIncome >= 0 ? 'ربح' : 'خسارة'); ?> - ج.م</p>
                </div>
            </div>
        </div>

        <!-- Cash Flow -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md border border-blue-200 p-6 hover:shadow-lg hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <div class="p-2 bg-blue-500 rounded-lg ml-3">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-blue-900">التدفق النقدي</p>
                    </div>
                    <p class="text-2xl font-bold text-blue-900 mb-1"><?php echo e(number_format($totalAssets - $totalLiabilities)); ?></p>
                    <p class="text-xs font-medium text-blue-700">الرصيد المتاح - ج.م</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue vs Expenses -->
        <div class="lg:col-span-2 bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="p-2 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg ml-3">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">أداء الشهر الحالي</h3>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-gradient-to-r from-green-500 to-green-600 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-600">الإيرادات</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-gradient-to-r from-red-500 to-red-600 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-600">المصروفات</span>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-500 rounded-lg ml-4">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-green-700 mb-1">إيرادات الشهر</p>
                            <p class="text-2xl font-bold text-green-900"><?php echo e(number_format($monthlyRevenue)); ?> <span class="text-sm">ج.م</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-green-600 font-medium"><?php echo e($monthlyRevenue > 0 ? '+' : ''); ?><?php echo e(number_format(($monthlyRevenue / max($totalRevenue, 1)) * 100, 1)); ?>%</p>
                        <p class="text-xs text-gray-500">من الإجمالي</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-5 bg-gradient-to-r from-red-50 to-rose-50 rounded-xl border border-red-200 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-500 rounded-lg ml-4">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-red-700 mb-1">مصروفات الشهر</p>
                            <p class="text-2xl font-bold text-red-900"><?php echo e(number_format($monthlyExpenses)); ?> <span class="text-sm">ج.م</span></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-red-600 font-medium"><?php echo e(number_format(($monthlyExpenses / max($totalExpenses, 1)) * 100, 1)); ?>%</p>
                        <p class="text-xs text-gray-500">من الإجمالي</p>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                    <span class="text-sm font-semibold text-blue-900">صافي أداء الشهر</span>
                    <span class="text-lg font-bold <?php echo e(($monthlyRevenue - $monthlyExpenses) >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e(number_format($monthlyRevenue - $monthlyExpenses)); ?> ج.م
                    </span>
                </div>
            </div>
        </div>

        <!-- Pending Items -->
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center mb-6">
                <div class="p-2 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg ml-3">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900">معاملات معلقة</h3>
            </div>
            <div class="space-y-4">
                <div class="p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl border border-yellow-200 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-500 rounded-lg ml-3">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-yellow-900">فواتير معلقة</span>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-yellow-900 mr-11"><?php echo e(number_format($pendingInvoices)); ?> <span class="text-sm">ج.م</span></p>
                </div>
                <div class="p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="p-2 bg-orange-500 rounded-lg ml-3">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-orange-900">مدفوعات معلقة</span>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-orange-900 mr-11"><?php echo e(number_format($pendingPayments)); ?> <span class="text-sm">ج.م</span></p>
                </div>
                <div class="p-3 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-purple-900">إجمالي المعلق</span>
                        <span class="text-lg font-bold text-purple-900"><?php echo e(number_format($pendingInvoices + $pendingPayments)); ?> ج.م</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Chart of Accounts -->
        <a href="<?php echo e(route('accounting.accounts')); ?>" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg border border-blue-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-bold text-white mb-2">دليل الحسابات</p>
                    <p class="text-2xl font-bold text-white">إدارة</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-xl group-hover:bg-opacity-30 transition-all duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Journal Entries -->
        <a href="<?php echo e(route('accounting.journal-entries')); ?>" class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg border border-green-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-bold text-white mb-2">القيود المحاسبية</p>
                    <p class="text-2xl font-bold text-white">عرض</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-xl group-hover:bg-opacity-30 transition-all duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Invoices -->
        <a href="<?php echo e(route('invoices.index')); ?>" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg border border-purple-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-bold text-white mb-2">الفواتير</p>
                    <p class="text-2xl font-bold text-white">إدارة</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-xl group-hover:bg-opacity-30 transition-all duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </a>

        <!-- Payments -->
        <a href="<?php echo e(route('payments.index')); ?>" class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg border border-red-700 p-6 hover:shadow-xl hover:scale-105 transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-bold text-white mb-2">المدفوعات</p>
                    <p class="text-2xl font-bold text-white">عرض</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-xl group-hover:bg-opacity-30 transition-all duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </a>
    </div>

    <!-- Account Balances Summary -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md border border-gray-200 p-6 mb-8 hover:shadow-lg transition-shadow duration-300">
        <div class="flex items-center mb-6">
            <div class="p-2 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg ml-3">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">ملخص الأرصدة الحسابية</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="text-center p-5 bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl border border-green-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-3 bg-green-500 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-green-700 mb-2">الأصول</p>
                <p class="text-xl font-bold text-green-900"><?php echo e(number_format($totalAssets)); ?> <span class="text-sm">ج.م</span></p>
            </div>
            <div class="text-center p-5 bg-gradient-to-br from-orange-50 to-red-100 rounded-xl border border-orange-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-3 bg-orange-500 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-orange-700 mb-2">الخصوم</p>
                <p class="text-xl font-bold text-orange-900"><?php echo e(number_format($totalLiabilities)); ?> <span class="text-sm">ج.م</span></p>
            </div>
            <div class="text-center p-5 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl border border-blue-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-3 bg-blue-500 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-blue-700 mb-2">حقوق الملكية</p>
                <p class="text-xl font-bold text-blue-900"><?php echo e(number_format($totalEquity ?? 0)); ?> <span class="text-sm">ج.م</span></p>
            </div>
            <div class="text-center p-5 bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl border border-purple-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-3 bg-purple-500 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-purple-700 mb-2">الإيرادات</p>
                <p class="text-xl font-bold text-purple-900"><?php echo e(number_format($totalRevenue ?? 0)); ?> <span class="text-sm">ج.م</span></p>
            </div>
            <div class="text-center p-5 bg-gradient-to-br from-red-50 to-rose-100 rounded-xl border border-red-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-3 bg-red-500 rounded-full w-16 h-16 mx-auto mb-3 flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-red-700 mb-2">المصروفات</p>
                <p class="text-xl font-bold text-red-900"><?php echo e(number_format($totalExpenses ?? 0)); ?> <span class="text-sm">ج.م</span></p>
            </div>
        </div>
    </div>

    <!-- Recent Journal Entries -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-2 bg-gradient-to-br from-teal-500 to-teal-600 rounded-lg ml-3">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">القيود المحاسبية الأخيرة</h3>
                </div>
                <a href="<?php echo e(route('accounting.journal-entries')); ?>" class="text-sm text-blue-600 hover:text-blue-700 font-semibold bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition-colors duration-200">عرض الكل</a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المرجع</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الوصف</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">المجموع</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="p-2 bg-gray-100 rounded-lg ml-3">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4h8m-8 0H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-2" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-900"><?php echo e($entry->date->format('Y-m-d')); ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900 bg-gray-100 px-2 py-1 rounded"><?php echo e($entry->reference); ?></span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600"><?php echo e(Str::limit($entry->description, 50)); ?></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-900"><?php echo e(number_format($entry->total_debit)); ?> <span class="text-xs text-gray-500">ج.م</span></span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?php echo e($entry->status_color); ?>">
                                <?php echo e($entry->status_in_arabic); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="<?php echo e(route('accounting.journal-entries.show', $entry)); ?>" class="text-blue-600 hover:text-blue-800 font-semibold bg-blue-50 px-3 py-1 rounded-lg hover:bg-blue-100 transition-colors duration-200">عرض</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="p-4 bg-gray-100 rounded-full mb-4">
                                    <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد قيود محاسبية</h3>
                                <p class="text-gray-600 mb-4">ابدأ بإنشاء قيد محاسبي جديد لإدارة معاملاتك المالية</p>
                                <a href="<?php echo e(route('accounting.journal-entries.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                                    إنشاء قيد جديد
                                </a>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\index.blade.php ENDPATH**/ ?>