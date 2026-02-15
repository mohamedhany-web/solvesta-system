@extends('layouts.app')

@section('page-title', 'الميزانية العمومية')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">الميزانية العمومية</h1>
                <p class="text-gray-600">عرض الأصول والخصوم وحقوق الملكية</p>
            </div>
            <div class="flex gap-3">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option>31 يناير 2024</option>
                    <option>31 ديسمبر 2023</option>
                    <option>30 نوفمبر 2023</option>
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
        <!-- Total Assets -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الأصول</p>
                    <p class="text-3xl font-bold text-green-600">1,250,000 ج.م</p>
                    <p class="text-xs text-green-600 mt-1">زيادة 5% عن الفترة السابقة</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Liabilities -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الخصوم</p>
                    <p class="text-3xl font-bold text-red-600">425,000 ج.م</p>
                    <p class="text-xs text-red-600 mt-1">انخفاض 3% عن الفترة السابقة</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Equity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">حقوق الملكية</p>
                    <p class="text-3xl font-bold text-purple-600">825,000 ج.م</p>
                    <p class="text-xs text-purple-600 mt-1">زيادة 8% عن الفترة السابقة</p>
                </div>
                <div class="p-4 bg-purple-50 rounded-xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Sheet -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Assets -->
        <div class="space-y-6">
            <!-- Current Assets -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-blue-800 flex items-center">
                        <svg class="w-6 h-6 text-blue-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        الأصول المتداولة
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">النقدية والصندوق</span>
                            <span class="font-bold text-gray-900">125,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">البنك الأهلي</span>
                            <span class="font-bold text-gray-900">285,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">أرصدة العملاء</span>
                            <span class="font-bold text-gray-900">180,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">المخزون</span>
                            <span class="font-bold text-gray-900">95,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-t border-gray-200 pt-3">
                            <span class="font-bold text-blue-800">إجمالي الأصول المتداولة</span>
                            <span class="font-bold text-xl text-blue-600">685,000 ج.م</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fixed Assets -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-purple-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-purple-800 flex items-center">
                        <svg class="w-6 h-6 text-purple-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        الأصول الثابتة
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">المباني</span>
                            <span class="font-bold text-gray-900">450,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">المعدات والأثاث</span>
                            <span class="font-bold text-gray-900">185,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">أجهزة الحاسوب</span>
                            <span class="font-bold text-gray-900">85,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">مطروح: مجمع الإهلاك</span>
                            <span class="font-bold text-red-600">-155,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-t border-gray-200 pt-3">
                            <span class="font-bold text-purple-800">صافي الأصول الثابتة</span>
                            <span class="font-bold text-xl text-purple-600">565,000 ج.م</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Assets -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">1,250,000 ج.م</div>
                    <div class="text-lg font-semibold text-gray-700">إجمالي الأصول</div>
                </div>
            </div>
        </div>

        <!-- Liabilities & Equity -->
        <div class="space-y-6">
            <!-- Current Liabilities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-orange-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-orange-800 flex items-center">
                        <svg class="w-6 h-6 text-orange-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        الخصوم المتداولة
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">أرصدة الموردين</span>
                            <span class="font-bold text-gray-900">95,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">الرواتب المستحقة</span>
                            <span class="font-bold text-gray-900">45,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">الضرائب المستحقة</span>
                            <span class="font-bold text-gray-900">25,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">خصوم أخرى</span>
                            <span class="font-bold text-gray-900">15,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-t border-gray-200 pt-3">
                            <span class="font-bold text-orange-800">إجمالي الخصوم المتداولة</span>
                            <span class="font-bold text-xl text-orange-600">180,000 ج.م</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Long-term Liabilities -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-red-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-red-800 flex items-center">
                        <svg class="w-6 h-6 text-red-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        الخصوم طويلة الأجل
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">قرض بنكي طويل الأجل</span>
                            <span class="font-bold text-gray-900">245,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-t border-gray-200 pt-3">
                            <span class="font-bold text-red-800">إجمالي الخصوم طويلة الأجل</span>
                            <span class="font-bold text-xl text-red-600">245,000 ج.م</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-purple-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-purple-800 flex items-center">
                        <svg class="w-6 h-6 text-purple-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        حقوق الملكية
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">رأس المال</span>
                            <span class="font-bold text-gray-900">500,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">الأرباح المحتجزة</span>
                            <span class="font-bold text-gray-900">275,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-gray-700">صافي الربح الحالي</span>
                            <span class="font-bold text-gray-900">50,000 ج.م</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-t border-gray-200 pt-3">
                            <span class="font-bold text-purple-800">إجمالي حقوق الملكية</span>
                            <span class="font-bold text-xl text-purple-600">825,000 ج.م</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Liabilities & Equity -->
            <div class="bg-gradient-to-r from-red-50 to-purple-50 rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600 mb-2">425,000 ج.م</div>
                    <div class="text-lg font-semibold text-gray-700 mb-4">إجمالي الخصوم</div>
                    <div class="text-3xl font-bold text-purple-600 mb-2">825,000 ج.م</div>
                    <div class="text-lg font-semibold text-gray-700 mb-4">إجمالي حقوق الملكية</div>
                    <div class="border-t border-gray-300 pt-4">
                        <div class="text-3xl font-bold text-blue-600 mb-2">1,250,000 ج.م</div>
                        <div class="text-lg font-semibold text-gray-700">إجمالي الخصوم + حقوق الملكية</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Verification -->
    <div class="mt-8 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">تحقق من توازن الميزانية</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">1,250,000 ج.م</div>
                    <div class="text-gray-600">إجمالي الأصول</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">1,250,000 ج.م</div>
                    <div class="text-gray-600">إجمالي الخصوم + حقوق الملكية</div>
                </div>
            </div>
            <div class="mt-6">
                <div class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 rounded-full">
                    <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    الميزانية متوازنة - المعادلة المحاسبية صحيحة
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
