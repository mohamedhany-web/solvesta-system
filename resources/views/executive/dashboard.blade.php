@extends('layouts.app')

@section('page-title', 'Control Center')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp

<div class="w-full max-w-full px-2 sm:px-0 font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'Solvesta Control Center',
        'subtitle' => 'إدارة بالاستثناء — الأحمر والأصفر فقط، والتفاصيل داخل كل كيان',
        'icon' => 'chart',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('executive.finance') }}" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">التقارير المالية →</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-3 mb-8">
        @foreach([
            ['إيراد الشهر', number_format($health['revenue']).' ج.م', ''],
            ['Pipeline', number_format($health['pipeline']).' ج.م', ''],
            ['مشاريع نشطة', $health['active_projects'], ''],
            ['متأخرة', $health['overdue_projects'], $health['overdue_projects'] ? '#dc2626' : '#059669'],
            ['حرجة', $health['open_critical'], $health['open_critical'] ? '#dc2626' : ''],
            ['تحذير', $health['open_warning'], $health['open_warning'] ? '#d97706' : ''],
            ['SLA', $health['sla_compliance'].'%', $health['sla_compliance'] < 90 ? '#dc2626' : '#059669'],
            ['Leads جديدة', $health['leads_new'], ''],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 text-center">
            <p class="text-gray-500 text-xs mb-1">{{ $label }}</p>
            <p class="text-xl font-bold" @if($color) style="color: {{ $color }};" @endif>{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <section class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-lg text-gray-900">ما يحتاج تدخلك</h2>
                <p class="text-sm text-gray-500">{{ $exceptions->count() }} استثناء مفتوح — الباقي على المسار</p>
            </div>
        </div>

        @forelse($exceptions as $exception)
            @php
                $url = app(\App\Services\CompanyOs\ExceptionService::class)->subjectUrl($exception->subject, $exception->subject_type, $exception->subject_id);
                $tone = $exception->severity === 'critical'
                    ? 'border-red-200 bg-red-50/70'
                    : 'border-amber-200 bg-amber-50/60';
                $dot = $exception->severity === 'critical' ? 'bg-red-600' : 'bg-amber-500';
            @endphp
            <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 {{ $tone }}">
                <div class="flex items-start gap-3 min-w-0">
                    <span class="mt-1.5 h-2.5 w-2.5 rounded-full {{ $dot }} shrink-0"></span>
                    <div class="min-w-0">
                        <p class="font-bold text-gray-900">{{ $exception->title }}</p>
                        @if($exception->message)
                            <p class="text-sm text-gray-600 mt-0.5">{{ $exception->message }}</p>
                        @endif
                        <p class="text-xs text-gray-500 mt-1">
                            المسؤول: {{ $exception->owner?->name ?? 'غير معيّن' }}
                            · {{ $exception->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @if($url)
                    <a href="{{ $url }}" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-800 hover:bg-gray-50">فتح</a>
                @endif
            </div>
        @empty
            <div class="px-6 py-12 text-center text-gray-500">
                لا توجد استثناءات مفتوحة. الشركة على المسار.
            </div>
        @endforelse
    </section>
</div>
@endsection
