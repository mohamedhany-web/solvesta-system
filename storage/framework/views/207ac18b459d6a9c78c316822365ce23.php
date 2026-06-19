

<?php $__env->startSection('page-title', 'تكامل GitHub'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $login = \App\Services\GitHubSettings::connectedLogin();
    $avatar = \App\Services\GitHubSettings::connectedAvatar();
    $connectedAt = \App\Services\GitHubSettings::connectedAt();
    $repos = $dashboard['repos'] ?? [];
    $teams = $dashboard['teams'] ?? [];
    $members = $dashboard['members'] ?? [];
    $pulls = $dashboard['pull_requests'] ?? [];
    $stats = $dashboard['stats'] ?? [];
    $orgInfo = $dashboard['organization'] ?? $dashboard['profile'] ?? null;
    $isPersonal = ($accountType ?? '') === 'personal' || ($dashboard['account_type'] ?? '') === 'personal';
    $rateLimit = $dashboard['rate_limit'] ?? null;
    $syncedAt = $dashboard['synced_at'] ?? null;
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'تكامل GitHub',
        'subtitle' => 'عدة حسابات GitHub — وزّعها على المشاريع والمستودعات كما تريد',
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap gap-2 mb-6 -mt-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-dev-workflow')): ?>
        <a href="<?php echo e(route('dev-workflow.index')); ?>" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">بيئة التطوير</a>
        <?php endif; ?>
        <a href="<?php echo e(route('workspace.index')); ?>" class="px-4 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700 hover:bg-gray-50 shadow-sm">مساحة عملي</a>
        <?php if($connected && $activeAccount): ?>
        <form method="POST" action="<?php echo e(route('github.refresh')); ?>"><?php echo csrf_field(); ?>
            <input type="hidden" name="account" value="<?php echo e($activeAccount->id); ?>">
            <?php if($selectedRepo): ?><input type="hidden" name="repo" value="<?php echo e($selectedRepo); ?>"><?php endif; ?>
            <button type="submit" class="px-4 py-2 rounded-xl text-white text-sm font-bold shadow-sm" style="background: <?php echo e($themeColor); ?>;">تحديث الحساب النشط</button>
        </form>
        <?php endif; ?>
    </div>

    <?php if($connected && !empty($cacheMiss)): ?>
    <div class="mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-900">
        <strong>البيانات غير محمّلة بعد.</strong> اضغط «تحديث من GitHub» لجلب المستودعات وPRs — الصفحة لن تتوقف أثناء التحميل.
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        
        <div class="xl:col-span-4 space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/80 flex items-center justify-between gap-2">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                        حسابات GitHub
                    </h2>
                    <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-lg"><?php echo e($accounts->count()); ?></span>
                </div>
                <div class="p-4 space-y-2 max-h-[42vh] overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $isActive = $activeAccount && $activeAccount->id === $acc->id; ?>
                    <div class="rounded-xl border-2 p-3 transition <?php echo e($isActive ? 'border-blue-400 bg-blue-50/40' : 'border-gray-100 hover:border-gray-200'); ?>">
                        <div class="flex items-start gap-3">
                            <?php if($acc->avatar_url): ?><img src="<?php echo e($acc->avatar_url); ?>" alt="" class="w-10 h-10 rounded-full border border-white shadow shrink-0"><?php endif; ?>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <a href="<?php echo e(route('github.index', ['account' => $acc->id, 'tab' => $tab, 'repo' => $selectedRepo])); ?>"
                                       class="font-bold text-sm text-gray-900 hover:underline truncate"><?php echo e($acc->displayLabel()); ?></a>
                                    <?php if($acc->is_default): ?><span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800">افتراضي</span><?php endif; ?>
                                    <?php if($isActive): ?><span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-green-100 text-green-800">نشط</span><?php endif; ?>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-0.5 font-mono" dir="ltr">{{ $acc->login }}</p>
                                <p class="text-[10px] text-gray-400"><?php echo e($acc->accountTypeLabel()); ?><?php if(!$acc->isPersonal()): ?> · <?php echo e($acc->organization); ?><?php endif; ?></p>
                            </div>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-github-integration')): ?>
                        <div class="flex flex-wrap gap-1.5 mt-2 pt-2 border-t border-gray-100/80">
                            <?php if(!$acc->is_default): ?>
                            <form method="POST" action="<?php echo e(route('github.accounts.default', $acc)); ?>"><?php echo csrf_field(); ?>
                                <button type="submit" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-200 hover:bg-white">افتراضي</button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="<?php echo e(route('github.accounts.test', $acc)); ?>"><?php echo csrf_field(); ?>
                                <button type="submit" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-gray-200 hover:bg-white">اختبار</button>
                            </form>
                            <form method="POST" action="<?php echo e(route('github.accounts.destroy', $acc)); ?>" onsubmit="return confirm('حذف حساب <?php echo e($acc->displayLabel()); ?>؟')"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-[10px] font-bold px-2 py-1 rounded-lg border border-red-200 text-red-700 hover:bg-red-50">حذف</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-500 text-center py-4">لا حسابات مربوطة بعد.</p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-github-integration')): ?>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/80">
                    <h3 class="font-bold text-sm text-gray-900">إضافة حساب GitHub</h3>
                </div>
                <div class="p-5">
                    <p class="text-xs text-gray-600 mb-4">أضف حساباً شخصياً أو Organization — يمكنك ربط كل مستودع بالحساب المناسب له.</p>
                    <form method="POST" action="<?php echo e(route('github.accounts.store')); ?>" class="space-y-3" id="github-connect-form">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">اسم تعريفي (اختياري)</label>
                            <input name="label" value="<?php echo e(old('label')); ?>" placeholder="مثال: حساب العميل أ / فريق الموبايل"
                                   class="w-full rounded-xl border-gray-300 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-2">نوع الحساب</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border-2 cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 border-gray-200">
                                    <input type="radio" name="account_type" value="personal" class="text-blue-600" <?php echo e(old('account_type', 'personal') === 'personal' ? 'checked' : ''); ?> onchange="toggleOrgField()">
                                    <span class="text-xs font-bold">شخصي</span>
                                </label>
                                <label class="flex items-center gap-2 p-2.5 rounded-xl border-2 cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 border-gray-200">
                                    <input type="radio" name="account_type" value="organization" class="text-blue-600" <?php echo e(old('account_type') === 'organization' ? 'checked' : ''); ?> onchange="toggleOrgField()">
                                    <span class="text-xs font-bold">Organization</span>
                                </label>
                            </div>
                        </div>
                        <div id="org-field" class="<?php echo e(old('account_type', 'personal') === 'organization' ? '' : 'hidden'); ?>">
                            <label class="block text-xs font-bold text-gray-600 mb-1">اسم Organization</label>
                            <input name="organization" value="<?php echo e(old('organization')); ?>" dir="ltr" placeholder="solvesta-org" class="w-full rounded-xl border-gray-300 text-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Personal Access Token</label>
                            <input type="password" name="access_token" required dir="ltr" autocomplete="off" placeholder="ghp_..." class="w-full rounded-xl border-gray-300 text-sm font-mono">
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-xl text-white font-bold text-sm" style="background: <?php echo e($themeColor); ?>;">+ إضافة حساب</button>
                    </form>
                </div>
            </div>
            <?php $__env->startPush('scripts'); ?>
            <script>
            function toggleOrgField() {
                const isOrg = document.querySelector('#github-connect-form input[name="account_type"][value="organization"]')?.checked;
                const field = document.getElementById('org-field');
                const input = field?.querySelector('input[name="organization"]');
                if (field) field.classList.toggle('hidden', !isOrg);
                if (input) { isOrg ? input.setAttribute('required', '') : input.removeAttribute('required'); }
            }
            document.addEventListener('DOMContentLoaded', toggleOrgField);
            </script>
            <?php $__env->stopPush(); ?>
            <?php else: ?>
            <?php if(!$connected): ?>
            <p class="text-sm text-gray-500 bg-white rounded-2xl border border-gray-200 p-5">لم يُربط GitHub بعد. اطلب من المسؤول إضافة حساب.</p>
            <?php endif; ?>
            <?php endif; ?>

            <?php if($connected && $rateLimit): ?>
            <div class="bg-gray-900 rounded-2xl p-4 text-white text-sm">
                <p class="text-gray-400 text-xs mb-1">GitHub API Rate Limit</p>
                <p class="font-bold"><?php echo e($rateLimit['remaining'] ?? '—'); ?> / <?php echo e($rateLimit['limit'] ?? '—'); ?></p>
                <?php if($syncedAt): ?><p class="text-[10px] text-gray-500 mt-2">آخر مزامنة: <?php echo e(\Carbon\Carbon::parse($syncedAt)->diffForHumans()); ?></p><?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if($connected && $orgInfo): ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm">
                <h3 class="font-bold text-sm mb-2"><?php echo e($isPersonal ? ($orgInfo['name'] ?? $orgInfo['login'] ?? 'حسابي') : ($orgInfo['name'] ?? $org)); ?></h3>
                <?php if(!empty($orgInfo['bio']) || !empty($orgInfo['description'])): ?>
                <p class="text-xs text-gray-600"><?php echo e($orgInfo['bio'] ?? $orgInfo['description']); ?></p>
                <?php endif; ?>
                <div class="grid grid-cols-2 gap-2 mt-3 text-center">
                    <div class="bg-gray-50 rounded-xl p-2"><p class="text-lg font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e(count($repos) ?: ($stats['repos'] ?? '—')); ?></p><p class="text-[10px] text-gray-500">مستودعات</p></div>
                    <?php if(!$isPersonal): ?>
                    <div class="bg-gray-50 rounded-xl p-2"><p class="text-lg font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e(count($teams) ?: ($stats['teams'] ?? '—')); ?></p><p class="text-[10px] text-gray-500">فرق</p></div>
                    <div class="bg-gray-50 rounded-xl p-2"><p class="text-lg font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e(count($members) ?: ($stats['members'] ?? '—')); ?></p><p class="text-[10px] text-gray-500">أعضاء</p></div>
                    <?php else: ?>
                    <div class="bg-gray-50 rounded-xl p-2"><p class="text-lg font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e($orgInfo['public_repos'] ?? '—'); ?></p><p class="text-[10px] text-gray-500">عامة على GitHub</p></div>
                    <?php endif; ?>
                    <div class="bg-gray-50 rounded-xl p-2"><p class="text-lg font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e(count($pulls) ?: ($stats['pulls'] ?? '—')); ?></p><p class="text-[10px] text-gray-500">PRs مفتوحة</p></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="xl:col-span-8">
            <?php if(!$connected): ?>
            <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                <h3 class="text-lg font-bold text-gray-700 mb-2">اربط GitHub لعرض البيانات</h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto">المستودعات، الفرق، الأعضاء، Pull Requests والفروع ستظهر هنا مباشرة من Organization.</p>
            </div>
            <?php else: ?>
            <div class="flex flex-wrap gap-1 mb-4 border-b border-gray-200 pb-0">
                <?php $__currentLoopData = array_filter([
                    'repos' => 'المستودعات',
                    'teams' => $isPersonal ? null : 'الفرق',
                    'members' => $isPersonal ? null : 'الأعضاء',
                    'pulls' => 'Pull Requests',
                    'branches' => 'الفروع',
                    'access' => auth()->user()->can('manage-github-integration') ? 'طلبات الوصول' : null,
                ]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('github.index', ['account' => $activeAccount?->id, 'tab' => $key, 'repo' => $selectedRepo])); ?>"
                   class="px-4 py-2.5 text-sm font-bold rounded-t-xl border-b-2 -mb-px transition <?php echo e($tab === $key ? 'border-current text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700'); ?>"
                   <?php if($tab === $key): ?> style="border-color: <?php echo e($themeColor); ?>; color: <?php echo e($themeColor); ?>;" <?php endif; ?>><?php echo e($label); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <?php if($tab === 'repos'): ?>
                <div class="divide-y divide-gray-100 max-h-[70vh] overflow-y-auto" id="github-repos-list">
                    <?php $__empty_1 = true; $__currentLoopData = $repos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $fullName = $repo['full_name'] ?? '';
                        $isLinked = $linkedRepos->has(strtolower($fullName));
                        $isHighlighted = $selectedRepo && strtolower($selectedRepo) === strtolower($fullName);
                    ?>
                    <div id="repo-row-<?php echo e(md5($fullName)); ?>"
                         class="px-5 py-4 hover:bg-gray-50/50 transition <?php echo e($isHighlighted ? 'bg-blue-50/60 ring-2 ring-inset ring-blue-200' : ''); ?>"
                         data-repo-full-name="<?php echo e($fullName); ?>"
                         data-default-branch="<?php echo e($repo['default_branch'] ?? 'main'); ?>"
                         data-is-linked="<?php echo e($isLinked ? '1' : '0'); ?>">
                        <div class="flex flex-wrap justify-between gap-3 items-start">
                            <div class="min-w-0 flex-1">
                                <a href="<?php echo e($repo['html_url'] ?? '#'); ?>" target="_blank" rel="noopener" class="font-bold text-sm hover:underline" style="color: <?php echo e($themeColor); ?>;" dir="ltr"><?php echo e($fullName); ?></a>
                                <?php if(!empty($repo['description'])): ?><p class="text-xs text-gray-500 mt-1 line-clamp-2"><?php echo e($repo['description']); ?></p><?php endif; ?>
                                <div class="flex flex-wrap gap-2 mt-2 text-[10px]">
                                    <?php if($repo['private'] ?? false): ?><span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-bold">Private</span><?php else: ?><span class="px-2 py-0.5 rounded bg-green-100 text-green-800">Public</span><?php endif; ?>
                                    <?php if(!empty($repo['language'])): ?><span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700"><?php echo e($repo['language']); ?></span><?php endif; ?>
                                    <span class="text-gray-400">⭐ <?php echo e($repo['stargazers_count'] ?? 0); ?></span>
                                    <span class="text-gray-400 font-mono">default: <?php echo e($repo['default_branch'] ?? 'main'); ?></span>
                                </div>
                                <?php if($isLinked): ?>
                                <p class="text-[10px] text-green-600 mt-2 font-bold">✓ مربوط بمشروع: <?php echo e($linkedRepos[strtolower($fullName)]->project?->name); ?>

                                    <?php if($linkedRepos[strtolower($fullName)]->githubAccount): ?>
                                    <span class="text-gray-500 font-normal">· عبر <?php echo e($linkedRepos[strtolower($fullName)]->githubAccount->displayLabel()); ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col gap-2 shrink-0">
                                <button type="button" onclick="navigator.clipboard.writeText('git clone <?php echo e($repo['clone_url'] ?? ''); ?>');this.textContent='تم!';"
                                        class="text-[10px] font-bold px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200">نسخ clone</button>
                                <a href="<?php echo e(route('github.index', ['tab' => 'branches', 'repo' => $fullName])); ?>" class="text-[10px] font-bold px-3 py-1.5 rounded-lg border border-gray-200 text-center hover:bg-gray-50">الفروع</a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-project-repos')): ?>
                                <?php if(!$isLinked): ?>
                                <button type="button"
                                        onclick="openLinkRepoModal(<?php echo \Illuminate\Support\Js::from($fullName)->toHtml() ?>, <?php echo \Illuminate\Support\Js::from($repo['default_branch'] ?? 'main')->toHtml() ?>)"
                                        class="text-[10px] font-bold px-3 py-1.5 rounded-lg text-white" style="background: <?php echo e($themeColor); ?>;">ربط بمشروع</button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-5 py-12 text-center text-gray-500 text-sm">لا مستودعات أو لا صلاحية للعرض.</div>
                    <?php endif; ?>
                </div>

                <?php elseif($tab === 'teams'): ?>
                <?php if($isPersonal): ?>
                <div class="px-5 py-12 text-center text-gray-500 text-sm">الفرق متاحة لـ Organization فقط — أنت مربوط بحساب شخصي.</div>
                <?php else: ?>
                <div class="divide-y divide-gray-100 max-h-[70vh] overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-5 py-4 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-sm"><?php echo e($team['name'] ?? '—'); ?></p>
                            <p class="text-xs text-gray-500 mt-0.5"><?php echo e($team['description'] ?? 'بدون وصف'); ?></p>
                            <p class="text-[10px] text-gray-400 mt-1" dir="ltr"><?php echo e($team['slug'] ?? ''); ?> · <?php echo e($team['privacy'] ?? ''); ?></p>
                        </div>
                        <a href="<?php echo e($team['html_url'] ?? '#'); ?>" target="_blank" class="text-xs font-bold hover:underline" style="color: <?php echo e($themeColor); ?>;">GitHub</a>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-5 py-12 text-center text-gray-500 text-sm">لا فرق أو تحتاج صلاحية read:org.</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php elseif($tab === 'members'): ?>
                <?php if($isPersonal): ?>
                <div class="px-5 py-12 text-center text-gray-500 text-sm">قائمة الأعضاء خاصة بـ Organization — حسابك الشخصي يعرض مستودعاتك وPRs.</div>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-0 divide-y sm:divide-y-0 sm:gap-3 sm:p-4 sm:divide-none max-h-[70vh] overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-5 py-3 sm:rounded-xl sm:border sm:border-gray-100 flex items-center gap-3">
                        <img src="<?php echo e($member['avatar_url'] ?? ''); ?>" alt="" class="w-10 h-10 rounded-full">
                        <div>
                            <a href="<?php echo e($member['html_url'] ?? '#'); ?>" target="_blank" class="font-bold text-sm hover:underline" dir="ltr"><?php echo e($member['login'] ?? ''); ?></a>
                            <p class="text-[10px] text-gray-400"><?php echo e($member['type'] ?? 'User'); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-2 px-5 py-12 text-center text-gray-500 text-sm">لا أعضاء.</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php elseif($tab === 'pulls'): ?>
                <div class="divide-y divide-gray-100 max-h-[70vh] overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $pulls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-5 py-4">
                        <a href="<?php echo e($pr['html_url'] ?? '#'); ?>" target="_blank" class="font-bold text-sm hover:underline line-clamp-2" style="color: <?php echo e($themeColor); ?>;"><?php echo e($pr['title'] ?? ''); ?></a>
                        <p class="text-xs text-gray-500 mt-1" dir="ltr"><?php echo e($pr['repository_url'] ? basename(dirname($pr['repository_url'])).'/'.basename($pr['repository_url']) : ''); ?></p>
                        <div class="flex gap-2 mt-2 text-[10px]">
                            <span class="px-2 py-0.5 rounded bg-green-100 text-green-800 font-bold">Open</span>
                            <?php if(!empty($pr['user']['login'])): ?><span class="text-gray-500">by <?php echo e($pr['user']['login']); ?></span><?php endif; ?>
                            <span class="text-gray-400"><?php echo e(isset($pr['created_at']) ? \Carbon\Carbon::parse($pr['created_at'])->diffForHumans() : ''); ?></span>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-5 py-12 text-center text-gray-500 text-sm">لا Pull Requests مفتوحة.</div>
                    <?php endif; ?>
                </div>

                <?php elseif($tab === 'branches'): ?>
                <div class="p-4 border-b border-gray-100">
                    <form method="GET" action="<?php echo e(route('github.index')); ?>" class="flex flex-wrap gap-2 items-end">
                        <input type="hidden" name="tab" value="branches">
                        <?php if($activeAccount): ?><input type="hidden" name="account" value="<?php echo e($activeAccount->id); ?>"><?php endif; ?>
                        <div class="flex-1 min-w-[200px]">
                            <label class="text-xs font-bold text-gray-600">المستودع</label>
                            <select name="repo" class="w-full rounded-xl border-gray-300 text-sm mt-1" onchange="this.form.submit()">
                                <option value="">اختر مستودعاً</option>
                                <?php $__currentLoopData = $repos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($repo['full_name']); ?>" <?php if($selectedRepo === ($repo['full_name'] ?? '')): echo 'selected'; endif; ?>><?php echo e($repo['full_name']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </form>
                </div>
                <?php if($selectedRepo): ?>
                <div class="divide-y divide-gray-100 max-h-[60vh] overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $branches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-5 py-3 flex justify-between items-center">
                        <code class="text-xs font-bold text-gray-800" dir="ltr"><?php echo e($branch['name'] ?? ''); ?></code>
                        <?php if(!empty($branch['commit']['sha'])): ?>
                        <span class="text-[10px] font-mono text-gray-400" dir="ltr"><?php echo e(Str::limit($branch['commit']['sha'], 7, '')); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-5 py-8 text-center text-gray-500 text-sm">لا فروع في الذاكرة المؤقتة. اضغط «تحديث من GitHub» بعد اختيار المستودع.</div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="px-5 py-12 text-center text-gray-500 text-sm">اختر مستودعاً لعرض الفروع.</div>
                <?php endif; ?>

                <?php elseif($tab === 'access'): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-github-integration')): ?>
                <div class="p-4 border-b border-gray-100 flex flex-wrap gap-2 items-center justify-between">
                    <p class="text-xs text-gray-600">طلبات وصول الموظفين — اعتماد الحساب يرسل دعوات collaborator تلقائياً على مستودعات مشاريعهم.</p>
                    <form method="GET" action="<?php echo e(route('github.index')); ?>" class="flex gap-2">
                        <input type="hidden" name="tab" value="access">
                        <?php if($activeAccount): ?><input type="hidden" name="account" value="<?php echo e($activeAccount->id); ?>"><?php endif; ?>
                        <select name="access_status" class="rounded-xl border-gray-300 text-xs" onchange="this.form.submit()">
                            <option value="">معلّق + معتمد</option>
                            <option value="pending" <?php if(request('access_status') === 'pending'): echo 'selected'; endif; ?>>بانتظار الإدارة</option>
                            <option value="approved" <?php if(request('access_status') === 'approved'): echo 'selected'; endif; ?>>معتمد</option>
                            <option value="rejected" <?php if(request('access_status') === 'rejected'): echo 'selected'; endif; ?>>مرفوض</option>
                        </select>
                    </form>
                </div>
                <div class="divide-y divide-gray-100 max-h-[70vh] overflow-y-auto">
                    <?php $__empty_1 = true; $__currentLoopData = $accessRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $employee = $req->user?->employee;
                        $deptName = $employee?->department?->name;
                    ?>
                    <div class="px-5 py-4 hover:bg-gray-50/50">
                        <div class="flex flex-wrap justify-between gap-3 items-start">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <p class="font-bold text-sm text-gray-900"><?php echo e($req->user?->name ?? '—'); ?></p>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg <?php echo e($req->statusColor()); ?>"><?php echo e($req->statusLabel()); ?></span>
                                </div>
                                <p class="text-xs text-gray-500">
                                    <?php if($deptName): ?><span><?php echo e($deptName); ?></span><span class="text-gray-300 mx-1">·</span><?php endif; ?>
                                    <a href="<?php echo e($req->profile_url); ?>" target="_blank" rel="noopener" class="font-mono hover:underline" dir="ltr" style="color: <?php echo e($themeColor); ?>;"><?php echo e($req->username); ?></a>
                                    <span class="text-gray-300 mx-1">·</span>
                                    <span dir="ltr"><?php echo e($req->email); ?></span>
                                </p>
                                <?php if($req->employee_note): ?>
                                <p class="text-xs text-gray-600 mt-1 bg-gray-50 rounded-lg px-2 py-1">ملاحظة الموظف: <?php echo e($req->employee_note); ?></p>
                                <?php endif; ?>
                                <?php if($req->admin_notes): ?>
                                <p class="text-xs text-gray-500 mt-1">ملاحظة الإدارة: <?php echo e($req->admin_notes); ?></p>
                                <?php endif; ?>
                                <p class="text-[10px] text-gray-400 mt-1"><?php echo e($req->created_at?->diffForHumans()); ?></p>
                            </div>
                            <div class="flex flex-wrap gap-2 shrink-0">
                                <?php if($req->status === \App\Models\UserGitIdentity::STATUS_PENDING): ?>
                                <form method="POST" action="<?php echo e(route('github.access.approve', $req)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="px-3 py-1.5 rounded-xl text-white text-xs font-bold" style="background: <?php echo e($themeColor); ?>;">اعتماد وإرسال دعوات</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('github.access.reject', $req)); ?>" class="inline" onsubmit="return confirm('رفض طلب GitHub؟');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="px-3 py-1.5 rounded-xl border border-red-200 text-red-700 text-xs font-bold hover:bg-red-50">رفض</button>
                                </form>
                                <?php elseif($req->status === \App\Models\UserGitIdentity::STATUS_APPROVED): ?>
                                <form method="POST" action="<?php echo e(route('github.access.resync', $req)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-700 hover:bg-gray-50">إعادة مزامنة الدعوات</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-5 py-12 text-center text-gray-500 text-sm">لا توجد طلبات وصول GitHub حالياً.</div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <div class="px-5 py-12 text-center text-gray-500 text-sm">ليس لديك صلاحية إدارة طلبات الوصول.</div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-project-repos')): ?>
<div id="link-repo-modal" class="fixed inset-0 z-[100] hidden" aria-modal="true" role="dialog">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="closeLinkRepoModal()"></div>
    <div class="absolute inset-x-4 top-[8vh] mx-auto max-w-lg bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden max-h-[84vh] flex flex-col">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/80 flex items-start justify-between gap-3 shrink-0">
            <div>
                <h3 class="font-bold text-gray-900">ربط مستودع بمشروع</h3>
                <p class="text-xs text-gray-500 mt-1 font-mono" dir="ltr" id="link-repo-modal-name">—</p>
            </div>
            <button type="button" onclick="closeLinkRepoModal()" class="p-2 rounded-lg hover:bg-gray-200 text-gray-500" aria-label="إغلاق">✕</button>
        </div>
        <form method="POST" action="<?php echo e(route('github.link-project')); ?>" id="link-repo-form" class="flex flex-col min-h-0 flex-1">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="repo_full_name" id="link-repo-full-name">
            <input type="hidden" name="default_branch" id="link-repo-default-branch">
            <?php if($activeAccount): ?><input type="hidden" name="github_account_id" id="link-github-account-id" value="<?php echo e($activeAccount->id); ?>"><?php endif; ?>
            <div class="p-5 space-y-4 overflow-y-auto flex-1">
                <?php if($activeAccount): ?>
                <div class="p-3 rounded-xl bg-blue-50 border border-blue-100 text-xs text-blue-900">
                    سيتم الربط عبر حساب GitHub: <strong><?php echo e($activeAccount->displayLabel()); ?></strong>
                    <?php if($accounts->count() > 1): ?>
                    <span class="text-blue-700/80">— بدّل الحساب من القائمة على اليسار قبل الربط إن أردت.</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <div>
                    <label for="link-project-search" class="block text-xs font-bold text-gray-600 mb-2">اختر المشروع</label>
                    <input type="text" id="link-project-search" placeholder="ابحث بالاسم أو العميل..."
                           class="w-full rounded-xl border-gray-300 text-sm px-4 py-2.5 mb-3"
                           oninput="filterLinkableProjects(this.value)">
                    <?php if($linkableProjects->isEmpty()): ?>
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-sm text-amber-900">
                        لا توجد مشاريع يمكنك ربطها. تحتاج صلاحية تعديل المشروع (<code class="text-xs">edit-projects</code> أو مدير المشروع).
                    </div>
                    <?php else: ?>
                    <div class="space-y-2 max-h-[45vh] overflow-y-auto pr-1" id="link-project-options">
                        <?php $__currentLoopData = $linkableProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="link-project-option flex items-start gap-3 p-3 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50/80 cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50 transition"
                               data-search="<?php echo e(strtolower($p->name.' '.($p->client?->name ?? '').' '.$p->status_name)); ?>">
                            <input type="radio" name="project_id" value="<?php echo e($p->id); ?>" required
                                   class="mt-1 text-blue-600 shrink-0">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-sm text-gray-900"><?php echo e($p->name); ?></p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <?php if($p->client): ?><span><?php echo e($p->client->name); ?></span><span class="text-gray-300 mx-1">·</span><?php endif; ?>
                                    <span><?php echo e($p->status_name); ?></span>
                                </p>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-2"><?php echo e($linkableProjects->count()); ?> مشروع متاح للربط</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap gap-2 justify-end shrink-0">
                <button type="button" onclick="closeLinkRepoModal()" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-bold text-gray-700 hover:bg-white">إلغاء</button>
                <?php if($linkableProjects->isNotEmpty()): ?>
                <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold" style="background: <?php echo e($themeColor); ?>;">ربط المستودع</button>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function openLinkRepoModal(fullName, defaultBranch) {
    const modal = document.getElementById('link-repo-modal');
    if (!modal) return;
    document.getElementById('link-repo-modal-name').textContent = fullName;
    document.getElementById('link-repo-full-name').value = fullName;
    document.getElementById('link-repo-default-branch').value = defaultBranch || 'main';
    const search = document.getElementById('link-project-search');
    if (search) { search.value = ''; filterLinkableProjects(''); }
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    setTimeout(() => search?.focus(), 100);
}

function closeLinkRepoModal() {
    const modal = document.getElementById('link-repo-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function filterLinkableProjects(query) {
    const q = (query || '').trim().toLowerCase();
    document.querySelectorAll('.link-project-option').forEach(el => {
        const hay = el.getAttribute('data-search') || '';
        el.classList.toggle('hidden', q !== '' && !hay.includes(q));
    });
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLinkRepoModal(); });

document.addEventListener('DOMContentLoaded', () => {
    const autoRepo = <?php echo json_encode($selectedRepo, 15, 512) ?>;
    const onReposTab = <?php echo json_encode($tab === 'repos', 15, 512) ?>;
    if (!autoRepo || !onReposTab) return;

    const row = Array.from(document.querySelectorAll('[data-repo-full-name]'))
        .find(el => (el.getAttribute('data-repo-full-name') || '').toLowerCase() === autoRepo.toLowerCase());
    if (row) {
        row.scrollIntoView({ block: 'center', behavior: 'smooth' });
        if (row.getAttribute('data-is-linked') !== '1' && document.getElementById('link-repo-modal')) {
            openLinkRepoModal(row.getAttribute('data-repo-full-name'), row.getAttribute('data-default-branch') || 'main');
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\github\index.blade.php ENDPATH**/ ?>