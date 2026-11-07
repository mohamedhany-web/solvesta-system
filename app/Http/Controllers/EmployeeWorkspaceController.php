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
use App\Services\WorkflowTrackService;
use App\Services\WorkspaceGovernanceService;
use App\Support\ProjectScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeWorkspaceController extends Controller
{
    public function __construct(
        private DevWorkflowService $devWorkflow,
        private WorkflowTrackService $tracks,
        private WorkspaceGovernanceService $governance,
    ) {}

    public function index(Request $request): View
    {
        $user = auth()->user();
        $teamView = $user->can('view-all-tasks') && $request->get('scope') === 'team';
        $projectId = $request->integer('project_id') ?: null;
        $milestoneId = $request->integer('milestone_id') ?: null;
        $viewMode = in_array($request->get('view'), ['kanban', 'list'], true) ? $request->get('view') : 'kanban';
        $priorityFilter = $request->get('priority');
        $specialtyFilter = $request->get('specialty');
        $forcedTrack = $request->get('track');
        if ($forcedTrack && ! isset($this->tracks->tracks()[$forcedTrack])) {
            $forcedTrack = null;
        }

        $workflowTrack = $this->tracks->resolve($user, null, $forcedTrack);
        $trackMeta = $this->tracks->track($workflowTrack);
        $availableTracks = $this->tracks->tracks();

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
            ->when($specialtyFilter, fn ($q) => $q->where('specialization', $specialtyFilter))
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
            ->when($projectId, fn ($q) => $q->where('project_id', $projectId))
            ->when($specialtyFilter, fn ($q) => $q->where('specialization', $specialtyFilter));

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

        $statCards = [];
        foreach (['open', 'in_progress', 'code_review', 'qa_testing', 'done', 'overdue', 'blockers', 'hours'] as $key) {
            $labels = $this->tracks->statLabel($key, $workflowTrack);
            $value = $key === 'hours' ? ($stats['estimated_hours'].'س') : ($stats[$key] ?? 0);
            $statCards[] = [
                'key' => $key,
                'label' => $labels[0],
                'sub' => $labels[1],
                'value' => $value,
            ];
        }

        $projects = $this->accessibleProjects($user, $teamView);
        $epics = $projectId
            ? ProjectMilestone::where('project_id', $projectId)->orderBy('sort_order')->get()
            : collect();

        $showGit = $this->tracks->hasWidget($workflowTrack, 'git')
            || $this->tracks->hasWidget($workflowTrack, 'prs')
            || $this->tracks->hasWidget($workflowTrack, 'repos');

        $devStats = ($showGit && $user->can('view-dev-workflow'))
            ? $this->devWorkflow->pipelineStats($teamView ? null : $user)
            : [];

        $myBranches = collect();
        $myPullRequests = collect();
        $prsToReview = collect();
        $projectRepos = collect();
        $accessRequests = collect();
        $pendingAccessCount = 0;
        $githubConfigured = false;

        if ($showGit) {
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

            $projectRepos = ProjectRepository::with('project')
                ->whereIn('project_id', $projects->pluck('id'))
                ->where('is_active', true)
                ->get();

            $githubConfigured = \App\Services\GitHubSettings::isConfigured();

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
        }

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

        $recentActivity = TaskUpdate::with(['user', 'task.project'])
            ->whereIn('task_id', $tasks->pluck('id'))
            ->latest()
            ->take(12)
            ->get();

        $recentDeliveries = $this->tracks->hasWidget($workflowTrack, 'deliveries')
            ? $this->tracks->recentDeliveriesForUser($user, $teamView)
            : collect();

        $awaitingReview = $this->tracks->hasWidget($workflowTrack, 'awaiting_review')
            ? $this->tracks->awaitingReview($user, $teamView)
            : collect();

        $capacity = collect();
        if ($this->tracks->hasWidget($workflowTrack, 'capacity') && ($teamView || $user->can('view-all-tasks'))) {
            $departmentId = optional($user->employee)->department_id;
            $capacity = $this->tracks->teamCapacity(
                $teamView ? null : $departmentId,
                $projectId
            );
        }

        $specialtyOptions = $this->tracks->specialtyOptions();
        $widgets = $trackMeta['widgets'] ?? [];
        $quickLinks = $trackMeta['quick_links'] ?? [];
        $pendingApprovals = $this->governance->pendingApprovalsForManager($user);

        return view('workspace.index', compact(
            'board',
            'stats',
            'statCards',
            'projects',
            'epics',
            'teamView',
            'projectId',
            'milestoneId',
            'viewMode',
            'priorityFilter',
            'specialtyFilter',
            'specialtyOptions',
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
            'recentDeliveries',
            'awaitingReview',
            'capacity',
            'githubConfigured',
            'accessRequests',
            'pendingAccessCount',
            'workflowTrack',
            'trackMeta',
            'availableTracks',
            'forcedTrack',
            'widgets',
            'quickLinks',
            'showGit',
            'pendingApprovals',
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

        $deliveryError = $this->tracks->validateDelivery($task, $status, $user);
        if ($deliveryError) {
            return response()->json(['message' => $deliveryError], 422);
        }

        if ($this->governance->requiresApproval($task, $status, $user)) {
            $approval = $this->governance->requestStatusApproval($task, $status, $user);

            return response()->json([
                'message' => 'تم إرسال طلب موافقة للمدير قبل اعتماد الحالة.',
                'approval_required' => true,
                'approval_id' => $approval->id,
                'task' => [
                    'id' => $task->id,
                    'status' => $task->status,
                    'status_label' => $task->status_label,
                    'status_label_ar' => $task->status_label_ar,
                ],
            ], 202);
        }

        $oldStatus = $task->status;
        $track = $this->tracks->resolve($user, $task);
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
            'comment' => 'تم نقل المهمة من «'.$this->tracks->statusLabel($oldStatus, $track).'» إلى «'.$this->tracks->statusLabel($status, $track).'»',
            'type' => 'status_change',
            'metadata' => [
                'from' => $oldStatus,
                'to' => $status,
                'track' => $track,
            ],
        ]);

        $this->governance->recordStatusChange($task, $oldStatus, $status, $user, $track);

        return response()->json([
            'message' => 'تم تحديث حالة المهمة.',
            'task' => [
                'id' => $task->id,
                'status' => $task->status,
                'status_label' => $this->tracks->statusLabel($task->status, $track, false),
                'status_label_ar' => $this->tracks->statusLabel($task->status, $track, true),
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
