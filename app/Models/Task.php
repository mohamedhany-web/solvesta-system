<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    public const WORKFLOW_STATUSES = [
        'backlog',
        'todo',
        'in_progress',
        'code_review',
        'qa_testing',
        'client_review',
        'done',
    ];

    public const LEGACY_STATUS_MAP = [
        'pending' => 'backlog',
        'review' => 'code_review',
        'completed' => 'done',
    ];

    protected $fillable = [
        'title',
        'description',
        'project_id',
        'milestone_id',
        'assigned_to',
        'created_by',
        'parent_task_id',
        'due_date',
        'start_date',
        'status',
        'priority',
        'specialization',
        'estimated_hours',
        'actual_hours',
        'has_blocker',
        'blocker_description',
        'progress_percentage',
        'tags',
        'attachments',
    ];

    protected $casts = [
        'due_date' => 'date',
        'start_date' => 'date',
        'tags' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Get the project that owns the task.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function milestone(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'milestone_id');
    }

    /**
     * Get the user assigned to the task.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created the task.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the parent task.
     */
    public function parentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    /**
     * Get the subtasks.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    /**
     * Get the updates/comments for the task.
     */
    public function updates(): HasMany
    {
        return $this->hasMany(TaskUpdate::class);
    }

    public function gitBranches(): HasMany
    {
        return $this->hasMany(GitBranch::class);
    }

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function activeBranch(): ?GitBranch
    {
        return $this->gitBranches()->where('status', 'active')->latest()->first();
    }

    /**
     * Get task status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match (self::normalizeStatus($this->status)) {
            'backlog' => 'slate',
            'todo' => 'gray',
            'in_progress' => 'blue',
            'code_review' => 'violet',
            'qa_testing' => 'amber',
            'client_review' => 'orange',
            'done' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusLabel($this->status);
    }

    public function getStatusLabelArAttribute(): string
    {
        return self::statusLabelAr($this->status);
    }

    public function getTaskKeyAttribute(): string
    {
        $projectCode = $this->project_id
            ? str_pad((string) $this->project_id, 3, '0', STR_PAD_LEFT)
            : '000';

        return 'SOLV-'.$projectCode.'-'.$this->id;
    }

    public static function workflowStatuses(): array
    {
        return self::WORKFLOW_STATUSES;
    }

    public static function allStatuses(): array
    {
        return array_values(array_unique(array_merge(
            self::WORKFLOW_STATUSES,
            ['cancelled'],
            array_keys(self::LEGACY_STATUS_MAP)
        )));
    }

    public static function openStatuses(): array
    {
        return array_values(array_diff(self::WORKFLOW_STATUSES, ['done']));
    }

    public static function normalizeStatus(?string $status): string
    {
        if (! $status) {
            return 'backlog';
        }

        return self::LEGACY_STATUS_MAP[$status] ?? $status;
    }

    public static function statusLabel(?string $status): string
    {
        return match (self::normalizeStatus($status)) {
            'backlog' => 'Backlog',
            'todo' => 'To Do',
            'in_progress' => 'In Progress',
            'code_review' => 'Code Review',
            'qa_testing' => 'QA Testing',
            'client_review' => 'Client Review',
            'done' => 'Done',
            'cancelled' => 'ملغي',
            default => (string) $status,
        };
    }

    public static function statusLabelAr(?string $status): string
    {
        return match (self::normalizeStatus($status)) {
            'backlog' => 'قائمة الانتظار',
            'todo' => 'للتنفيذ',
            'in_progress' => 'قيد التنفيذ',
            'code_review' => 'مراجعة الكود',
            'qa_testing' => 'اختبار QA',
            'client_review' => 'مراجعة العميل',
            'done' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => (string) $status,
        };
    }

    public static function columnStyles(): array
    {
        return [
            'backlog' => ['header' => 'bg-slate-100 text-slate-800', 'dot' => 'bg-slate-500'],
            'todo' => ['header' => 'bg-gray-100 text-gray-800', 'dot' => 'bg-gray-500'],
            'in_progress' => ['header' => 'bg-blue-100 text-blue-800', 'dot' => 'bg-blue-500'],
            'code_review' => ['header' => 'bg-violet-100 text-violet-800', 'dot' => 'bg-violet-500'],
            'qa_testing' => ['header' => 'bg-amber-100 text-amber-800', 'dot' => 'bg-amber-500'],
            'client_review' => ['header' => 'bg-orange-100 text-orange-800', 'dot' => 'bg-orange-500'],
            'done' => ['header' => 'bg-green-100 text-green-800', 'dot' => 'bg-green-500'],
        ];
    }

    public function employeeCanChangeStatus(User $user, string $newStatus): bool
    {
        if ($user->can('edit-tasks')) {
            return in_array($newStatus, self::allStatuses(), true);
        }

        if ((int) $this->assigned_to !== (int) $user->id) {
            return false;
        }

        if ($newStatus === 'cancelled') {
            return false;
        }

        return in_array($newStatus, self::WORKFLOW_STATUSES, true);
    }

    public function isDone(): bool
    {
        return in_array($this->status, ['done', 'completed'], true);
    }

    /**
     * Get priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }

    /**
     * Check if task is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date < now() && ! $this->isDone() && $this->status !== 'cancelled';
    }

    /**
     * Scope for active tasks.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', self::openStatuses());
    }

    /**
     * Scope for completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->whereIn('status', ['done', 'completed']);
    }

    /**
     * Scope for overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->whereNotIn('status', ['done', 'completed', 'cancelled']);
    }
}