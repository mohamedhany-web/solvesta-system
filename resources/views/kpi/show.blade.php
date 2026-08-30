@extends('layouts.app')

@section('page-title', 'KPI — '.$user->name)

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $monthsAr = [
        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
    ];
    $total = (float) $period->total_score;
    $totalColor = $total >= 90 ? '#059669' : ($total >= 75 ? '#2563eb' : ($total >= 60 ? '#d97706' : '#dc2626'));
    $metrics = [
        ['التزام (حضور)', $period->adherence_score, $weights['adherence'] ?? 0, '#059669'],
        ['إنجاز المهام', $period->task_completion_score, $weights['task_completion'] ?? 0, '#2563eb'],
        ['جودة الكود/العمل', $period->quality_score, $weights['quality'] ?? 0, '#7c3aed'],
        ['تقييم Team Lead', $period->team_lead_rating ?? 0, $weights['team_lead'] ?? 0, '#d97706'],
    ];
@endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-blue-100">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0"
                         style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white border border-gray-200 text-gray-700">
                                {{ $monthsAr[$month] ?? $month }} {{ $year }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                  style="background: {{ $totalColor }}15; color: {{ $totalColor }};">
                                {{ $period->grade_label }}
                            </span>
                        </div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 break-words">KPI — {{ $user->name }}</h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-1">
                            {{ $period->role_template ?: 'دور عام' }}
                            @if($user->employee?->position)
                                · {{ $user->employee->position }}
                            @endif
                        </p>
                    </div>
                </div>
                <a href="{{ route('kpi.index', ['year' => $year, 'month' => $month]) }}"
                   class="w-full sm:w-auto bg-gray-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">ملخص الأداء</h2>
            </div>
            <div class="p-5 sm:p-6 lg:p-8">
                <div class="text-center mb-8">
                    <p class="text-5xl sm:text-6xl font-bold" style="color: {{ $totalColor }};">{{ $period->total_score }}%</p>
                    <p class="text-sm text-gray-500 mt-2">الدرجة الإجمالية · خصومات: {{ $period->kpi_deductions }} نقطة</p>
                    <div class="mx-auto mt-4 h-3 max-w-md bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all" style="width: {{ min(100, $total) }}%; background: {{ $totalColor }};"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($metrics as [$label, $score, $weight, $color])
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 sm:p-5">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <p class="text-sm font-semibold text-gray-700">{{ $label }}</p>
                                <span class="text-xs font-bold text-gray-500">وزن {{ $weight }}%</span>
                            </div>
                            <p class="text-2xl sm:text-3xl font-bold" style="color: {{ $color }};">{{ $score }}%</p>
                            <div class="h-2 bg-gray-200 rounded-full mt-3 overflow-hidden">
                                <div class="h-full rounded-full" style="width: {{ min(100, (float) $score) }}%; background: {{ $color }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($period->notes)
                    <div class="mt-6 rounded-xl border border-gray-100 bg-white px-4 py-4">
                        <h3 class="text-sm font-bold text-gray-900 mb-2">ملاحظات</h3>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $period->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-4">
            @can('edit-employees')
            <form method="POST" action="{{ route('kpi.rate', $user) }}"
                  class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-5 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-gray-900">تقييم Team Lead</h3>
                    <p class="text-xs text-gray-500 mt-1">من 0 إلى 100</p>
                </div>
                <div class="p-4 sm:p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الدرجة</label>
                        <input type="number" name="team_lead_rating" min="0" max="100" step="1" required
                               value="{{ old('team_lead_rating', $period->team_lead_rating) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0-100">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">ملاحظات</label>
                        <textarea name="notes" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="ملاحظات التقييم...">{{ old('notes', $period->notes) }}</textarea>
                    </div>
                    <button type="submit"
                            class="w-full py-3 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition"
                            style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        حفظ التقييم
                    </button>
                </div>
            </form>
            @endcan

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-3">معلومات الفترة</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex justify-between gap-3">
                        <span class="text-gray-500">الشهر</span>
                        <span class="font-semibold text-gray-900">{{ $monthsAr[$month] ?? $month }} {{ $year }}</span>
                    </li>
                    <li class="flex justify-between gap-3">
                        <span class="text-gray-500">القالب</span>
                        <span class="font-semibold text-gray-900">{{ $period->role_template ?: '—' }}</span>
                    </li>
                    <li class="flex justify-between gap-3">
                        <span class="text-gray-500">التقدير</span>
                        <span class="font-semibold" style="color: {{ $totalColor }};">{{ $period->grade_label }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if($history->count())
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900">السجل التاريخي</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @foreach($history as $h)
                @php
                    $hScore = (float) $h->total_score;
                    $hColor = $hScore >= 90 ? '#059669' : ($hScore >= 75 ? '#2563eb' : ($hScore >= 60 ? '#d97706' : '#dc2626'));
                @endphp
                <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 hover:bg-gray-50 transition-colors">
                    <div>
                        <p class="font-bold text-gray-900">{{ $h->period_label }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $h->grade_label }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-28 h-2 bg-gray-100 rounded-full overflow-hidden hidden sm:block">
                            <div class="h-full rounded-full" style="width: {{ min(100, $hScore) }}%; background: {{ $hColor }};"></div>
                        </div>
                        <span class="text-lg font-bold" style="color: {{ $hColor }};">{{ $h->total_score }}%</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
