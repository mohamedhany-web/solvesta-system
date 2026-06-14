@extends('layouts.app')

@section('page-title', 'KPI — أداء الموظفين')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'KPI الموظفين',
        'subtitle' => 'التزام · إنجاز المهام · جودة · تقييم Team Lead',
        'icon' => 'chart',
    ])

    <div class="flex flex-wrap justify-between gap-3 mb-6">
        <form method="GET" class="flex flex-wrap gap-2 items-center">
            <select name="year" class="border rounded-xl px-3 py-2 text-sm">
                @for($y = now()->year; $y >= now()->year - 2; $y--)
                    <option value="{{ $y }}" @selected($year==$y)>{{ $y }}</option>
                @endfor
            </select>
            <select name="month" class="border rounded-xl px-3 py-2 text-sm">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($month==$m)>{{ $m }}</option>
                @endfor
            </select>
            <button class="px-4 py-2 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }};">تصفية</button>
        </form>
        @can('edit-employees')
        <form method="POST" action="{{ route('kpi.recalculate') }}">
            @csrf
            <input type="hidden" name="year" value="{{ $year }}">
            <input type="hidden" name="month" value="{{ $month }}">
            <button class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-sm">إعادة حساب الكل</button>
        </form>
        @endcan
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['متوسط KPI', $stats['avg_score'].'%', $themeColor],
            ['ممتاز (90+)', $stats['excellent'], '#059669'],
            ['يحتاج تحسين', $stats['needs_improvement'], '#dc2626'],
            ['موظفون', $stats['employees'], '#6b7280'],
        ] as [$l,$v,$c])
        <div class="bg-white rounded-2xl shadow-lg border p-4 text-center">
            <p class="text-xs text-gray-500 font-tajawal">{{ $l }}</p>
            <p class="text-2xl font-bold" style="color:{{ $c }};">{{ $v }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background:{{ $themeColor }}08;">
                <tr>
                    <th class="px-4 py-3 text-right">الموظف</th>
                    <th class="px-4 py-3 text-right">الدور</th>
                    <th class="px-4 py-3 text-right">التزام</th>
                    <th class="px-4 py-3 text-right">المهام</th>
                    <th class="px-4 py-3 text-right">الجودة</th>
                    <th class="px-4 py-3 text-right">TL</th>
                    <th class="px-4 py-3 text-right">الإجمالي</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($periods as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold">{{ $p->user?->name }}</td>
                    <td class="px-4 py-3 text-xs">{{ $p->role_template }}</td>
                    <td class="px-4 py-3">{{ $p->adherence_score }}%</td>
                    <td class="px-4 py-3">{{ $p->task_completion_score }}%</td>
                    <td class="px-4 py-3">{{ $p->quality_score }}%</td>
                    <td class="px-4 py-3">{{ $p->team_lead_rating ?? '—' }}</td>
                    <td class="px-4 py-3">
                        <span class="font-bold" style="color:{{ $themeColor }};">{{ $p->total_score }}%</span>
                        <span class="text-xs text-gray-500">({{ $p->grade_label }})</span>
                    </td>
                    <td class="px-4 py-3"><a href="{{ route('kpi.show', $p->user) }}?year={{ $year }}&month={{ $month }}" class="font-bold text-blue-600">تفاصيل</a></td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-12 text-center text-gray-500">لا توجد بيانات KPI — اضغط «إعادة حساب الكل».</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $periods->links() }}</div>
    </div>
</div>
@endsection
