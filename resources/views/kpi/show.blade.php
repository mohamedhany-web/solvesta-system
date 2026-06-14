@extends('layouts.app')

@section('page-title', 'KPI — '.$user->name)

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'KPI — '.$user->name,
        'subtitle' => 'الفترة '.$period->period_label.' · '.$period->grade_label,
        'icon' => 'chart',
    ])

    <div class="flex gap-2 mb-6">
        <a href="{{ route('kpi.index', ['year'=>$year,'month'=>$month]) }}" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold">← القائمة</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border p-6">
            <div class="text-center mb-6">
                <p class="text-6xl font-bold" style="color:{{ $themeColor }};">{{ $period->total_score }}%</p>
                <p class="text-gray-500 font-tajawal">خصومات: {{ $period->kpi_deductions }} نقطة</p>
            </div>
            <div class="grid grid-cols-2 gap-4 text-sm font-tajawal">
                @foreach([
                    ['التزام (حضور)', $period->adherence_score, $weights['adherence']],
                    ['إنجاز المهام', $period->task_completion_score, $weights['task_completion']],
                    ['جودة الكود/العمل', $period->quality_score, $weights['quality']],
                    ['تقييم Team Lead', $period->team_lead_rating ?? 0, $weights['team_lead']],
                ] as [$label, $score, $weight])
                <div class="p-4 rounded-xl bg-gray-50 border">
                    <p class="text-gray-600">{{ $label }} <span class="text-xs">({{ $weight }}%)</span></p>
                    <p class="text-2xl font-bold mt-1">{{ $score }}%</p>
                    <div class="h-2 bg-gray-200 rounded-full mt-2"><div class="h-full rounded-full" style="width:{{ min(100,$score) }}%;background:{{ $themeColor }};"></div></div>
                </div>
                @endforeach
            </div>
        </div>

        @can('edit-employees')
        <div class="bg-white rounded-2xl shadow-lg border p-6">
            <h3 class="font-bold mb-4 font-tajawal">تقييم Team Lead</h3>
            <form method="POST" action="{{ route('kpi.rate', $user) }}" class="space-y-3">
                @csrf
                <input type="hidden" name="year" value="{{ $year }}">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="number" name="team_lead_rating" min="0" max="100" step="1" required
                       value="{{ $period->team_lead_rating ?? '' }}" placeholder="0-100"
                       class="w-full border rounded-xl px-4 py-2.5">
                <textarea name="notes" rows="3" placeholder="ملاحظات..." class="w-full border rounded-xl px-3 py-2 text-sm">{{ $period->notes }}</textarea>
                <button class="w-full py-2.5 rounded-xl text-white font-bold" style="background:{{ $themeColor }};">حفظ التقييم</button>
            </form>
        </div>
        @endcan
    </div>

    @if($history->count())
    <div class="bg-white rounded-2xl shadow-lg border p-6">
        <h3 class="font-bold mb-4 font-tajawal">السجل التاريخي</h3>
        <ul class="space-y-2 text-sm font-tajawal">
            @foreach($history as $h)
            <li class="flex justify-between py-2 border-b">
                <span>{{ $h->period_label }}</span>
                <strong style="color:{{ $themeColor }};">{{ $h->total_score }}% — {{ $h->grade_label }}</strong>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection
