@extends('layouts.app')

@section('page-title', 'تقرير يومي')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $isUpdate = (bool) ($existing ?? null);
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => $isUpdate ? 'تحديث التقرير اليومي' : 'تقرير يومي جديد',
        'subtitle' => now()->locale('ar')->translatedFormat('l، d F Y').' — سجّل إنجازاتك وساعات عملك',
        'icon' => 'doc',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="{{ route('daily-reports.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            كل التقارير
        </a>
        <a href="{{ route('workspace.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            مساحة العمل
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-bold mb-1">يرجى تصحيح الأخطاء:</p>
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            @include('daily-reports.partials.form', [
                'action' => route('daily-reports.store'),
                'method' => 'POST',
                'submitLabel' => $isUpdate ? 'تحديث التقرير' : 'حفظ التقرير',
                'cancelUrl' => route('daily-reports.index'),
                'projects' => $projects,
                'tasks' => $tasks,
                'existing' => $existing,
                'themeColor' => $themeColor,
            ])
        </div>
        <div class="xl:col-span-4">
            @include('daily-reports.partials.form-sidebar', [
                'stats' => $stats,
                'themeColor' => $themeColor,
            ])
        </div>
    </div>
</div>
@endsection
