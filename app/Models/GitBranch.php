<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GitBranch extends Model
{
    public const TYPES = ['feature', 'bugfix', 'hotfix', 'release'];

    public const STATUSES = ['active', 'merged', 'closed'];

    protected $fillable = [
        'project_repository_id',
        'task_id',
        'name',
        'branch_type',
        'base_branch',
        'status',
        'created_by',
        'github_sha',
        'merged_at',
    ];

    protected $casts = [
        'merged_at' => 'datetime',
    ];

    public function repository(): BelongsTo
    {
        return $this->belongsTo(ProjectRepository::class, 'project_repository_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function typeLabelAr(): string
    {
        return match ($this->branch_type) {
            'bugfix' => 'إصلاح',
            'hotfix' => 'Hotfix',
            'release' => 'إصدار',
            default => 'ميزة',
        };
    }

    public function statusLabelAr(): string
    {
        return match ($this->status) {
            'merged' => 'مُدمج',
            'closed' => 'مغلق',
            default => 'نشط',
        };
    }

    public function githubUrl(): ?string
    {
        $repo = $this->repository;
        if (! $repo) {
            return null;
        }

        return "{$repo->webUrl()}/tree/{$this->name}";
    }
}
