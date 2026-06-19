@extends('layouts.app')

@section('page-title', 'التقارير اليومية')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $view = $view ?? 'my';
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'التقارير اليومية',
        'subtitle' => 'التسلسل: موظف → Team Lead → رئيس القسم → الإدارة العليا',
        'icon' => 'doc',
    ])

    <div class="flex flex-wrap justify-between gap-3 mb-6 -mt-2">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('daily-reports.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-sm hover:opacity-95"
               style="background: {{ $themeColor }};">+ تقرير اليوم</a>

            @if($caps['team_lead'])
            <a href="{{ route('daily-reports.index', ['view' => 'team']) }}"
               class="px-4 py-2.5 rounded-xl border text-sm font-bold {{ $view === 'team' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white border-gray-200' }}">
                مراجعة Team Lead
                @if($stats['pending_team'])<span class="mr-1 rounded-full bg-red-500 text-white px-1.5 text-xs">{{ $stats['pending_team'] }}</span>@endif
            </a>
            @endif
            @if($caps['dept_head'])
            <a href="{{ route('daily-reports.index', ['view' => 'department']) }}"
               class="px-4 py-2.5 rounded-xl border text-sm font-bold {{ $view === 'department' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white border-gray-200' }}">
                مراجعة القسم
                @if($stats['pending_dept'])<span class="mr-1 rounded-full bg-red-500 text-white px-1.5 text-xs">{{ $stats['pending_dept'] }}</span>@endif
            </a>
            @endif
            @if($caps['executive'])
            <a href="{{ route('daily-reports.index', ['view' => 'executive']) }}"
               class="px-4 py-2.5 rounded-xl border text-sm font-bold {{ $view === 'executive' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white border-gray-200' }}">
                الإدارة العليا
                @if($stats['pending_executive'])<span class="mr-1 rounded-full bg-red-500 text-white px-1.5 text-xs">{{ $stats['pending_executive'] }}</span>@endif
            </a>
            @endif
            @if($view !== 'my')
            <a href="{{ route('daily-reports.index') }}" class="px-4 py-2.5 rounded-xl border bg-white text-sm font-semibold text-gray-600">تقاريري</a>
            @endif
        </div>
        <div class="flex gap-4 text-sm">
            <span>ساعات الأسبوع: <strong style="color:{{ $themeColor }}">{{ $stats['week_hours'] }}</strong></span>
            @if($stats['open_blockers'])<span class="text-red-600 font-bold">{{ $stats['open_blockers'] }} blocker</span>@endif
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50/80">
                <tr>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">التاريخ</th>
                    @if($view !== 'my')<th class="px-4 py-3 text-right font-bold text-gray-600">الموظف</th>@endif
                    <th class="px-4 py-3 text-right font-bold text-gray-600">المشروع</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">الساعات</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">Blocker</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">الحالة</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-600">إجراء</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reports as $r)
                @php
                    $reviewLevel = match(true) {
                        $view === 'executive' => 'executive',
                        $view === 'department' => 'dept_head',
                        $view === 'team' => 'team_lead',
                        default => null,
                    };
                @endphp
                <tr class="hover:bg-gray-50/80">
                    <td class="px-4 py-3">{{ $r->report_date->format('Y/m/d') }}</td>
                    @if($view !== 'my')<td class="px-4 py-3 font-semibold">{{ $r->user?->name }}</td>@endif
                    <td class="px-4 py-3">{{ $r->project?->name ?? '—' }}</td>
                    <td class="px-4 py-3 font-bold">{{ $r->hours_worked }}</td>
                    <td class="px-4 py-3">@if($r->has_blocker)<span class="text-red-600 text-xs font-bold">نعم</span>@else — @endif</td>
                    <td class="px-4 py-3">
                        <span class="text-xs font-semibold px-2 py-1 rounded-lg bg-slate-100 text-slate-700">
                            {{ $statusLabels[$r->review_status] ?? $r->review_status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($reviewLevel && $r->review_status !== 'closed')
                        <form method="POST" action="{{ route('daily-reports.review', $r) }}" class="inline-flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="level" value="{{ $reviewLevel }}">
                            <input type="text" name="notes" placeholder="ملاحظة..." class="border border-gray-200 rounded-lg px-2 py-1 text-xs w-28">
                            <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg text-white" style="background:{{ $themeColor }}">مراجعة</button>
                        </form>
                        @elseif($r->review_status === 'closed')
                        <span class="text-emerald-600 text-xs font-bold">✓ مكتمل</span>
                        @else
                        —
                        @endif
                    </td>
                </tr>
                @if($r->work_summary)
                <tr class="bg-gray-50/50">
                    <td colspan="{{ $view !== 'my' ? 7 : 6 }}" class="px-4 py-2 text-xs text-gray-600 whitespace-pre-wrap">{{ Str::limit($r->work_summary, 300) }}</td>
                </tr>
                @endif
                @empty
                <tr><td colspan="{{ $view !== 'my' ? 7 : 6 }}" class="px-4 py-12 text-center text-gray-500">لا توجد تقارير.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $reports->links() }}</div>
    </div>
</div>
@endsection
