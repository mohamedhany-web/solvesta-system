@extends('layouts.app')

@section('page-title', 'تقارير تشغيل Workspace')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $kpis = $snapshot['kpis'] ?? [];
    $kpiCards = [
        ['مفتوحة', $kpis['open'] ?? 0, 'مهام نشطة', $themeColor],
        ['مكتمل الأسبوع', $kpis['done_week'] ?? 0, 'Throughput', '#059669'],
        ['متأخرة', $kpis['overdue'] ?? 0, 'تحتاج تدخل', ($kpis['overdue'] ?? 0) ? '#dc2626' : '#059669'],
        ['عوائق', $kpis['blockers'] ?? 0, 'Blockers', ($kpis['blockers'] ?? 0) ? '#ea580c' : '#059669'],
        ['مراجعة', $kpis['in_review'] ?? 0, 'في مسار المراجعة', '#7c3aed'],
        ['موافقات', $kpis['pending_approvals'] ?? 0, 'بانتظار اعتماد', ($kpis['pending_approvals'] ?? 0) ? '#d97706' : '#059669'],
        ['استثناءات', $kpis['exceptions_open'] ?? 0, 'منها '.($kpis['exceptions_critical'] ?? 0).' حرجة', ($kpis['exceptions_critical'] ?? 0) ? '#dc2626' : '#0891b2'],
        ['حمل مرتفع', $kpis['high_load'] ?? 0, 'أعضاء الفريق', ($kpis['high_load'] ?? 0) ? '#dc2626' : '#059669'],
        ['التزام الجدول', ($kpis['on_time_rate'] ?? 100).'%', 'نسبة غير المتأخر', (($kpis['on_time_rate'] ?? 100) >= 85) ? '#059669' : '#dc2626'],
    ];
@endphp

<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'تقارير تشغيل Workspace',
        'subtitle' => 'حوكمة · أتمتة SLA · طاقة الفريق · '.now()->translatedFormat('l j F Y'),
        'icon' => 'workspace',
    ])

    <div class="flex flex-wrap gap-2 mb-6 -mt-2">
        <a href="{{ route('workspace.ops') }}"
           class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border {{ !$track ? 'text-white border-transparent' : 'bg-white text-gray-700 border-gray-200' }}"
           @if(!$track) style="background: {{ $themeColor }};" @endif>كل المسارات</a>
        @foreach($availableTracks as $key => $t)
            <a href="{{ route('workspace.ops', ['track' => $key]) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border {{ $track === $key ? 'text-white border-transparent' : 'bg-white text-gray-700 border-gray-200' }}"
               @if($track === $key) style="background: {{ $themeColor }};" @endif>
                {{ $t['label_ar'] ?? $key }}
            </a>
        @endforeach
        <a href="{{ route('workspace.index', ['scope' => 'team']) }}" class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-white border border-gray-200 text-gray-700">مساحة الفريق</a>
        <a href="{{ route('executive.dashboard') }}" class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-white border border-gray-200 text-gray-700">Control Center</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-9 gap-3 mb-6">
        @foreach($kpiCards as [$label, $value, $sub, $color])
        <div class="bg-white rounded-2xl border border-gray-200 p-3 shadow-sm">
            <p class="text-[10px] text-gray-500 font-medium">{{ $label }}</p>
            <p class="text-xl font-bold mt-0.5" style="color: {{ $color }};">{{ $value }}</p>
            <p class="text-[9px] text-gray-400 mt-0.5 truncate">{{ $sub }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
        <div class="xl:col-span-4 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-bold text-sm">توزيع الحالات</h3>
            </div>
            <ul class="divide-y divide-gray-50">
                @foreach($snapshot['by_status'] ?? [] as $status => $count)
                <li class="px-4 py-2.5 flex items-center justify-between text-sm">
                    <span class="text-gray-700">{{ \App\Models\Task::statusLabelAr($status, $track ?: 'general') }}</span>
                    <span class="font-bold" style="color: {{ $themeColor }};">{{ $count }}</span>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="xl:col-span-4 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-bold text-sm">حسب المسار</h3>
            </div>
            <ul class="divide-y divide-gray-50">
                @foreach($snapshot['by_track'] ?? [] as $key => $row)
                <li class="px-4 py-2.5 flex items-center justify-between text-sm">
                    <a href="{{ route('workspace.ops', ['track' => $key]) }}" class="text-gray-700 hover:underline">{{ $row['label'] }}</a>
                    <span class="font-bold">{{ $row['open'] }} مفتوحة</span>
                </li>
                @endforeach
            </ul>
        </div>

        <div class="xl:col-span-4 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-bold text-sm">سياسات الحوكمة النشطة</h3>
            </div>
            @php $gov = $snapshot['governance'] ?? []; @endphp
            <ul class="p-4 space-y-2 text-xs text-gray-700">
                <li>اعتماد قبل: <strong>{{ implode('، ', $gov['approval_required_statuses'] ?? []) ?: '—' }}</strong></li>
                <li>أولويات تتطلب اعتماد: <strong>{{ implode('، ', $gov['approval_required_priorities'] ?? []) ?: 'الكل' }}</strong></li>
                <li>تصعيد عائق بعد: <strong>{{ $gov['blocker_escalate_days'] ?? '—' }}</strong> يوم</li>
                <li>تأخر حرج بعد: <strong>{{ $gov['overdue_critical_days'] ?? '—' }}</strong> يوم</li>
                <li>ركود تنفيذ بعد: <strong>{{ $gov['stale_in_progress_days'] ?? '—' }}</strong> يوم</li>
                <li>فحص تلقائي: <strong>كل ساعة</strong> عبر solvesta:scan-sla</li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-6">
        <div class="xl:col-span-7 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-sm">استثناءات المهام المفتوحة</h3>
                <span class="text-[10px] text-gray-400">أتمتة SLA</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-right px-4 py-2 text-[10px] font-bold text-gray-500">الخطورة</th>
                            <th class="text-right px-4 py-2 text-[10px] font-bold text-gray-500">العنوان</th>
                            <th class="text-right px-4 py-2 text-[10px] font-bold text-gray-500">المسؤول</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($snapshot['exceptions'] ?? [] as $ex)
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-2.5">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ex->severity === 'critical' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $ex->severity }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5">
                                <p class="text-xs font-semibold text-gray-900">{{ $ex->title }}</p>
                                <p class="text-[10px] text-gray-500 mt-0.5 line-clamp-1">{{ $ex->message }}</p>
                            </td>
                            <td class="px-4 py-2.5 text-xs text-gray-600">{{ $ex->owner?->name ?? '—' }}</td>
                            <td class="px-4 py-2.5">
                                @if($ex->subject_id)
                                <a href="{{ route('tasks.show', $ex->subject_id) }}" class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">فتح</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-10 text-center text-gray-400 text-sm">لا استثناءات مفتوحة</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="xl:col-span-5 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="font-bold text-sm">موافقات معلّقة</h3>
            </div>
            <ul class="divide-y divide-gray-50">
                @forelse($snapshot['pending_approvals'] ?? [] as $approval)
                @php $task = $approval->subject; @endphp
                <li class="px-4 py-3">
                    <p class="text-xs font-semibold text-gray-900">{{ $task?->task_key ?? ('#'.$approval->id) }} — {{ $task?->title ?? $approval->notes }}</p>
                    <p class="text-[10px] text-gray-500 mt-1">طلب: {{ $approval->requester?->name }} · {{ $approval->created_at?->diffForHumans() }}</p>
                    @if($task)
                    <div class="flex flex-wrap gap-2 mt-2">
                        <a href="{{ route('tasks.show', $task) }}" class="text-[10px] font-bold hover:underline" style="color: {{ $themeColor }};">المهمة</a>
                        <form method="POST" action="{{ route('os.approvals.decide', $approval) }}" class="inline">
                            @csrf
                            <input type="hidden" name="decision" value="approve">
                            <button class="text-[10px] font-bold text-emerald-700 hover:underline">اعتماد</button>
                        </form>
                        <form method="POST" action="{{ route('os.approvals.decide', $approval) }}" class="inline">
                            @csrf
                            <input type="hidden" name="decision" value="reject">
                            <button class="text-[10px] font-bold text-red-600 hover:underline">رفض</button>
                        </form>
                    </div>
                    @endif
                </li>
                @empty
                <li class="px-4 py-10 text-center text-gray-400 text-sm">لا موافقات معلّقة</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">لوحة الطاقة الاستيعابية</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-right px-4 py-2 text-[10px] font-bold text-gray-500">الموظف</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">مفتوحة</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">تنفيذ</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">مراجعة</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">متأخرة</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">الحمل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($snapshot['capacity'] ?? [] as $row)
                    @php
                        $loadClass = match($row['load']) {
                            'high' => 'bg-red-100 text-red-800',
                            'medium' => 'bg-amber-100 text-amber-800',
                            default => 'bg-emerald-100 text-emerald-800',
                        };
                        $loadLabel = match($row['load']) {
                            'high' => 'مرتفع',
                            'medium' => 'متوسط',
                            default => 'منخفض',
                        };
                    @endphp
                    <tr>
                        <td class="px-4 py-2.5 text-xs font-semibold">{{ $row['name'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs font-bold">{{ $row['open'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs">{{ $row['in_progress'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs">{{ $row['review'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs {{ $row['overdue'] > 0 ? 'text-red-600 font-bold' : '' }}">{{ $row['overdue'] }}</td>
                        <td class="px-3 py-2.5 text-center"><span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $loadClass }}">{{ $loadLabel }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">لا بيانات طاقة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">سجل الحوكمة (آخر الأحداث)</h3>
        </div>
        <ul class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
            @forelse($snapshot['recent_events'] ?? [] as $event)
            <li class="px-4 py-2.5 flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-900">{{ $event->event }}</p>
                    <p class="text-[10px] text-gray-500 mt-0.5 truncate">
                        @if(is_array($event->payload))
                            {{ json_encode($event->payload, JSON_UNESCAPED_UNICODE) }}
                        @endif
                    </p>
                </div>
                <div class="text-left shrink-0">
                    @if($event->subject_id)
                    <a href="{{ route('tasks.show', $event->subject_id) }}" class="text-[10px] font-bold hover:underline block" style="color: {{ $themeColor }};">مهمة</a>
                    @endif
                    <span class="text-[10px] text-gray-400">{{ $event->created_at?->diffForHumans() }}</span>
                </div>
            </li>
            @empty
            <li class="px-4 py-10 text-center text-gray-400 text-sm">لا أحداث بعد — ستظهر مع تغيّر الحالات والفحص التلقائي</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
