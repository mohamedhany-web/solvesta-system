@extends('layouts.app')

@section('page-title', 'إدارة المشاريع')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $statusLabels = [
        'planning' => 'تخطيط',
        'in_progress' => 'قيد التنفيذ',
        'on_hold' => 'معلق',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
    ];
    $statusColors = [
        'planning' => 'bg-amber-100 text-amber-900',
        'in_progress' => 'bg-blue-100 text-blue-900',
        'on_hold' => 'bg-orange-100 text-orange-900',
        'completed' => 'bg-emerald-100 text-emerald-900',
        'cancelled' => 'bg-red-100 text-red-900',
    ];
    $priorityLabels = [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'عالية',
        'urgent' => 'عاجلة',
    ];
    $cardColors = ['#2563eb', '#059669', '#7c3aed', '#d97706', '#db2777', '#0891b2'];
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'إدارة المشاريع',
        'subtitle' => 'تتبع المشاريع، التقدم، الفريق، والربط مع PMO والمالية',
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        @can('create-projects')
        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            مشروع جديد
        </a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['إجمالي المشاريع', $stats['total'], $themeColor],
            ['نشطة', $stats['active'], '#059669'],
            ['مكتملة', $stats['completed'], '#7c3aed'],
            ['معلقة', $stats['on_hold'], '#d97706'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500">{{ $label }}</p>
            <p class="text-3xl font-bold mt-1" style="color: {{ $color }};">{{ $val }}</p>
            @if($label === 'نشطة' && $stats['total'] > 0)
                <p class="text-xs text-gray-400 mt-1">{{ $stats['percentage_active'] }}% من الإجمالي</p>
            @elseif($label === 'مكتملة' && $stats['total'] > 0)
                <p class="text-xs text-gray-400 mt-1">{{ $stats['percentage_completed'] }}% من الإجمالي</p>
            @endif
        </div>
        @endforeach
    </div>

    @if($featuredProjects->count() > 0)
    <div class="mb-6">
        <h2 class="text-sm font-bold text-gray-600 mb-3">أحدث المشاريع</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach($featuredProjects as $index => $project)
            @php
                $color = $cardColors[$index % count($cardColors)];
                $totalTasks = $project->tasks->count();
                $completedTasks = $project->tasks->where('status', 'completed')->count();
                $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : ($project->progress_percentage ?? 0);
            @endphp
            <a href="{{ route('projects.show', $project) }}" class="block bg-white rounded-2xl border border-gray-200 p-5 shadow-sm hover:shadow-md hover:border-blue-200 transition-all">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-11 w-11 rounded-xl flex items-center justify-center text-white font-bold shrink-0" style="background: {{ $color }};">
                            {{ mb_substr($project->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-bold text-gray-900 truncate">{{ $project->name }}</h3>
                            <p class="text-xs text-gray-500 truncate">{{ $project->client->name ?? 'بدون عميل' }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded-full shrink-0 {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $statusLabels[$project->status] ?? $project->status }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $project->description ?: 'لا يوجد وصف' }}</p>
                <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
                    <span>{{ $completedTasks }}/{{ $totalTasks }} مهام</span>
                    <span class="font-bold">{{ $progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="width: {{ $progress }}%; background: {{ $color }};"></div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <form method="GET" action="{{ route('projects.index') }}" class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
        <div class="flex-1 min-w-[12rem]">
            <label class="text-xs font-bold text-gray-600 block mb-1">بحث</label>
            <input name="search" value="{{ request('search') }}" placeholder="اسم المشروع أو الوصف..."
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">القسم</label>
            <select name="department_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">العميل</label>
            <select name="client_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>{{ $client->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الحالة</label>
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <option value="">الكل</option>
                @foreach($statusLabels as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الأولوية</label>
            <select name="priority" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                <option value="">الكل</option>
                @foreach($priorityLabels as $value => $label)
                    <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-800">تصفية</button>
        @if(request()->hasAny(['search', 'status', 'priority', 'client_id', 'department_id']))
        <a href="{{ route('projects.index') }}" class="border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">إعادة تعيين</a>
        @endif
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-2">
            <h2 class="font-bold text-gray-900">جميع المشاريع <span class="text-gray-400 font-normal text-sm">({{ $projects->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المشروع</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">العميل</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">قائد الفريق</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الأولوية</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التقدم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">البداية</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($projects as $project)
                    @php
                        $totalTasks = $project->tasks()->count();
                        $completedTasks = $project->tasks()->where('status', 'completed')->count();
                        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : ($project->progress_percentage ?? 0);
                    @endphp
                    <tr class="hover:bg-blue-50/40 transition-colors align-middle">
                        <td class="px-4 py-3">
                            <a href="{{ route('projects.show', $project) }}" class="font-bold text-gray-900 hover:text-blue-700">{{ $project->name }}</a>
                            @if($project->description)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $project->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($project->client)
                                <a href="{{ route('clients.show', $project->client) }}" class="text-blue-600 hover:underline">{{ $project->client->name }}</a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $project->department->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($project->projectManager)
                                <span class="text-gray-700">{{ $project->projectManager->name }}</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-100 text-amber-900 px-2 py-0.5 text-xs font-bold">بانتظار رئيس القسم</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold text-gray-600">{{ $priorityLabels[$project->priority] ?? $project->priority }}</span>
                        </td>
                        <td class="px-4 py-3 min-w-[7rem]">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-8">{{ $progress }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                            {{ $project->start_date ? $project->start_date->format('Y/m/d') : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $statusColors[$project->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $statusLabels[$project->status] ?? $project->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('projects.show', $project) }}" class="text-xs font-bold text-blue-600 hover:underline">عرض</a>
                                @can('edit-projects')
                                <a href="{{ route('projects.edit', $project) }}" class="text-xs font-bold text-gray-600 hover:underline">تعديل</a>
                                @endcan
                                @can('delete-projects')
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('حذف المشروع «{{ $project->name }}»؟');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-600 hover:underline">حذف</button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا توجد مشاريع</p>
                            <p class="text-sm">@can('create-projects')<a href="{{ route('projects.create') }}" class="text-blue-600 font-semibold hover:underline">أنشئ مشروعاً جديداً</a> للبدء.@else لا توجد مشاريع مرتبطة بك حالياً.@endcan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($projects->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $projects->links() }}</div>
        @endif
    </div>
</div>
@endsection
