@extends('layouts.app')

@section('page-title', 'لوحة مدير القسم')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">لوحة مدير القسم</h1>
            <p class="text-gray-600 mt-2">
                قسم: <span class="font-semibold text-gray-900">{{ $department->name }}</span>
                @if($department->manager?->user)
                    <span class="text-gray-400">—</span>
                    المدير: <span class="font-semibold text-gray-900">{{ $department->manager->user->name }}</span>
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('department-manager.tasks.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-sm">
                إنشاء مهمة + إسناد
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm font-bold text-gray-600">المشاريع</div>
            <div class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['projects_total'] }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm font-bold text-gray-600">المهام</div>
            <div class="text-2xl font-extrabold text-gray-900 mt-2">{{ $stats['tasks_total'] }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm font-bold text-gray-600">قيد التنفيذ</div>
            <div class="text-2xl font-extrabold text-blue-600 mt-2">{{ $stats['tasks_in_progress'] }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="text-sm font-bold text-gray-600">متأخرة</div>
            <div class="text-2xl font-extrabold text-red-600 mt-2">{{ $stats['tasks_overdue'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-extrabold text-gray-900">مشاريع القسم</h2>
                <span class="text-sm text-gray-500">آخر المشاريع</span>
            </div>
            <div class="p-6 space-y-4">
                @forelse($projects as $project)
                    <div class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-bold text-gray-900">{{ $project->name }}</div>
                                <div class="text-sm text-gray-600 mt-1">
                                    العميل: {{ $project->client?->company_name ?? $project->client?->name ?? '—' }}
                                </div>
                                <div class="text-xs text-gray-500 mt-2">
                                    عدد المهام: {{ $project->tasks_count }}
                                </div>
                            </div>
                            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 font-bold text-sm hover:underline">فتح</a>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-600">لا توجد مشاريع مرتبطة بقسمك حتى الآن.</div>
                @endforelse

                <div>
                    {{ $projects->links() }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-extrabold text-gray-900">أحدث المهام</h2>
                <p class="text-sm text-gray-600 mt-1">لمتابعة التوزيع وسرعة التنفيذ</p>
            </div>
            <div class="p-6 space-y-4">
                @forelse($recentTasks as $task)
                    <div class="rounded-xl border border-gray-200 p-4 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-bold text-gray-900">{{ $task->title }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    مشروع: <span class="font-semibold">{{ $task->project?->name ?? '—' }}</span>
                                    <span class="text-gray-300 mx-1">|</span>
                                    المسؤول: <span class="font-semibold">{{ $task->assignedTo?->name ?? '—' }}</span>
                                </div>
                            </div>
                            <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 font-bold text-sm hover:underline">عرض</a>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-600">لا توجد مهام حتى الآن.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

