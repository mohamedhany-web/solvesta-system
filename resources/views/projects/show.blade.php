@extends('layouts.app')

@section('page-title', 'تفاصيل المشروع')

@section('content')
<!-- Enhanced Header Section -->
<div class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl mb-8 shadow-2xl">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-black/10"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-32 translate-x-32"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24"></div>
    
    <div class="relative p-8 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-start gap-6">
                <!-- Project Icon -->
                <div class="h-20 w-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-xl">
                    <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
</div>

                <!-- Project Info -->
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <h1 class="text-3xl font-bold">{{ $project->name }}</h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($project->status == 'active') bg-green-500/20 text-green-100 border border-green-400/30
                            @elseif($project->status == 'completed') bg-blue-500/20 text-blue-100 border border-blue-400/30
                            @elseif($project->status == 'pending') bg-yellow-500/20 text-yellow-100 border border-yellow-400/30
                            @else bg-gray-500/20 text-gray-100 border border-gray-400/30 @endif">
                                @if($project->status == 'active') نشط
                                @elseif($project->status == 'completed') مكتمل
                                @elseif($project->status == 'pending') معلق
                                @else {{ $project->status }} @endif
                            </span>
                        </div>
                    
                    <div class="flex flex-wrap items-center gap-4 text-purple-100">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>{{ $project->client->name ?? 'غير محدد' }}</span>
                        </div>
                        
                        @if($project->projectManager)
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span>{{ $project->projectManager->name }}</span>
                        </div>
                        @endif
                        
                        @can('view-finance')
                        @if($project->budget)
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            <span>{{ number_format($project->budget) }} ج.م</span>
                        </div>
                        @endif
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-3">
                @can('edit-projects')
                <a href="{{ route('projects.edit', $project) }}" 
                   class="bg-white/20 backdrop-blur-sm text-white px-6 py-3 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center gap-2 shadow-lg">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    تعديل
                </a>
                @endcan
                <a href="{{ route('projects.index') }}" 
                   class="bg-white text-indigo-600 px-6 py-3 rounded-xl hover:bg-gray-100 transition-all duration-200 flex items-center gap-2 shadow-lg">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة
                </a>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto">
    <!-- Project Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Progress Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold">{{ $stats['progress_percentage'] }}%</span>
            </div>
            <h3 class="text-sm font-medium text-blue-100">التقدم</h3>
            <div class="mt-2 bg-white/20 rounded-full h-2">
                <div class="bg-white rounded-full h-2" style="width: {{ $stats['progress_percentage'] }}%"></div>
            </div>
        </div>

        <!-- Tasks Card -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <span class="text-2xl font-bold">{{ $stats['total_tasks'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-green-100">إجمالي المهام</h3>
            <p class="text-xs text-green-200 mt-1">{{ $stats['completed_tasks'] }} مكتملة</p>
        </div>

        <!-- Team Card -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-2xl font-bold">{{ $stats['team_members_count'] }}</span>
            </div>
            <h3 class="text-sm font-medium text-purple-100">أعضاء الفريق</h3>
            <p class="text-xs text-purple-200 mt-1">فريق العمل</p>
        </div>

        @can('view-finance')
        <!-- Budget Card -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <span class="text-2xl font-bold">{{ $project->budget ? number_format($project->budget) : '0' }}</span>
            </div>
            <h3 class="text-sm font-medium text-orange-100">الميزانية</h3>
            <p class="text-xs text-orange-200 mt-1">ج.م</p>
        </div>
        @endcan
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Project Details -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-8 w-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        تفاصيل المشروع
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @if($project->description)
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                الوصف
                            </h3>
                            <p class="text-gray-900 leading-relaxed bg-gray-50 p-4 rounded-lg">{{ $project->description }}</p>
                        </div>
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">العميل</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $project->client->name ?? 'غير محدد' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">مدير المشروع</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $project->projectManager->name ?? 'غير محدد' }}</span>
                    </div>
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">الأولوية</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($project->priority == 'urgent') bg-red-100 text-red-800
                            @elseif($project->priority == 'high') bg-orange-100 text-orange-800
                            @elseif($project->priority == 'medium') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            @if($project->priority == 'urgent') عاجلة
                            @elseif($project->priority == 'high') عالية
                            @elseif($project->priority == 'medium') متوسطة
                            @elseif($project->priority == 'low') منخفضة
                            @else {{ $project->priority }} @endif
                        </span>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">تاريخ البداية</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('Y/m/d') : 'غير محدد' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">تاريخ الانتهاء</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('Y/m/d') : 'غير محدد' }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3 px-4 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">تاريخ الإنشاء</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $project->created_at->format('Y/m/d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Tasks -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            مهام المشروع
                        </h2>
                        @can('create-tasks')
                        <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2 text-sm font-medium">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        إضافة مهمة
                    </a>
                    @endcan
                    </div>
                </div>
                
                <div class="p-6">
                    @if($project->tasks && $project->tasks->count() > 0)
                        <!-- Task Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <div class="text-2xl font-bold text-green-600">{{ $stats['completed_tasks'] }}</div>
                                <div class="text-sm text-green-700">مكتملة</div>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['in_progress_tasks'] }}</div>
                                <div class="text-sm text-blue-700">قيد التنفيذ</div>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-xl">
                                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_tasks'] }}</div>
                                <div class="text-sm text-yellow-700">معلقة</div>
                            </div>
                        </div>
                        
                        <!-- Tasks List -->
                        <div class="space-y-4">
                            @foreach($project->tasks->take(5) as $task)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                        @if($task->status == 'completed')
                                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @elseif($task->status == 'in_progress')
                                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $task->title }}</h4>
                                        @if($task->description)
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 60) }}</p>
                                        @endif
                                        @if($task->due_date)
                                            <p class="text-xs text-gray-500 mt-1">
                                                <svg class="h-3 w-3 inline ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($task->due_date)->format('Y/m/d') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($task->status == 'completed') bg-green-100 text-green-800
                            @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                                        @if($task->status == 'completed') مكتملة
                                        @elseif($task->status == 'in_progress') قيد التنفيذ
                                        @else معلقة @endif
                        </span>
                                    <a href="{{ route('tasks.show', $task) }}" 
                                       class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                        عرض
                                    </a>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($project->tasks->count() > 5)
                            <div class="text-center pt-4">
                                <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" 
                                   class="text-indigo-600 hover:text-indigo-700 font-medium">
                                    عرض جميع المهام ({{ $project->tasks->count() }})
                                </a>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد مهام</h3>
                            <p class="text-gray-600 mb-6">ابدأ بإنشاء أول مهمة لهذا المشروع</p>
                            @can('create-tasks')
                            <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" 
                               class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-colors inline-flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                إضافة مهمة جديدة
                            </a>
                            @endcan
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Project Status Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6 text-white">
                    <div class="text-center">
                        <div class="h-16 w-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold mb-2">حالة المشروع</h3>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                            @if($project->status == 'active') نشط
                            @elseif($project->status == 'completed') مكتمل
                            @elseif($project->status == 'pending') معلق
                            @else {{ $project->status }} @endif
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['progress_percentage'] }}%</div>
                        <div class="text-sm text-gray-600 mb-4">معدل الإنجاز</div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $stats['progress_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            @if($project->teamMembers && $project->teamMembers->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-8 w-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        فريق العمل
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($project->teamMembers->take(5) as $member)
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-white">{{ substr($member->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $member->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $member->roles->first()?->name ?? 'موظف' }}</p>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($project->teamMembers->count() > 5)
                        <div class="text-center pt-2">
                            <span class="text-sm text-gray-500">و {{ $project->teamMembers->count() - 5 }} آخرين</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-8 w-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        إجراءات سريعة
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @can('edit-projects')
                    <a href="{{ route('projects.edit', $project) }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors shadow-lg">
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        تعديل المشروع
                    </a>
                    @endcan
                    @can('create-tasks')
                    <a href="{{ route('tasks.create', ['project_id' => $project->id]) }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors shadow-lg">
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        إضافة مهمة
                    </a>
                    @endcan
                    <a href="{{ route('tasks.index', ['project_id' => $project->id]) }}" 
                       class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        عرض المهام
                    </a>
                </div>
            </div>

            <!-- Project Timeline -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                        <div class="h-8 w-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        الجدول الزمني
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-3 bg-green-50 rounded-xl">
                            <div class="h-3 w-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">تاريخ الإنشاء</p>
                                <p class="text-xs text-gray-600">{{ $project->created_at->format('Y/m/d H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($project->start_date)
                        <div class="flex items-center gap-4 p-3 bg-blue-50 rounded-xl">
                            <div class="h-3 w-3 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">تاريخ البداية</p>
                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($project->start_date)->format('Y/m/d') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($project->end_date)
                        <div class="flex items-center gap-4 p-3 bg-purple-50 rounded-xl">
                            <div class="h-3 w-3 bg-purple-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">تاريخ الانتهاء</p>
                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($project->end_date)->format('Y/m/d') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($project->end_date && \Carbon\Carbon::parse($project->end_date)->isFuture())
                        <div class="flex items-center gap-4 p-3 bg-yellow-50 rounded-xl">
                            <div class="h-3 w-3 bg-yellow-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">المتبقي</p>
                                <p class="text-xs text-gray-600">{{ \Carbon\Carbon::parse($project->end_date)->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
    
    // Add hover effects to cards
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endpush
