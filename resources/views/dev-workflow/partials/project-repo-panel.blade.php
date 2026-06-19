@php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $repository = $project->activeRepository();
@endphp
@if(auth()->user()->can('manage-project-repos') || $repository)
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between">
        <h3 class="font-bold text-gray-900 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
            مستودع GitHub
        </h3>
        <a href="{{ route('dev-workflow.index') }}" class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">بيئة التطوير</a>
    </div>
    <div class="p-5">
        @if($repository)
        <div class="rounded-xl border border-gray-200 p-4 mb-4 bg-gray-50/50">
            <p class="font-bold text-sm" dir="ltr">{{ $repository->fullName() }}</p>
            <p class="text-xs text-gray-500 mt-1">الفرع الافتراضي: <code>{{ $repository->default_branch }}</code></p>
            <div class="mt-3 flex flex-wrap gap-2">
                <a href="{{ $repository->webUrl() }}" target="_blank" rel="noopener" class="text-xs font-bold hover:underline" style="color: {{ $themeColor }};">GitHub</a>
                <span class="text-gray-300">·</span>
                <code class="text-xs text-gray-600" dir="ltr">git clone {{ $repository->cloneUrl() }}</code>
            </div>
        </div>
        @endif

        @can('manage-project-repos')
        <form method="POST" action="{{ route('dev-workflow.repositories.store', $project) }}" class="space-y-3">
            @csrf
            <p class="text-xs text-gray-600">{{ $repository ? 'تحديث الربط' : 'اربط المشروع بمستودع GitHub' }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input name="owner" value="{{ old('owner', $repository?->owner) }}" placeholder="owner / org" required
                       class="border border-gray-300 rounded-xl px-3 py-2 text-sm" dir="ltr">
                <input name="repo_name" value="{{ old('repo_name', $repository?->repo_name) }}" placeholder="repo-name" required
                       class="border border-gray-300 rounded-xl px-3 py-2 text-sm" dir="ltr">
            </div>
            <input name="default_branch" value="{{ old('default_branch', $repository?->default_branch ?? 'main') }}" placeholder="main"
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm" dir="ltr">
            <button type="submit" class="w-full py-2.5 rounded-xl text-white text-sm font-bold" style="background: {{ $themeColor }};">
                {{ $repository ? 'تحديث المستودع' : 'ربط المستودع' }}
            </button>
        </form>
        @endcan
    </div>
</div>
@endif
