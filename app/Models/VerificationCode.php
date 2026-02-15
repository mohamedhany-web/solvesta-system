<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class VerificationCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'used',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Get the user that owns the verification code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the code is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the code is valid (not used and not expired).
     */
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    /**
     * Mark the code as used.
     */
    public function markAsUsed(): void
    {
        $this->update([
            'used' => true,
            'used_at' => now(),
        ]);
    }

    /**
     * Generate a random 6-digit code.
     */
    public static function generateCode(): string
    {
        return str_pad((string) mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new verification code for a user.
     * Uses parameterized queries to prevent SQL injection.
     */
    public static function createForUser(int $userId, int $expiresInMinutes = 10): self
    {
        // Invalidate all previous unused codes for this user
        // Using parameterized queries (Laravel Query Builder automatically does this)
        static::where('user_id', $userId)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->update(['used' => true]);

        return static::create([
            'user_id' => $userId,
            'code' => static::generateCode(),
            'expires_at' => now()->addMinutes($expiresInMinutes),
            'used' => false,
        ]);
    }

    /**
     * Find a valid code for a user.
     * Uses parameterized queries to prevent SQL injection.
     */
    public static function findValidCode(int $userId, string $code): ?self
    {
        // Using parameterized queries (Laravel Query Builder automatically prevents SQL injection)
        return static::where('user_id', $userId)
            ->where('code', $code) // This is automatically sanitized by Laravel
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();
    }
}

