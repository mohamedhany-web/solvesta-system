<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectRepository extends Model
{
    protected $fillable = [
        'project_id',
        'github_account_id',
        'provider',
        'owner',
        'repo_name',
        'default_branch',
        'repo_url',
        'github_repo_id',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function githubAccount(): BelongsTo
    {
        return $this->belongsTo(GitHubAccount::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(GitBranch::class);
    }

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function authorizedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_repository_users')
            ->withPivot(['access_level', 'invite_status', 'invited_at', 'invite_error'])
            ->withTimestamps();
    }

    public function fullName(): string
    {
        return "{$this->owner}/{$this->repo_name}";
    }

    public function cloneUrl(): string
    {
        return $this->repo_url ?: "https://github.com/{$this->fullName()}.git";
    }

    public function webUrl(): string
    {
        return "https://github.com/{$this->fullName()}";
    }
}
