@extends('layouts.app')

@section('page-title', 'مركز التحكم التنفيذي')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $criticalCount = (int) ($health['open_critical'] ?? 0);
    $warningCount = (int) ($health['open_warning'] ?? 0);
    $slaOk = ((float) ($health['sla_compliance'] ?? 100)) >= 90;
    $kpiCards = [
        [
            'label' => 'إيراد الشهر',
            'value' => number_format((float) $health['revenue']).' ج.م',
            'sub' => 'فواتير محصّلة هذا الشهر',
            'color' => '#059669',
            'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'Pipeline',
            'value' => number_format((float) $health['pipeline']).' ج.م',
            'sub' => 'فرص مفتوحة',
            'color' => '#2563eb',
            'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        ],
        [
            'label' => 'مشاريع نشطة',
            'value' => (string) $health['active_projects'],
            'sub' => 'تخطيط وتنفيذ',
            'color' => $themeColor,
            'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        ],
        [
            'label' => 'مشاريع متأخرة',
            'value' => (string) $health['overdue_projects'],
            'sub' => $health['overdue_projects'] ? 'تحتاج متابعة فورية' : 'لا تأخير حالياً',
            'color' => $health['overdue_projects'] ? '#dc2626' : '#059669',
            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'حرجة',
            'value' => (string) $criticalCount,
            'sub' => 'استثناءات critical',
            'color' => $criticalCount ? '#dc2626' : '#059669',
            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
        ],
        [
            'label' => 'تحذير',
            'value' => (string) $warningCount,
            'sub' => 'استثناءات warning',
            'color' => $warningCount ? '#d97706' : '#059669',
            'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'التزام SLA',
            'value' => $health['sla_compliance'].'%',
            'sub' => $slaOk ? 'ضمن المستوى المطلوب' : 'أقل من 90%',
            'color' => $slaOk ? '#059669' : '#dc2626',
            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        ],
        [
            'label' => 'Leads جديدة',
            'value' => (string) $health['leads_new'],
            'sub' => 'بانتظار المتابعة',
            'color' => '#7c3aed',
            'icon' => 'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z',
        ],
    ];
@endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6 sm:mb-8">
        <div class="rounded-2xl p-5 sm:p-6 lg:p-8 shadow-xl border overflow-hidden relative"
             style="background: linear-gradient(135deg, {{ $themeColor }}15 0%, {{ $themeColor }}05 50%, {{ $themeColor }}10 100%); border-color: {{ $themeColor }}30;">
            <div class="absolute inset-0 opacity-5 pointer-events-none overflow-hidden">
                <div class="absolute top-8 right-8 w-48 h-48 rounded-full" style="background: {{ $themeColor }};"></div>
                <div class="absolute bottom-8 left-8 w-32 h-32 rounded-full" style="background: {{ $themeColor }};"></div>
            </div>
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="h-14 w-14 sm:h-16 sm:w-16 rounded-2xl flex items-center justify-center shadow-xl flex-shrink-0"
                         style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        <svg class="h-7 w-7 sm:h-8 sm:w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">مركز التحكم التنفيذي</h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1">إدارة بالاستثناء — ركّز على الأحمر والأصفر فقط</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">{{ now()->locale('ar')->translatedFormat('l، d F Y') }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 sm:gap-3">
                    <a href="{{ route('executive.finance') }}"
                       class="px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-md hover:shadow-lg transition"
                       style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        التقارير المالية
                    </a>
                    <a href="{{ route('leads.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                        Leads
                    </a>
                    <a href="{{ route('sales.index') }}"
                       class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm transition">
                        Pipeline
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 mb-6 sm:mb-8">
        @foreach($kpiCards as $card)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0 text-right flex-1">
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">{{ $card['label'] }}</p>
                        <p class="text-2xl sm:text-3xl font-bold truncate" style="color: {{ $card['color'] }};">{{ $card['value'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $card['sub'] }}</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-5 mb-6 sm:mb-8">
        <div class="rounded-2xl border p-5 sm:p-6 {{ $criticalCount ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200' }} shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-gray-900">حرجة</h3>
                <span class="text-2xl font-bold {{ $criticalCount ? 'text-red-600' : 'text-emerald-600' }}">{{ $criticalCount }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $criticalCount ? 'تحتاج تدخلاً فورياً من الإدارة' : 'لا توجد حالات حرجة حالياً' }}</p>
        </div>
        <div class="rounded-2xl border p-5 sm:p-6 {{ $warningCount ? 'bg-amber-50 border-amber-200' : 'bg-white border-gray-200' }} shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-gray-900">تحذيرات</h3>
                <span class="text-2xl font-bold {{ $warningCount ? 'text-amber-600' : 'text-emerald-600' }}">{{ $warningCount }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $warningCount ? 'راقبها قبل أن تتحول لحرجة' : 'لا توجد تحذيرات مفتوحة' }}</p>
        </div>
        <div class="rounded-2xl border p-5 sm:p-6 {{ $slaOk ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200' }} shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="font-bold text-gray-900">صحة SLA</h3>
                <span class="text-2xl font-bold {{ $slaOk ? 'text-emerald-600' : 'text-red-600' }}">{{ $health['sla_compliance'] }}%</span>
            </div>
            <p class="text-sm text-gray-600">{{ $slaOk ? 'الالتزام ضمن المستوى المطلوب' : 'نسبة الالتزام أقل من المستهدف' }}</p>
        </div>
    </div>

    <section class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
        <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
             style="background: {{ $themeColor }}08;">
            <div>
                <h2 class="font-bold text-lg sm:text-xl text-gray-900">ما يحتاج تدخلك</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $exceptions->count() }} استثناء مفتوح — الباقي على المسار</p>
            </div>
            @if($exceptions->isNotEmpty())
                <div class="flex flex-wrap gap-2 text-xs font-semibold">
                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">حرجة {{ $criticalCount }}</span>
                    <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700">تحذير {{ $warningCount }}</span>
                </div>
            @endif
        </div>

        @forelse($exceptions as $exception)
            @php
                $url = app(\App\Services\CompanyOs\ExceptionService::class)->subjectUrl($exception->subject, $exception->subject_type, $exception->subject_id);
                $isCritical = $exception->severity === 'critical';
                $tone = $isCritical ? 'bg-red-50/60 hover:bg-red-50' : 'bg-amber-50/40 hover:bg-amber-50';
                $badge = $isCritical
                    ? 'bg-red-100 text-red-800 border-red-200'
                    : 'bg-amber-100 text-amber-800 border-amber-200';
                $dot = $isCritical ? 'bg-red-600' : 'bg-amber-500';
            @endphp
            <div class="px-5 sm:px-6 py-4 border-b border-gray-100 last:border-b-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $tone }} transition-colors">
                <div class="flex items-start gap-3 min-w-0">
                    <span class="mt-2 h-2.5 w-2.5 rounded-full {{ $dot }} shrink-0"></span>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <p class="font-bold text-gray-900 text-base">{{ $exception->title }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $badge }}">
                                {{ $isCritical ? 'حرج' : 'تحذير' }}
                            </span>
                        </div>
                        @if($exception->message)
                            <p class="text-sm sm:text-base text-gray-700 leading-relaxed">{{ $exception->message }}</p>
                        @endif
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">
                            المسؤول: {{ $exception->owner?->name ?? 'غير معيّن' }}
                            · {{ $exception->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @if($url)
                    <a href="{{ $url }}"
                       class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition shrink-0"
                       style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        فتح التفاصيل
                    </a>
                @endif
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <div class="mx-auto h-14 w-14 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                    <svg class="h-7 w-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">لا توجد استثناءات مفتوحة</h3>
                <p class="mt-1 text-sm text-gray-500">الشركة على المسار حالياً</p>
            </div>
        @endforelse
    </section>
</div>
@endsection
