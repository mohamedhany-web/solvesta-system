<?php

namespace App\Http\Controllers;

use App\Models\GitHubAccount;
use App\Models\Project;
use App\Models\ProjectRepository;
use App\Models\UserGitIdentity;
use App\Services\DevWorkflowService;
use App\Services\GitHubAccessService;
use App\Services\GitHubService;
use App\Services\GitHubSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GitHubIntegrationController extends Controller
{
    public function __construct(
        private GitHubService $github,
        private DevWorkflowService $devWorkflow,
        private GitHubAccessService $gitAccess
    ) {}

    public function index(Request $request): View
    {
        $accounts = GitHubSettings::allAccounts();
        $activeAccount = $this->resolveActiveAccount($request, $accounts);
        GitHubSettings::setActiveAccountId($activeAccount?->id);

        $connected = GitHubSettings::hasAccounts();
        $accountType = GitHubSettings::accountType();
        $org = GitHubSettings::organization();
        $ownerSlug = GitHubSettings::ownerSlug();
        $tab = in_array($request->get('tab'), ['repos', 'teams', 'members', 'pulls', 'branches', 'access'], true)
            ? $request->get('tab')
            : 'repos';

        $dashboard = [];
        $cacheMiss = false;

        if ($connected && GitHubSettings::isConfigured()) {
            $loadTab = in_array($tab, ['branches', 'access'], true) ? 'repos' : $tab;
            $summary = $this->github->getCachedAccountSummary();
            $tabData = $this->github->getCachedTabData($loadTab);

            if ($summary) {
                $dashboard = array_merge($summary, $tabData ?? []);
            } else {
                $dashboard = array_merge($this->github->offlineDashboard(), $tabData ?? []);
                $cacheMiss = true;
            }

            if ($tabData === null) {
                $cacheMiss = true;
            }
        }

        $selectedRepo = $request->get('repo');
        $branches = [];

        if ($connected && $selectedRepo && str_contains($selectedRepo, '/')) {
            $branchKey = GitHubSettings::cacheKeyPrefix().'_branches_'.md5(strtolower($selectedRepo));
            $branches = \Illuminate\Support\Facades\Cache::get($branchKey, []);
        }

        $linkedRepos = ProjectRepository::with(['project', 'githubAccount'])
            ->where('is_active', true)
            ->get()
            ->keyBy(fn ($r) => strtolower($r->fullName()));

        $user = $request->user();

        $linkableProjects = Project::with('client')
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('name')
            ->get()
            ->sortBy(fn (Project $project) => match ($project->status) {
                'in_progress' => 0,
                'planning' => 1,
                'on_hold' => 2,
                'completed' => 3,
                default => 4,
            })
            ->filter(fn (Project $project) => $user->can('update', $project)
                || ($user->can('manage-project-repos') && $user->can('view', $project)))
            ->values();

        $accessRequests = UserGitIdentity::with(['user.employee.department'])
            ->where('provider', 'github')
            ->when($request->get('access_status'), fn ($q) => $q->where('status', $request->get('access_status')))
            ->when(! $request->get('access_status'), fn ($q) => $q->whereIn('status', [
                UserGitIdentity::STATUS_PENDING,
                UserGitIdentity::STATUS_APPROVED,
            ]))
            ->latest()
            ->take(50)
            ->get();

        return view('github.index', compact(
            'accounts',
            'activeAccount',
            'connected',
            'accountType',
            'org',
            'ownerSlug',
            'tab',
            'dashboard',
            'cacheMiss',
            'selectedRepo',
            'branches',
            'linkedRepos',
            'linkableProjects',
            'accessRequests'
        ));
    }

    public function approveGitAccess(Request $request, UserGitIdentity $gitIdentity): RedirectResponse
    {
        abort_unless($request->user()->can('manage-github-integration'), 403);

        $notes = $request->validate(['admin_notes' => 'nullable|string|max:2000'])['admin_notes'] ?? null;
        $result = $this->gitAccess->approve($gitIdentity, $request->user(), $notes, true);

        return back()->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    public function rejectGitAccess(Request $request, UserGitIdentity $gitIdentity): RedirectResponse
    {
        abort_unless($request->user()->can('manage-github-integration'), 403);

        $notes = $request->validate(['admin_notes' => 'nullable|string|max:2000'])['admin_notes'] ?? null;
        $this->gitAccess->reject($gitIdentity, $request->user(), $notes);

        return back()->with('success', 'تم رفض طلب GitHub.');
    }

    public function resyncGitInvites(Request $request, UserGitIdentity $gitIdentity): RedirectResponse
    {
        abort_unless($request->user()->can('manage-github-integration'), 403);

        if ($gitIdentity->status !== UserGitIdentity::STATUS_APPROVED) {
            return back()->with('error', 'يجب اعتماد الحساب أولاً.');
        }

        $results = $this->gitAccess->syncInvitesForUser($gitIdentity->user);
        $ok = collect($results)->where('ok', true)->count();
        $fail = collect($results)->where('ok', false)->count();

        return back()->with('success', "تمت مزامنة الدعوات: {$ok} ناجحة".($fail ? "، {$fail} فشلت" : '').'.');
    }

    public function storeAccount(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'access_token' => 'required|string|min:20|max:500',
            'account_type' => 'required|in:personal,organization',
            'organization' => 'nullable|required_if:account_type,organization|string|max:100|regex:/^[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
            'label' => 'nullable|string|max:100',
        ], [
            'organization.required_if' => 'أدخل اسم Organization عند اختيار نوع Organization.',
            'organization.regex' => 'اسم Organization غير صالح.',
        ]);

        $accountType = $data['account_type'];
        $org = $accountType === GitHubSettings::TYPE_ORGANIZATION
            ? ($data['organization'] ?? null)
            : null;

        $test = $this->github->testConnection($data['access_token'], $accountType, $org);

        if (! $test['ok']) {
            return back()->with('error', $test['message'])->withInput();
        }

        $account = GitHubSettings::saveConnection(
            $data['access_token'],
            $accountType,
            $org,
            $test['user'],
            $data['label'] ?? null,
            $request->user()->id
        );

        GitHubSettings::setActiveAccountId($account->id);
        $this->github->seedSummaryFromUser($test['user'], $accountType);

        return redirect()->route('github.index', ['account' => $account->id])
            ->with('success', $test['message'].' اضغط «تحديث من GitHub» لتحميل المستودعات.');
    }

    /** @deprecated use storeAccount */
    public function connect(Request $request): RedirectResponse
    {
        return $this->storeAccount($request);
    }

    public function destroyAccount(GitHubAccount $githubAccount): RedirectResponse
    {
        GitHubSettings::disconnect($githubAccount);

        return redirect()->route('github.index')
            ->with('success', 'تم حذف حساب GitHub «'.$githubAccount->displayLabel().'».');
    }

    /** @deprecated use destroyAccount */
    public function disconnect(): RedirectResponse
    {
        $account = GitHubSettings::activeAccount();
        if ($account?->id) {
            return $this->destroyAccount($account);
        }

        GitHubSettings::disconnect();

        return redirect()->route('github.index')
            ->with('success', 'تم فصل الاتصال بـ GitHub.');
    }

    public function setDefaultAccount(GitHubAccount $githubAccount): RedirectResponse
    {
        GitHubAccount::setAsDefault($githubAccount);

        return redirect()->route('github.index', ['account' => $githubAccount->id])
            ->with('success', 'تم تعيين «'.$githubAccount->displayLabel().'» كحساب افتراضي.');
    }

    public function refresh(Request $request): RedirectResponse
    {
        $this->resolveAndSetActiveAccount($request);

        if (! GitHubSettings::isConfigured()) {
            return back()->with('error', 'GitHub غير مربوط.');
        }

        @set_time_limit(120);

        $this->github->refreshOrganizationCache();

        $repo = $request->get('repo');
        if ($repo && str_contains($repo, '/')) {
            [$owner, $name] = explode('/', $repo, 2);
            $branchKey = GitHubSettings::cacheKeyPrefix().'_branches_'.md5(strtolower($repo));
            \Illuminate\Support\Facades\Cache::put(
                $branchKey,
                $this->github->listRepoBranches($owner, $name),
                300
            );
        }

        return back()->with('success', 'تم تحديث البيانات من GitHub.');
    }

    public function testAccount(Request $request, ?GitHubAccount $githubAccount = null): RedirectResponse
    {
        if ($githubAccount) {
            GitHubSettings::setActiveAccountId($githubAccount->id);
        } else {
            $this->resolveAndSetActiveAccount($request);
        }

        $test = $this->github->testConnection(
            null,
            GitHubSettings::accountType(),
            GitHubSettings::organization()
        );

        return back()->with(
            $test['ok'] ? 'success' : 'error',
            $test['message']
        );
    }

    /** @deprecated use testAccount */
    public function test(Request $request): RedirectResponse
    {
        return $this->testAccount($request);
    }

    public function linkProject(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('manage-project-repos'), 403);

        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'repo_full_name' => 'required|string|max:200',
            'default_branch' => 'nullable|string|max:100',
            'github_account_id' => 'nullable|exists:github_accounts,id',
        ]);

        if (! str_contains($data['repo_full_name'], '/')) {
            return back()->with('error', 'صيغة المستودع: owner/repo');
        }

        [$owner, $repoName] = explode('/', $data['repo_full_name'], 2);
        $project = Project::findOrFail($data['project_id']);

        $this->authorize('update', $project);

        if (! empty($data['github_account_id'])) {
            GitHubSettings::setActiveAccountId((int) $data['github_account_id']);
        }

        $this->devWorkflow->linkRepository($project, [
            'owner' => $owner,
            'repo_name' => $repoName,
            'default_branch' => $data['default_branch'] ?? 'main',
            'github_account_id' => $data['github_account_id'] ?? GitHubSettings::activeAccount()?->id,
        ], $request->user());

        $accountId = $data['github_account_id'] ?? GitHubSettings::activeAccount()?->id;

        return redirect()->route('github.index', [
            'account' => $accountId,
            'tab' => 'repos',
            'repo' => $data['repo_full_name'],
        ])->with('success', "تم ربط المستودع {$data['repo_full_name']} بالمشروع {$project->name}.");
    }

    private function resolveActiveAccount(Request $request, $accounts): ?GitHubAccount
    {
        $accountId = $request->integer('account');

        if ($accountId) {
            $account = $accounts->firstWhere('id', $accountId);
            if ($account) {
                return $account;
            }
        }

        return $accounts->firstWhere('is_default', true) ?? $accounts->first() ?? GitHubSettings::activeAccount();
    }

    private function resolveAndSetActiveAccount(Request $request): void
    {
        $accountId = $request->input('account');
        if ($accountId) {
            GitHubSettings::setActiveAccountId((int) $accountId);

            return;
        }

        $accounts = GitHubSettings::allAccounts();
        $active = $this->resolveActiveAccount($request, $accounts);
        GitHubSettings::setActiveAccountId($active?->id);
    }
}
