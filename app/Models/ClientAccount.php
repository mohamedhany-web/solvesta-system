<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAccount extends Authenticatable
{
    use Notifiable;

    protected $table = 'client_accounts';

    protected $fillable = [
        'client_id',
        'name',
        'email',
        'password',
        'is_active',
        'portal_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function portalRole(): string
    {
        return $this->portal_role ?: 'owner';
    }

    public function canAccessBilling(): bool
    {
        return in_array($this->portalRole(), ['owner', 'billing'], true);
    }

    public function canAccessTechnicalRequests(): bool
    {
        return in_array($this->portalRole(), ['owner', 'technical'], true);
    }
}

