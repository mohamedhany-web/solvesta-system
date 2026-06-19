<?php

namespace App\Services;

use App\Models\GitBranch;
use App\Models\Project;
use App\Models\ProjectRepository;
use App\Models\PullRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;

class DevWorkflowService
{
    public function __construct(
        private GitHubService $github,
        private GitHubAccessService $gitAccess
    ) {}

    public function suggestBranchName(Task $task, string $type = 'feature'): string
    {
        $slug = Str::slug(Str::limit($task->title, 35, ''), '-');
        $slug = $slug ?: 'work';

        return "{$type}/task-{$task->id}-{$slug}";
    }

    public function linkRepository(Project $project, array $data, User $user): ProjectRepository
    {
        $owner = trim($data['owner']);
        $repoName = trim($data['repo_name']);
        $defaultBranch = $data['default_branch'] ?? 'main';
        $githubAccountId = $data['github_account_id'] ?? null;

        if ($githubAccountId) {
            GitHubSettings::setActiveAccountId((int) $githubAccountId);
        } elseif ($existing = ProjectRepository::query()
            ->where('project_id', $project->id)
            ->where('provider', 'github')
            ->where('owner', $owner)
            ->where('repo_name', $repoName)
            ->value('github_account_id')) {
            GitHubSettings::setActiveAccountId((int) $existing);
        }

        if ($this->github->isConfigured()) {
            $remoteDefault = $this->github->getDefaultBranch($owner, $repoName);
            if ($remoteDefault) {
                $defaultBranch = $remoteDefault;
            }
        }

        $repository = ProjectRepository::updateOrCreate(
            [
                'project_id' => $project->id,
                'provider' => 'github',
                'owner' => $owner,
                'repo_name' => $repoName,
            ],
            [
                'default_branch' => $defaultBranch,
                'repo_url' => "https://github.com/{$owner}/{$repoName}.git",
                'github_account_id' => $githubAccountId ?: GitHubSettings::activeAccount()?->id,
                'is_active' => true,
                'created_by' => $user->id,
            ]
        );

        $teamIds = $project->teamMembers()->pluck('users.id');
        if ($project->project_manager_id) {
            $teamIds->push($project->project_manager_id);
        }
        $repository->authorizedUsers()->syncWithoutDetaching(
            $teamIds->unique()->mapWithKeys(fn ($id) => [$id => ['access_level' => 'write']])->all()
        );

        $this->gitAccess->inviteApprovedUsersToRepository($repository);

        return $repository;
    }

    public function createBranchForTask(
        Task $task,
        ProjectRepository $repository,
        User $user,
        string $branchType = 'feature',
        ?string $customName = null
    ): GitBranch {
        $name = $customName ?: $this->suggestBranchName($task, $branchType);
        $baseBranch = $repository->default_branch;

        $sha = null;
        $this->useRepositoryAccount($repository);
        if ($this->github->isConfigured()) {
            $sha = $this->github->createBranch(
                $repository->owner,
                $repository->repo_name,
                $name,
                $baseBranch
            );
        }

        $branch = GitBranch::create([
            'project_repository_id' => $repository->id,
            'task_id' => $task->id,
            'name' => $name,
            'branch_type' => $branchType,
            'base_branch' => $baseBranch,
            'status' => 'active',
            'created_by' => $user->id,
            'github_sha' => $sha,
        ]);

        if (in_array($task->status, ['todo', 'pending', 'backlog'], true)) {
            $task->update(['status' => 'in_progress']);
        }

        return $branch;
    }

    public function createPullRequestForBranch(
        GitBranch $branch,
        User $user,
        ?string $title = null,
        ?string $description = null,
        ?int $reviewerId = null
    ): PullRequest {
        $repository = $branch->repository;
        $task = $branch->task;

        $prTitle = $title ?: ($task ? "Task #{$task->id}: {$task->title}" : "Merge {$branch->name}");
        $prBody = $description ?: ($task?->description);

        $githubData = null;
        $this->useRepositoryAccount($repository);
        if ($this->github->isConfigured()) {
            $githubData = $this->github->createPullRequest(
                $repository->owner,
                $repository->repo_name,
                $prTitle,
                $branch->name,
                $branch->base_branch,
                $prBody
            );
        }

        $pr = PullRequest::create([
            'project_repository_id' => $repository->id,
            'git_branch_id' => $branch->id,
            'task_id' => $branch->task_id,
            'number' => $githubData['number'] ?? null,
            'title' => $prTitle,
            'description' => $prBody,
            'source_branch' => $branch->name,
            'target_branch' => $branch->base_branch,
            'status' => 'open',
            'author_id' => $user->id,
            'reviewer_id' => $reviewerId,
            'github_pr_id' => $githubData['id'] ?? null,
            'pr_url' => $githubData['html_url'] ?? "{$repository->webUrl()}/compare/{$branch->base_branch}...{$branch->name}?expand=1",
        ]);

        if ($task && in_array($task->status, ['in_progress', 'todo'], true)) {
            $task->update(['status' => 'code_review']);
        }

        return $pr;
    }

    public function reviewPullRequest(PullRequest $pr, User $reviewer, string $action, ?string $notes = null): PullRequest
    {
        $status = match ($action) {
            'approve' => 'approved',
            'request_changes' => 'changes_requested',
            'merge' => 'merged',
            default => 'closed',
        };

        $pr->update([
            'status' => $status,
            'reviewer_id' => $reviewer->id,
            'review_notes' => $notes,
            'reviewed_at' => now(),
            'merged_at' => $status === 'merged' ? now() : $pr->merged_at,
        ]);

        if ($status === 'merged' && $pr->git_branch_id) {
            GitBranch::where('id', $pr->git_branch_id)->update([
                'status' => 'merged',
                'merged_at' => now(),
            ]);
        }

        if ($status === 'merged' && $pr->task) {
            $pr->task->update(['status' => 'qa_testing']);
        }

        if ($status === 'approved' && $pr->task && ! in_array($pr->task->status, ['code_review', 'qa_testing', 'client_review', 'done'], true)) {
            $pr->task->update(['status' => 'code_review']);
        }

        return $pr->fresh();
    }

    private function useRepositoryAccount(ProjectRepository $repository): void
    {
        if ($repository->github_account_id) {
            GitHubSettings::setActiveAccountId($repository->github_account_id);
        }
    }

    public function pipelineStats(?User $user = null): array
    {
        $taskQuery = Task::query()->whereHas('project');
        if ($user && ! $user->can('view-all-tasks')) {
            $taskQuery->where('assigned_to', $user->id);
        }

        return [
            'assigned' => (clone $taskQuery)->whereIn('status', ['todo', 'pending', 'backlog', 'in_progress'])->count(),
            'in_review' => (clone $taskQuery)->whereIn('status', ['review', 'code_review', 'qa_testing', 'client_review'])->count(),
            'active_branches' => GitBranch::where('status', 'active')->count(),
            'open_prs' => PullRequest::whereIn('status', ['open', 'changes_requested'])->count(),
            'approved_prs' => PullRequest::where('status', 'approved')->count(),
            'merged_this_week' => PullRequest::where('status', 'merged')
                ->where('merged_at', '>=', now()->startOfWeek())
                ->count(),
        ];
    }
}
