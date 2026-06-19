<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGitIdentity extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'user_id',
        'provider',
        'username',
        'email',
        'status',
        'employee_note',
        'admin_notes',
        'profile_url',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'معتمد — جاهز للعمل',
            self::STATUS_REJECTED => 'مرفوض',
            default => 'بانتظار اعتماد الإدارة',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'text-emerald-700 bg-emerald-50',
            self::STATUS_REJECTED => 'text-red-700 bg-red-50',
            default => 'text-amber-700 bg-amber-50',
        };
    }
}
