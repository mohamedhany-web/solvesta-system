<?php

namespace App\Services;

use App\Models\GitHubAccount;
use App\Models\SystemSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GitHubSettings
{
    public const TYPE_PERSONAL = GitHubAccount::TYPE_PERSONAL;

    public const TYPE_ORGANIZATION = GitHubAccount::TYPE_ORGANIZATION;

    private static ?int $activeAccountId = null;

    public static function setActiveAccountId(?int $accountId): void
    {
        self::$activeAccountId = $accountId;
    }

    public static function activeAccount(): ?GitHubAccount
    {
        if (self::$activeAccountId) {
            $account = GitHubAccount::query()
                ->where('id', self::$activeAccountId)
                ->where('is_active', true)
                ->first();

            if ($account) {
                return $account;
            }
        }

        $account = GitHubAccount::defaultAccount();
        if ($account) {
            return $account;
        }

        return self::legacyVirtualAccount();
    }

    public static function allAccounts(): Collection
    {
        return GitHubAccount::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('label')
            ->orderBy('login')
            ->get();
    }

    public static function hasAccounts(): bool
    {
        return GitHubAccount::query()->where('is_active', true)->exists() || self::legacyToken() !== null;
    }

    public static function isConfigured(): bool
    {
        return (bool) self::token();
    }

    public static function token(): ?string
    {
        return self::activeAccount()?->token();
    }

    public static function accountType(): string
    {
        return self::activeAccount()?->account_type ?? self::TYPE_PERSONAL;
    }

    public static function isPersonalAccount(): bool
    {
        return self::accountType() === self::TYPE_PERSONAL;
    }

    public static function organization(): ?string
    {
        if (self::isPersonalAccount()) {
            return null;
        }

        return self::activeAccount()?->organization;
    }

    public static function ownerSlug(): ?string
    {
        $account = self::activeAccount();

        return $account?->ownerSlug();
    }

    public static function cacheKeyPrefix(): string
    {
        $account = self::activeAccount();

        if ($account?->id) {
            return $account->cacheKeyPrefix();
        }

        return 'github_'.self::accountType().'_'.(self::ownerSlug() ?: 'default');
    }

    public static function cacheKey(): string
    {
        return self::cacheKeyPrefix().'_legacy';
    }

    public static function connectedLogin(): ?string
    {
        return self::activeAccount()?->login;
    }

    public static function connectedAt(): ?string
    {
        $at = self::activeAccount()?->connected_at;

        return $at?->toIso8601String();
    }

    public static function connectedAvatar(): ?string
    {
        return self::activeAccount()?->avatar_url;
    }

    public static function isManagedInApp(): bool
    {
        return GitHubAccount::query()->exists() || (bool) SystemSetting::get('github_access_token');
    }

    public static function saveConnection(string $token, string $accountType, ?string $organization, array $user, ?string $label = null, ?int $connectedBy = null): GitHubAccount
    {
        return GitHubAccount::createFromConnection(
            $token,
            $accountType,
            $organization,
            $user,
            $label,
            $connectedBy
        );
    }

    public static function disconnect(?GitHubAccount $account = null): void
    {
        if ($account) {
            $account->clearCache();
            $account->delete();

            if (! GitHubAccount::query()->where('is_default', true)->exists()) {
                $next = GitHubAccount::defaultAccount();
                if ($next) {
                    GitHubAccount::setAsDefault($next);
                }
            }

            return;
        }

        foreach ([
            'github_access_token',
            'github_account_type',
            'github_organization',
            'github_connected_login',
            'github_connected_avatar',
            'github_connected_at',
        ] as $key) {
            SystemSetting::where('key', $key)->delete();
            Cache::forget("system_setting_{$key}");
        }

        self::clearCache();
    }

    public static function clearCache(?GitHubAccount $account = null): void
    {
        if ($account) {
            $account->clearCache();

            return;
        }

        GitHubAccount::query()->get()->each->clearCache();

        $base = self::cacheKeyPrefix();
        foreach (['summary', 'tab_repos', 'tab_teams', 'tab_members', 'tab_pulls', 'tab_repo_names'] as $suffix) {
            Cache::forget("{$base}_{$suffix}");
        }

        Cache::forget(self::cacheKey());
        Cache::forget('github_org_data');
    }

    public static function maskedToken(): ?string
    {
        return self::activeAccount()?->maskedToken();
    }

    public static function accountLabel(): string
    {
        $account = self::activeAccount();
        if (! $account) {
            return '—';
        }

        return $account->displayLabel();
    }

    private static function legacyToken(): ?string
    {
        $encrypted = SystemSetting::get('github_access_token');

        if ($encrypted) {
            try {
                return decrypt($encrypted);
            } catch (\Throwable) {
                return null;
            }
        }

        return config('services.github.token') ?: null;
    }

    private static function legacyVirtualAccount(): ?GitHubAccount
    {
        $token = self::legacyToken();
        if (! $token) {
            return null;
        }

        $account = new GitHubAccount([
            'account_type' => SystemSetting::get('github_account_type')
                ?: (SystemSetting::get('github_organization') || config('services.github.org') ? self::TYPE_ORGANIZATION : self::TYPE_PERSONAL),
            'login' => SystemSetting::get('github_connected_login') ?: 'env',
            'organization' => SystemSetting::get('github_organization') ?: config('services.github.org'),
            'avatar_url' => SystemSetting::get('github_connected_avatar'),
            'access_token' => $token,
            'is_default' => true,
            'is_active' => true,
        ]);

        return $account;
    }
}
