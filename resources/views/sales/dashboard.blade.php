@extends('layouts.app')

@section('page-title', 'لوحة التحكم')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $fmt = function ($val, $type) {
        if ($type === 'currency') return number_format((float) $val, 0).' ج.م';
        if ($type === 'percent') return number_format((float) $val, 1).'%';
        if ($type === 'ratio') return number_format((float) $val, 2).'×';
        return number_format((float) $val, 0);
    };
    $displayName = $repUser->name;
    $roleName = $repUser->roles->first()
        ? \App\Helpers\RoleHelper::getRoleName($repUser->roles->first()->name)
        : 'موظف مبيعات';
@endphp

<div class="w-full max-w-full px-2 sm:px-0 font-tajawal">

    {{-- Welcome header — نفس أسلوب لوحة التحكم الرئيسية --}}
    <div class="mb-4 sm:mb-6 lg:mb-8">
        <div class="rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl border overflow-hidden relative"
             style="background: linear-gradient(135deg, {{ $themeColor }}15 0%, {{ $themeColor }}05 50%, {{ $themeColor }}10 100%); border-color: {{ $themeColor }}30;">
            <div class="absolute top-0 left-0 w-full h-full opacity-5 overflow-hidden pointer-events-none">
                <div class="absolute top-10 right-10 w-64 h-64 rounded-full" style="background: {{ $themeColor }};"></div>
                <div class="absolute bottom-10 left-10 w-48 h-48 rounded-full" style="background: {{ $themeColor }};"></div>
            </div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl flex items-center justify-center shadow-xl flex-shrink-0"
                         style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        <svg class="h-7 w-7 sm:h-8 sm:w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-tajawal">
                            مرحباً، {{ $displayName }}
                        </h1>
                        <p class="text-gray-700 text-sm sm:text-base mt-1 font-tajawal">
                            <span class="font-bold px-3 py-1 rounded-xl text-sm inline-flex items-center gap-1 shadow-sm text-white"
                                  style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                                {{ $roleName }}
                            </span>
                            <span class="text-gray-500 mr-2">· {{ $dashboard['period_label'] }}</span>
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2 font-tajawal">
                            {{ now()->locale('ar')->translatedFormat('l، d F Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <a href="{{ route('leads.index') }}" class="px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-md hover:shadow-lg transition"
                       style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);">Leads</a>
                    <a href="{{ route('sales.index') }}" class="px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-md hover:shadow-lg transition"
                       style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">Pipeline</a>
                    @can('edit-employees')
                    <a href="{{ route('sales.kpi-targets.index', ['user_id' => $repUser->id, 'year' => $year, 'month' => $month]) }}"
                       class="px-4 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">ضبط الأهداف</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-5 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            @if($canViewAll && $salesReps->isNotEmpty())
            <div class="flex-1 min-w-[160px]">
                <label class="block text-xs font-bold text-gray-500 mb-1 font-tajawal">موظف المبيعات</label>
                <select name="user_id" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-tajawal">
                    @foreach($salesReps as $rep)
                    <option value="{{ $rep->id }}" @selected($repUser->id == $rep->id)>{{ $rep->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 font-tajawal">السنة</label>
                <select name="year" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-tajawal">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1 font-tajawal">الشهر</label>
                <select name="month" class="border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-tajawal">
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($month == $m)>{{ $m }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95"
                    style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">تصفية</button>
        </form>
    </div>

    {{-- KPI stat cards — نفس نمط بطاقات لوحة التحكم --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-6 sm:mb-8">
        @foreach([
            ['label' => 'الدرجة الإجمالية KPI', 'value' => $dashboard['overall_score'].'%', 'sub' => 'أداء الشهر', 'color' => $themeColor, 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['label' => 'Pipeline مفتوح', 'value' => number_format($dashboard['pipeline']['total_value'], 0).' ج.م', 'sub' => $dashboard['pipeline']['count'].' فرصة', 'color' => '#2563eb', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
            ['label' => 'إيراد Won', 'value' => $fmt($dashboard['metrics']['revenue_won']['actual'] ?? 0, 'currency'), 'sub' => 'صفقات مغلقة', 'color' => '#059669', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['label' => 'Quota Attainment', 'value' => ($dashboard['metrics']['revenue_won']['attainment_pct'] ?? 0).'%', 'sub' => 'تحقيق الهدف', 'color' => '#7c3aed', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
        ] as $card)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 transform group">
            <div class="flex items-center justify-between gap-3">
                <div class="text-right flex-1 min-w-0">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1 font-tajawal">{{ $card['label'] }}</div>
                    <div class="text-2xl sm:text-3xl font-bold font-tajawal truncate" style="color: {{ $card['color'] }};">{{ $card['value'] }}</div>
                    <div class="text-xs text-gray-500 mt-2 font-tajawal">{{ $card['sub'] }}</div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300"
                     style="background: linear-gradient(135deg, {{ $card['color'] }} 0%, {{ $card['color'] }}cc 100%);">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" />
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(!empty($dashboard['monthly_duties']))
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6 shadow-sm">
        <h3 class="font-bold text-amber-900 mb-3 font-tajawal">واجباتك الشهرية (من الإدارة)</h3>
        <ul class="space-y-2 text-sm text-amber-950 font-tajawal">
            @foreach($dashboard['monthly_duties'] as $duty)
            <li class="flex gap-2"><span class="font-bold shrink-0">{{ $duty['metric'] }}:</span> {{ $duty['duty'] }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
        <div class="xl:col-span-8 bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center gap-3"
                 style="background: linear-gradient(135deg, {{ $themeColor }}08 0%, {{ $themeColor }}04 100%);">
                <div class="p-2 rounded-xl shadow-sm" style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-lg text-gray-900 font-tajawal">KPIs — B2B Software Sales</h2>
                    <p class="text-xs text-gray-500 mt-0.5 font-tajawal">Quota · Pipeline · Win Rate · SQL · Opportunities</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm font-tajawal">
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
                <h3 class="font-bold text-gray-900 mb-3 font-tajawal">أبرز Pipeline</h3>
                <ul class="space-y-3 text-sm font-tajawal">
                    @forelse($dashboard['pipeline']['deals'] as $deal)
                    <li class="border border-gray-100 rounded-xl p-3 hover:border-gray-200 hover:shadow-sm transition">
                        <a href="{{ route('sales.show', $deal) }}" class="font-bold hover:underline" style="color: {{ $themeColor }};">{{ $deal->product_service ?: 'فرصة #'.$deal->id }}</a>
                        <p class="text-xs text-gray-500 mt-1">{{ $deal->client?->name ?? '—' }} · {{ $deal->stage }}</p>
                        <p class="text-sm font-bold mt-1 text-gray-900">{{ number_format((float) ($deal->estimated_value ?? 0), 0) }} ج.م</p>
                    </li>
                    @empty
                    <li class="text-gray-500 text-sm">لا توجد فرص مفتوحة.</li>
                    @endforelse
                </ul>
            </div>
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
                <h3 class="font-bold text-gray-900 mb-3 font-tajawal">صفقات Won — الشهر</h3>
                <ul class="space-y-2 text-sm font-tajawal">
                    @forelse($dashboard['recent_wins'] as $win)
                    <li class="flex justify-between gap-2 border-b border-gray-50 pb-2 last:border-0">
                        <span class="text-gray-700 truncate">{{ $win->client?->name ?? $win->product_service }}</span>
                        <span class="font-bold text-emerald-700 shrink-0">{{ number_format((float) ($win->actual_value ?? $win->estimated_value ?? 0), 0) }}</span>
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
