@extends('layouts.app')

@section('page-title', 'تفاصيل الاختبار')

@section('content')
<div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center ml-4">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $qa->test_number }}</h1>
                <p class="text-purple-100">{{ $qa->name }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 space-x-reverse">
            <a href="{{ route('qa.edit', $qa) }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200">
                تعديل
            </a>
            <a href="{{ route('qa.index') }}" class="bg-white text-purple-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                العودة
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل الاختبار</h2>
        <div class="space-y-4">
            @if($qa->description)
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">الوصف</h3>
                <p class="text-sm text-gray-900">{{ $qa->description }}</p>
            </div>
            @endif
            <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">المشروع</span>
                    <span class="text-sm text-gray-900">{{ $qa->project->name ?? 'عام' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">النوع</span>
                    <span class="text-sm text-gray-900">{{ $qa->type_name }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">الحالة</span>
                    <span class="text-sm text-gray-900">{{ $qa->status_name }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">الأولوية</span>
                    <span class="text-sm text-gray-900">{{ $qa->priority_name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

