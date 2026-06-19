<div class="space-y-5">
    {{-- Daily report --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-gray-900">التقرير اليومي</h3>
            @if($todayReport)
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">مُسجّل ✓</span>
            @else
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">مطلوب</span>
            @endif
        </div>
        <div class="p-4">
            @if($todayReport)
                <p class="text-xs text-gray-600 line-clamp-2">{{ $todayReport->work_summary }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $todayReport->hours_worked }} ساعة اليوم</p>
            @else
                <p class="text-xs text-gray-500 mb-3">لم تُسجّل ساعات عملك اليوم بعد.</p>
            @endif
            <a href="{{ route('daily-reports.index') }}" class="block w-full text-center py-2 rounded-xl text-white text-xs font-bold" style="background: {{ $themeColor }};">
                {{ $todayReport ? 'عرض التقارير' : 'اكتب تقرير اليوم' }}
            </a>
            <p class="text-[10px] text-gray-400 mt-2 text-center">هذا الأسبوع: <strong class="text-gray-700">{{ $weekHours }}س</strong></p>
        </div>
    </div>

    {{-- Focus --}}
    @if($focusTasks->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm text-gray-900">🎯 تركيز اليوم</h3>
            <p class="text-[10px] text-gray-500">عاجلة أو تستحق خلال 3 أيام</p>
        </div>
        <ul class="divide-y divide-gray-50">
            @foreach($focusTasks as $ft)
            <li class="px-4 py-3 hover:bg-gray-50/80 transition">
                <a href="{{ route('tasks.show', $ft) }}" class="block">
                    <p class="text-[10px] font-mono font-bold" style="color: {{ $themeColor }};">{{ $ft->task_key }}</p>
                    <p class="text-xs font-semibold text-gray-900 mt-0.5 line-clamp-2">{{ $ft->title }}</p>
                    <p class="text-[10px] text-gray-500 mt-1">{{ $ft->project->name ?? '' }} · {{ \App\Models\Task::statusLabelAr($ft->status) }}</p>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Blockers --}}
    @if($blockerTasks->isNotEmpty())
    <div class="bg-white rounded-2xl border border-red-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-red-100 bg-red-50/50">
            <h3 class="font-bold text-sm text-red-800">🚧 عوائق ({{ $stats['blockers'] }})</h3>
        </div>
        <ul class="divide-y divide-red-50">
            @foreach($blockerTasks as $bt)
            <li class="px-4 py-3">
                <a href="{{ route('tasks.show', $bt) }}" class="text-xs font-semibold text-gray-900 hover:underline">{{ $bt->title }}</a>
                @if($bt->blocker_description)
                <p class="text-[10px] text-red-600 mt-1 line-clamp-2">{{ $bt->blocker_description }}</p>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @can('view-dev-workflow')
    @can('manage-github-integration')
    @include('dev-workflow.partials.git-access-requests', [
        'accessRequests' => $accessRequests,
        'pendingCount' => $pendingAccessCount,
        'themeColor' => $themeColor,
        'compact' => true,
    ])
    @else
    @include('dev-workflow.partials.git-identity-form', ['themeColor' => $themeColor])
    @endcan

    {{-- Active branches --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-sm">فروعي النشطة</h3>
            <a href="{{ route('dev-workflow.index') }}" class="text-[10px] font-bold hover:underline" style="color: {{ $themeColor }};">الكل</a>
        </div>
        <ul class="divide-y divide-gray-50 text-xs">
            @forelse($myBranches as $branch)
            <li class="px-4 py-3">
                <code class="text-[10px] font-bold text-gray-800 block truncate" dir="ltr">{{ $branch->name }}</code>
                <p class="text-[10px] text-gray-500 mt-1">{{ $branch->repository?->project?->name }}</p>
                @if($branch->task)
                <a href="{{ route('tasks.show', $branch->task) }}" class="text-[10px] hover:underline" style="color: {{ $themeColor }};">{{ Str::limit($branch->task->title, 30) }}</a>
                @endif
            </li>
            @empty
            <li class="px-4 py-6 text-center text-gray-400 text-[11px]">لا فروع — أنشئ فرعاً من صفحة المهمة</li>
            @endforelse
        </ul>
    </div>

    {{-- My PRs --}}
    @if($myPullRequests->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">Pull Requests الخاصة بي</h3>
        </div>
        <ul class="divide-y divide-gray-50">
            @foreach($myPullRequests as $pr)
            <li class="px-4 py-3">
                <a href="{{ route('dev-workflow.pull-requests.show', $pr) }}" class="text-xs font-semibold hover:underline line-clamp-2" style="color: {{ $themeColor }};">{{ $pr->title }}</a>
                <span class="text-[10px] mt-1 inline-block px-1.5 py-0.5 rounded {{ $pr->statusBadgeClass() }}">{{ $pr->statusLabelAr() }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @can('review-code')
    @if($prsToReview->isNotEmpty())
    <div class="bg-white rounded-2xl border border-amber-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-amber-100 bg-amber-50/50">
            <h3 class="font-bold text-sm text-amber-900">مراجعة مطلوبة</h3>
        </div>
        <ul class="divide-y divide-amber-50">
            @foreach($prsToReview as $pr)
            <li class="px-4 py-3">
                <a href="{{ route('dev-workflow.pull-requests.show', $pr) }}" class="text-xs font-semibold text-gray-900 hover:underline">{{ Str::limit($pr->title, 40) }}</a>
                <p class="text-[10px] text-gray-500 mt-1">{{ $pr->author?->name }}</p>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
    @endcan

    {{-- Repos --}}
    @if($projectRepos->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">مستودعات المشاريع</h3>
        </div>
        <ul class="divide-y divide-gray-50">
            @foreach($projectRepos as $repo)
            <li class="px-4 py-3">
                <p class="text-xs font-bold text-gray-900">{{ $repo->project?->name }}</p>
                <p class="text-[10px] font-mono text-gray-600 mt-0.5" dir="ltr">{{ $repo->fullName() }}</p>
                <button type="button" onclick="navigator.clipboard.writeText('git clone {{ $repo->cloneUrl() }}');this.textContent='تم النسخ!';"
                        class="mt-2 text-[10px] font-bold px-2 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
                    نسخ git clone
                </button>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
    @endcan

    {{-- Activity --}}
    @if($recentActivity->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="font-bold text-sm">آخر النشاط</h3>
        </div>
        <ul class="divide-y divide-gray-50 max-h-64 overflow-y-auto">
            @foreach($recentActivity as $act)
            <li class="px-4 py-2.5">
                <p class="text-[11px] text-gray-800 line-clamp-2">{{ $act->comment }}</p>
                <p class="text-[10px] text-gray-400 mt-0.5">{{ $act->user?->name }} · {{ $act->created_at?->diffForHumans() }}</p>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Quick links --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
        <h3 class="font-bold text-sm text-gray-900 mb-3">اختصارات</h3>
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('tasks.index') }}" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">المهام</a>
            <a href="{{ route('projects.index') }}" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">المشاريع</a>
            @can('view-dev-workflow')
            <a href="{{ route('dev-workflow.index') }}" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">Git / PR</a>
            @endcan
            <a href="{{ route('messages.index') }}" class="text-center py-2.5 rounded-xl bg-gray-50 hover:bg-gray-100 text-[11px] font-bold text-gray-700">الرسائل</a>
        </div>
    </div>
</div>
