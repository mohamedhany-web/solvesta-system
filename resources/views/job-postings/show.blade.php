@extends('layouts.app')

@section('page-title', $jobPosting->title)

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $statusBadge = match($jobPosting->status) {
        'published' => 'bg-emerald-100 text-emerald-800',
        'closed' => 'bg-gray-100 text-gray-600',
        default => 'bg-amber-100 text-amber-800',
    };
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => $jobPosting->title,
        'subtitle' => $jobPosting->department?->name ? $jobPosting->department->name . ' · ' . $jobPosting->employmentTypeLabel() : $jobPosting->employmentTypeLabel(),
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('job-postings.applications', $jobPosting) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, #111827 0%, #374151 100%);">
            الطلبات ({{ $jobPosting->applications_count }})
        </a>
        @can('edit-jobs')
        <a href="{{ route('job-postings.edit', $jobPosting) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
            تعديل
        </a>
        @endcan
        <a href="{{ route('website.careers.show', $jobPosting->slug) }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            معاينة الموقع
        </a>
        <a href="{{ route('job-postings.index') }}" class="border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">كل الوظائف</a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            @if($jobPosting->summary)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">الملخص</h2>
                </div>
                <div class="p-6 text-gray-800 leading-relaxed">{{ $jobPosting->summary }}</div>
            </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">وصف الوظيفة</h2>
                </div>
                <div class="p-6 text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $jobPosting->description }}</div>
            </div>

            @if($jobPosting->requirements)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">المتطلبات</h2>
                </div>
                <div class="p-6 text-gray-800 whitespace-pre-wrap leading-relaxed">{{ $jobPosting->requirements }}</div>
            </div>
            @endif
        </div>

        <div class="xl:col-span-4 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">التفاصيل</h2>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $statusBadge }}">{{ $jobPosting->statusLabelAr() }}</span>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">{{ $jobPosting->employmentTypeLabel() }}</span>
                        @if($jobPosting->is_featured)
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-orange-100 text-orange-800">مميزة</span>
                        @endif
                    </div>
                    <dl class="grid grid-cols-1 gap-3 text-sm">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">الموقع</dt>
                            <dd class="font-semibold text-gray-900">{{ $jobPosting->location ?? '—' }}</dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">القسم</dt>
                            <dd class="font-semibold text-gray-900">{{ $jobPosting->department?->name ?? '—' }}</dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">ترتيب العرض</dt>
                            <dd class="font-semibold text-gray-900">{{ $jobPosting->sort_order }}</dd>
                        </div>
                        @if($jobPosting->published_at)
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">تاريخ النشر</dt>
                            <dd>{{ $jobPosting->published_at->format('Y/m/d H:i') }}</dd>
                        </div>
                        @endif
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">أنشأها</dt>
                            <dd>{{ $jobPosting->creator?->name ?? '—' }}</dd>
                        </div>
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                            <dt class="text-xs font-bold text-gray-500 mb-1">رابط الموقع</dt>
                            <dd class="text-xs break-all" dir="ltr">
                                <a href="{{ route('website.careers.show', $jobPosting->slug) }}" target="_blank" rel="noopener" class="hover:underline" style="color: {{ $themeColor }};">
                                    {{ route('website.careers.show', $jobPosting->slug) }}
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            @can('delete-jobs')
            <div class="bg-white border border-red-200 rounded-2xl shadow-sm p-5">
                <h3 class="font-bold text-sm text-red-800 mb-2">منطقة الخطر</h3>
                <p class="text-xs text-gray-600 mb-4">حذف الوظيفة يزيل جميع طلبات التقديم المرتبطة بها.</p>
                <form method="POST" action="{{ route('job-postings.destroy', $jobPosting) }}" onsubmit="return confirm('حذف هذه الوظيفة وجميع طلباتها؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-2.5 rounded-xl border border-red-300 text-red-700 text-sm font-bold hover:bg-red-50">حذف الوظيفة</button>
                </form>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
