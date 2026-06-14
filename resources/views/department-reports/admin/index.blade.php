@extends('layouts.app')

@section('page-title', 'تقارير الأقسام')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'تقارير الأقسام',
        'subtitle' => 'متابعة التقارير المرفوعة من مديري الأقسام',
        'icon' => 'doc',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('admin.department-oversight.index') }}"
           class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            لوحة متابعة الأقسام
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['إجمالي التقارير', $stats['total'], $themeColor],
            ['مُرسلة', $stats['submitted'], '#059669'],
            ['مسودات', $stats['draft'], '#d97706'],
            ['هذا الشهر', $stats['this_month'], '#7c3aed'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500">{{ $label }}</p>
            <p class="text-3xl font-bold mt-1" style="color: {{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">القسم</label>
            <select name="department_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" @selected(request('department_id') == $dept->id)>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الحالة</label>
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                <option value="submitted" @selected(request('status') === 'submitted')>مُرسل</option>
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold">تصفية</button>
        @if(request()->hasAny(['department_id', 'status']))
        <a href="{{ route('admin.department-reports.index') }}" class="border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50">إعادة تعيين</a>
        @endif
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">قائمة التقارير <span class="text-gray-400 font-normal text-sm">({{ $reports->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">التاريخ</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المدير</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المشروع</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الفترة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reports as $report)
                    <tr class="hover:bg-blue-50/40 transition-colors align-middle">
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                            <div>{{ $report->created_at?->format('Y/m/d') }}</div>
                            <div class="text-gray-400">{{ $report->created_at?->format('H:i') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-bold text-gray-900">{{ $report->department?->name ?? '—' }}</span>
                            @if($report->summary)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ Str::limit($report->summary, 60) }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $report->department?->manager?->user?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-700 text-xs">{{ $report->project?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                            @if($report->period_start || $report->period_end)
                                {{ $report->period_start?->format('Y/m/d') ?? '—' }}
                                <span class="text-gray-300">→</span>
                                {{ $report->period_end?->format('Y/m/d') ?? '—' }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-bold px-2 py-1 rounded-full {{ $report->status === 'submitted' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $report->status === 'submitted' ? 'مُرسل' : 'مسودة' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <a href="{{ route('admin.department-reports.show', $report) }}" class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">عرض</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا توجد تقارير</p>
                            <p class="text-sm">ستظهر هنا عند رفع مديري الأقسام لتقاريرهم.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $reports->links() }}</div>
        @endif
    </div>
</div>
@endsection
