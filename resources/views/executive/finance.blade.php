@extends('layouts.app')

@section('page-title', 'التقارير المالية — CEO')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'التقارير المالية',
        'subtitle' => 'إيرادات · مستحقات · مصروفات · ربحية المشاريع',
        'icon' => 'chart',
    ])

    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('executive.dashboard') }}" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold hover:shadow-md">← لوحة CEO</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['إيرادات محصّلة', $summary['paid_revenue'], '#059669'],
            ['مستحقات', $summary['outstanding'], '#d97706'],
            ['فواتير تسليم معلّقة', $summary['delivery_pending_count'], '#2563eb'],
            ['ربح تقديري', $summary['estimated_profit'], $themeColor],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 text-center">
            <p class="text-xs text-gray-500 font-tajawal">{{ $label }}</p>
            <p class="text-2xl font-bold font-tajawal mt-1" style="color: {{ $color }};">
                {{ is_numeric($val) && $label !== 'فواتير تسليم معلّقة' ? number_format($val) : $val }}
                @if(is_numeric($val) && $label !== 'فواتير تسليم معلّقة') <span class="text-sm">ج.م</span> @endif
            </p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
        <div class="rounded-2xl p-5 border shadow-lg" style="background: linear-gradient(135deg, {{ $themeColor }}10 0%, white 100%); border-color: {{ $themeColor }}25;">
            <h2 class="font-bold mb-3 font-tajawal">تفصيل المصروفات</h2>
            <ul class="space-y-2 text-sm font-tajawal">
                <li class="flex justify-between"><span>مصروفات مرتبطة بمشاريع</span><strong class="text-red-700">{{ number_format($summary['project_expenses']) }}</strong></li>
                <li class="flex justify-between"><span>مصروفات عامة</span><strong>{{ number_format($summary['general_expenses']) }}</strong></li>
                <li class="flex justify-between border-t pt-2"><span>مستحقات فواتير التسليم</span><strong class="text-amber-700">{{ number_format($summary['delivery_outstanding']) }}</strong></li>
            </ul>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <h2 class="font-bold mb-3 font-tajawal">معادلة الربح</h2>
            <p class="text-sm text-gray-600 font-tajawal leading-relaxed">
                الربح التقديري = الإيرادات المحصّلة − مصروفات المشاريع − المصروفات العامة
            </p>
            <p class="text-3xl font-bold mt-4 font-tajawal" style="color: {{ $summary['estimated_profit'] >= 0 ? $themeColor : '#dc2626' }};">
                {{ number_format($summary['estimated_profit']) }} ج.م
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-8">
        <div class="px-5 py-4 border-b font-bold font-tajawal" style="background: {{ $themeColor }}08;">ربحية المشاريع</div>
        <table class="w-full text-sm font-tajawal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">محصّل</th>
                    <th class="px-4 py-3 text-right">مصروفات</th>
                    <th class="px-4 py-3 text-right">ربح</th>
                    <th class="px-4 py-3 text-right">هامش</th>
                    <th class="px-4 py-3 text-right">مستحقات</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($summary['project_rows'] as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('projects.show', $row['project']) }}" class="font-bold" style="color:{{ $themeColor }};">{{ $row['project']->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-emerald-700">{{ number_format($row['revenue_paid']) }}</td>
                    <td class="px-4 py-3 text-red-700">{{ number_format($row['expenses']) }}</td>
                    <td class="px-4 py-3 font-bold">{{ number_format($row['profit']) }}</td>
                    <td class="px-4 py-3">{{ $row['margin_percent'] }}%</td>
                    <td class="px-4 py-3 text-amber-700">{{ number_format($row['outstanding']) }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد بيانات مشاريع.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
        <h2 class="font-bold mb-4 font-tajawal">آخر فواتير التسليم</h2>
        <ul class="space-y-2 text-sm font-tajawal">
            @forelse($summary['recent_delivery_invoices'] as $inv)
            <li class="flex justify-between py-2 border-b border-gray-50">
                <a href="{{ route('invoices.show', $inv) }}" class="text-blue-600 font-semibold">{{ $inv->invoice_number }}</a>
                <span>{{ $inv->client?->name }} — {{ $inv->project?->name }}</span>
                <strong>{{ number_format($inv->total_amount) }} ({{ $inv->status }})</strong>
            </li>
            @empty
            <li class="text-gray-500">لم تُصدر فواتير تسليم بعد.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
