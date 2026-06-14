@extends('layouts.app')

@section('page-title', 'تعديل وظيفة')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'تعديل: ' . $jobPosting->title,
        'subtitle' => 'تحديث بيانات الوظيفة وإعدادات النشر',
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('job-postings.applications', $jobPosting) }}"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            الطلبات ({{ $jobPosting->applications()->count() }})
        </a>
        <a href="{{ route('job-postings.show', $jobPosting) }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            عرض الوظيفة
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
            @include('job-postings.partials.form', [
                'action' => route('job-postings.update', $jobPosting),
                'method' => 'PUT',
                'submitLabel' => 'تحديث الوظيفة',
                'cancelUrl' => route('job-postings.show', $jobPosting),
                'jobPosting' => $jobPosting,
                'departments' => $departments,
                'employmentTypes' => $employmentTypes,
                'statuses' => $statuses,
                'themeColor' => $themeColor,
            ])
        </div>
        <div class="xl:col-span-4">
            @include('job-postings.partials.form-sidebar', ['jobPosting' => $jobPosting, 'themeColor' => $themeColor])
        </div>
    </div>
</div>
@endsection
