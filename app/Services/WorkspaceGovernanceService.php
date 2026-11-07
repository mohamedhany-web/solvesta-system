<?php

namespace App\Services;

use App\Models\ApprovalRequest;
use App\Models\CompanyEvent;
use App\Models\CompanyException;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\User;
use App\Services\CompanyOs\CompanyEventBus;
use App\Services\CompanyOs\CompanyOsNotifier;
use App\Services\CompanyOs\ExceptionService;
use Illuminate\Support\Collection;

class WorkspaceGovernanceService
{
    public function __construct(
        private WorkflowTrackService $tracks,
        private CompanyEventBus $events,
        private ExceptionService $exceptions,
        private CompanyOsNotifier $notifier,
    ) {}

    public function governanceFor(?string $track = null): array
    {
        $defaults = config('workflow_tracks.governance', []);
        if (! $track) {
            return $defaults;
        }

        $override = $this->tracks->track($track)['governance'] ?? [];

        return array_replace_recursive($defaults, $override);
    }

    /**
     * Whether moving to $newStatus needs manager approval for this actor.
     */
    public function requiresApproval(Task $task, string $newStatus, User $user): bool
    {
        $track = $this->tracks->resolve($user, $task);
        $gov = $this->governanceFor($track);

        $statuses = $gov['approval_required_statuses'] ?? [];
        if (! in_array($newStatus, $statuses, true)) {
            return false;
        }

        $priorities = $gov['approval_required_priorities'] ?? [];
        if ($priorities !== [] && ! in_array((string) $task->priority, $priorities, true)) {
            return false;
        }

        return ! $this->canBypassApproval($user, $gov);
    }

    public function canBypassApproval(User $user, ?array $gov = null): bool
    {
        $gov = $gov ?? $this->governanceFor();

        foreach ($gov['approval_bypass_permissions'] ?? [] as $perm) {
            if ($user->can($perm)) {
                return true;
            }
        }

        $roles = $gov['approval_bypass_roles'] ?? [];

        return $roles !== [] && $user->hasAnyRole($roles);
    }

    /**
     * Queue a status change for approval. Returns existing pending request if any.
     */
    public function requestStatusApproval(Task $task, string $targetStatus, User $user): ApprovalRequest
    {
        $existing = $task->approvalRequests()
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existing) {
            return $existing;
        }

        $track = $this->tracks->resolve($user, $task);
        $gov = $this->governanceFor($track);
        $steps = $gov['approval_steps'] ?? [['role' => 'manager', 'label' => 'اعتماد مدير الفريق']];
        $steps[0]['target_status'] = $targetStatus;
        $steps[0]['from_status'] = $task->status;
        $steps[0]['track'] = $track;

        $request = ApprovalRequest::create([
            'subject_type' => $task->getMorphClass(),
            'subject_id' => $task->getKey(),
            'rule_id' => null,
            'amount' => 0,
            'status' => 'pending',
            'current_step' => 0,
            'steps' => $steps,
            'requested_by' => $user->id,
            'notes' => 'طلب نقل المهمة إلى «'.$this->tracks->statusLabel($targetStatus, $track).'»',
        ]);

        $this->events->recordUser($task, 'workspace.approval_requested', [
            'approval_id' => $request->id,
            'from' => $task->status,
            'to' => $targetStatus,
            'track' => $track,
        ], $user);

        $ownerId = $task->project?->project_manager_id;
        $this->exceptions->open(
            $task,
            'task_approval_pending',
            'موافقة مطلوبة على مهمة',
            'warning',
            $task->task_key.' — بانتظار اعتماد النقل إلى «'.$this->tracks->statusLabel($targetStatus, $track).'»',
            $ownerId
        );

        $this->notifyApprovers($request, $task, $track, $targetStatus);

        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => $user->id,
            'comment' => 'طُلب اعتماد نقل المهمة إلى «'.$this->tracks->statusLabel($targetStatus, $track).'»',
            'type' => 'status_change',
            'metadata' => [
                'approval_id' => $request->id,
                'pending_to' => $targetStatus,
                'from' => $task->status,
            ],
        ]);

        return $request;
    }

    /**
     * Apply an approved status transition (called from ApprovalEngine).
     */
    public function applyApprovedStatus(Task $task, string $newStatus, User $actor): void
    {
        $oldStatus = $task->status;
        $track = $this->tracks->resolve($actor, $task);

        $updates = ['status' => $newStatus];
        if ($newStatus === 'done') {
            $updates['progress_percentage'] = 100;
        } elseif ($newStatus === 'in_progress' && ! $task->start_date) {
            $updates['start_date'] = now()->toDateString();
        }

        $task->update($updates);

        TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => $actor->id,
            'comment' => 'اعتمد النقل من «'.$this->tracks->statusLabel($oldStatus, $track).'» إلى «'.$this->tracks->statusLabel($newStatus, $track).'»',
            'type' => 'status_change',
            'metadata' => [
                'from' => $oldStatus,
                'to' => $newStatus,
                'track' => $track,
                'via' => 'approval',
            ],
        ]);

        $this->recordStatusChange($task, $oldStatus, $newStatus, $actor, $track);
    }

    public function recordStatusChange(Task $task, string $oldStatus, string $newStatus, User $user, ?string $track = null): void
    {
        $track = $track ?: $this->tracks->resolve($user, $task);

        $this->events->recordUser($task, 'workspace.status_changed', [
            'from' => $oldStatus,
            'to' => $newStatus,
            'track' => $track,
            'priority' => $task->priority,
            'specialization' => $task->specialization,
        ], $user);

        if (in_array($newStatus, ['done', 'cancelled'], true)) {
            $this->exceptions->resolve($task, 'task_overdue');
            $this->exceptions->resolve($task, 'task_blocker');
            $this->exceptions->resolve($task, 'task_stale');
            $this->exceptions->resolve($task, 'task_approval_pending');
        }

        if (! $task->has_blocker) {
            $this->exceptions->resolve($task, 'task_blocker');
        }
    }

    /**
     * Hourly automation: overdue, blockers, stale work, capacity overload.
     */
    public function scan(): int
    {
        $opened = 0;
        $opened += $this->scanOverdueTasks();
        $opened += $this->scanLongBlockers();
        $opened += $this->scanStaleInProgress();
        $opened += $this->scanCapacityOverload();

        return $opened;
    }

    private function scanOverdueTasks(): int
    {
        $count = 0;
        $gov = $this->governanceFor();
        $criticalDays = (int) ($gov['overdue_critical_days'] ?? 3);

        $tasks = Task::query()
            ->with(['project', 'assignedTo'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now())
            ->whereNotIn('status', ['done', 'completed', 'cancelled'])
            ->get();

        foreach ($tasks as $task) {
            $days = now()->startOfDay()->diffInDays($task->due_date);
            $severity = $days >= $criticalDays ? 'critical' : 'warning';
            $this->exceptions->open(
                $task,
                'task_overdue',
                'مهمة متأخرة — '.$task->task_key,
                $severity,
                ($task->title).' متأخرة بـ '.$days.' يوم.',
                $task->assigned_to ?: $task->project?->project_manager_id
            );
            $count++;
        }

        return $count;
    }

    private function scanLongBlockers(): int
    {
        $count = 0;
        $days = (int) ($this->governanceFor()['blocker_escalate_days'] ?? 2);

        $tasks = Task::query()
            ->with(['project'])
            ->where('has_blocker', true)
            ->whereNotIn('status', ['done', 'completed', 'cancelled'])
            ->where('updated_at', '<', now()->subDays($days))
            ->get();

        foreach ($tasks as $task) {
            $this->exceptions->open(
                $task,
                'task_blocker',
                'عائق معلّق — '.$task->task_key,
                'critical',
                $task->blocker_description ?: 'عائق مفتوح منذ أكثر من '.$days.' أيام.',
                $task->project?->project_manager_id ?: $task->assigned_to
            );
            $count++;
        }

        return $count;
    }

    private function scanStaleInProgress(): int
    {
        $count = 0;
        $days = (int) ($this->governanceFor()['stale_in_progress_days'] ?? 5);

        $tasks = Task::query()
            ->with(['project'])
            ->where('status', 'in_progress')
            ->where('updated_at', '<', now()->subDays($days))
            ->get();

        foreach ($tasks as $task) {
            $this->exceptions->open(
                $task,
                'task_stale',
                'مهمة راكدة — '.$task->task_key,
                'warning',
                'لم يُحدَّث التنفيذ منذ '.$days.' أيام.',
                $task->assigned_to ?: $task->project?->project_manager_id
            );
            $count++;
        }

        return $count;
    }

    private function scanCapacityOverload(): int
    {
        $count = 0;
        $gov = $this->governanceFor();
        $capacity = $this->tracks->teamCapacity();

        foreach ($capacity as $row) {
            if (($row['load'] ?? '') !== 'high') {
                continue;
            }

            $user = User::find($row['user_id']);
            if (! $user) {
                continue;
            }

            // Attach exception to the employee's user via a lightweight open on a representative overdue/open task if any.
            $sample = Task::query()
                ->where('assigned_to', $user->id)
                ->whereNotIn('status', ['done', 'completed', 'cancelled'])
                ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
                ->first();

            if (! $sample) {
                continue;
            }

            $beforeId = CompanyException::query()
                ->where('subject_type', $sample->getMorphClass())
                ->where('subject_id', $sample->getKey())
                ->where('code', 'capacity_overload')
                ->where('is_open', true)
                ->value('id');

            $exception = $this->exceptions->open(
                $sample,
                'capacity_overload',
                'حمل مرتفع — '.$row['name'],
                'warning',
                'مفتوحة: '.$row['open'].' · متأخرة: '.$row['overdue'].' · ساعات: '.$row['estimated_hours'],
                $user->id
            );

            if (! $beforeId) {
                try {
                    $managers = User::role(['project_manager', 'admin', 'super_admin'])->get();
                } catch (\Throwable) {
                    $managers = collect();
                }
                foreach ($managers->take(5) as $manager) {
                    $this->notifier->actionRequired(
                        $manager,
                        'حمل فريق مرتفع',
                        $row['name'].' لديه حمل مرتفع ('.$row['open'].' مهام مفتوحة).',
                        ['url' => route('workspace.ops'), 'user_id' => $user->id, 'exception_id' => $exception->id]
                    );
                }
            }

            $count++;
        }

        return $count;
    }

    /**
     * Executive / ops snapshot — dynamic by track when provided.
     *
     * @return array<string, mixed>
     */
    public function executiveSnapshot(?string $track = null, ?int $projectId = null): array
    {
        $open = Task::openStatuses();
        $base = Task::query()->where('status', '!=', 'cancelled');
        if ($projectId) {
            $base->where('project_id', $projectId);
        }

        if ($track) {
            $specialtyKeys = $this->tracks->specialtyKeysForTrack($track);
            $projectTypes = $this->tracks->track($track)['project_types'] ?? [];
            $base->where(function ($q) use ($specialtyKeys, $projectTypes, $track) {
                if ($specialtyKeys !== []) {
                    $q->whereIn('specialization', $specialtyKeys);
                }
                if ($projectTypes !== []) {
                    $q->orWhereHas('project', fn ($pq) => $pq->whereIn('project_type', $projectTypes));
                }
                if ($specialtyKeys === [] && $projectTypes === [] && $track === 'general') {
                    $q->orWhereNull('specialization');
                }
            });
        }

        $openCount = (clone $base)->whereIn('status', $open)->count();
        $doneWeek = (clone $base)->whereIn('status', ['done', 'completed'])
            ->where('updated_at', '>=', now()->startOfWeek())
            ->count();
        $overdue = (clone $base)->overdue()->count();
        $blockers = (clone $base)->where('has_blocker', true)->whereIn('status', $open)->count();
        $inReview = (clone $base)->whereIn('status', ['code_review', 'qa_testing', 'client_review'])->count();
        $pendingApprovals = ApprovalRequest::query()
            ->where('subject_type', (new Task)->getMorphClass())
            ->where('status', 'pending')
            ->count();

        $exceptionsOpen = CompanyException::query()
            ->open()
            ->where('subject_type', (new Task)->getMorphClass())
            ->count();
        $exceptionsCritical = CompanyException::query()
            ->open()
            ->where('subject_type', (new Task)->getMorphClass())
            ->where('severity', 'critical')
            ->count();

        $capacity = $this->tracks->teamCapacity(null, $projectId);
        $highLoad = $capacity->where('load', 'high')->count();
        $throughput = $doneWeek;
        $onTimeRate = $openCount + $overdue > 0
            ? round((($openCount) / max(1, $openCount + $overdue)) * 100, 1)
            : 100;

        $byStatus = [];
        foreach (Task::workflowStatuses() as $status) {
            $byStatus[$status] = (clone $base)->where('status', $status)->count();
        }

        $byTrack = [];
        foreach (array_keys($this->tracks->tracks()) as $key) {
            $keys = $this->tracks->specialtyKeysForTrack($key);
            $types = $this->tracks->track($key)['project_types'] ?? [];
            $q = Task::query()->whereIn('status', $open)->where('status', '!=', 'cancelled');
            $q->where(function ($inner) use ($keys, $types) {
                if ($keys !== []) {
                    $inner->whereIn('specialization', $keys);
                }
                if ($types !== []) {
                    $inner->orWhereHas('project', fn ($pq) => $pq->whereIn('project_type', $types));
                }
            });
            $byTrack[$key] = [
                'label' => $this->tracks->track($key)['label_ar'] ?? $key,
                'open' => $q->count(),
            ];
        }

        $recentEvents = CompanyEvent::query()
            ->where('subject_type', (new Task)->getMorphClass())
            ->whereIn('event', [
                'workspace.status_changed',
                'workspace.approval_requested',
                'exception.opened',
                'exception.resolved',
                'approved',
                'rejected',
            ])
            ->latest()
            ->take(20)
            ->get();

        $topExceptions = CompanyException::query()
            ->open()
            ->where('subject_type', (new Task)->getMorphClass())
            ->with('owner')
            ->orderByRaw("CASE severity WHEN 'critical' THEN 0 ELSE 1 END")
            ->latest()
            ->take(15)
            ->get();

        $pendingTaskApprovals = ApprovalRequest::query()
            ->with(['subject', 'requester'])
            ->where('subject_type', (new Task)->getMorphClass())
            ->where('status', 'pending')
            ->latest()
            ->take(15)
            ->get();

        return [
            'track' => $track,
            'kpis' => [
                'open' => $openCount,
                'done_week' => $throughput,
                'overdue' => $overdue,
                'blockers' => $blockers,
                'in_review' => $inReview,
                'pending_approvals' => $pendingApprovals,
                'exceptions_open' => $exceptionsOpen,
                'exceptions_critical' => $exceptionsCritical,
                'high_load' => $highLoad,
                'on_time_rate' => $onTimeRate,
            ],
            'by_status' => $byStatus,
            'by_track' => $byTrack,
            'capacity' => $capacity,
            'recent_events' => $recentEvents,
            'exceptions' => $topExceptions,
            'pending_approvals' => $pendingTaskApprovals,
            'governance' => $this->governanceFor($track),
        ];
    }

    public function pendingApprovalsForManager(User $user, int $limit = 8): Collection
    {
        if (! $this->canBypassApproval($user) && ! $user->hasAnyRole(['project_manager', 'admin', 'super_admin'])) {
            return collect();
        }

        return ApprovalRequest::query()
            ->with(['subject.project', 'requester'])
            ->where('subject_type', (new Task)->getMorphClass())
            ->where('status', 'pending')
            ->latest()
            ->take($limit)
            ->get();
    }

    private function notifyApprovers(ApprovalRequest $request, Task $task, string $track, string $targetStatus): void
    {
        try {
            $users = User::role(['project_manager', 'admin', 'super_admin'])->get();
        } catch (\Throwable) {
            $users = User::role(['admin'])->get();
        }

        if ($pm = $task->project?->projectManager) {
            $users = $users->push($pm)->unique('id');
        }

        foreach ($users->take(8) as $approver) {
            $this->notifier->actionRequired(
                $approver,
                'موافقة مهمة مطلوبة',
                $task->task_key.' → '.$this->tracks->statusLabel($targetStatus, $track),
                [
                    'approval_id' => $request->id,
                    'task_id' => $task->id,
                    'url' => route('tasks.show', $task),
                ]
            );
        }
    }
}
