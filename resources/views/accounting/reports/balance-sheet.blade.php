@extends('layouts.app')

@section('page-title', 'الميزانية العمومية')

@section('content')
<div class="w-full max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">الميزانية العمومية</h1>
                <p class="text-gray-600">تقرير الميزانية العمومية حتى تاريخ {{ $date }}</p>
            </div>
            <div class="flex items-center gap-3 no-print">
                <form method="GET" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6 no-print">
        <div class="text-center">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getCompanyName() ?? 'شركة سولفيستا للبرمجيات' }}</h2>
            <h3 class="text-xl font-semibold text-gray-700 mb-4">الميزانية العمومية</h3>
            <p class="text-gray-600">حتى تاريخ: {{ $reportDate->format('Y-m-d') }}</p>
        </div>
    </div>
    
    <!-- Print Header -->
    <div class="bg-white p-8 mb-6 text-center hidden print:block" style="page-break-after: avoid;">
        <div class="border-b-2 border-gray-900 pb-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getCompanyName() ?? 'شركة سولفيستا للبرمجيات' }}</h2>
            <p class="text-sm text-gray-600">{{ \App\Helpers\SettingsHelper::getCompanyAddress() ?? '' }}</p>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">الميزانية العمومية</h3>
        <p class="text-gray-700 font-medium">حتى تاريخ: {{ $reportDate->format('Y-m-d') }}</p>
    </div>

    <!-- Balance Sheet Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Assets Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-green-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-green-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    الأصول
                </h3>
            </div>
            
            <div class="p-6">
                @if($assets->count() > 0)
                <div class="space-y-3">
                    @foreach($assets as $asset)
                    <!-- حساب رئيسي -->
                    <div class="flex items-center justify-between py-2 border-b border-gray-200">
                        <div class="flex items-center">
                            <span class="text-sm font-bold text-gray-900">{{ $asset->code ?? '' }} - {{ $asset->name ?? $asset->account_name ?? 'غير محدد' }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ number_format($asset->total_balance ?? $asset->balance ?? 0, 2) }} ج.م</span>
                    </div>
                    <!-- الحسابات الفرعية -->
                    @if($asset->children && $asset->children->count() > 0)
                        @foreach($asset->children as $child)
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
                    
                    <!-- Total Assets -->
                    <div class="flex items-center justify-between py-3 border-t-2 border-green-200 bg-green-50 rounded-lg px-4 mt-4">
                        <span class="text-lg font-bold text-green-800">إجمالي الأصول</span>
                        <span class="text-lg font-bold text-green-800">{{ number_format($totalAssets, 2) }} ج.م</span>
                    </div>
                </div>
                @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-lg font-medium text-gray-900 mb-2">لا توجد أصول</p>
                    <p class="text-gray-600">لم يتم تسجيل أي أصول بعد</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Liabilities & Equity Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 text-blue-600 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                    الخصوم وحقوق الملكية
                </h3>
            </div>
            
            <div class="p-6">
                <!-- Liabilities -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-orange-600 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        الخصوم
                    </h4>
                    
                    @if($liabilities->count() > 0)
                    <div class="space-y-3">
                        @foreach($liabilities as $liability)
                        <!-- حساب رئيسي -->
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-gray-900">{{ $liability->code ?? '' }} - {{ $liability->name ?? $liability->account_name ?? 'غير محدد' }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($liability->total_balance ?? $liability->balance ?? 0, 2) }} ج.م</span>
                        </div>
                        <!-- الحسابات الفرعية -->
                        @if($liability->children && $liability->children->count() > 0)
                            @foreach($liability->children as $child)
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
                        
                        <!-- Total Liabilities -->
                        <div class="flex items-center justify-between py-2 border-t border-orange-200 bg-orange-50 rounded px-3 mt-2">
                            <span class="text-sm font-bold text-orange-800">إجمالي الخصوم</span>
                            <span class="text-sm font-bold text-orange-800">{{ number_format($totalLiabilities, 2) }} ج.م</span>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">لا توجد خصوم</p>
                    @endif
                </div>

                <!-- Equity -->
                <div class="mb-6">
                    <h4 class="text-md font-semibold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-blue-600 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        حقوق الملكية
                    </h4>
                    
                    @if($equity->count() > 0)
                    <div class="space-y-3">
                        @foreach($equity as $equityItem)
                        <!-- حساب رئيسي -->
                        <div class="flex items-center justify-between py-2 border-b border-gray-200">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-gray-900">{{ $equityItem->code ?? '' }} - {{ $equityItem->name ?? $equityItem->account_name ?? 'غير محدد' }}</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($equityItem->total_balance ?? $equityItem->balance ?? 0, 2) }} ج.م</span>
                        </div>
                        <!-- الحسابات الفرعية -->
                        @if($equityItem->children && $equityItem->children->count() > 0)
                            @foreach($equityItem->children as $child)
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
                        
                        <!-- Retained Earnings -->
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-700">الأرباح المحتجزة</span>
                            <span class="text-sm font-bold text-gray-900">{{ number_format($retainedEarnings, 2) }} ج.م</span>
                        </div>
                        
                        <!-- Total Equity -->
                        <div class="flex items-center justify-between py-2 border-t border-blue-200 bg-blue-50 rounded px-3 mt-2">
                            <span class="text-sm font-bold text-blue-800">إجمالي حقوق الملكية</span>
                            <span class="text-sm font-bold text-blue-800">{{ number_format($totalEquity, 2) }} ج.م</span>
                        </div>
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">لا توجد حقوق ملكية</p>
                    @endif
                </div>

                <!-- Total Liabilities & Equity -->
                <div class="flex items-center justify-between py-3 border-t-2 border-blue-200 bg-blue-50 rounded-lg px-4">
                    <span class="text-lg font-bold text-blue-800">إجمالي الخصوم وحقوق الملكية</span>
                    <span class="text-lg font-bold text-blue-800">{{ number_format($totalLiabilitiesEquity, 2) }} ج.م</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Verification -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="text-center">
            @if(abs($totalAssets - $totalLiabilitiesEquity) < 0.01)
                <div class="flex items-center justify-center text-green-600 mb-2">
                    <svg class="w-8 h-8 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xl font-bold">الميزانية متوازنة</span>
                </div>
                <p class="text-gray-600">إجمالي الأصول = إجمالي الخصوم وحقوق الملكية</p>
            @else
                <div class="flex items-center justify-center text-red-600 mb-2">
                    <svg class="w-8 h-8 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xl font-bold">الميزانية غير متوازنة</span>
                </div>
                <p class="text-gray-600">الفرق: {{ number_format(abs($totalAssets - $totalLiabilitiesEquity), 2) }} ج.م</p>
            @endif
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
    
    .bg-gray-50, .bg-green-50, .bg-blue-50, .bg-orange-50 {
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
