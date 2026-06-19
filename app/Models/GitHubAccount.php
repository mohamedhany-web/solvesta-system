<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class GitHubAccount extends Model
{
    public const TYPE_PERSONAL = 'personal';

    public const TYPE_ORGANIZATION = 'organization';

    protected $table = 'github_accounts';

    protected $fillable = [
        'label',
        'account_type',
        'login',
        'organization',
        'avatar_url',
        'access_token',
        'is_default',
        'is_active',
        'connected_by',
        'connected_at',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'connected_at' => 'datetime',
        'access_token' => 'encrypted',
    ];

    protected $hidden = [
        'access_token',
    ];

    public function connectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'connected_by');
    }

    public function projectRepositories(): HasMany
    {
        return $this->hasMany(ProjectRepository::class, 'github_account_id');
    }

    public function isPersonal(): bool
    {
        return $this->account_type === self::TYPE_PERSONAL;
    }

    public function ownerSlug(): string
    {
        return $this->isPersonal()
            ? $this->login
            : ($this->organization ?: $this->login);
    }

    public function displayLabel(): string
    {
        if ($this->label) {
            return $this->label;
        }

        if ($this->isPersonal()) {
            return '@'.$this->login;
        }

        return $this->organization ?: $this->login;
    }

    public function accountTypeLabel(): string
    {
        return $this->isPersonal() ? 'حساب شخصي' : 'Organization';
    }

    public function token(): ?string
    {
        if (! $this->exists) {
            $raw = $this->attributes['access_token'] ?? null;
            if (! $raw) {
                return null;
            }

            try {
                return decrypt($raw);
            } catch (\Throwable) {
                return $raw;
            }
        }

        return $this->access_token;
    }

    public function maskedToken(): string
    {
        $token = $this->token();
        if (! $token) {
            return '—';
        }

        return '••••••••'.substr($token, -4);
    }

    public function cacheKeyPrefix(): string
    {
        return 'github_acc_'.$this->id;
    }

    public function clearCache(): void
    {
        $base = $this->cacheKeyPrefix();

        foreach (['summary', 'tab_repos', 'tab_teams', 'tab_members', 'tab_pulls'] as $suffix) {
            Cache::forget("{$base}_{$suffix}");
        }

        $repos = $this->projectRepositories()->get(['owner', 'repo_name']);
        foreach ($repos as $repo) {
            Cache::forget($base.'_branches_'.md5(strtolower($repo->fullName())));
        }
    }

    public static function defaultAccount(): ?self
    {
        return static::query()
            ->where('is_active', true)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->first();
    }

    public static function setAsDefault(self $account): void
    {
        static::query()->update(['is_default' => false]);
        $account->update(['is_default' => true, 'is_active' => true]);
    }

    public static function createFromConnection(
        string $token,
        string $accountType,
        ?string $organization,
        array $user,
        ?string $label,
        ?int $connectedBy
    ): self {
        $login = $user['login'] ?? '';

        $account = static::updateOrCreate(
            [
                'login' => $login,
                'account_type' => $accountType,
                'organization' => $accountType === self::TYPE_ORGANIZATION ? $organization : null,
            ],
            [
                'label' => $label,
                'avatar_url' => $user['avatar_url'] ?? null,
                'access_token' => $token,
                'is_active' => true,
                'connected_by' => $connectedBy,
                'connected_at' => now(),
            ]
        );

        if (! static::query()->where('is_default', true)->where('id', '!=', $account->id)->exists()) {
            static::setAsDefault($account);
        }

        $account->clearCache();

        return $account;
    }
}
