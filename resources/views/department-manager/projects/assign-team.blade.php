@extends('layouts.app')

@section('page-title', 'فريق المشروع')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $readOnly = $readOnly ?? true;
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'فريق المشروع: '.$project->name,
        'subtitle' => 'عرض فقط — التعيين من صفحة القسم بواسطة الإدارة',
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="{{ route('department-manager.dashboard') }}" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            لوحة القسم
        </a>
        <a href="{{ route('department-manager.tasks.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }}">
            توزيع مهمة
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">تفاصيل الفريق</h2>
                    <p class="text-sm text-gray-500 mt-0.5">قسم: {{ $project->department?->name }}</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 mb-1">قائد الفريق (Team Leader)</p>
                        <p class="font-bold text-gray-900">{{ $project->projectManager?->name ?? 'لم يُعيَّن بعد — تواصل مع الإدارة' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-500 mb-1">أعضاء الفريق</p>
                        @if($project->teamMembers->isNotEmpty())
                        <ul class="space-y-1">
                            @foreach($project->teamMembers as $member)
                            <li class="text-gray-800">• {{ $member->name }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-gray-500">لا يوجد أعضاء مُعيَّنون بعد.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-4">
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm text-blue-900">
                <h3 class="font-bold mb-2">مسار العمل</h3>
                <ol class="space-y-2 list-decimal list-inside">
                    <li>الإدارة تُعيّن الفريق من صفحة القسم</li>
                    <li>أنت كرئيس قسم تستلم المشروع جاهزاً</li>
                    <li>توزّع المهام من «إنشاء مهمة + إسناد»</li>
                    <li>تراجع التقارير اليومية للفريق</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
