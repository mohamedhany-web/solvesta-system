@extends('layouts.app')

@section('page-title', 'لوحة متابعة الأقسام')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'لوحة متابعة الأقسام',
        'subtitle' => 'نظرة تشغيلية على الأقسام — المشاريع · المهام · التقارير',
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('admin.department-reports.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
            تقارير الأقسام
        </a>
        @can('view-departments')
        <a href="{{ route('departments.index') }}"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            إدارة الأقسام
        </a>
        @endcan
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        @foreach([
            ['الأقسام', $stats['departments_total'], $themeColor],
            ['المشاريع', $stats['projects_total'], '#2563eb'],
            ['تقارير الأقسام', $stats['reports_total'], '#7c3aed'],
            ['مهام متأخرة', $stats['overdue_tasks'], $stats['overdue_tasks'] > 0 ? '#dc2626' : '#6b7280'],
            ['بدون مدير', $stats['without_manager'], $stats['without_manager'] > 0 ? '#d97706' : '#6b7280'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500">{{ $label }}</p>
            <p class="text-3xl font-bold mt-1" style="color: {{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    @if($departmentsAtRisk > 0 || $stats['without_manager'] > 0)
    <div class="mb-6 flex flex-wrap gap-3">
        @if($departmentsAtRisk > 0)
        <div class="flex-1 min-w-[14rem] rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <span class="font-bold">{{ $departmentsAtRisk }}</span> قسم لديه مهام متأخرة — يحتاج متابعة من الإدارة.
        </div>
        @endif
        @if($stats['without_manager'] > 0)
        <div class="flex-1 min-w-[14rem] rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            <span class="font-bold">{{ $stats['without_manager'] }}</span> قسم بدون مدير معيّن.
        </div>
        @endif
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="font-bold text-gray-900">ملخص الأقسام <span class="text-gray-400 font-normal text-sm">({{ $departments->count() }})</span></h2>
                    <a href="{{ route('projects.index') }}" class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">كل المشاريع</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">المدير</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">موظفون</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">مشاريع</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">مهام</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">متأخرة</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600">آخر تقرير</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($departments as $dept)
                            @php
                                $overdue = (int) ($overdueCountsByDept[$dept->id] ?? 0);
                                $tasks = (int) ($taskCountsByDept[$dept->id] ?? 0);
                                $latestReport = isset($latestReportByDept[$dept->id])
                                    ? \Illuminate\Support\Carbon::parse($latestReportByDept[$dept->id])
                                    : null;
                            @endphp
                            <tr class="hover:bg-blue-50/40 transition-colors align-middle {{ $overdue > 0 ? 'bg-red-50/30' : '' }}">
                                <td class="px-4 py-3">
                                    @can('view-departments')
                                    <a href="{{ route('departments.show', $dept) }}" class="font-bold text-gray-900 hover:underline">{{ $dept->name }}</a>
                                    @else
                                    <span class="font-bold text-gray-900">{{ $dept->name }}</span>
                                    @endcan
                                    @if(!$dept->manager_id)
                                        <span class="block text-[10px] text-amber-600 font-bold mt-0.5">بدون مدير</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">{{ $dept->manager?->user?->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $dept->employees_count }}</td>
                                <td class="px-4 py-3">
                                    @if($dept->projects_count > 0)
                                    <a href="{{ route('projects.index', ['department_id' => $dept->id]) }}" class="font-bold hover:underline" style="color: {{ $themeColor }};">{{ $dept->projects_count }}</a>
                                    @else
                                    <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-700">{{ $tasks }}</td>
                                <td class="px-4 py-3">
                                    @if($overdue > 0)
                                        <span class="text-xs font-bold px-2 py-1 rounded-full bg-red-100 text-red-700">{{ $overdue }}</span>
                                    @else
                                        <span class="text-gray-400">0</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                                    {{ $latestReport?->format('Y/m/d') ?? '—' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <a href="{{ route('admin.department-reports.index', ['department_id' => $dept->id]) }}"
                                       class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">التقارير</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden sticky top-20">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-bold text-gray-900">آخر التقارير</h2>
                    <p class="text-xs text-gray-500 mt-1">للمتابعة السريعة واتخاذ القرار</p>
                </div>
                <div class="p-4 space-y-3 max-h-[32rem] overflow-y-auto">
                    @forelse($recentReports as $report)
                    <div class="rounded-xl border border-gray-200 p-4 hover:border-gray-300 hover:shadow-sm transition">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="font-bold text-gray-900 text-sm">{{ $report->department?->name ?? '—' }}</div>
                                <p class="text-xs text-gray-500 mt-1 truncate">
                                    {{ $report->project?->name ?? 'بدون مشروع' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $report->department?->manager?->user?->name ?? '—' }}
                                    <span class="mx-1">·</span>
                                    {{ $report->created_at?->diffForHumans() }}
                                </p>
                                <span class="inline-block mt-2 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $report->status === 'submitted' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $report->status === 'submitted' ? 'مُرسل' : 'مسودة' }}
                                </span>
                            </div>
                            <a href="{{ route('admin.department-reports.show', $report) }}"
                               class="text-xs font-bold shrink-0 hover:underline" style="color: {{ $themeColor }};">عرض</a>
                        </div>
                        @if($report->summary)
                        <p class="text-xs text-gray-600 mt-2 line-clamp-2">{{ $report->summary }}</p>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-10 text-gray-500 text-sm">
                        <p class="font-bold mb-1">لا توجد تقارير بعد</p>
                        <p class="text-xs">ستظهر هنا عند رفع مديري الأقسام لتقاريرهم.</p>
                    </div>
                    @endforelse
                </div>
                @if($recentReports->isNotEmpty())
                <div class="px-4 py-3 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.department-reports.index') }}" class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">عرض كل التقارير →</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
