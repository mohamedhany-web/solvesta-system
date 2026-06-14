@extends('layouts.app')

@section('page-title', 'PMO — إدارة التنفيذ')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'PMO',
        'subtitle' => 'Milestones · توزيع المهام · Blockers · متابعة التنفيذ',
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('daily-reports.create') }}" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">+ تقرير يومي</a>
        <a href="{{ route('daily-reports.index') }}" class="px-5 py-2.5 rounded-xl border bg-white font-bold text-sm hover:shadow-md">التقارير اليومية</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        @foreach([
            ['مشاريع نشطة', $stats['active_projects'], $themeColor],
            ['مراحل متأخرة', $stats['overdue_milestones'], '#dc2626'],
            ['Blockers مفتوحة', $stats['open_blockers'], '#d97706'],
            ['تقارير اليوم', $stats['reports_today'], '#2563eb'],
            ['بانتظار مراجعة', $stats['unreviewed_reports'], '#7c3aed'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 text-center">
            <p class="text-xs text-gray-500 font-tajawal">{{ $label }}</p>
            <p class="text-2xl font-bold font-tajawal mt-1" style="color: {{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h2 class="font-bold text-red-700 mb-4 font-tajawal">Blockers نشطة</h2>
            <ul class="space-y-3 text-sm font-tajawal">
                @forelse($blockers as $task)
                <li class="p-3 rounded-xl bg-red-50 border border-red-100">
                    <div class="flex justify-between gap-2">
                        <strong>{{ $task->title }}</strong>
                        @can('edit-projects')
                        <form method="POST" action="{{ route('pmo.tasks.resolve-blocker', $task) }}">@csrf
                            <button class="text-xs text-emerald-700 font-bold">حلّ ✓</button>
                        </form>
                        @endcan
                    </div>
                    <p class="text-xs text-gray-600 mt-1">{{ $task->project?->name }} — {{ $task->assignedTo?->name }}</p>
                    @if($task->blocker_description)<p class="text-xs text-red-800 mt-1">{{ $task->blocker_description }}</p>@endif
                </li>
                @empty
                <li class="text-gray-500">لا توجد عوائق مفتوحة.</li>
                @endforelse
            </ul>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h2 class="font-bold text-amber-700 mb-4 font-tajawal">مراحل متأخرة</h2>
            <ul class="space-y-3 text-sm font-tajawal">
                @forelse($overdueMilestones as $m)
                <li class="p-3 rounded-xl bg-amber-50 border border-amber-100 flex justify-between">
                    <div>
                        <strong>{{ $m->name }}</strong>
                        <p class="text-xs text-gray-600">{{ $m->project?->name }}</p>
                    </div>
                    <span class="text-xs text-red-700 font-bold">{{ $m->due_date?->format('Y/m/d') }}</span>
                </li>
                @empty
                <li class="text-gray-500">لا توجد مراحل متأخرة.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b font-bold font-tajawal" style="background: {{ $themeColor }}08;">المشاريع تحت الإدارة</div>
        <table class="w-full text-sm font-tajawal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-right">المشروع</th>
                    <th class="px-4 py-3 text-right">العميل</th>
                    <th class="px-4 py-3 text-right">المراحل</th>
                    <th class="px-4 py-3 text-right">التقدم</th>
                    <th class="px-4 py-3 text-right"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($projects as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold">{{ $p->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->client?->name }}</td>
                    <td class="px-4 py-3">{{ $p->milestones->count() }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full max-w-[80px]">
                                <div class="h-full rounded-full" style="width:{{ $p->progress_percentage ?? 0 }}%; background:{{ $themeColor }};"></div>
                            </div>
                            <span>{{ $p->progress_percentage ?? 0 }}%</span>
                        </div>
                    </td>
                    <td class="px-4 py-3"><a href="{{ route('projects.show', $p) }}" class="font-bold" style="color:{{ $themeColor }};">فتح</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">لا توجد مشاريع نشطة.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3">{{ $projects->links() }}</div>
    </div>
</div>
@endsection
