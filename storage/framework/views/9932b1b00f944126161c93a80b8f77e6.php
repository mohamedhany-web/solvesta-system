

<?php $__env->startSection('page-title', 'قائمة الدخل'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">قائمة الدخل</h1>
                <p class="text-gray-600">عرض الأرباح والخسائر للفترة المحددة</p>
            </div>
            <div class="flex gap-3">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>يناير 2024</option>
                    <option>فبراير 2024</option>
                    <option>مارس 2024</option>
                </select>
                <button class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    تصدير PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الإيرادات</p>
                    <p class="text-3xl font-bold text-green-600">485,000 ج.م</p>
                    <p class="text-xs text-green-600 mt-1">+12% من الشهر الماضي</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Expenses -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المصروفات</p>
                    <p class="text-3xl font-bold text-red-600">320,000 ج.م</p>
                    <p class="text-xs text-red-600 mt-1">+8% من الشهر الماضي</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">صافي الربح</p>
                    <p class="text-3xl font-bold text-blue-600">165,000 ج.م</p>
                    <p class="text-xs text-blue-600 mt-1">+18% من الشهر الماضي</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Profit Margin -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">معدل الربحية</p>
                    <p class="text-3xl font-bold text-purple-600">34%</p>
                    <p class="text-xs text-purple-600 mt-1">هامش ربح جيد</p>
                </div>
                <div class="p-4 bg-purple-50 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Profit & Loss Statement -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Revenue Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-green-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-green-800 flex items-center">
                    <svg class="w-6 h-6 text-green-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    الإيرادات
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">مبيعات الخدمات</span>
                        </div>
                            <span class="font-bold text-gray-900">350,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">مبيعات المنتجات</span>
                        </div>
                            <span class="font-bold text-gray-900">95,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-purple-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">إيرادات أخرى</span>
                        </div>
                            <span class="font-bold text-gray-900">40,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 pt-4 border-t-2 border-green-200">
                        <span class="font-bold text-green-800">إجمالي الإيرادات</span>
                        <span class="font-bold text-2xl text-green-600">485,000 ج.م</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-red-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-red-800 flex items-center">
                    <svg class="w-6 h-6 text-red-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                    المصروفات
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-orange-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">رواتب الموظفين</span>
                        </div>
                            <span class="font-bold text-gray-900">180,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">إيجار المكتب</span>
                        </div>
                            <span class="font-bold text-gray-900">25,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-pink-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">المرافق والخدمات</span>
                        </div>
                            <span class="font-bold text-gray-900">15,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-indigo-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">مصروفات تسويقية</span>
                        </div>
                            <span class="font-bold text-gray-900">35,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-teal-500 rounded-full ml-3"></div>
                            <span class="text-gray-700">مصروفات أخرى</span>
                        </div>
                            <span class="font-bold text-gray-900">65,000 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between py-3 pt-4 border-t-2 border-red-200">
                        <span class="font-bold text-red-800">إجمالي المصروفات</span>
                        <span class="font-bold text-2xl text-red-600">320,000 ج.م</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Net Profit Summary -->
    <div class="mt-8 bg-gradient-to-r from-blue-50 to-green-50 rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">ملخص الأداء المالي</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">485,000 ج.م</div>
                    <div class="text-gray-600">إجمالي الإيرادات</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-red-600 mb-2">320,000 ج.م</div>
                    <div class="text-gray-600">إجمالي المصروفات</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">165,000 ج.م</div>
                    <div class="text-gray-600">صافي الربح</div>
                </div>
            </div>
            <div class="mt-6">
                <div class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 rounded-full">
                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    ربحية إيجابية - الأداء ممتاز
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\profit-loss.blade.php ENDPATH**/ ?>