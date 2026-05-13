@extends('layouts.app')

@section('page-title', $project->name)

@section('content')
@php
    $statusBadges = [
        'planning' => 'bg-yellow-100 text-yellow-800',
        'in_progress' => 'bg-blue-100 text-blue-800',
        'on_hold' => 'bg-orange-100 text-orange-800',
        'completed' => 'bg-green-100 text-green-800',
        'cancelled' => 'bg-red-100 text-red-800',
    ];
    $statusBadge = $statusBadges[$project->status] ?? 'bg-gray-100 text-gray-800';
    $priorityBadges = [
        'urgent' => 'bg-red-100 text-red-800',
        'high' => 'bg-orange-100 text-orange-800',
        'medium' => 'bg-yellow-100 text-yellow-800',
        'low' => 'bg-gray-100 text-gray-800',
    ];
    $priorityBadge = $priorityBadges[$project->priority] ?? 'bg-gray-100 text-gray-800';
    $priorityLabels = [
        'urgent' => 'عاجلة',
        'high' => 'عالية',
        'medium' => 'متوسطة',
        'low' => 'منخفضة',
    ];
    $priorityLabel = $priorityLabels[$project->priority] ?? $project->priority;
@endphp

<div class="w-full">
    {{-- رأس الصفحة (نفس أسلوب المهام / قائمة المشاريع) --}}
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                <a href="{{ route('projects.index') }}" class="shrink-0 p-2 rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-colors" title="العودة للمشاريع">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 truncate font-tajawal">{{ $project->name }}</h1>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $statusBadge }}">{{ $project->status_name }}</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $priorityBadge }}">{{ $priorityLabel }}</span>
                    </div>
                    <p class="text-sm text-gray-600 font-tajawal flex flex-wrap gap-x-4 gap-y-1">
                        <span class="inline-flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            {{ $project->client->name ?? 'بدون عميل' }}
                        </span>
                        @if($project->projectManager)
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $project->projectManager->name }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 shrink-0">
                @can('edit-projects')
                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-2.5 sm:px-5 sm:py-3 rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold shadow-sm">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        تعديل
                    </a>
                @endcan
                <a href="{{ route('projects.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:px-5 sm:py-3 rounded-lg border border-gray-300 bg-white text-gray-800 hover:bg-gray-50 text-sm font-semibold transition-colors">
                    كل المشاريع
                </a>
            </div>
        </div>
    </div>

    {{-- بطاقات إحصاءات (مثل index) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">التقدم</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['progress_percentage'] }}%</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl shrink-0">
                    <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
            </div>
            <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full transition-all" style="width: {{ min(100, max(0, (int) $stats['progress_percentage'])) }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">المهام</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total_tasks'] }}</p>
                    <p class="text-xs text-green-600 mt-1">{{ $stats['completed_tasks'] }} مكتملة</p>
                </div>
                <div class="p-3 bg-green-50 rounded-xl shrink-0">
                    <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الفريق</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['team_members_count'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">أعضاء مرتبطون</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl shrink-0">
                    <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
            </div>
        </div>
        @can('view-finance')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">الميزانية</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $project->budget ? number_format($project->budget) : '—' }}</p>
                    <p class="text-xs text-gray-500 mt-1">ج.م</p>
                </div>
                <div class="p-3 bg-orange-50 rounded-xl shrink-0">
                    <svg class="w-7 h-7 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" /></svg>
                </div>
            </div>
        </div>
        @endcan
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50/80">
                    <h2 class="text-lg font-bold text-gray-900 font-tajawal flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        تفاصيل المشروع
                    </h2>
                </div>
                <div class="p-5 sm:p-6 space-y-6">
                    @if($project->description)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">الوصف</h3>
                            <p class="text-gray-800 leading-relaxed whitespace-pre-wrap bg-gray-50 rounded-lg p-4 border border-gray-100">{{ $project->description }}</p>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-3 py-2.5 px-3 rounded-lg bg-gray-50 border border-gray-100">
                                <span class="text-sm text-gray-600">العميل</span>
                                <span class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $project->client->name ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 py-2.5 px-3 rounded-lg bg-gray-50 border border-gray-100">
                                <span class="text-sm text-gray-600">مدير المشروع</span>
                                <span class="text-sm font-semibold text-gray-900 truncate mr-2">{{ $project->projectManager->name ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between gap-3 py-2.5 px-3 rounded-lg bg-gray-50 border border-gray-100">
                                <span class="text-sm text-gray-600">تاريخ البداية</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $project->start_date ? $project->start_date->format('Y/m/d') : '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 py-2.5 px-3 rounded-lg bg-gray-50 border border-gray-100">
                                <span class="text-sm text-gray-600">تاريخ الانتهاء</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $project->end_date ? $project->end_date->format('Y/m/d') : '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3 py-2.5 px-3 rounded-lg bg-gray-50 border border-gray-100">
                                <span class="text-sm text-gray-600">تاريخ الإنشاء</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $project->created_at->format('Y/m/d') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <h2 class="text-lg font-bold text-gray-900 font-tajawal flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        مهام المشروع
                    </h2>
                    @can('create-tasks')
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-semibold shadow-sm shrink-0">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            إضافة مهمة
                        </a>
                    @endcan
                </div>
                <div class="p-5 sm:p-6">
                    @if($project->tasks && $project->tasks->count() > 0)
                        <div class="grid grid-cols-3 gap-3 mb-6">
                            <div class="text-center p-3 rounded-lg bg-green-50 border border-green-100">
                                <div class="text-xl font-bold text-green-700">{{ $stats['completed_tasks'] }}</div>
                                <div class="text-xs text-green-800 font-medium">مكتملة</div>
                            </div>
                            <div class="text-center p-3 rounded-lg bg-blue-50 border border-blue-100">
                                <div class="text-xl font-bold text-blue-700">{{ $stats['in_progress_tasks'] }}</div>
                                <div class="text-xs text-blue-800 font-medium">قيد التنفيذ</div>
                            </div>
                            <div class="text-center p-3 rounded-lg bg-yellow-50 border border-yellow-100">
                                <div class="text-xl font-bold text-yellow-800">{{ $stats['pending_tasks'] }}</div>
                                <div class="text-xs text-yellow-900 font-medium">معلقة</div>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-100 border border-gray-100 rounded-lg overflow-hidden">
                            @foreach($project->tasks->take(8) as $task)
                                @php
                                    $taskBadge = match ($task->status) {
                                        'completed' => ['bg-green-100 text-green-800', 'مكتملة'],
                                        'in_progress' => ['bg-blue-100 text-blue-800', 'قيد التنفيذ'],
                                        'review' => ['bg-amber-100 text-amber-900', 'مراجعة'],
                                        'cancelled' => ['bg-red-100 text-red-800', 'ملغاة'],
                                        default => ['bg-gray-100 text-gray-800', 'قيد الانتظار'],
                                    };
                                @endphp
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 hover:bg-gray-50/80 transition-colors">
                                    <div class="min-w-0">
                                        <h4 class="font-semibold text-gray-900 truncate">{{ $task->title }}</h4>
                                        @if($task->description)
                                            <p class="text-sm text-gray-600 mt-0.5 line-clamp-2">{{ Str::limit($task->description, 100) }}</p>
                                        @endif
                                        @if($task->due_date)
                                            <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($task->due_date)->format('Y/m/d') }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold {{ $taskBadge[0] }}">{{ $taskBadge[1] }}</span>
                                        <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">عرض</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($project->tasks->count() > 8)
                            <div class="text-center mt-4">
                                <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">عرض كل المهام ({{ $project->tasks->count() }})</a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-10 px-4">
                            <p class="text-gray-600 mb-4">لا توجد مهام لهذا المشروع بعد.</p>
                            @can('create-tasks')
                                <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 text-sm font-semibold shadow-sm">إضافة مهمة</a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
                <h3 class="text-sm font-bold text-gray-900 mb-4 font-tajawal">حالة الإنجاز</h3>
                <div class="text-center mb-4">
                    <div class="text-3xl font-bold text-gray-900">{{ $stats['progress_percentage'] }}%</div>
                    <p class="text-xs text-gray-500 mt-1">نسبة التقدم</p>
                </div>
                <div class="w-full h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ min(100, max(0, (int) $stats['progress_percentage'])) }}%"></div>
                </div>
            </div>

            @if($project->teamMembers && $project->teamMembers->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3 border-b border-gray-200 bg-gray-50/80">
                        <h3 class="text-sm font-bold text-gray-900 font-tajawal">فريق العمل</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($project->teamMembers->take(8) as $member)
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-sm font-bold shrink-0">{{ mb_substr($member->name, 0, 1) }}</div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $member->roles->first()?->name ?? 'موظف' }}</p>
                                </div>
                            </div>
                        @endforeach
                        @if($project->teamMembers->count() > 8)
                            <p class="text-xs text-center text-gray-500 pt-1">و {{ $project->teamMembers->count() - 8 }} آخرين</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50/80">
                    <h3 class="text-sm font-bold text-gray-900 font-tajawal">إجراءات سريعة</h3>
                </div>
                <div class="p-4 space-y-2">
                    @can('edit-projects')
                        <a href="{{ route('projects.edit', $project) }}" class="flex items-center justify-center w-full px-4 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">تعديل المشروع</a>
                    @endcan
                    @can('create-tasks')
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" class="flex items-center justify-center w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-800 text-sm font-semibold hover:bg-gray-50">إضافة مهمة</a>
                    @endcan
                    <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" class="flex items-center justify-center w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white text-gray-800 text-sm font-semibold hover:bg-gray-50">عرض المهام</a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-200 bg-gray-50/80">
                    <h3 class="text-sm font-bold text-gray-900 font-tajawal">الجدول الزمني</h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div class="flex gap-3">
                        <span class="w-2 h-2 mt-1.5 rounded-full bg-green-500 shrink-0"></span>
                        <div>
                            <p class="font-semibold text-gray-900">إنشاء المشروع</p>
                            <p class="text-gray-500 text-xs">{{ $project->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>
                    @if($project->start_date)
                        <div class="flex gap-3">
                            <span class="w-2 h-2 mt-1.5 rounded-full bg-blue-500 shrink-0"></span>
                            <div>
                                <p class="font-semibold text-gray-900">البداية</p>
                                <p class="text-gray-500 text-xs">{{ $project->start_date->format('Y/m/d') }}</p>
                            </div>
                        </div>
                    @endif
                    @if($project->end_date)
                        <div class="flex gap-3">
                            <span class="w-2 h-2 mt-1.5 rounded-full bg-purple-500 shrink-0"></span>
                            <div>
                                <p class="font-semibold text-gray-900">الانتهاء المتوقع</p>
                                <p class="text-gray-500 text-xs">{{ $project->end_date->format('Y/m/d') }}</p>
                                @if($project->end_date->isFuture())
                                    <p class="text-xs text-amber-700 mt-0.5">{{ $project->end_date->diffForHumans() }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
