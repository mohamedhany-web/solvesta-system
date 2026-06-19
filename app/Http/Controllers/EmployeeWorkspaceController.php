<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\GitBranch;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectRepository;
use App\Models\PullRequest;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\UserGitIdentity;
use App\Services\DevWorkflowService;
use App\Support\ProjectScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeWorkspaceController extends Controller
{
    public function __construct(
        private DevWorkflowService $devWorkflow
    ) {}

    public function index(Request $request): View
    {
        $user = auth()->user();
        $teamView = $user->can('view-all-tasks') && $request->get('scope') === 'team';
        $projectId = $request->integer('project_id') ?: null;
        $milestoneId = $request->integer('milestone_id') ?: null;
        $viewMode = in_array($request->get('view'), ['kanban', 'list'], true) ? $request->get('view') : 'kanban';
        $priorityFilter = $request->get('priority');

        $taskQuery = Task::with([
            'project.client',
            'milestone',
            'assignedTo',
            'gitBranches' => fn ($q) => $q->where('status', 'active'),
            'pullRequests' => fn ($q) => $q->whereIn('status', ['open', 'changes_requested', 'approved']),
        ])
            ->where('status', '!=', 'cancelled')
            ->when(! $teamView, fn ($q) => $q->where('assigned_to', $user->id))
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId))
            ->when($milestoneId, fn ($q) => $q->where('milestone_id', $milestoneId))
            ->when($priorityFilter, fn ($q) => $q->where('priority', $priorityFilter))
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('due_date');

        $tasks = $taskQuery->get();

        $board = [];
        foreach (Task::workflowStatuses() as $status) {
            $board[$status] = $tasks->filter(
                fn (Task $task) => Task::normalizeStatus($task->status) === $status
            )->values();
        }

        $openStatuses = Task::openStatuses();
        $statsBase = Task::query()
            ->when(! $teamView, fn ($q) => $q->where('assigned_to', $user->id))
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId));

        $stats = [
            'open' => (clone $statsBase)->whereIn('status', $openStatuses)->count(),
            'in_progress' => (clone $statsBase)->where('status', 'in_progress')->count(),
            'code_review' => (clone $statsBase)->where('status', 'code_review')->count(),
            'qa_testing' => (clone $statsBase)->whereIn('status', ['qa_testing', 'client_review'])->count(),
            'done' => (clone $statsBase)->whereIn('status', ['done', 'completed'])->count(),
            'overdue' => (clone $statsBase)->overdue()->count(),
            'blockers' => (clone $statsBase)->where('has_blocker', true)->whereIn('status', $openStatuses)->count(),
            'estimated_hours' => (float) (clone $statsBase)->whereIn('status', $openStatuses)->sum('estimated_hours'),
        ];

        $projects = $this->accessibleProjects($user, $teamView);
        $epics = $projectId
            ? ProjectMilestone::where('project_id', $projectId)->orderBy('sort_order')->get()
            : collect();

        $devStats = $user->can('view-dev-workflow')
            ? $this->devWorkflow->pipelineStats($teamView ? null : $user)
            : [];

        $myBranches = GitBranch::with(['repository.project', 'task'])
            ->where('status', 'active')
            ->when(! $teamView, fn ($q) => $q->where('created_by', $user->id))
            ->latest()
            ->take(8)
            ->get();

        $myPullRequests = PullRequest::with(['repository.project', 'task'])
            ->whereIn('status', ['open', 'changes_requested', 'approved'])
            ->when(! $teamView, fn ($q) => $q->where('author_id', $user->id))
            ->latest()
            ->take(6)
            ->get();

        $prsToReview = $user->can('review-code')
            ? PullRequest::with(['repository.project', 'author', 'task'])
                ->whereIn('status', ['open', 'changes_requested'])
                ->when(! $user->can('view-all-tasks'), fn ($q) => $q->where('reviewer_id', $user->id))
                ->latest()
                ->take(5)
                ->get()
            : collect();

        $blockerTasks = Task::with('project')
            ->where('has_blocker', true)
            ->whereIn('status', $openStatuses)
            ->when(! $teamView, fn ($q) => $q->where('assigned_to', $user->id))
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId))
            ->latest()
            ->take(5)
            ->get();

        $focusTasks = (clone $statsBase)
            ->with(['project', 'milestone'])
            ->whereIn('status', ['todo', 'in_progress', 'code_review'])
            ->where(function ($q) {
                $q->whereIn('priority', ['urgent', 'high'])
                    ->orWhere(function ($q2) {
                        $q2->where('due_date', '<=', now()->addDays(3))->whereNotNull('due_date');
                    });
            })
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->take(6)
            ->get();

        $todayReport = DailyReport::where('user_id', $user->id)
            ->whereDate('report_date', today())
            ->first();

        $weekHours = (float) DailyReport::where('user_id', $user->id)
            ->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('hours_worked');

        $projectRepos = ProjectRepository::with('project')
            ->whereIn('project_id', $projects->pluck('id'))
            ->where('is_active', true)
            ->get();

        $recentActivity = TaskUpdate::with(['user', 'task.project'])
            ->whereIn('task_id', $tasks->pluck('id'))
            ->latest()
            ->take(12)
            ->get();

        $githubConfigured = \App\Services\GitHubSettings::isConfigured();

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
                ->take(5)
                ->get();

            $pendingAccessCount = UserGitIdentity::query()
                ->where('provider', 'github')
                ->where('status', UserGitIdentity::STATUS_PENDING)
                ->count();
        }

        return view('workspace.index', compact(
            'board',
            'stats',
            'projects',
            'epics',
            'teamView',
            'projectId',
            'milestoneId',
            'viewMode',
            'priorityFilter',
            'tasks',
            'devStats',
            'myBranches',
            'myPullRequests',
            'prsToReview',
            'blockerTasks',
            'focusTasks',
            'todayReport',
            'weekHours',
            'projectRepos',
            'recentActivity',
            'githubConfigured',
            'accessRequests',
            'pendingAccessCount',
        ));
    }

    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $user = auth()->user();
        $status = $request->validate([
            'status' => ['required', 'in:'.implode(',', Task::allStatuses())],
        ])['status'];

        if (! $this->canManageTask($user, $task)) {
            return response()->json(['message' => 'غير مصرح بتعديل هذه المهمة.'], 403);
        }

        if (! $task->employeeCanChangeStatus($user, $status)) {
            return response()->json(['message' => 'لا يمكنك نقل المهمة إلى هذه الحالة.'], 422);
        }

        $oldStatus = $task->status;
        $updates = ['status' => $status];

        if ($status === 'done') {
            $updates['progress_percentage'] = 100;
        } elseif ($status === 'in_progress' && ! $task->start_date) {
            $updates['start_date'] = now()->toDateString();
        }

        $task->update($updates);

        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => 'تم نقل المهمة من «'.Task::statusLabelAr($oldStatus).'» إلى «'.Task::statusLabelAr($status).'»',
            'type' => 'status_change',
            'metadata' => [
                'from' => $oldStatus,
                'to' => $status,
            ],
        ]);

        return response()->json([
            'message' => 'تم تحديث حالة المهمة.',
            'task' => [
                'id' => $task->id,
                'status' => $task->status,
                'status_label' => $task->status_label,
            ],
        ]);
    }

    private function canManageTask($user, Task $task): bool
    {
        if ($user->can('edit-tasks')) {
            return true;
        }

        return (int) $task->assigned_to === (int) $user->id;
    }

    private function accessibleProjects($user, bool $teamView)
    {
        $query = Project::with('client')
            ->whereIn('status', ['planning', 'in_progress', 'on_hold'])
            ->orderBy('name');

        if ($teamView || $user->can('view-all-projects')) {
            return $query->get();
        }

        ProjectScope::apply($query, $user);

        return $query->get();
    }
}
