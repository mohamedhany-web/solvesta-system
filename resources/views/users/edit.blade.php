@extends('layouts.app')

@section('page-title', 'تعديل المستخدم')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'تعديل المستخدم',
        'subtitle' => $user->name . ' — ' . $user->email,
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('users.show', $user) }}" class="border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">عرض الملف</a>
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            كل المستخدمين
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
            @include('users.partials.form', [
                'user' => $user,
                'action' => route('users.update', $user),
                'method' => 'PUT',
                'submitLabel' => 'حفظ التغييرات',
                'cancelUrl' => route('users.show', $user),
                'roles' => $roles,
                'departments' => $departments,
                'themeColor' => $themeColor,
            ])
        </div>
        <div class="xl:col-span-4">
            @include('users.partials.form-sidebar', ['user' => $user])
        </div>
    </div>
</div>
@endsection
