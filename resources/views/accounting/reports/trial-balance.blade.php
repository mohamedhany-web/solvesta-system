@extends('layouts.app')

@section('page-title', 'ميزان المراجعة')

@section('content')
<div class="w-full max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8 no-print">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">ميزان المراجعة</h1>
                <p class="text-gray-600">حتى تاريخ {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</p>
            </div>
            <div class="flex items-center gap-3">
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6 text-center no-print">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getCompanyName() ?? 'شركة سولفيستا للبرمجيات' }}</h2>
        <h3 class="text-xl font-semibold text-gray-700 mb-4">ميزان المراجعة</h3>
        <p class="text-gray-600">حتى تاريخ: {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</p>
    </div>
    
    <!-- Print Header -->
    <div class="bg-white p-8 mb-6 text-center hidden print:block" style="page-break-after: avoid;">
        <div class="border-b-2 border-gray-900 pb-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ \App\Helpers\SettingsHelper::getCompanyName() ?? 'شركة سولفيستا للبرمجيات' }}</h2>
            <p class="text-sm text-gray-600">{{ \App\Helpers\SettingsHelper::getCompanyAddress() ?? '' }}</p>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">ميزان المراجعة</h3>
        <p class="text-gray-700 font-medium">حتى تاريخ: {{ \Carbon\Carbon::parse($date)->format('Y-m-d') }}</p>
    </div>

    <!-- Trial Balance Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($accounts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رمز الحساب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم الحساب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الحساب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مدين</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">دائن</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($accounts as $account)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $account->code }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="flex items-center">
                                @if($account->parent_id)
                                    <svg class="w-4 h-4 text-gray-400 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                @endif
                                {{ $account->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $account->type === 'asset' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $account->type === 'liability' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $account->type === 'equity' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $account->type === 'revenue' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $account->type === 'expense' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $account->type_in_arabic }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($account->debit_balance > 0)
                                <span class="font-medium text-green-600">{{ number_format($account->debit_balance, 2) }} ج.م</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($account->credit_balance > 0)
                                <span class="font-medium text-blue-600">{{ number_format($account->credit_balance, 2) }} ج.م</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                            الإجمالي
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            {{ number_format($totalDebit, 2) }} ج.م
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                            {{ number_format($totalCredit, 2) }} ج.م
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="px-6 py-12 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-lg font-medium text-gray-900 mb-2">لا توجد حسابات</p>
            <p class="text-gray-600">لم يتم تسجيل أي حسابات بعد</p>
        </div>
        @endif
    </div>

    <!-- Balance Verification -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="text-center">
            @if(abs($totalDebit - $totalCredit) < 0.01)
                <div class="flex items-center justify-center text-green-600 mb-2">
                    <svg class="w-8 h-8 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xl font-bold">ميزان المراجعة متوازن</span>
                </div>
                <p class="text-gray-600">إجمالي المدين = إجمالي الدائن</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalDebit, 2) }} ج.م</p>
            @else
                <div class="flex items-center justify-center text-red-600 mb-2">
                    <svg class="w-8 h-8 ml-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xl font-bold">ميزان المراجعة غير متوازن</span>
                </div>
                <p class="text-gray-600">الفرق: {{ number_format(abs($totalDebit - $totalCredit), 2) }} ج.م</p>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-600">إجمالي المدين</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($totalDebit, 2) }} ج.م</p>
                    </div>
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm font-medium text-gray-600">إجمالي الدائن</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($totalCredit, 2) }} ج.م</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary by Type -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8 no-print">
        @php
            $accountsByType = $accounts->groupBy('type');
        @endphp
        
        @foreach(['asset' => ['الأصول', 'green'], 'liability' => ['الخصوم', 'orange'], 'equity' => ['حقوق الملكية', 'blue'], 'revenue' => ['الإيرادات', 'green'], 'expense' => ['المصروفات', 'red']] as $type => $typeData)
            @if(isset($accountsByType[$type]))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-semibold text-gray-900">{{ $typeData[0] }}</h4>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $typeData[1] }}-100 text-{{ $typeData[1] }}-800">
                        {{ $accountsByType[$type]->count() }} حساب
                    </span>
                </div>
                <div class="text-2xl font-bold text-{{ $typeData[1] }}-600">
                    {{ number_format($accountsByType[$type]->sum('balance'), 2) }} ج.م
                </div>
            </div>
            @endif
        @endforeach
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
    
    .bg-gray-50, .bg-green-50, .bg-blue-50, .bg-orange-50, .bg-red-50 {
        background: #f9fafb !important;
    }
    
    @page {
        margin: 15mm;
        size: A4;
    }
    
    .print\\:block {
        display: block !important;
    }
    
    table {
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}

.hidden {
    display: none;
}
</style>
@endsection
