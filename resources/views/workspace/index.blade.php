@extends('layouts.app')

@section('page-title', $trackMeta['workspace_title'] ?? 'مساحة عملي')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $workflowTrack = $workflowTrack ?? \App\Models\Task::resolveWorkflowTrack(auth()->user());
    $trackMeta = $trackMeta ?? [];
    $statColors = [
        'open' => $themeColor,
        'in_progress' => '#2563eb',
        'code_review' => '#7c3aed',
        'qa_testing' => '#0891b2',
        'done' => '#059669',
        'overdue' => '#dc2626',
        'blockers' => '#ea580c',
        'hours' => '#d97706',
    ];
@endphp
<div class="w-full max-w-full font-tajawal" id="workspace-app" data-track="{{ $workflowTrack }}">
    @include('partials.erp-page-header', [
        'title' => $trackMeta['workspace_title'] ?? 'مساحة عملي',
        'subtitle' => ($teamView ? 'عرض الفريق' : 'مهامي') . ' · ' . ($trackMeta['workspace_subtitle'] ?? 'Kanban') . ' · ' . now()->translatedFormat('l j F'),
        'icon' => 'workspace',
    ])

    {{-- Track switcher (managers / multi-role) --}}
    @if(count($availableTracks ?? []) > 1 && (auth()->user()->can('view-all-tasks') || auth()->user()->hasAnyRole(['admin', 'super_admin', 'project_manager'])))
    <div class="flex flex-wrap gap-2 mb-4 -mt-2">
        @foreach($availableTracks as $key => $t)
            <a href="{{ route('workspace.index', array_filter([
                'track' => $key,
                'scope' => $teamView ? 'team' : null,
                'project_id' => $projectId,
                'view' => $viewMode,
                'priority' => $priorityFilter,
                'specialty' => $specialtyFilter,
            ])) }}"
               class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold border transition
                      {{ $workflowTrack === $key ? 'text-white border-transparent' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}"
               @if($workflowTrack === $key) style="background: {{ $themeColor }};" @endif>
                {{ $t['label_ar'] ?? $t['label'] ?? $key }}
            </a>
        @endforeach
    </div>
    @endif

    {{-- Dynamic quick actions from track config --}}
    <div class="flex flex-wrap gap-2 mb-6 -mt-1">
        @foreach($quickLinks ?? [] as $link)
            @if($link === 'dev_workflow')
                @can('view-dev-workflow')
                <a href="{{ route('dev-workflow.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-90 transition" style="background: {{ $themeColor }};">بيئة التطوير</a>
                @endcan
            @elseif($link === 'design')
                @can('view-design')
                <a href="{{ route('design.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-90 transition" style="background: {{ $themeColor }};">إدارة التصميم</a>
                @endcan
            @elseif($link === 'tasks')
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">قائمة المهام</a>
            @elseif($link === 'projects')
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">المشاريع</a>
            @elseif($link === 'clients')
                @can('view-clients')
                <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">العملاء</a>
                @endcan
            @elseif($link === 'bugs')
                @can('view-bugs')
                <a href="{{ route('bugs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">الأخطاء</a>
                @endcan
            @elseif($link === 'qa')
                @can('view-bugs')
                <a href="{{ route('bugs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">الجودة</a>
                @endcan
            @elseif($link === 'messages')
                <a href="{{ route('messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">الرسائل</a>
            @elseif($link === 'ops')
                @if(auth()->user()->can('view-all-tasks') || auth()->user()->can('view-reports') || auth()->user()->hasAnyRole(['super_admin', 'admin', 'project_manager']))
                <a href="{{ route('workspace.ops') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">تقارير التشغيل</a>
                @endif
            @endif
        @endforeach
        <a href="{{ route('daily-reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">
            التقرير اليومي
            @unless($todayReport)<span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>@endunless
        </a>
        @if(!empty($showGit))
            @can('view-github-integration')
            <a href="{{ route('github.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-bold shadow-sm hover:bg-gray-800">تكامل GitHub</a>
            @endcan
        @endif
    </div>

    @if(!empty($showGit) && auth()->user()->can('view-dev-workflow') && !($githubConfigured ?? false))
    <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 flex flex-wrap items-center gap-2">
        <strong>GitHub غير مربوط.</strong>
        @can('manage-github-integration')
        <a href="{{ route('github.index') }}" class="font-bold underline">اربط من صفحة تكامل GitHub</a>
        @endcan
    </div>
    @endif

    {{-- Dynamic stats from track --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 xl:grid-cols-8 gap-3 mb-6">
        @foreach($statCards as $card)
        <div class="bg-white rounded-2xl border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <p class="text-[10px] text-gray-500 font-medium">{{ $card['label'] }}</p>
            <p class="text-xl font-bold mt-0.5" style="color: {{ $statColors[$card['key']] ?? $themeColor }};">{{ $card['value'] }}</p>
            <p class="text-[9px] text-gray-400 mt-0.5 truncate">{{ $card['sub'] }}</p>
        </div>
        @endforeach
    </div>

    @if(!empty($showGit) && !empty($devStats))
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @foreach([
            ['مهام Git', $devStats['assigned'] ?? 0, 'تنفيذ'],
            ['فروع', $devStats['active_branches'] ?? 0, 'نشطة'],
            ['PR مفتوحة', $devStats['open_prs'] ?? 0, 'مراجعة'],
            ['موافق', $devStats['approved_prs'] ?? 0, 'دمج'],
            ['QA', $devStats['in_review'] ?? 0, 'اختبار'],
            ['مُدمجة', $devStats['merged_this_week'] ?? 0, 'هذا الأسبوع'],
        ] as [$label, $val, $sub])
        <div class="bg-gray-900 rounded-2xl p-3 text-white shadow-sm">
            <p class="text-[10px] text-gray-400">{{ $label }}</p>
            <p class="text-xl font-bold">{{ $val }}</p>
            <p class="text-[9px] text-gray-500">{{ $sub }}</p>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Capacity board --}}
    @if(in_array('capacity', $widgets ?? [], true) && ($capacity ?? collect())->isNotEmpty())
    <div class="mb-6 bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-sm text-gray-900">لوحة الطاقة الاستيعابية</h3>
                <p class="text-[10px] text-gray-500">حمل الفريق · مهام مفتوحة · متأخرة</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-right px-4 py-2 text-[10px] font-bold text-gray-500">الموظف</th>
                        <th class="text-right px-3 py-2 text-[10px] font-bold text-gray-500">القسم</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">مفتوحة</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">تنفيذ</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">مراجعة</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">متأخرة</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">ساعات</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">الأسبوع</th>
                        <th class="text-center px-3 py-2 text-[10px] font-bold text-gray-500">الحمل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($capacity as $row)
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
                    <tr class="hover:bg-gray-50/80">
                        <td class="px-4 py-2.5 text-xs font-semibold text-gray-900">{{ $row['name'] }}</td>
                        <td class="px-3 py-2.5 text-[11px] text-gray-500">{{ $row['department'] ?? '—' }}</td>
                        <td class="px-3 py-2.5 text-center text-xs font-bold">{{ $row['open'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs">{{ $row['in_progress'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs">{{ $row['review'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs {{ $row['overdue'] > 0 ? 'text-red-600 font-bold' : '' }}">{{ $row['overdue'] }}</td>
                        <td class="px-3 py-2.5 text-center text-xs">{{ $row['estimated_hours'] }}س</td>
                        <td class="px-3 py-2.5 text-center text-xs">{{ $row['week_hours'] }}س</td>
                        <td class="px-3 py-2.5 text-center">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $loadClass }}">{{ $loadLabel }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Workflow path (dynamic labels) --}}
    <div class="mb-6 bg-white border border-gray-200 rounded-2xl p-4 shadow-sm hidden md:block">
        <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold text-gray-600">
            @foreach(\App\Models\Task::workflowStatuses() as $i => $status)
                @if($i > 0)<span class="text-gray-300">→</span>@endif
                <span class="px-2.5 py-1 rounded-lg bg-gray-50 border border-gray-100">{{ \App\Models\Task::statusLabelAr($status, $workflowTrack) }}</span>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 min-w-0">
            @include('workspace.partials.filters')

            @if($viewMode === 'list')
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">المهمة</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">المشروع</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">التخصص</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">الحالة</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">الأولوية</th>
                                <th class="text-right px-4 py-3 text-xs font-bold text-gray-600">التسليم</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="workspace-list-body">
                            @forelse($tasks as $task)
                            <tr class="workspace-list-row hover:bg-gray-50/80" data-search="{{ strtolower($task->task_key.' '.$task->title.' '.($task->specialization ?? '')) }}" data-priority="{{ $task->priority }}">
                                <td class="px-4 py-3">
                                    <p class="text-[11px] font-mono font-bold" style="color: {{ $themeColor }};">{{ $task->task_key }}</p>
                                    <p class="font-semibold text-gray-900 text-xs mt-0.5">{{ $task->title }}</p>
                                    @if($task->has_blocker)<span class="text-[10px] text-red-600">🚧 عائق</span>@endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">{{ $task->project->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    {{ app(\App\Services\WorkflowTrackService::class)->specialtyLabel($task->specialization) }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100">{{ \App\Models\Task::statusLabelAr($task->status, $workflowTrack) }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs capitalize">{{ $task->priority }}</td>
                                <td class="px-4 py-3 text-xs {{ $task->is_overdue ? 'text-red-600 font-bold' : 'text-gray-500' }}">
                                    {{ $task->due_date?->format('Y-m-d') ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('tasks.show', $task) }}" class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">فتح</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="px-4 py-12 text-center text-gray-500 text-sm">لا مهام — تحقق من الفلاتر أو انتظر تعيين مهام</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="overflow-x-auto pb-4 -mx-1 px-1" id="workspace-kanban">
                <div class="flex gap-3 min-w-max xl:min-w-0 xl:grid xl:grid-cols-7 xl:gap-2">
                    @foreach(\App\Models\Task::workflowStatuses() as $status)
                        @php
                            $styles = \App\Models\Task::columnStyles()[$status];
                            $columnTasks = $board[$status] ?? collect();
                        @endphp
                        <div class="workspace-column w-[260px] xl:w-auto flex-shrink-0 flex flex-col max-h-[calc(100vh-280px)] min-h-[380px]" data-status="{{ $status }}">
                            <div class="rounded-t-xl px-3 py-2 {{ $styles['header'] }} flex items-center justify-between sticky top-0 z-10 border border-b-0 border-gray-200/50">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="w-2 h-2 rounded-full {{ $styles['dot'] }} shrink-0"></span>
                                    <span class="text-xs font-bold truncate">{{ \App\Models\Task::statusLabelAr($status, $workflowTrack) }}</span>
                                </div>
                                <span class="text-[10px] font-bold bg-white/70 rounded-full px-1.5 py-0.5 column-count shrink-0">{{ $columnTasks->count() }}</span>
                            </div>
                            <div class="workspace-dropzone flex-1 bg-white border border-gray-200 rounded-b-xl p-2 space-y-2 overflow-y-auto transition-colors min-h-[120px]"
                                 data-status="{{ $status }}">
                                @forelse($columnTasks as $task)
                                    @include('workspace.partials.task-card', ['task' => $task, 'teamView' => $teamView, 'themeColor' => $themeColor, 'workflowTrack' => $workflowTrack])
                                @empty
                                    <div class="empty-state text-center py-6 text-gray-300 text-[11px]">
                                        <p>فارغ</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="xl:col-span-4">
            @include('workspace.partials.sidebar')
        </div>
    </div>

    <p id="workspace-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden px-5 py-2.5 rounded-xl text-sm font-bold shadow-xl"></p>
</div>

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    let draggedCard = null;

    document.querySelectorAll('.workspace-task-card').forEach(card => {
        card.addEventListener('dragstart', e => {
            draggedCard = card;
            e.dataTransfer.effectAllowed = 'move';
            card.classList.add('opacity-50', 'ring-2', 'ring-blue-300');
        });
        card.addEventListener('dragend', () => {
            card.classList.remove('opacity-50', 'ring-2', 'ring-blue-300');
            draggedCard = null;
            document.querySelectorAll('.workspace-dropzone').forEach(z => z.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50/50'));
        });
    });

    document.querySelectorAll('.workspace-dropzone').forEach(zone => {
        zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('ring-2', 'ring-blue-400', 'bg-blue-50/50'); });
        zone.addEventListener('dragleave', e => {
            if (!zone.contains(e.relatedTarget)) zone.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50/50');
        });
        zone.addEventListener('drop', async e => {
            e.preventDefault();
            zone.classList.remove('ring-2', 'ring-blue-400', 'bg-blue-50/50');
            if (!draggedCard) return;
            const newStatus = zone.dataset.status;
            const oldStatus = draggedCard.dataset.status;
            const taskId = draggedCard.dataset.taskId;
            if (newStatus === oldStatus) return;
            const empty = zone.querySelector('.empty-state');
            if (empty) empty.remove();
            zone.prepend(draggedCard);
            draggedCard.dataset.status = newStatus;
            updateColumnCounts();
            try {
                const res = await fetch(`{{ url('workspace/tasks') }}/${taskId}/status`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify({ status: newStatus }),
                });
                const data = await res.json();
                if (data.approval_required) {
                    showToast(data.message || 'بانتظار موافقة المدير', 'success');
                    setTimeout(() => location.reload(), 1200);
                    return;
                }
                if (!res.ok) throw new Error(data.message || 'فشل التحديث');
                showToast(data.message, 'success');
            } catch (err) {
                showToast(err.message || 'تعذر تحديث الحالة', 'error');
                setTimeout(() => location.reload(), 1800);
            }
        });
    });

    function updateColumnCounts() {
        document.querySelectorAll('.workspace-column').forEach(col => {
            const count = col.querySelectorAll('.workspace-task-card:not([style*="display: none"])').length;
            const badge = col.querySelector('.column-count');
            if (badge) badge.textContent = count;
            const zone = col.querySelector('.workspace-dropzone');
            const visible = col.querySelectorAll('.workspace-task-card:not([style*="display: none"])').length;
            if (visible === 0 && zone && !zone.querySelector('.empty-state')) {
                zone.innerHTML = '<div class="empty-state text-center py-6 text-gray-300 text-[11px]"><p>فارغ</p></div>';
            }
        });
    }

    const searchInput = document.getElementById('workspace-search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('.workspace-task-card').forEach(card => {
                const match = !q || (card.dataset.search || '').includes(q);
                card.style.display = match ? '' : 'none';
            });
            document.querySelectorAll('.workspace-list-row').forEach(row => {
                const match = !q || (row.dataset.search || '').includes(q);
                row.style.display = match ? '' : 'none';
            });
            updateColumnCounts();
        });
    }

    function showToast(msg, type) {
        const el = document.getElementById('workspace-toast');
        if (!el) return;
        el.textContent = msg;
        el.className = 'fixed bottom-6 left-1/2 -translate-x-1/2 z-50 px-5 py-2.5 rounded-xl text-sm font-bold shadow-xl ' +
            (type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white');
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 2800);
    }
})();
</script>
@endpush
@endsection
