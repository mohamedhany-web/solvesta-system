<?php

namespace App\Services;

use App\Models\ProjectRepository;
use App\Models\User;
use App\Models\UserGitIdentity;
use Illuminate\Support\Collection;

class GitHubAccessService
{
    public function __construct(
        private GitHubService $github
    ) {}

    public function submitRequest(User $user, string $username, string $email, ?string $note = null): UserGitIdentity
    {
        $username = ltrim(trim($username), '@');
        $email = strtolower(trim($email));

        return UserGitIdentity::updateOrCreate(
            ['user_id' => $user->id, 'provider' => 'github'],
            [
                'username' => $username,
                'email' => $email,
                'profile_url' => "https://github.com/{$username}",
                'status' => UserGitIdentity::STATUS_PENDING,
                'employee_note' => $note,
                'admin_notes' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );
    }

    public function approve(UserGitIdentity $identity, User $admin, ?string $adminNotes = null, bool $sendInvites = true): array
    {
        if (! $this->github->isConfigured()) {
            return ['ok' => false, 'message' => 'اربط GitHub أولاً من صفحة التكامل.'];
        }

        $profile = $this->github->getUserProfile($identity->username);
        if (! $profile || empty($profile['login'])) {
            return ['ok' => false, 'message' => "تعذر التحقق من مستخدم GitHub «{$identity->username}»."];
        }

        $identity->update([
            'status' => UserGitIdentity::STATUS_APPROVED,
            'admin_notes' => $adminNotes,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        if (! $sendInvites) {
            return ['ok' => true, 'message' => 'تم اعتماد حساب GitHub.', 'invites' => []];
        }

        $invites = $this->syncInvitesForUser($identity->user);

        return [
            'ok' => true,
            'message' => 'تم الاعتماد وإرسال دعوات الوصول للمستودعات.',
            'invites' => $invites,
        ];
    }

    public function reject(UserGitIdentity $identity, User $admin, ?string $adminNotes = null): void
    {
        $identity->update([
            'status' => UserGitIdentity::STATUS_REJECTED,
            'admin_notes' => $adminNotes,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);
    }

    /**
     * @return array<int, array{repo: string, ok: bool, message: string}>
     */
    public function inviteApprovedUsersToRepository(ProjectRepository $repository): array
    {
        $results = [];

        $repository->loadMissing('project.teamMembers', 'project.projectManager');
        $userIds = $repository->authorizedUsers()->pluck('users.id');

        foreach (User::whereIn('id', $userIds)->with('gitIdentity')->get() as $user) {
            if ($user->gitIdentity?->status !== UserGitIdentity::STATUS_APPROVED) {
                continue;
            }
            $results[] = $this->inviteToRepository($repository, $user);
        }

        return $results;
    }

    /**
     * @return array<int, array{repo: string, ok: bool, message: string}>
     */
    public function syncInvitesForUser(User $user): array
    {
        $identity = $user->gitIdentity;
        if (! $identity || $identity->status !== UserGitIdentity::STATUS_APPROVED) {
            return [];
        }

        $repositories = $this->repositoriesForUser($user);
        $results = [];

        foreach ($repositories as $repository) {
            $results[] = $this->inviteToRepository($repository, $user, $identity->username);
        }

        return $results;
    }

    public function repositoriesForUser(User $user): Collection
    {
        $fromPivot = ProjectRepository::query()
            ->where('is_active', true)
            ->whereHas('authorizedUsers', fn ($q) => $q->where('users.id', $user->id))
            ->with('project')
            ->get();

        $fromTeam = ProjectRepository::query()
            ->where('is_active', true)
            ->whereHas('project', function ($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', fn ($t) => $t->where('users.id', $user->id));
            })
            ->with('project')
            ->get();

        return $fromPivot->merge($fromTeam)->unique('id')->values();
    }

    /**
     * @return array{repo: string, ok: bool, message: string}
     */
    public function inviteToRepository(ProjectRepository $repository, User $user, ?string $username = null): array
    {
        $username = $username ?: $user->gitIdentity?->username;
        $fullName = $repository->fullName();

        if (! $username) {
            return ['repo' => $fullName, 'ok' => false, 'message' => 'لا يوجد username GitHub.'];
        }

        if (! $this->github->isConfigured()) {
            return ['repo' => $fullName, 'ok' => false, 'message' => 'GitHub غير مربوط.'];
        }

        if ($repository->github_account_id) {
            GitHubSettings::setActiveAccountId((int) $repository->github_account_id);
        }

        $permission = $this->mapAccessLevel(
            $repository->authorizedUsers()->where('users.id', $user->id)->value('access_level') ?? 'write'
        );

        if ($this->github->isCollaborator($repository->owner, $repository->repo_name, $username)) {
            $repository->authorizedUsers()->syncWithoutDetaching([
                $user->id => [
                    'access_level' => $repository->authorizedUsers()->where('users.id', $user->id)->value('access_level') ?? 'write',
                    'invite_status' => 'active',
                    'invited_at' => now(),
                    'invite_error' => null,
                ],
            ]);

            return ['repo' => $fullName, 'ok' => true, 'message' => 'لديه وصول بالفعل.'];
        }

        $result = $this->github->inviteCollaborator(
            $repository->owner,
            $repository->repo_name,
            $username,
            $permission
        );

        $repository->authorizedUsers()->syncWithoutDetaching([
            $user->id => [
                'access_level' => $repository->authorizedUsers()->where('users.id', $user->id)->value('access_level') ?? 'write',
                'invite_status' => $result['ok'] ? 'invited' : 'failed',
                'invited_at' => $result['ok'] ? now() : null,
                'invite_error' => $result['ok'] ? null : ($result['message'] ?? 'فشل'),
            ],
        ]);

        return [
            'repo' => $fullName,
            'ok' => $result['ok'],
            'message' => $result['message'] ?? ($result['ok'] ? 'تم إرسال الدعوة.' : 'فشل'),
        ];
    }

    private function mapAccessLevel(string $level): string
    {
        return match ($level) {
            'admin' => 'admin',
            'read' => 'pull',
            default => 'push',
        };
    }

    public static function statusLabels(): array
    {
        return [
            UserGitIdentity::STATUS_PENDING => 'بانتظار الإدارة',
            UserGitIdentity::STATUS_APPROVED => 'معتمد',
            UserGitIdentity::STATUS_REJECTED => 'مرفوض',
        ];
    }
}
