@extends('layouts.app')

@section('page-title', $notification->title)

@section('content')
@php
    $actionUrl = $notification->actionUrl();
    $data = $notification->data ?? [];
    $gradients = [
        'task' => 'from-green-500 to-emerald-600',
        'project' => 'from-blue-500 to-indigo-600',
        'message' => 'from-purple-500 to-pink-600',
        'official_mail' => 'from-sky-500 to-blue-600',
        'meeting' => 'from-amber-500 to-orange-600',
        'training' => 'from-teal-500 to-cyan-600',
        'critical' => 'from-red-500 to-rose-600',
        'action_required' => 'from-orange-500 to-amber-600',
    ];
    $gradient = $gradients[$notification->type] ?? 'from-gray-500 to-gray-600';
@endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-gradient-to-br {{ $gradient }} rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/80 text-gray-700 border border-gray-200">
                                {{ $notification->typeLabel() }}
                            </span>
                            @if($notification->is_read)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">مقروء</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">جديد</span>
                            @endif
                        </div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 break-words">{{ $notification->title }}</h1>
                        <p class="text-gray-600 text-sm sm:text-base mt-1">
                            {{ $notification->created_at->format('Y/m/d H:i') }} · {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <a href="{{ route('notifications.index') }}"
                   class="w-full sm:w-auto bg-gray-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة للإشعارات
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">محتوى الإشعار</h2>
                </div>
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="prose prose-sm max-w-none text-gray-800 leading-relaxed whitespace-pre-wrap text-base">
                        {{ $notification->message }}
                    </div>
                </div>
            </div>

            @if(!empty($data))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">بيانات إضافية</h2>
                </div>
                <div class="p-4 sm:p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($data as $key => $value)
                            @if(is_scalar($value) || $value === null)
                                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                    <dt class="text-xs font-semibold text-gray-500 mb-1">{{ $key }}</dt>
                                    <dd class="text-sm text-gray-900 break-all">
                                        @if(is_bool($value))
                                            {{ $value ? 'نعم' : 'لا' }}
                                        @elseif(filter_var((string) $value, FILTER_VALIDATE_URL))
                                            <a href="{{ $value }}" class="text-blue-600 hover:underline" target="_blank" rel="noopener">{{ $value }}</a>
                                        @else
                                            {{ $value }}
                                        @endif
                                    </dd>
                                </div>
                            @endif
                        @endforeach
                    </dl>
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4">إجراءات</h3>
                <div class="space-y-3">
                    @if($actionUrl)
                        <a href="{{ $actionUrl }}"
                           class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition-colors shadow-sm">
                            {{ $notification->actionLabel() }}
                        </a>
                    @endif

                    <a href="{{ route('notifications.index') }}"
                       class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition-colors">
                        قائمة الإشعارات
                    </a>

                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl border border-red-200 text-red-700 text-sm font-semibold hover:bg-red-50 transition-colors">
                            حذف الإشعار
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-3">معلومات</h3>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex justify-between gap-3">
                        <span class="text-gray-500">النوع</span>
                        <span class="font-semibold text-gray-900">{{ $notification->typeLabel() }}</span>
                    </li>
                    <li class="flex justify-between gap-3">
                        <span class="text-gray-500">الحالة</span>
                        <span class="font-semibold text-gray-900">{{ $notification->is_read ? 'مقروء' : 'غير مقروء' }}</span>
                    </li>
                    @if($notification->read_at)
                    <li class="flex justify-between gap-3">
                        <span class="text-gray-500">قُرئ في</span>
                        <span class="font-semibold text-gray-900">{{ $notification->read_at->format('Y/m/d H:i') }}</span>
                    </li>
                    @endif
                    <li class="flex justify-between gap-3">
                        <span class="text-gray-500">تاريخ الإنشاء</span>
                        <span class="font-semibold text-gray-900">{{ $notification->created_at->format('Y/m/d H:i') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
