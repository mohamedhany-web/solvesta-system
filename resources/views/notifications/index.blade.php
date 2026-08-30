@extends('layouts.app')

@section('page-title', 'الإشعارات')

@section('content')
@php
    $typeMeta = [
        'task' => ['from-green-500 to-emerald-600', 'مهمة'],
        'project' => ['from-blue-500 to-indigo-600', 'مشروع'],
        'message' => ['from-purple-500 to-pink-600', 'رسالة'],
        'official_mail' => ['from-sky-500 to-blue-600', 'بريد'],
        'meeting' => ['from-amber-500 to-orange-600', 'اجتماع'],
        'training' => ['from-teal-500 to-cyan-600', 'تدريب'],
        'critical' => ['from-red-500 to-rose-600', 'حرج'],
        'action_required' => ['from-orange-500 to-amber-600', 'مطلوب'],
    ];
@endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1">الإشعارات</h1>
                        <p class="text-gray-600 text-sm sm:text-base">
                            {{ $unreadCount }} غير مقروء من أصل {{ $totalCount }}
                        </p>
                    </div>
                </div>
                @if($unreadCount > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-emerald-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base font-semibold">
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        تحديد الكل كمقروء
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 mb-6">
        <div class="flex flex-col lg:flex-row gap-4 lg:items-center">
            <div class="flex flex-wrap gap-2">
                @php
                    $tabs = [
                        'all' => ['الكل', $totalCount, 'bg-blue-100 text-blue-700'],
                        'unread' => ['غير مقروءة', $unreadCount, 'bg-red-100 text-red-700'],
                        'today' => ['اليوم', $todayCount, 'bg-purple-100 text-purple-700'],
                        'message' => ['الرسائل', $messageCount, 'bg-indigo-100 text-indigo-700'],
                        'official_mail' => ['البريد', $mailCount, 'bg-sky-100 text-sky-700'],
                        'task' => ['المهام', $taskCount, 'bg-green-100 text-green-700'],
                        'project' => ['المشاريع', $projectCount, 'bg-blue-100 text-blue-700'],
                    ];
                @endphp
                @foreach($tabs as $key => [$label, $count, $activeClass])
                    <a href="{{ route('notifications.index', array_filter(['filter' => $key === 'all' ? null : $key, 'search' => $search])) }}"
                       class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors {{ $filter === $key || ($key === 'all' && $filter === 'all') ? $activeClass : 'text-gray-600 hover:bg-gray-100' }}">
                        {{ $label }} ({{ $count }})
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('notifications.index') }}" class="flex-1 lg:max-w-md">
                @if($filter !== 'all')
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}"
                           placeholder="البحث في الإشعارات..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    @php
                        $gradient = $typeMeta[$notification->type][0] ?? 'from-gray-500 to-gray-600';
                    @endphp
                    <div class="group {{ !$notification->is_read ? 'bg-blue-50/70' : 'bg-white' }} hover:bg-gray-50 transition-colors">
                        <div class="flex items-stretch gap-2 sm:gap-3 {{ !$notification->is_read ? 'border-r-4 border-r-blue-500' : 'border-r-4 border-r-transparent' }}">
                            <a href="{{ route('notifications.show', $notification) }}"
                               class="flex-1 min-w-0 flex items-start gap-3 sm:gap-4 p-4 sm:p-5">
                                <div class="flex-shrink-0 relative">
                                    <div class="h-11 w-11 sm:h-12 sm:w-12 bg-gradient-to-br {{ $gradient }} rounded-xl flex items-center justify-center shadow-md">
                                        @if($notification->type === 'task')
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                        @elseif($notification->type === 'message' || $notification->type === 'official_mail')
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        @elseif($notification->type === 'project')
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        @else
                                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                        @endif
                                    </div>
                                    @if(!$notification->is_read)
                                        <span class="absolute -top-1 -right-1 h-3.5 w-3.5 bg-red-500 rounded-full border-2 border-white"></span>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <h3 class="text-base sm:text-lg font-bold text-gray-900">{{ $notification->title }}</h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700">
                                            {{ $notification->typeLabel() }}
                                        </span>
                                        @if(!$notification->is_read)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-100 text-blue-800">جديد</span>
                                        @endif
                                    </div>
                                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed line-clamp-3">{{ $notification->message }}</p>
                                    <p class="text-xs sm:text-sm text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }} · {{ $notification->created_at->format('Y/m/d H:i') }}</p>
                                </div>

                                <div class="hidden sm:flex items-center gap-1.5 flex-shrink-0 self-center text-blue-600">
                                    <span class="text-sm font-semibold whitespace-nowrap group-hover:underline">عرض التفاصيل</span>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </div>
                            </a>

                            <div class="flex items-start pt-4 sm:pt-5 pl-3 sm:pl-4 flex-shrink-0">
                                <form method="POST" action="{{ route('notifications.destroy', $notification) }}"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                            title="حذف">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $notifications->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-16 px-4">
                <div class="mx-auto h-14 w-14 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="h-7 w-7 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">لا توجد إشعارات</h3>
                <p class="mt-1 text-sm text-gray-500">سيتم إشعارك بأي تحديثات جديدة هنا</p>
            </div>
        @endif
    </div>
</div>
@endsection
