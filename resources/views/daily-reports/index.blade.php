@extends('layouts.app')

@section('page-title', 'التقارير اليومية')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'التقارير اليومية',
        'subtitle' => 'ماذا عملت؟ كم ساعة؟ هل يوجد Blocker؟',
        'icon' => 'doc',
    ])

    <div class="flex flex-wrap justify-between gap-3 mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('daily-reports.create') }}" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm"
               style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">+ تقرير اليوم</a>
            @if($canReview)
            <a href="{{ route('daily-reports.index', ['view' => 'team']) }}" class="px-4 py-2.5 rounded-xl border bg-white text-sm font-bold">مراجعة الفريق</a>
            @endif
        </div>
        <div class="flex gap-4 text-sm font-tajawal">
            <span>ساعات الأسبوع: <strong style="color:{{ $themeColor }}">{{ $stats['week_hours'] }}</strong></span>
            @if($stats['open_blockers'])<span class="text-red-600 font-bold">{{ $stats['open_blockers'] }} blocker</span>@endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <table class="w-full text-sm font-tajawal">
            <thead style="background: {{ $themeColor }}08;">
                <tr>
                    <th class="px-4 py-3 text-right">التاريخ</th>
                    @if($canReview && request('view')==='team')<th class="px-4 py-3 text-right">الموظف</th>@endif
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">الساعات</th>
                    <th class="px-4 py-3 text-right">Blocker</th>
                    <th class="px-4 py-3 text-right">مراجعة</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($reports as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $r->report_date->format('Y/m/d') }}</td>
                    @if($canReview && request('view')==='team')<td class="px-4 py-3">{{ $r->user?->name }}</td>@endif
                    <td class="px-4 py-3">{{ $r->project?->name ?? '—' }}</td>
                    <td class="px-4 py-3 font-bold">{{ $r->hours_worked }}</td>
                    <td class="px-4 py-3">
                        @if($r->has_blocker)<span class="text-red-600 text-xs font-bold">نعم</span>@else — @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($r->reviewed_at)
                            <span class="text-emerald-600 text-xs">✓</span>
                        @elseif($canReview && request('view')==='team')
                        <form method="POST" action="{{ route('daily-reports.review', $r) }}" class="inline">@csrf
                            <button class="text-xs font-bold" style="color:{{ $themeColor }};">مراجعة</button>
                        </form>
                        @else
                            —
                        @endif
                    </td>
                </tr>
                @if($r->work_summary)
                <tr class="bg-gray-50/50"><td colspan="6" class="px-4 py-2 text-xs text-gray-600 whitespace-pre-wrap">{{ Str::limit($r->work_summary, 200) }}</td></tr>
                @endif
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">لا توجد تقارير.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $reports->links() }}</div>
    </div>
</div>
@endsection
