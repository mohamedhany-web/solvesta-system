@extends('layouts.app')

@section('page-title', 'Pre-Sales — تقدير وتسعير')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'Pre-Sales',
        'subtitle' => 'تقدير التكلفة · إصدار العروض · بين التأهيل والعقد',
        'icon' => 'doc',
    ])

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['label' => 'في الطابور', 'value' => $stats['queue'], 'color' => $themeColor],
            ['label' => 'بانتظار تقدير', 'value' => $stats['needs_estimate'], 'color' => '#d97706'],
            ['label' => 'عروض مُرسلة', 'value' => $stats['proposals_sent'], 'color' => '#2563eb'],
            ['label' => 'عروض مقبولة', 'value' => $stats['proposals_accepted'], 'color' => '#059669'],
        ] as $card)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
            <p class="text-xs text-gray-500 mb-1 font-tajawal">{{ $card['label'] }}</p>
            <p class="text-3xl font-bold font-tajawal" style="color: {{ $card['color'] }};">{{ $card['value'] }}</p>
        </div>
        @endforeach
    </div>

    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-6 flex flex-wrap gap-3 items-end">
        <select name="filter" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-tajawal">
            <option value="">كل الفرص المؤهّلة</option>
            <option value="needs_estimate" @selected(request('filter')==='needs_estimate')>تحتاج تقدير تكلفة</option>
            <option value="needs_proposal" @selected(request('filter')==='needs_proposal')>جاهزة لإصدار عرض</option>
            <option value="pending_client" @selected(request('filter')==='pending_client')>بانتظار رد العميل</option>
        </select>
        <button class="px-5 py-2.5 rounded-xl text-white text-sm font-bold font-tajawal"
                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">تصفية</button>
    </form>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background: {{ $themeColor }}08;">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">العميل / الخدمة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">مندوب المبيعات</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">التقدير</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">العرض</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-700">المرحلة</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($sales as $sale)
                @php
                    $est = $sale->costEstimations->sortByDesc('id')->first();
                    $prop = $sale->proposals->sortByDesc('id')->first();
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-4">
                        <p class="font-bold text-gray-900">{{ $sale->client?->name }}</p>
                        <p class="text-xs text-gray-500">{{ $sale->product_service }}</p>
                    </td>
                    <td class="px-4 py-4 text-gray-600">{{ $sale->salesRep?->name ?? '—' }}</td>
                    <td class="px-4 py-4">
                        @if($est)
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">{{ $est->status_label }}</span>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($est->total_cost) }} ج.م</p>
                        @else
                            <span class="text-amber-600 text-xs font-bold">مطلوب</span>
                        @endif
                    </td>
                    <td class="px-4 py-4">
                        @if($prop)
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $prop->status_color }}">{{ $prop->status_label }}</span>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-gray-600">{{ $sale->stage }}</td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <a href="{{ route('pre-sales.estimate', $sale) }}" class="font-bold text-sm" style="color: {{ $themeColor }};">تقدير</a>
                        @if($prop)
                            · <a href="{{ route('pre-sales.proposals.show', $prop) }}" class="text-blue-600 font-bold text-sm">عرض</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-16 text-center text-gray-500">لا توجد فرص مؤهّلة في طابور Pre-Sales.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t">{{ $sales->links() }}</div>
    </div>
</div>
@endsection
