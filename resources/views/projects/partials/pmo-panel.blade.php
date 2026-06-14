@include('partials.erp-page-header', [
    'title' => 'PMO — '.$project->name,
    'subtitle' => 'Milestones · توزيع العمل · متابعة التقدم',
    'icon' => 'briefcase',
])
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp

<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-5">
        <h2 class="text-lg font-bold font-tajawal">مراحل المشروع (Milestones)</h2>
        @can('edit-projects')
            @if($project->milestones->isEmpty())
            <form method="POST" action="{{ route('pmo.projects.seed-milestones', $project) }}">@csrf
                <button class="px-4 py-2 rounded-xl text-white text-sm font-bold"
                        style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                    إنشاء المراحل الافتراضية (4 Phases)
                </button>
            </form>
            @endif
        @endcan
    </div>

    @if($project->milestones->isEmpty())
        <p class="text-sm text-gray-500 font-tajawal">لم تُنشأ مراحل بعد. ابدأ بقالب Phase 1–4 (UI/UX → Backend → Frontend → Testing).</p>
    @else
        <div class="space-y-4">
            @foreach($project->milestones as $milestone)
            <div class="border rounded-xl p-4 {{ $milestone->is_overdue ? 'border-red-200 bg-red-50/30' : 'border-gray-200' }}">
                <div class="flex flex-wrap justify-between gap-2 mb-3">
                    <div>
                        <h3 class="font-bold text-gray-900">{{ $milestone->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $milestone->phase_label }} · {{ $milestone->status_label }}
                            @if($milestone->due_date) · حتى {{ $milestone->due_date->format('Y/m/d') }}@endif
                        </p>
                    </div>
                    <div class="text-left">
                        <span class="text-lg font-bold" style="color:{{ $themeColor }};">{{ $milestone->progress_percentage }}%</span>
                    </div>
                </div>
                <div class="h-2 bg-gray-100 rounded-full mb-3">
                    <div class="h-full rounded-full transition-all" style="width:{{ $milestone->progress_percentage }}%; background:{{ $themeColor }};"></div>
                </div>

                @if($milestone->tasks->count())
                <ul class="text-sm space-y-2 mb-3 font-tajawal">
                    @foreach($milestone->tasks as $task)
                    <li class="flex flex-wrap justify-between gap-2 py-2 px-3 bg-gray-50 rounded-lg">
                        <span>
                            {{ $task->title }}
                            @if($task->has_blocker)<span class="text-red-600 text-xs font-bold">⛔ Blocker</span>@endif
                        </span>
                        <span class="text-xs text-gray-500">{{ $task->assignedTo?->name }} · {{ $task->estimated_hours }}س</span>
                    </li>
                    @endforeach
                </ul>
                @endif

                @can('edit-projects')
                <details class="text-sm">
                    <summary class="cursor-pointer font-bold text-blue-600 mb-2">+ توزيع مهمة على هذه المرحلة</summary>
                    <form method="POST" action="{{ route('pmo.projects.assign-task', $project) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2 p-3 bg-gray-50 rounded-xl">
                        @csrf
                        <input type="hidden" name="milestone_id" value="{{ $milestone->id }}">
                        <input name="title" required placeholder="عنوان المهمة" class="border rounded-lg px-3 py-2 md:col-span-2">
                        <select name="assigned_to" required class="border rounded-lg px-3 py-2">
                            <option value="">اختر الموظف</option>
                            @foreach($teamUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        <select name="specialization" class="border rounded-lg px-3 py-2">
                            <option value="backend">Backend</option>
                            <option value="frontend">Frontend</option>
                            <option value="ui_ux">UI/UX</option>
                            <option value="qa">QA</option>
                            <option value="design">Design</option>
                        </select>
                        <input type="number" step="0.5" name="estimated_hours" placeholder="ساعات تقديرية" class="border rounded-lg px-3 py-2">
                        <input type="date" name="due_date" class="border rounded-lg px-3 py-2">
                        <button class="md:col-span-2 py-2 rounded-lg text-white font-bold" style="background:{{ $themeColor }};">توزيع المهمة</button>
                    </form>
                </details>
                @endcan
            </div>
            @endforeach
        </div>
    @endif
</div>

@if(($stats['blockers_count'] ?? 0) > 0)
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 text-sm font-tajawal">
    <strong class="text-red-800">⚠ {{ $stats['blockers_count'] }} Blocker(s) مفتوحة</strong> في هذا المشروع — راجع لوحة PMO أو حلّها من المهام.
</div>
@endif
