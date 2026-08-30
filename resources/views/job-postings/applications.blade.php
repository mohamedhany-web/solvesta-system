@extends('layouts.app')

@section('page-title', 'طلبات التوظيف — ATS')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 border border-blue-100">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">ATS — طلبات التوظيف</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $jobPosting->title }} · متوسط التقييم {{ $appStats['avg_score'] ?: '—' }}%</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('website.careers.show', $jobPosting->slug) }}" target="_blank" rel="noopener"
                       class="px-4 py-2.5 rounded-xl border bg-white text-sm font-bold text-gray-700 hover:bg-gray-50">رابط التقديم</a>
                    <a href="{{ route('job-postings.show', $jobPosting) }}"
                       class="px-4 py-2.5 rounded-xl bg-gray-600 text-white text-sm font-bold hover:bg-gray-700">تفاصيل الوظيفة</a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @foreach([
            ['الإجمالي', $appStats['total'], $themeColor],
            ['جديدة', $appStats['pending'], '#3b82f6'],
            ['مراجعة', $appStats['reviewing'], '#d97706'],
            ['مختصرة', $appStats['shortlisted'], '#7c3aed'],
            ['توظيف', $appStats['hired'], '#059669'],
            ['متوسط ATS', ($appStats['avg_score'] ?: 0).'%', '#0f766e'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm text-center">
            <p class="text-[10px] text-gray-500">{{ $label }}</p>
            <p class="text-2xl font-bold mt-0.5" style="color: {{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <form method="GET" class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 mb-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-3 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">بحث</label>
                <input name="search" value="{{ request('search') }}" class="w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="اسم / بريد / هاتف">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">الحالة</label>
                <select name="status" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    @foreach(\App\Models\JobApplication::STATUSES as $st)
                        <option value="{{ $st }}" @selected(request('status')===$st)>{{ (new \App\Models\JobApplication(['status'=>$st]))->statusLabelAr() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">مرحلة ATS</label>
                <select name="stage" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                    <option value="">الكل</option>
                    @foreach(config('ats.stages') as $code => $label)
                        <option value="{{ $code }}" @selected(request('stage')===$code)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">حد أدنى للدرجة</label>
                <input type="number" min="0" max="100" name="min_score" value="{{ request('min_score') }}" class="w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="مثلاً 70">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-1">مهارة</label>
                <input name="skill" value="{{ request('skill') }}" class="w-full border rounded-xl px-3 py-2.5 text-sm" placeholder="laravel / react">
            </div>
            <div class="flex gap-2">
                <button class="flex-1 px-4 py-2.5 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }};">تصفية</button>
                <a href="{{ route('job-postings.applications', $jobPosting) }}" class="px-4 py-2.5 rounded-xl border text-sm font-semibold">مسح</a>
            </div>
        </div>
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[900px]">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المتقدم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">مهارات مستخرجة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">ATS</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المرحلة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($applications as $a)
                        @php
                            $scoreColor = match(true) {
                                ($a->ats_score ?? 0) >= 85 => '#059669',
                                ($a->ats_score ?? 0) >= 70 => '#2563eb',
                                ($a->ats_score ?? 0) >= 55 => '#d97706',
                                default => '#dc2626',
                            };
                        @endphp
                        <tr class="hover:bg-blue-50/40">
                            <td class="px-4 py-3">
                                <a href="{{ route('job-postings.applications.show', [$jobPosting, $a]) }}" class="font-bold text-gray-900 hover:underline">{{ $a->full_name }}</a>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $a->email }}</div>
                                @if($a->years_experience)
                                    <div class="text-[11px] text-gray-400 mt-0.5">خبرة ≈ {{ $a->years_experience }} سنة</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @forelse(array_slice($a->extracted_skills ?? [], 0, 6) as $skill)
                                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[10px] font-semibold">{{ $skill }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @if($a->ats_score !== null)
                                    <div class="font-bold text-lg" style="color:{{ $scoreColor }};">{{ $a->ats_score }}%</div>
                                    <div class="text-[11px] text-gray-500">Grade {{ $a->ats_grade }}</div>
                                @else
                                    <span class="text-xs text-amber-600">قيد التحليل</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs font-semibold">{{ $a->stageLabelAr() }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-bold px-2 py-1 rounded-full bg-gray-100 text-gray-700">{{ $a->statusLabelAr() }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('job-postings.applications.show', [$jobPosting, $a]) }}"
                                   class="inline-flex px-3 py-1.5 rounded-lg text-xs font-bold text-white"
                                   style="background:{{ $themeColor }};">عرض ATS</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-14 text-center text-gray-500">لا توجد طلبات مطابقة للفلاتر</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
            <div class="px-4 py-3 border-t">{{ $applications->links() }}</div>
        @endif
    </div>
</div>
@endsection
