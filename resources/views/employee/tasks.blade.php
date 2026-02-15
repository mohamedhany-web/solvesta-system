@extends('layouts.app')

@section('page-title', 'مهامي')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">مهامي</h1>
            <p class="text-green-100">جميع المهام المخصصة لك</p>
        </div>
        <a href="{{ route('employee.dashboard') }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200 flex items-center">
            <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة للوحة التحكم
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-yellow-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">معلقة</p>
                <p class="text-xl font-bold text-gray-900">{{ $tasks->where('status', 'pending')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">قيد التنفيذ</p>
                <p class="text-xl font-bold text-gray-900">{{ $tasks->where('status', 'in_progress')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">مكتملة</p>
                <p class="text-xl font-bold text-gray-900">{{ $tasks->where('status', 'completed')->count() }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-red-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">متأخرة</p>
                <p class="text-xl font-bold text-gray-900">{{ $tasks->where('status', 'overdue')->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <div class="flex space-x-1 space-x-reverse">
        <button class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') == null ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:text-gray-900' }}" 
                onclick="window.location.href='{{ route('employee.tasks') }}'">
            الكل ({{ $tasks->count() }})
        </button>
        <button class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'text-gray-600 hover:text-gray-900' }}" 
                onclick="window.location.href='{{ route('employee.tasks', ['status' => 'pending']) }}'">
            معلقة ({{ $tasks->where('status', 'pending')->count() }})
        </button>
        <button class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'text-gray-600 hover:text-gray-900' }}" 
                onclick="window.location.href='{{ route('employee.tasks', ['status' => 'in_progress']) }}'">
            قيد التنفيذ ({{ $tasks->where('status', 'in_progress')->count() }})
        </button>
        <button class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request('status') == 'completed' ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:text-gray-900' }}" 
                onclick="window.location.href='{{ route('employee.tasks', ['status' => 'completed']) }}'">
            مكتملة ({{ $tasks->where('status', 'completed')->count() }})
        </button>
    </div>
</div>

<!-- Tasks List -->
<div class="space-y-4">
    @forelse($tasks as $task)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $task->title }}</h3>
                    <span class="mr-3 px-2 py-1 text-xs font-medium rounded-full
                        @if($task->status == 'completed') bg-green-100 text-green-800
                        @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800
                        @elseif($task->status == 'overdue') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($task->status == 'completed') مكتملة
                        @elseif($task->status == 'in_progress') قيد التنفيذ
                        @elseif($task->status == 'overdue') متأخرة
                        @else معلقة @endif
                    </span>
                </div>
                
                <p class="text-gray-600 mb-3">{{ $task->description }}</p>
                
                <div class="flex items-center space-x-4 space-x-reverse text-sm text-gray-500">
                    <div class="flex items-center">
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        المشروع: {{ $task->project->name ?? 'غير محدد' }}
                    </div>
                    
                    @if($task->due_date)
                    <div class="flex items-center">
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        موعد التسليم: {{ \Carbon\Carbon::parse($task->due_date)->format('Y/m/d') }}
                    </div>
                    @endif
                    
                    <div class="flex items-center">
                        <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        تم الإنشاء: {{ $task->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-2 space-x-reverse">
                @if($task->status == 'pending')
                <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        بدء العمل
                    </button>
                </form>
                @endif
                
                @if($task->status == 'in_progress')
                <form action="{{ route('employee.tasks.update-status', $task) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        إكمال المهمة
                    </button>
                </form>
                @endif
                
                <a href="{{ route('employee.tasks.show', $task) }}" class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    عرض التفاصيل
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد مهام</h3>
        <p class="text-gray-500">لا توجد مهام مخصصة لك حالياً</p>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($tasks->hasPages())
<div class="flex items-center justify-between mt-6">
    <div class="text-sm text-gray-700">
        عرض {{ $tasks->firstItem() }} إلى {{ $tasks->lastItem() }} من {{ $tasks->total() }} نتيجة
    </div>
    <div class="flex space-x-1 space-x-reverse">
        {{ $tasks->links() }}
    </div>
</div>
@endif
@endsection

