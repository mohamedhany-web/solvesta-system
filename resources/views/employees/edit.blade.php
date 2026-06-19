@extends('layouts.app')

@section('page-title', 'تعديل الموظف')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $fullName = trim($employee->first_name.' '.$employee->last_name);
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'تعديل الموظف',
        'subtitle' => $fullName.' · '.($employee->employee_id ?? ''),
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            عرض الملف
        </a>
        <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            كل الموظفين
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-bold mb-1">يرجى تصحيح الأخطاء:</p>
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            @include('employees.partials.edit-form', [
                'action' => route('employees.update', $employee),
                'method' => 'PUT',
                'submitLabel' => 'حفظ التغييرات',
                'cancelUrl' => route('employees.show', $employee),
                'employee' => $employee,
                'departments' => $departments,
                'supervisorOptions' => $supervisorOptions ?? collect(),
                'roleLabels' => $roleLabels ?? [],
                'assignableRoles' => $assignableRoles ?? [],
                'currentRole' => $currentRole ?? 'employee',
                'deptProfile' => $deptProfile ?? [],
                'themeColor' => $themeColor,
            ])
        </div>
        <div class="xl:col-span-4">
            <div class="space-y-4">
                <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm text-center">
                    <div class="h-16 w-16 mx-auto rounded-2xl flex items-center justify-center text-white text-2xl font-bold mb-3" style="background: {{ $themeColor }};">
                        {{ mb_substr($employee->first_name, 0, 1) }}
                    </div>
                    <p class="font-bold text-gray-900">{{ $fullName }}</p>
                    <p class="text-xs text-gray-500 mt-1 font-mono" dir="ltr">{{ $employee->employee_id }}</p>
                </div>
                <div class="rounded-2xl border border-amber-100 bg-amber-50/60 p-5 text-sm text-amber-900">
                    <p class="font-bold mb-2">تنبيه</p>
                    <p class="leading-relaxed">تعديل البريد يحدّث أيضاً حساب المستخدم المرتبط إن وُجد.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
