@extends('layouts.app')

@section('page-title', 'إضافة مشروع جديد')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'إضافة مشروع جديد',
        'subtitle' => 'أنشئ مشروعاً واربطه بالعميل ومدير المشروع وفريق التنفيذ',
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            كل المشاريع
        </a>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-bold mb-1">يرجى تصحيح الأخطاء التالية:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            @include('projects.partials.form', [
                'action' => route('projects.store'),
                'method' => 'POST',
                'submitLabel' => 'إنشاء المشروع',
                'cancelUrl' => route('projects.index'),
                'clients' => $clients,
                'departments' => $departments,
                'themeColor' => $themeColor,
            ])
        </div>
        <div class="xl:col-span-4">
            @include('projects.partials.form-sidebar')
        </div>
    </div>
</div>
@endsection
