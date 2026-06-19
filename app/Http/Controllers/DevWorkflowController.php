<?php

namespace App\Http\Controllers;

use App\Models\GitBranch;
use App\Models\Project;
use App\Models\ProjectRepository;
use App\Models\PullRequest;
use App\Models\Task;
use App\Models\UserGitIdentity;
use App\Services\DevWorkflowService;
use App\Services\GitHubAccessService;
use App\Services\GitHubService;
use Illuminate\Http\Request;

class DevWorkflowController extends Controller
{
    public function __construct(
        private DevWorkflowService $workflow,
        private GitHubService $github,
        private GitHubAccessService $gitAccess
    ) {}

    public function index(Request $request)
    {
        abort_unless(
            $request->user()->can('view-dev-workflow') || $request->user()->can('manage-github-integration'),
            403
        );

        $user = $request->user();
        $stats = $this->workflow->pipelineStats($user);

        $pendingReviews = PullRequest::with(['repository.project', 'author', 'task'])
            ->whereIn('status', ['open', 'changes_requested'])
            ->when(! $user->can('review-code'), fn ($q) => $q->where('reviewer_id', $user->id))
            ->latest()
            ->take(10)
            ->get();

        $recentBranches = GitBranch::with(['repository.project', 'task', 'creator'])
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();

        $recentMerged = PullRequest::with(['repository.project', 'author'])
            ->where('status', 'merged')
            ->latest('merged_at')
            ->take(6)
            ->get();

        $accessRequests = collect();
        $pendingAccessCount = 0;

        if ($user->can('manage-github-integration')) {
            $accessRequests = UserGitIdentity::with(['user.employee.department'])
                ->where('provider', 'github')
                ->whereIn('status', [
                    UserGitIdentity::STATUS_PENDING,
                    UserGitIdentity::STATUS_APPROVED,
                ])
                ->latest()
                ->take(25)
                ->get();

            $pendingAccessCount = UserGitIdentity::query()
                ->where('provider', 'github')
                ->where('status', UserGitIdentity::STATUS_PENDING)
                ->count();
        }

        return view('dev-workflow.index', compact(
            'stats',
            'pendingReviews',
            'recentBranches',
            'recentMerged',
            'accessRequests',
            'pendingAccessCount',
        ));
    }

    public function storeRepository(Request $request, Project $project)
    {
        abort_unless($request->user()->can('manage-project-repos'), 403);
        $this->authorize('update', $project);

        $data = $request->validate([
            'owner' => 'required|string|max:100',
            'repo_name' => 'required|string|max:100',
            'default_branch' => 'nullable|string|max:100',
        ]);

        $this->workflow->linkRepository($project, $data, $request->user());

        return back()->with('success', 'تم ربط المستودع بالمشروع بنجاح.');
    }

    public function storeBranch(Request $request, Task $task)
    {
        abort_unless($request->user()->can('create-git-branches'), 403);

        $task->load('project.repositories');
        $repository = $task->project?->activeRepository();
        if (! $repository) {
            return back()->with('error', 'لا يوجد مستودع Git مرتبط بهذا المشروع.');
        }

        $data = $request->validate([
            'branch_type' => 'required|in:feature,bugfix,hotfix,release',
            'branch_name' => 'nullable|string|max:120|regex:/^[a-zA-Z0-9._\/-]+$/',
        ]);

        $branch = $this->workflow->createBranchForTask(
            $task,
            $repository,
            $request->user(),
            $data['branch_type'],
            $data['branch_name'] ?? null
        );

        $message = $this->github->isConfigured()
            ? "تم إنشاء الفرع «{$branch->name}» على GitHub."
            : "تم تسجيل الفرع «{$branch->name}». اربط GitHub من صفحة تكامل GitHub لتفعيل الإنشاء التلقائي.";

        return back()->with('success', $message);
    }

    public function storePullRequest(Request $request, GitBranch $branch)
    {
        abort_unless($request->user()->can('create-pull-requests'), 403);

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:5000',
            'reviewer_id' => 'nullable|exists:users,id',
        ]);

        $pr = $this->workflow->createPullRequestForBranch(
            $branch,
            $request->user(),
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['reviewer_id'] ?? null
        );

        return back()->with('success', 'تم إنشاء Pull Request.')->with('pr_url', $pr->pr_url);
    }

    public function showPullRequest(PullRequest $pullRequest)
    {
        abort_unless(auth()->user()->can('view-dev-workflow'), 403);

        $pullRequest->load(['repository.project', 'branch', 'task', 'author', 'reviewer']);

        return view('dev-workflow.pull-request-show', ['pr' => $pullRequest]);
    }

    public function reviewPullRequest(Request $request, PullRequest $pullRequest)
    {
        abort_unless($request->user()->can('review-code'), 403);

        $data = $request->validate([
            'action' => 'required|in:approve,request_changes,merge,close',
            'review_notes' => 'nullable|string|max:2000',
        ]);

        $this->workflow->reviewPullRequest(
            $pullRequest,
            $request->user(),
            $data['action'],
            $data['review_notes'] ?? null
        );

        return redirect()->route('dev-workflow.pull-requests.show', $pullRequest)
            ->with('success', 'تم تحديث حالة المراجعة.');
    }

    public function updateGitIdentity(Request $request)
    {
        abort_if(
            $request->user()->can('manage-github-integration'),
            403,
            'الإدارة تعتمد طلبات الوصول من لوحة الطلبات — الموظفون هم من يقدّمون طلباتهم.'
        );

        $data = $request->validate([
            'github_username' => 'required|string|max:100',
            'github_email' => 'required|email|max:255',
            'employee_note' => 'nullable|string|max:1000',
        ]);

        $this->gitAccess->submitRequest(
            $request->user(),
            $data['github_username'],
            $data['github_email'],
            $data['employee_note'] ?? null
        );

        return back()->with('success', 'تم إرسال طلب GitHub للإدارة. ستصلك صلاحيات الريبو بعد الاعتماد.');
    }
}
