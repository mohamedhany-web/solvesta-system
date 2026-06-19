@php
    $filterParams = array_filter([
        'scope' => $teamView ? 'team' : null,
        'project_id' => $projectId,
        'milestone_id' => $milestoneId,
        'view' => $viewMode,
        'priority' => $priorityFilter,
    ]);
@endphp
<div class="bg-white rounded-2xl border border-gray-200 p-4 mb-5 shadow-sm">
    <div class="flex flex-col lg:flex-row lg:items-end gap-4">
        <form method="GET" action="{{ route('workspace.index') }}" class="flex flex-wrap flex-1 gap-3 items-end">
            @if($teamView)<input type="hidden" name="scope" value="team">@endif
            @if($viewMode)<input type="hidden" name="view" value="{{ $viewMode }}">@endif
            <div class="min-w-[140px] flex-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">المشروع</label>
                <select name="project_id" class="w-full rounded-xl border-gray-300 text-sm" onchange="this.form.submit()">
                    <option value="">كل المشاريع</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @selected($projectId == $project->id)>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            @if($epics->isNotEmpty())
            <div class="min-w-[140px] flex-1">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Epic</label>
                <select name="milestone_id" class="w-full rounded-xl border-gray-300 text-sm" onchange="this.form.submit()">
                    <option value="">كل المراحل</option>
                    @foreach($epics as $epic)
                        <option value="{{ $epic->id }}" @selected($milestoneId == $epic->id)>{{ $epic->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="min-w-[120px]">
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">الأولوية</label>
                <select name="priority" class="w-full rounded-xl border-gray-300 text-sm" onchange="this.form.submit()">
                    <option value="">الكل</option>
                    @foreach(['urgent' => 'Critical', 'high' => 'High', 'medium' => 'Medium', 'low' => 'Low'] as $val => $lbl)
                        <option value="{{ $val }}" @selected($priorityFilter === $val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            @if($projectId || $milestoneId || $priorityFilter)
            <a href="{{ route('workspace.index', $teamView ? ['scope' => 'team', 'view' => $viewMode] : ['view' => $viewMode]) }}" class="text-xs text-gray-500 hover:text-gray-800 py-2 px-2">مسح</a>
            @endif
        </form>

        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <div class="relative flex-1 min-w-[180px] lg:min-w-[220px]">
                <input type="search" id="workspace-search" placeholder="بحث في المهام..."
                       class="w-full rounded-xl border-gray-300 text-sm pl-9 py-2">
                <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <div class="flex rounded-xl border border-gray-200 overflow-hidden text-xs font-bold">
                <a href="{{ route('workspace.index', array_merge($filterParams, ['view' => 'kanban'])) }}"
                   class="px-3 py-2 {{ $viewMode === 'kanban' ? 'text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}"
                   @if($viewMode === 'kanban') style="background: {{ $themeColor }};" @endif>Kanban</a>
                <a href="{{ route('workspace.index', array_merge($filterParams, ['view' => 'list'])) }}"
                   class="px-3 py-2 {{ $viewMode === 'list' ? 'text-white' : 'bg-white text-gray-600 hover:bg-gray-50' }}"
                   @if($viewMode === 'list') style="background: {{ $themeColor }};" @endif>قائمة</a>
            </div>

            @can('view-all-tasks')
            <div class="flex rounded-xl border border-gray-200 overflow-hidden text-xs font-bold">
                <a href="{{ route('workspace.index', array_filter(['project_id' => $projectId, 'milestone_id' => $milestoneId, 'view' => $viewMode, 'priority' => $priorityFilter])) }}"
                   class="px-3 py-2 {{ !$teamView ? 'text-white' : 'bg-white text-gray-600' }}"
                   @if(!$teamView) style="background: {{ $themeColor }};" @endif>مهامي</a>
                <a href="{{ route('workspace.index', array_filter(['scope' => 'team', 'project_id' => $projectId, 'milestone_id' => $milestoneId, 'view' => $viewMode, 'priority' => $priorityFilter])) }}"
                   class="px-3 py-2 {{ $teamView ? 'text-white' : 'bg-white text-gray-600' }}"
                   @if($teamView) style="background: {{ $themeColor }};" @endif>الفريق</a>
            </div>
            @endcan
        </div>
    </div>
</div>
