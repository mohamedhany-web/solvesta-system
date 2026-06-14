@extends('layouts.app')

@section('page-title', 'طلبات التوظيف')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'طلبات التوظيف',
        'subtitle' => 'الوظيفة: ' . $jobPosting->title,
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('website.careers.show', $jobPosting->slug) }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            رابط التقديم
        </a>
        <a href="{{ route('job-postings.show', $jobPosting) }}"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            تفاصيل الوظيفة
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
        @foreach([
            ['الإجمالي', $appStats['total'], $themeColor],
            ['جديدة', $appStats['pending'], '#3b82f6'],
            ['قيد المراجعة', $appStats['reviewing'], '#d97706'],
            ['قائمة مختصرة', $appStats['shortlisted'], '#7c3aed'],
            ['تم التوظيف', $appStats['hired'], '#059669'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm text-center">
            <p class="text-[10px] text-gray-500">{{ $label }}</p>
            <p class="text-2xl font-bold mt-0.5" style="color: {{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">قائمة المتقدمين <span class="text-gray-400 font-normal text-sm">({{ $applications->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المتقدم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التواصل</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">السيرة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التاريخ</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($applications as $a)
                    @php
                        $appBadge = match($a->status) {
                            'hired' => 'bg-emerald-100 text-emerald-800',
                            'shortlisted' => 'bg-violet-100 text-violet-800',
                            'reviewing' => 'bg-amber-100 text-amber-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            default => 'bg-blue-100 text-blue-800',
                        };
                    @endphp
                    <tr class="hover:bg-blue-50/40 transition-colors align-top">
                        <td class="px-4 py-3">
                            <div class="font-bold text-gray-900">{{ $a->full_name }}</div>
                            @if($a->message)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($a->message, 80) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600">
                            <div>{{ $a->email }}</div>
                            <div class="text-gray-400">{{ $a->phone ?? '—' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            @if($a->cv_path)
                                <a class="inline-flex items-center gap-1 text-xs font-bold hover:underline" style="color: {{ $themeColor }};"
                                   href="{{ asset('storage/'.$a->cv_path) }}" target="_blank" rel="noopener">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    تحميل CV
                                </a>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-full {{ $appBadge }}">{{ $a->statusLabelAr() }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ $a->created_at->format('Y/m/d') }}</td>
                        <td class="px-4 py-3">
                            @can('edit-jobs')
                            <form method="POST" action="{{ route('job-postings.applications.status', $a) }}" class="flex flex-wrap items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="border border-gray-300 rounded-lg px-2 py-1.5 text-xs min-w-[7rem]">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'جديد',
                                            'reviewing' => 'قيد المراجعة',
                                            'shortlisted' => 'قائمة مختصرة',
                                            'rejected' => 'مرفوض',
                                            'hired' => 'تم التوظيف',
                                        ];
                                    @endphp
                                    @foreach(\App\Models\JobApplication::STATUSES as $st)
                                        <option value="{{ $st }}" @selected($a->status === $st)>{{ $statusLabels[$st] ?? $st }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-gray-900 text-white text-xs font-bold hover:bg-black">حفظ</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @if($a->message)
                    <tr class="bg-gray-50/80">
                        <td colspan="6" class="px-4 py-3 text-xs text-gray-700 whitespace-pre-wrap border-b border-gray-100">
                            <span class="font-bold text-gray-500">رسالة المتقدم:</span> {{ $a->message }}
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا توجد طلبات بعد</p>
                            <a href="{{ route('website.careers.show', $jobPosting->slug) }}" target="_blank" rel="noopener"
                               class="font-semibold hover:underline" style="color: {{ $themeColor }};">رابط التقديم على الموقع</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $applications->links() }}</div>
        @endif
    </div>
</div>
@endsection
