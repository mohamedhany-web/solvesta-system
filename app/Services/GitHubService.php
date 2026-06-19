<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GitHubService
{
    public function isConfigured(): bool
    {
        return GitHubSettings::isConfigured();
    }

    public function testConnection(?string $token = null, string $accountType = GitHubSettings::TYPE_ORGANIZATION, ?string $org = null): array
    {
        $token = $token ?: GitHubSettings::token();
        if (! $token) {
            return ['ok' => false, 'message' => 'لم يُدخل توكن GitHub.'];
        }

        $user = $this->requestWithToken('GET', '/user', [], $token);
        if (! $user || ! isset($user['login'])) {
            return ['ok' => false, 'message' => 'التوكن غير صالح أو منتهي الصلاحية.'];
        }

        if ($accountType === GitHubSettings::TYPE_ORGANIZATION) {
            if (! $org) {
                return ['ok' => false, 'message' => 'أدخل اسم Organization.'];
            }
            $orgData = $this->requestWithToken('GET', "/orgs/{$org}", [], $token);
            if (! $orgData || ! isset($orgData['login'])) {
                return ['ok' => false, 'message' => "تعذر الوصول إلى Organization «{$org}». تحقق من الاسم وصلاحية read:org — أو اختر «حساب شخصي»."];
            }
        }

        return [
            'ok' => true,
            'message' => $accountType === GitHubSettings::TYPE_PERSONAL
                ? "تم الربط بحسابك الشخصي @{$user['login']}."
                : 'الاتصال ناجح.',
            'user' => $user,
            'account_type' => $accountType,
        ];
    }

    public function getAuthenticatedUser(): ?array
    {
        return $this->get('/user');
    }

    public function getRateLimit(): ?array
    {
        $data = $this->get('/rate_limit');

        return $data['rate'] ?? null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listUserRepos(int $perPage = 30): array
    {
        $data = $this->get('/user/repos', [
            'affiliation' => 'owner',
            'sort' => 'updated',
            'per_page' => min($perPage, 50),
        ]);

        return is_array($data) ? $data : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchUserOpenPullRequests(string $username, int $perPage = 40): array
    {
        $data = $this->get('/search/issues', [
            'q' => "user:{$username} type:pr state:open",
            'per_page' => $perPage,
            'sort' => 'updated',
        ]);

        return $data['items'] ?? [];
    }

    public function getUserProfile(string $username): ?array
    {
        return $this->get("/users/{$username}");
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listOrgRepos(string $org, int $perPage = 50): array
    {
        $data = $this->get("/orgs/{$org}/repos", ['per_page' => $perPage, 'sort' => 'updated', 'type' => 'all']);

        return is_array($data) ? $data : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listOrgTeams(string $org): array
    {
        $data = $this->get("/orgs/{$org}/teams", ['per_page' => 100]);

        return is_array($data) ? $data : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listOrgMembers(string $org): array
    {
        $data = $this->get("/orgs/{$org}/members", ['per_page' => 100]);

        return is_array($data) ? $data : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listRepoBranches(string $owner, string $repo, int $perPage = 30): array
    {
        $data = $this->get("/repos/{$owner}/{$repo}/branches", ['per_page' => $perPage]);

        return is_array($data) ? $data : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listRepoPullRequests(string $owner, string $repo, string $state = 'open'): array
    {
        $data = $this->get("/repos/{$owner}/{$repo}/pulls", ['state' => $state, 'per_page' => 30, 'sort' => 'updated']);

        return is_array($data) ? $data : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchOpenPullRequests(string $org, int $perPage = 30): array
    {
        $data = $this->get('/search/issues', [
            'q' => "org:{$org} type:pr state:open",
            'per_page' => $perPage,
            'sort' => 'updated',
        ]);

        return $data['items'] ?? [];
    }

    public function getOrganizationDashboard(?string $org = null): array
    {
        return $this->getCachedDashboard('repos');
    }

    public function getAccountDashboard(): array
    {
        return $this->getCachedDashboard('repos');
    }

    public function getCachedDashboard(string $tab = 'repos'): array
    {
        $summary = $this->getCachedAccountSummary() ?? $this->offlineDashboard();
        $tabData = $this->getCachedTabData($tab === 'branches' ? 'repos' : $tab) ?? [];

        return array_merge($summary, $tabData);
    }

    public function getCachedAccountSummary(): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        return Cache::get(GitHubSettings::cacheKeyPrefix().'_summary');
    }

    public function getCachedTabData(string $tab): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        $tab = $this->normalizeTab($tab);

        return Cache::get(GitHubSettings::cacheKeyPrefix().'_tab_'.$tab);
    }

    public function offlineDashboard(): array
    {
        $login = GitHubSettings::connectedLogin();

        return [
            'account_type' => GitHubSettings::accountType(),
            'profile' => [
                'login' => $login,
                'name' => $login,
                'avatar_url' => GitHubSettings::connectedAvatar(),
            ],
            'organization' => GitHubSettings::isPersonalAccount() ? null : [
                'login' => GitHubSettings::organization(),
                'name' => GitHubSettings::organization(),
            ],
            'user' => null,
            'rate_limit' => null,
            'synced_at' => GitHubSettings::connectedAt(),
            'stats' => [
                'repos' => null,
                'teams' => null,
                'members' => null,
                'pulls' => null,
            ],
            'cache_miss' => true,
        ];
    }

    public function seedSummaryFromUser(array $user, string $accountType): void
    {
        $login = $user['login'] ?? GitHubSettings::connectedLogin();

        Cache::put(GitHubSettings::cacheKeyPrefix().'_summary', [
            'account_type' => $accountType,
            'profile' => $user,
            'organization' => $accountType === GitHubSettings::TYPE_ORGANIZATION
                ? ['login' => GitHubSettings::organization(), 'name' => GitHubSettings::organization()]
                : null,
            'user' => $user,
            'rate_limit' => null,
            'synced_at' => now()->toIso8601String(),
            'stats' => [
                'repos' => ($user['public_repos'] ?? 0) + ($user['total_private_repos'] ?? 0),
                'teams' => 0,
                'members' => 0,
                'pulls' => null,
            ],
        ], 300);
    }

    public function syncAccountSummary(): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        $cacheKey = GitHubSettings::cacheKeyPrefix().'_summary';

        $summary = $this->buildAccountSummary();
        Cache::put($cacheKey, $summary, 300);

        return $summary;
    }

    public function syncTabData(string $tab): array
    {
        if (! $this->isConfigured()) {
            return [];
        }

        $tab = $this->normalizeTab($tab);
        $cacheKey = GitHubSettings::cacheKeyPrefix().'_tab_'.$tab;

        $data = $this->buildTabData($tab);
        Cache::put($cacheKey, $data, 300);

        return $data;
    }

    public function syncAllTabs(): void
    {
        foreach (['repos', 'teams', 'members', 'pulls'] as $tab) {
            if (GitHubSettings::isPersonalAccount() && in_array($tab, ['teams', 'members'], true)) {
                continue;
            }
            $this->syncTabData($tab);
        }
    }

    private function normalizeTab(string $tab): string
    {
        return match ($tab) {
            'teams', 'members', 'pulls', 'repos' => $tab,
            default => 'repos',
        };
    }

    private function buildAccountSummary(): array
    {
        $user = $this->getAuthenticatedUser();

        if (GitHubSettings::isPersonalAccount()) {
            $login = $user['login'] ?? GitHubSettings::connectedLogin();
            $profile = $user ?: ($login ? $this->getUserProfile($login) : null);

            return [
                'account_type' => GitHubSettings::TYPE_PERSONAL,
                'profile' => $profile,
                'organization' => null,
                'user' => $user,
                'rate_limit' => $this->getRateLimit(),
                'synced_at' => now()->toIso8601String(),
                'stats' => [
                    'repos' => $profile
                        ? (($profile['public_repos'] ?? 0) + ($profile['total_private_repos'] ?? 0))
                        : null,
                    'teams' => 0,
                    'members' => 0,
                    'pulls' => null,
                ],
            ];
        }

        $org = GitHubSettings::organization();
        if (! $org) {
            return [];
        }

        $orgProfile = $this->getOrganization($org);

        return [
            'account_type' => GitHubSettings::TYPE_ORGANIZATION,
            'profile' => $orgProfile,
            'organization' => $orgProfile,
            'user' => $user,
            'rate_limit' => $this->getRateLimit(),
            'synced_at' => now()->toIso8601String(),
            'stats' => [
                'repos' => null,
                'teams' => null,
                'members' => null,
                'pulls' => null,
            ],
        ];
    }

    private function buildTabData(string $tab): array
    {
        if (GitHubSettings::isPersonalAccount()) {
            return match ($tab) {
                'repos' => ['repos' => $this->listUserRepos(30)],
                'pulls' => [
                    'pull_requests' => ($login = GitHubSettings::connectedLogin())
                        ? $this->searchUserOpenPullRequests($login, 25)
                        : [],
                ],
                default => [],
            };
        }

        $org = GitHubSettings::organization();
        if (! $org) {
            return [];
        }

        return match ($tab) {
            'repos' => ['repos' => $this->listOrgRepos($org, 30)],
            'teams' => ['teams' => $this->listOrgTeams($org)],
            'members' => ['members' => $this->listOrgMembers($org)],
            'pulls' => ['pull_requests' => $this->searchOpenPullRequests($org, 25)],
            default => [],
        };
    }

    public function refreshOrganizationCache(?string $org = null): array
    {
        GitHubSettings::clearCache();

        @set_time_limit(120);

        $summary = $this->syncAccountSummary();
        $this->syncAllTabs();

        return $summary;
    }

    public function getOrganization(string $org): ?array
    {
        return $this->get("/orgs/{$org}");
    }

    public function getDefaultBranch(string $owner, string $repo): ?string
    {
        $data = $this->get("/repos/{$owner}/{$repo}");
        if (! $data) {
            return null;
        }

        return $data['default_branch'] ?? 'main';
    }

    public function getBranchRef(string $owner, string $repo, string $branch): ?array
    {
        $data = $this->get("/repos/{$owner}/{$repo}/git/ref/heads/{$branch}");
        if (! $data || ! isset($data['object']['sha'])) {
            return null;
        }

        return ['sha' => $data['object']['sha']];
    }

    public function createBranch(string $owner, string $repo, string $newBranch, string $fromBranch): ?string
    {
        $ref = $this->getBranchRef($owner, $repo, $fromBranch);
        if (! $ref) {
            return null;
        }

        $response = $this->request('POST', "/repos/{$owner}/{$repo}/git/refs", [
            'ref' => "refs/heads/{$newBranch}",
            'sha' => $ref['sha'],
        ]);

        return $response['object']['sha'] ?? $ref['sha'];
    }

    public function createPullRequest(
        string $owner,
        string $repo,
        string $title,
        string $head,
        string $base,
        ?string $body = null
    ): ?array {
        $response = $this->request('POST', "/repos/{$owner}/{$repo}/pulls", [
            'title' => $title,
            'head' => $head,
            'base' => $base,
            'body' => $body ?? '',
        ]);

        if (! $response || ! isset($response['number'])) {
            return null;
        }

        return [
            'id' => $response['id'],
            'number' => $response['number'],
            'html_url' => $response['html_url'],
        ];
    }

    public function isCollaborator(string $owner, string $repo, string $username): bool
    {
        $response = $this->request('GET', "/repos/{$owner}/{$repo}/collaborators/{$username}", [
            'affiliation' => 'direct',
        ]);

        return $response !== null;
    }

    /**
     * @return array{ok: bool, message: string}
     */
    public function inviteCollaborator(string $owner, string $repo, string $username, string $permission = 'push'): array
    {
        $username = ltrim($username, '@');
        $permission = in_array($permission, ['pull', 'push', 'admin', 'maintain', 'triage'], true) ? $permission : 'push';

        $status = $this->requestStatus('PUT', "/repos/{$owner}/{$repo}/collaborators/{$username}", [
            'permission' => $permission,
        ]);

        if (in_array($status, [201, 204], true)) {
            return ['ok' => true, 'message' => 'تم إرسال دعوة الوصول على GitHub.'];
        }

        if ($status === 404) {
            return ['ok' => false, 'message' => 'المستودع أو المستخدم غير موجود.'];
        }

        if ($status === 422) {
            return ['ok' => false, 'message' => 'لا تملك صلاحية دعوة متعاونين على هذا المستودع.'];
        }

        return ['ok' => false, 'message' => "فشل دعوة المتعاون (HTTP {$status})."];
    }

    private function requestStatus(string $method, string $path, array $payload = []): int
    {
        $token = GitHubSettings::token();
        if (! $token) {
            return 0;
        }

        try {
            $request = Http::withToken($token)
                ->accept('application/vnd.github+json')
                ->timeout(12)
                ->connectTimeout(5);

            $response = match (strtoupper($method)) {
                'PUT' => $request->put($this->apiUrl($path), $payload),
                'POST' => $request->post($this->apiUrl($path), $payload),
                'DELETE' => $request->delete($this->apiUrl($path), $payload),
                default => $request->get($this->apiUrl($path), $payload),
            };

            return $response->status();
        } catch (\Throwable) {
            return 0;
        }
    }

    private function apiUrl(string $path): string
    {
        return 'https://api.github.com'.(str_starts_with($path, '/') ? $path : '/'.$path);
    }

    private function get(string $path, array $query = []): ?array
    {
        return $this->request('GET', $path, $query);
    }

    private function request(string $method, string $path, array $payload = []): ?array
    {
        $token = GitHubSettings::token();
        if (! $token) {
            return null;
        }

        return $this->requestWithToken($method, $path, $payload, $token);
    }

    private function requestWithToken(string $method, string $path, array $payload, string $token): ?array
    {
        try {
            $request = Http::withToken($token)
                ->accept('application/vnd.github+json')
                ->timeout(8)
                ->connectTimeout(5)
                ->withOptions([
                    'curl' => [
                        CURLOPT_CONNECTTIMEOUT => 5,
                        CURLOPT_TIMEOUT => 8,
                    ],
                ])
                ->withHeaders([
                    'X-GitHub-Api-Version' => '2022-11-28',
                ]);

            $url = 'https://api.github.com'.Str::start($path, '/');

            $response = match (strtoupper($method)) {
                'POST' => $request->post($url, $payload),
                'PATCH' => $request->patch($url, $payload),
                'DELETE' => $request->delete($url, $payload),
                default => $request->get($url, $payload),
            };

            if (! $response->successful()) {
                return null;
            }

            return $response->json();
        } catch (\Throwable) {
            return null;
        }
    }
}
