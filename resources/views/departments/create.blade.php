@extends('layouts.app')

@section('page-title', 'إضافة قسم جديد')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'إضافة قسم جديد',
        'subtitle' => 'تعريف قسم جديد مع صلاحيات السايدبار وملف الموظف الافتراضي',
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            كل الأقسام
        </a>
    </div>

    @if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-bold mb-1">يرجى تصحيح الأخطاء:</p>
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="max-w-4xl">
        @include('departments.partials.form', [
            'action' => route('departments.store'),
            'method' => 'POST',
            'submitLabel' => 'إنشاء القسم',
            'cancelUrl' => route('departments.index'),
            'department' => $department,
            'employees' => $employees,
            'parentDepartments' => $parentDepartments,
            'themeColor' => $themeColor,
        ])
    </div>
</div>
@endsection
