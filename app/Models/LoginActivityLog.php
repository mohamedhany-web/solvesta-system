<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'activity_type',
        'verification_code',
        'email',
        'status',
        'message',
        'ip_address',
        'user_agent',
        'activity_at',
    ];

    protected $casts = [
        'activity_at' => 'datetime',
    ];

    /**
     * Get the user that owns the login activity log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a login activity
     */
    public static function logActivity(
        int $userId,
        string $activityType,
        ?string $verificationCode = null,
        ?string $email = null,
        string $status = 'success',
        ?string $message = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'activity_type' => $activityType,
            'verification_code' => $verificationCode ? substr($verificationCode, 0, 2) . '****' : null, // إخفاء جزئي للكود
            'email' => $email ? substr($email, 0, 3) . '***' . substr($email, strpos($email, '@')) : null, // إخفاء جزئي للبريد
            'status' => $status,
            'message' => $message,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'activity_at' => now(),
        ]);
    }

    /**
     * Scope to filter by activity type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope to filter by status
     */
    public function scopeOfStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('activity_at', '>=', now()->subDays($days));
    }
}
