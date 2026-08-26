@extends('layouts.app')

@section('page-title', 'لوحة موظف المبيعات')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $fmt = function ($val, $type) {
        if ($type === 'currency') return number_format((float) $val, 0).' ج.م';
        if ($type === 'percent') return number_format((float) $val, 1).'%';
        if ($type === 'ratio') return number_format((float) $val, 2).'×';
        return number_format((float) $val, 0);
    };
@endphp
<div class="w-full max-w-full px-2 sm:px-0 font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'لوحة موظف المبيعات',
        'subtitle' => 'KPIs B2B Software — '.$dashboard['period_label'].' — '.$repUser->name,
        'icon' => 'chart',
    ])

    <div class="flex flex-wrap justify-between gap-3 mb-6">
        <form method="GET" class="flex flex-wrap gap-2 items-center bg-white rounded-2xl shadow-sm border border-gray-200 p-3">
            @if($canViewAll && $salesReps->isNotEmpty())
            <select name="user_id" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm min-w-[180px] font-tajawal">
                @foreach($salesReps as $rep)
                <option value="{{ $rep->id }}" @selected($repUser->id == $rep->id)>{{ $rep->name }}</option>
                @endforeach
            </select>
            @endif
            <select name="year" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-tajawal">
                @for($y = now()->year; $y >= now()->year - 2; $y--)
                <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
                @endfor
            </select>
            <select name="month" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-tajawal">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" @selected($month == $m)>{{ $m }}</option>
                @endfor
            </select>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95"
                    style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">تصفية</button>
        </form>
        <div class="flex flex-wrap gap-2">
            @can('edit-employees')
            <a href="{{ route('sales.kpi-targets.index', ['user_id' => $repUser->id, 'year' => $year, 'month' => $month]) }}"
               class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">ضبط الأهداف</a>
            @endcan
            <a href="{{ route('leads.index') }}" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-lg hover:shadow-xl transition"
               style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">Leads</a>
            <a href="{{ route('sales.index') }}" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-lg hover:shadow-xl transition"
               style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">Pipeline</a>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['الدرجة الإجمالية KPI', $dashboard['overall_score'].'%', $themeColor],
            ['Pipeline مفتوح', number_format($dashboard['pipeline']['total_value'], 0).' ج.م', '#2563eb'],
            ['إيراد Won', $fmt($dashboard['metrics']['revenue_won']['actual'] ?? 0, 'currency'), '#059669'],
            ['Quota Attainment', ($dashboard['metrics']['revenue_won']['attainment_pct'] ?? 0).'%', '#7c3aed'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 text-center hover:shadow-xl transition-all duration-300">
            <p class="text-gray-500 text-xs font-bold">{{ $label }}</p>
            <p class="text-2xl md:text-3xl font-bold mt-1" style="color: {{ $color }};">{{ $val }}</p>
            @if($label === 'Pipeline مفتوح')
            <p class="text-xs text-gray-500 mt-1">{{ $dashboard['pipeline']['count'] }} فرصة</p>
            @endif
        </div>
        @endforeach
    </div>

    @if(!empty($dashboard['monthly_duties']))
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 shadow-sm">
        <h3 class="font-bold text-amber-900 mb-3">واجباتك الشهرية (من الإدارة)</h3>
        <ul class="space-y-2 text-sm text-amber-950">
            @foreach($dashboard['monthly_duties'] as $duty)
            <li class="flex gap-2"><span class="font-bold shrink-0">{{ $duty['metric'] }}:</span> {{ $duty['duty'] }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
        <div class="xl:col-span-8 bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                <h2 class="font-bold text-lg text-gray-900">KPIs — B2B Software Sales</h2>
                <p class="text-xs text-gray-500 mt-0.5">Quota · Pipeline · Win Rate · SQL · Opportunities</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead style="background: {{ $themeColor }}08;">
                        <tr class="text-xs text-gray-600">
                            <th class="px-4 py-3 text-right font-bold">المؤشر</th>
                            <th class="px-4 py-3 text-right font-bold">فعلي</th>
                            <th class="px-4 py-3 text-right font-bold">الهدف</th>
                            <th class="px-4 py-3 text-right font-bold">التحقق</th>
                            <th class="px-4 py-3 text-right font-bold">الوزن</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($dashboard['metrics'] as $m)
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3">
                                <p class="font-bold text-gray-900">{{ $m['label'] }}</p>
                                <p class="text-xs text-gray-500 max-w-xs">{{ $m['description'] ?? '' }}</p>
                            </td>
                            <td class="px-4 py-3 font-semibold">{{ $fmt($m['actual'], $m['type']) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $fmt($m['target'], $m['type']) }}</td>
                            <td class="px-4 py-3">
                                @php $pct = $m['attainment_pct']; @endphp
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden max-w-[100px]">
                                        <div class="h-full rounded-full {{ $pct >= 100 ? 'bg-emerald-500' : ($pct >= 70 ? 'bg-amber-400' : 'bg-red-400') }}" style="width:{{ min(100, $pct) }}%"></div>
                                    </div>
                                    <span class="font-bold text-xs">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $m['weight'] }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
                <h3 class="font-bold text-gray-900 mb-3">أبرز Pipeline</h3>
                <ul class="space-y-3 text-sm">
                    @forelse($dashboard['pipeline']['deals'] as $deal)
                    <li class="border border-gray-100 rounded-xl p-3 hover:border-gray-200 transition">
                        <a href="{{ route('sales.show', $deal) }}" class="font-bold text-blue-600 hover:underline">{{ $deal->product_service ?: 'فرصة #'.$deal->id }}</a>
                        <p class="text-xs text-gray-500 mt-1">{{ $deal->client?->name ?? '—' }} · {{ $deal->stage }}</p>
                        <p class="text-sm font-bold mt-1">{{ number_format((float) ($deal->estimated_value ?? 0), 0) }} ج.م</p>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm">لا توجد فرص مفتوحة.</li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
                <h3 class="font-bold text-gray-900 mb-3">صفقات Won — الشهر</h3>
                <ul class="space-y-2 text-sm">
                    @forelse($dashboard['recent_wins'] as $win)
                    <li class="flex justify-between gap-2 border-b border-gray-50 pb-2">
                        <span>{{ $win->client?->name ?? $win->product_service }}</span>
                        <span class="font-bold text-emerald-700">{{ number_format((float) ($win->actual_value ?? $win->estimated_value ?? 0), 0) }}</span>
                    </li>
                    @empty
                    <li class="text-gray-500">لا صفقات مغلقة هذا الشهر.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
