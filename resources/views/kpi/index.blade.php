@extends('layouts.app')

@section('page-title', 'KPI — أداء الموظفين')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $monthsAr = [
        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
    ];
    $kpiCards = [
        ['متوسط KPI', $stats['avg_score'].'%', 'متوسط الدرجات للفترة', $themeColor, 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        ['ممتاز (90+)', (string) $stats['excellent'], 'أداء مرتفع', '#059669', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['يحتاج تحسين', (string) $stats['needs_improvement'], 'أقل من 60%', '#dc2626', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
        ['الموظفون', (string) $stats['employees'], 'إجمالي الموظفين', '#4f46e5', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
    ];
    $scoreColor = function ($score) {
        $score = (float) $score;
        if ($score >= 90) return '#059669';
        if ($score >= 75) return '#2563eb';
        if ($score >= 60) return '#d97706';
        return '#dc2626';
    };
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
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">KPI الموظفين</h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1">التزام · إنجاز المهام · جودة · تقييم Team Lead</p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">الفترة: {{ $monthsAr[$month] ?? $month }} {{ $year }}</p>
                    </div>
                </div>
                @can('edit-employees')
                <form method="POST" action="{{ route('kpi.recalculate') }}">
                    @csrf
                    <input type="hidden" name="year" value="{{ $year }}">
                    <input type="hidden" name="month" value="{{ $month }}">
                    <button type="submit"
                            class="w-full sm:w-auto px-5 py-2.5 sm:px-6 sm:py-3 rounded-xl text-white text-sm font-bold shadow-md hover:shadow-lg transition"
                            style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);"
                            onclick="return confirm('إعادة حساب KPI لجميع الموظفين لهذه الفترة؟')">
                        إعادة حساب الكل
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('kpi.index') }}"
          class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-6">
        <div class="flex flex-col sm:flex-row gap-3 sm:items-end">
            <div class="flex-1 sm:max-w-[160px]">
                <label class="block text-xs font-bold text-gray-500 mb-1.5">السنة</label>
                <select name="year" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @for($y = now()->year; $y >= now()->year - 2; $y--)
                        <option value="{{ $y }}" @selected($year == $y)>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="flex-1 sm:max-w-[200px]">
                <label class="block text-xs font-bold text-gray-500 mb-1.5">الشهر</label>
                <select name="month" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($monthsAr as $m => $label)
                        <option value="{{ $m }}" @selected($month == $m)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition"
                    style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                تصفية
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5 mb-6 sm:mb-8">
        @foreach($kpiCards as [$label, $value, $sub, $color, $icon])
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 group">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0 text-right flex-1">
                        <p class="text-xs sm:text-sm text-gray-500 mb-1">{{ $label }}</p>
                        <p class="text-2xl sm:text-3xl font-bold truncate" style="color: {{ $color }};">{{ $value }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $sub }}</p>
                    </div>
                    <div class="p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300"
                         style="background: linear-gradient(135deg, {{ $color }} 0%, {{ $color }}cc 100%);">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-100 flex items-center justify-between"
             style="background: {{ $themeColor }}08;">
            <div>
                <h2 class="font-bold text-lg text-gray-900">نتائج الفترة</h2>
                <p class="text-sm text-gray-500">{{ $monthsAr[$month] ?? $month }} {{ $year }}</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[860px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الموظف</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الدور</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">التزام</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">المهام</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الجودة</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">Team Lead</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700">الإجمالي</th>
                        <th class="px-4 py-3.5 text-right font-bold text-gray-700"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($periods as $p)
                        @php $totalColor = $scoreColor($p->total_score); @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5">
                                <p class="font-bold text-gray-900">{{ $p->user?->name ?? '—' }}</p>
                                @if($p->user?->employee?->position)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $p->user->employee->position }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    {{ $p->role_template ?: '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 font-semibold text-gray-700">{{ $p->adherence_score }}%</td>
                            <td class="px-4 py-3.5 font-semibold text-gray-700">{{ $p->task_completion_score }}%</td>
                            <td class="px-4 py-3.5 font-semibold text-gray-700">{{ $p->quality_score }}%</td>
                            <td class="px-4 py-3.5 font-semibold text-gray-700">{{ $p->team_lead_rating !== null ? $p->team_lead_rating.'%' : '—' }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-lg font-bold" style="color: {{ $totalColor }};">{{ $p->total_score }}%</span>
                                    <span class="text-xs text-gray-500">{{ $p->grade_label }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <a href="{{ route('kpi.show', ['user' => $p->user, 'year' => $year, 'month' => $month]) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm hover:opacity-95 transition"
                                   style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                                    تفاصيل
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-16 text-center">
                                <div class="mx-auto h-14 w-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">لا توجد بيانات KPI</h3>
                                <p class="mt-1 text-sm text-gray-500">اضغط «إعادة حساب الكل» لتوليد النتائج لهذه الفترة</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($periods->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $periods->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
