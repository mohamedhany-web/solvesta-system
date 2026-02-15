@extends('layouts.app')

@section('page-title', 'قائمة الدخل')

@section('content')
<div class="w-full max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8 no-print">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">قائمة الدخل</h1>
                <p class="text-gray-600">للفترة من {{ $reportStartDate->format('Y-m-d') }} إلى {{ $reportEndDate->format('Y-m-d') }}</p>
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
        <h3 class="text-xl font-semibold text-gray-700 mb-4">قائمة الدخل</h3>
        <p class="text-gray-600">للفترة من {{ $reportStartDate->format('Y-m-d') }} إلى {{ $reportEndDate->format('Y-m-d') }}</p>
    </div>
    
    <!-- Print Header -->
    <div class="bg-white p-8 mb-6 text-center hidden print:block" style="page-break-after: avoid;">
        <div class="border-b-2 border-gray-900 pb-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getCompanyName() ?? 'شركة سولفيستا للبرمجيات' }}</h2>
            <p class="text-sm text-gray-600">{{ \App\Helpers\SettingsHelper::getCompanyAddress() ?? '' }}</p>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">قائمة الدخل</h3>
        <p class="text-gray-700 font-medium">للفترة من {{ $reportStartDate->format('Y-m-d') }} إلى {{ $reportEndDate->format('Y-m-d') }}</p>
    </div>

    <!-- Income Statement -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
            <!-- Revenue Section -->
            <div class="border-r border-gray-200">
                <div class="bg-green-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 text-green-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        الإيرادات
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($revenues->count() > 0)
                    <div class="space-y-3">
                        @foreach($revenues as $revenue)
                        <!-- حساب رئيسي -->
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-gray-900">{{ $revenue->code ?? '' }} - {{ $revenue->name ?? 'غير محدد' }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($revenue->total_balance ?? $revenue->balance ?? 0, 2) }} ج.م</span>
                        </div>
                        <!-- الحسابات الفرعية -->
                        @if($revenue->children && $revenue->children->count() > 0)
                            @foreach($revenue->children as $child)
                            <div class="flex items-center justify-between py-1.5 pr-6 border-b border-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="text-xs text-gray-600">{{ $child->code ?? '' }} - {{ $child->name ?? 'غير محدد' }}</span>
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ number_format($child->balance ?? 0, 2) }} ج.م</span>
                            </div>
                            @endforeach
                        @endif
                        @endforeach
                        
                        <!-- Total Revenue -->
                        <div class="flex items-center justify-between py-3 border-t-2 border-green-200 bg-green-50 rounded-lg px-4 mt-4">
                            <span class="text-lg font-bold text-green-800">إجمالي الإيرادات</span>
                            <span class="text-lg font-bold text-green-800">{{ number_format($totalRevenue, 2) }} ج.م</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg font-medium text-gray-900 mb-2">لا توجد إيرادات</p>
                        <p class="text-gray-600">لم يتم تسجيل أي إيرادات للفترة المحددة</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Expenses Section -->
            <div>
                <div class="bg-red-50 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 text-red-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                        </svg>
                        المصروفات
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($expenses->count() > 0)
                    <div class="space-y-3">
                        @foreach($expenses as $expense)
                        <!-- حساب رئيسي -->
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-gray-900">{{ $expense->code ?? '' }} - {{ $expense->name ?? 'غير محدد' }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($expense->total_balance ?? $expense->balance ?? 0, 2) }} ج.م</span>
                        </div>
                        <!-- الحسابات الفرعية -->
                        @if($expense->children && $expense->children->count() > 0)
                            @foreach($expense->children as $child)
                            <div class="flex items-center justify-between py-1.5 pr-6 border-b border-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                    <span class="text-xs text-gray-600">{{ $child->code ?? '' }} - {{ $child->name ?? 'غير محدد' }}</span>
                                </div>
                                <span class="text-xs font-medium text-gray-700">{{ number_format($child->balance ?? 0, 2) }} ج.م</span>
                            </div>
                            @endforeach
                        @endif
                        @endforeach
                        
                        <!-- Total Expenses -->
                        <div class="flex items-center justify-between py-3 border-t-2 border-red-200 bg-red-50 rounded-lg px-4 mt-4">
                            <span class="text-lg font-bold text-red-800">إجمالي المصروفات</span>
                            <span class="text-lg font-bold text-red-800">{{ number_format($totalExpenses, 2) }} ج.م</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg font-medium text-gray-900 mb-2">لا توجد مصروفات</p>
                        <p class="text-gray-600">لم يتم تسجيل أي مصروفات للفترة المحددة</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Net Income -->
        <div class="p-6 border-t-2 border-gray-300 {{ $netIncome >= 0 ? 'bg-green-50' : 'bg-red-50' }}">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if($netIncome >= 0)
                        <svg class="w-8 h-8 text-green-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg class="w-8 h-8 text-red-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                    <span class="text-2xl font-bold {{ $netIncome >= 0 ? 'text-green-900' : 'text-red-900' }}">
                        {{ $netIncome >= 0 ? 'صافي الربح' : 'صافي الخسارة' }}
                    </span>
                </div>
                <span class="text-3xl font-bold {{ $netIncome >= 0 ? 'text-green-900' : 'text-red-900' }}">
                    {{ number_format(abs($netIncome), 2) }} ج.م
                </span>
            </div>
            @if($profitMargin)
            <div class="mt-4 text-center">
                <span class="text-sm text-gray-600">هامش الربح: </span>
                <span class="text-lg font-bold {{ $netIncome >= 0 ? 'text-green-700' : 'text-red-700' }}">
                    {{ number_format($profitMargin, 2) }}%
                </span>
            </div>
            @endif
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 no-print">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي الإيرادات</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($totalRevenue, 2) }} ج.م</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي المصروفات</p>
                    <p class="text-2xl font-bold text-red-600">{{ number_format($totalExpenses, 2) }} ج.م</p>
                </div>
                <div class="p-3 bg-red-50 rounded-lg">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">{{ $netIncome >= 0 ? 'صافي الربح' : 'صافي الخسارة' }}</p>
                    <p class="text-2xl font-bold {{ $netIncome >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                        {{ number_format(abs($netIncome), 2) }} ج.م
                    </p>
                    @if($profitMargin)
                    <p class="text-xs {{ $netIncome >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                        هامش: {{ number_format($profitMargin, 2) }}%
                    </p>
                    @endif
                </div>
                <div class="p-3 {{ $netIncome >= 0 ? 'bg-blue-50' : 'bg-red-50' }} rounded-lg">
                    <svg class="w-8 h-8 {{ $netIncome >= 0 ? 'text-blue-600' : 'text-red-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
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
    
    .bg-gray-50, .bg-green-50, .bg-red-50 {
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
