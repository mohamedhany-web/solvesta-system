@extends('layouts.app')

@section('page-title', 'قائمة التدفق النقدي')

@section('content')
<div class="w-full max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8 no-print">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">قائمة التدفق النقدي</h1>
                <p class="text-gray-600">للفترة من {{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }} إلى {{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        تحديث
                    </button>
                </form>
                <button onclick="window.print()" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    طباعة
                </button>
            </div>
        </div>
    </div>

    <!-- Company Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6 text-center no-print">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getCompanyName() ?? 'شركة سولفيستا للبرمجيات' }}</h2>
        <h3 class="text-xl font-semibold text-gray-700 mb-4">قائمة التدفق النقدي</h3>
        <p class="text-gray-600">للفترة من {{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }} إلى {{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}</p>
    </div>
    
    <!-- Print Header -->
    <div class="bg-white p-8 mb-6 text-center hidden print:block" style="page-break-after: avoid;">
        <div class="border-b-2 border-gray-900 pb-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getCompanyName() ?? 'شركة سولفيستا للبرمجيات' }}</h2>
            <p class="text-sm text-gray-600">{{ \App\Helpers\SettingsHelper::getCompanyAddress() ?? '' }}</p>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">قائمة التدفق النقدي</h3>
        <p class="text-gray-700 font-medium">للفترة من {{ \Carbon\Carbon::parse($startDate)->format('Y-m-d') }} إلى {{ \Carbon\Carbon::parse($endDate)->format('Y-m-d') }}</p>
    </div>

    <!-- Cash Flow Statement -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Operating Activities -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-blue-50 rounded-lg ml-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">التدفقات النقدية من الأنشطة التشغيلية</h3>
            </div>
            
            <div class="space-y-2 mr-14">
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-medium text-gray-700">التدفقات النقدية من العمليات</span>
                    <span class="text-sm font-bold {{ $operatingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format(abs($operatingCashFlow), 2) }} ج.م
                    </span>
                </div>
                
                <div class="flex items-center justify-between py-3 border-t-2 border-blue-200 bg-blue-50 rounded-lg px-4 mt-4">
                    <span class="font-bold text-blue-800">صافي التدفق النقدي من الأنشطة التشغيلية</span>
                    <span class="font-bold {{ $operatingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $operatingCashFlow >= 0 ? '+' : '-' }} {{ number_format(abs($operatingCashFlow), 2) }} ج.م
                    </span>
                </div>
            </div>
        </div>

        <!-- Investing Activities -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-purple-50 rounded-lg ml-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">التدفقات النقدية من الأنشطة الاستثمارية</h3>
            </div>
            
            <div class="space-y-2 mr-14">
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-medium text-gray-700">شراء الأصول الثابتة</span>
                    <span class="text-sm font-bold {{ $investingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format(abs($investingCashFlow), 2) }} ج.م
                    </span>
                </div>
                
                <div class="flex items-center justify-between py-3 border-t-2 border-purple-200 bg-purple-50 rounded-lg px-4 mt-4">
                    <span class="font-bold text-purple-800">صافي التدفق النقدي من الأنشطة الاستثمارية</span>
                    <span class="font-bold {{ $investingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $investingCashFlow >= 0 ? '+' : '-' }} {{ number_format(abs($investingCashFlow), 2) }} ج.م
                    </span>
                </div>
            </div>
        </div>

        <!-- Financing Activities -->
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center mb-4">
                <div class="p-3 bg-green-50 rounded-lg ml-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">التدفقات النقدية من الأنشطة التمويلية</h3>
            </div>
            
            <div class="space-y-2 mr-14">
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm font-medium text-gray-700">القروض والتمويل</span>
                    <span class="text-sm font-bold {{ $financingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format(abs($financingCashFlow), 2) }} ج.م
                    </span>
                </div>
                
                <div class="flex items-center justify-between py-3 border-t-2 border-green-200 bg-green-50 rounded-lg px-4 mt-4">
                    <span class="font-bold text-green-800">صافي التدفق النقدي من الأنشطة التمويلية</span>
                    <span class="font-bold {{ $financingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $financingCashFlow >= 0 ? '+' : '-' }} {{ number_format(abs($financingCashFlow), 2) }} ج.م
                    </span>
                </div>
            </div>
        </div>

        <!-- Net Cash Flow -->
        <div class="p-6 bg-gray-50">
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2">
                    <span class="text-lg font-semibold text-gray-700">صافي التغير في النقدية</span>
                    <span class="text-lg font-bold {{ $netCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $netCashFlow >= 0 ? '+' : '-' }} {{ number_format(abs($netCashFlow), 2) }} ج.م
                    </span>
                </div>
                
                <div class="flex items-center justify-between py-2 border-t border-gray-300">
                    <span class="text-sm font-medium text-gray-600">النقدية في بداية الفترة</span>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($beginningCash, 2) }} ج.م</span>
                </div>
                
                <div class="flex items-center justify-between py-3 border-t-2 border-gray-400 bg-white rounded-lg px-4">
                    <span class="text-xl font-bold text-gray-900">النقدية في نهاية الفترة</span>
                    <span class="text-xl font-bold text-blue-600">{{ number_format($endingCash, 2) }} ج.م</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Flow Analysis -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 no-print">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-900">الأنشطة التشغيلية</h4>
                <div class="p-2 bg-blue-50 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold {{ $operatingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format(abs($operatingCashFlow), 2) }} ج.م
            </p>
            <p class="text-sm text-gray-600 mt-1">
                {{ $operatingCashFlow >= 0 ? 'تدفق نقدي إيجابي' : 'تدفق نقدي سلبي' }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-900">الأنشطة الاستثمارية</h4>
                <div class="p-2 bg-purple-50 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold {{ $investingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format(abs($investingCashFlow), 2) }} ج.م
            </p>
            <p class="text-sm text-gray-600 mt-1">
                {{ $investingCashFlow >= 0 ? 'تدفق نقدي إيجابي' : 'تدفق نقدي سلبي' }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-gray-900">الأنشطة التمويلية</h4>
                <div class="p-2 bg-green-50 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold {{ $financingCashFlow >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ number_format(abs($financingCashFlow), 2) }} ج.م
            </p>
            <p class="text-sm text-gray-600 mt-1">
                {{ $financingCashFlow >= 0 ? 'تدفق نقدي إيجابي' : 'تدفق نقدي سلبي' }}
            </p>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .bg-white {
        background: white !important;
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
    
    .bg-gray-50, .bg-blue-50, .bg-purple-50, .bg-green-50 {
        background: #f9fafb !important;
    }
    
    @page {
        margin: 15mm;
        size: A4;
    }
    
    .print\\:block {
        display: block !important;
    }
}

.hidden {
    display: none;
}
</style>
@endsection
