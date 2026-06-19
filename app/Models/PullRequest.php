<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PullRequest extends Model
{
    public const STATUSES = ['draft', 'open', 'changes_requested', 'approved', 'merged', 'closed'];

    protected $fillable = [
        'project_repository_id',
        'git_branch_id',
        'task_id',
        'number',
        'title',
        'description',
        'source_branch',
        'target_branch',
        'status',
        'author_id',
        'reviewer_id',
        'review_notes',
        'reviewed_at',
        'merged_at',
        'github_pr_id',
        'pr_url',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'merged_at' => 'datetime',
    ];

    public function repository(): BelongsTo
    {
        return $this->belongsTo(ProjectRepository::class, 'project_repository_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(GitBranch::class, 'git_branch_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function statusLabelAr(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'changes_requested' => 'تعديلات مطلوبة',
            'approved' => 'موافق عليه',
            'merged' => 'مُدمج',
            'closed' => 'مغلق',
            default => 'مفتوح',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'approved' => 'bg-emerald-100 text-emerald-800',
            'merged' => 'bg-green-100 text-green-800',
            'changes_requested' => 'bg-amber-100 text-amber-800',
            'closed' => 'bg-gray-100 text-gray-600',
            'draft' => 'bg-slate-100 text-slate-600',
            default => 'bg-blue-100 text-blue-800',
        };
    }

    public function isPendingReview(): bool
    {
        return in_array($this->status, ['open', 'changes_requested'], true);
    }
}
