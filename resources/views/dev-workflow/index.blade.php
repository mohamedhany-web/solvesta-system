@extends('layouts.app')

@section('page-title', 'بيئة التطوير')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $githubConfigured = \App\Services\GitHubSettings::isConfigured();
@endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'بيئة التطوير',
        'subtitle' => 'المهام → الفروع → Pull Request → مراجعة → QA → نشر',
        'icon' => 'briefcase',
    ])

    @if(!$githubConfigured)
    <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        <strong>GitHub غير مربوط.</strong>
        @can('manage-github-integration')
        <a href="{{ route('github.index') }}" class="font-bold underline mr-1">اربط GitHub من الإعدادات</a>
        @else
        اطلب من المسؤول ربط GitHub من صفحة تكامل GitHub.
        @endcan
    </div>
    @endif

    @can('manage-github-integration')
    @include('dev-workflow.partials.git-access-requests', [
        'accessRequests' => $accessRequests,
        'pendingCount' => $pendingAccessCount,
        'themeColor' => $themeColor,
    ])
    @else
    @include('dev-workflow.partials.git-identity-form', ['themeColor' => $themeColor])
    @endcan

    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 mb-8">
        @foreach([
            ['مهام نشطة', $stats['assigned'], $themeColor, 'استلام وتنفيذ'],
            ['فروع نشطة', $stats['active_branches'], '#2563eb', 'feature/* bugfix/*'],
            ['PR مفتوحة', $stats['open_prs'], '#d97706', 'بانتظار المراجعة'],
            ['موافق عليها', $stats['approved_prs'], '#7c3aed', 'جاهزة للدمج'],
            ['قيد QA', $stats['in_review'], '#0891b2', 'مهام review'],
            ['مُدمجة هذا الأسبوع', $stats['merged_this_week'], '#059669', 'main'],
        ] as [$label, $val, $color, $sub])
        <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
            <p class="text-[10px] text-gray-500">{{ $label }}</p>
            <p class="text-2xl font-bold mt-0.5" style="color: {{ $color }};">{{ $val }}</p>
            <p class="text-[10px] text-gray-400 mt-1">{{ $sub }}</p>
        </div>
        @endforeach
    </div>

    <div class="mb-8 bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
        <h2 class="font-bold text-gray-900 mb-4 text-sm">مسار العمل الاحترافي</h2>
        <div class="flex flex-wrap items-center gap-2 text-xs">
            @foreach(['Task من PM/Lead', 'فرع Git', 'Commits', 'Pull Request', 'Code Review', 'QA', 'Deploy'] as $i => $step)
            <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 font-semibold">{{ $step }}</span>
            @if(!$loop->last)<span class="text-gray-300">→</span>@endif
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-7">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="font-bold">مراجعة الكود — PRs بانتظار الإجراء</h2>
                    @can('review-code')
                    <span class="text-xs text-gray-500">{{ $pendingReviews->count() }} معلّقة</span>
                    @endcan
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($pendingReviews as $pr)
                    <div class="px-5 py-4 hover:bg-blue-50/30 transition">
                        <div class="flex justify-between gap-3 items-start">
                            <div class="min-w-0">
                                <a href="{{ route('dev-workflow.pull-requests.show', $pr) }}" class="font-bold text-sm hover:underline" style="color: {{ $themeColor }};">
                                    {{ $pr->title }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $pr->repository?->project?->name }} · {{ $pr->source_branch }} → {{ $pr->target_branch }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">بواسطة {{ $pr->author?->name }} · {{ $pr->created_at?->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-bold px-2 py-1 rounded-full {{ $pr->statusBadgeClass() }} shrink-0">{{ $pr->statusLabelAr() }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="px-5 py-10 text-center text-gray-500 text-sm">لا توجد Pull Requests بانتظار المراجعة.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="xl:col-span-5 space-y-6">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-sm">فروع نشطة</h2>
                </div>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse($recentBranches as $branch)
                    <li class="px-5 py-3">
                        <code class="text-xs font-bold text-gray-800" dir="ltr">{{ $branch->name }}</code>
                        <p class="text-xs text-gray-500 mt-1">{{ $branch->repository?->project?->name }} · {{ $branch->creator?->name }}</p>
                        @if($branch->task)
                        <a href="{{ route('tasks.show', $branch->task) }}" class="text-xs hover:underline" style="color: {{ $themeColor }};">{{ Str::limit($branch->task->title, 40) }}</a>
                        @endif
                    </li>
                    @empty
                    <li class="px-5 py-8 text-center text-gray-500 text-xs">لا توجد فروع نشطة.</li>
                    @endforelse
                </ul>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h2 class="font-bold text-sm">آخر PRs مُدمجة</h2>
                </div>
                <ul class="divide-y divide-gray-100 text-sm">
                    @forelse($recentMerged as $pr)
                    <li class="px-5 py-3 flex justify-between gap-2">
                        <span class="truncate">{{ $pr->title }}</span>
                        <span class="text-xs text-gray-400 shrink-0">{{ $pr->merged_at?->format('m/d') }}</span>
                    </li>
                    @empty
                    <li class="px-5 py-8 text-center text-gray-500 text-xs">لا دمج حديث.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
