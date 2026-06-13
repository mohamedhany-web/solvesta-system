<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientSystemProject extends Model
{
    protected $fillable = [
        'reference_code',
        'client_id',
        'name',
        'description',
        'status',
        'admin_notes',
        'assigned_to',
        'created_by_client_account_id',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdByClientAccount(): BelongsTo
    {
        return $this->belongsTo(ClientAccount::class, 'created_by_client_account_id');
    }

    public function features(): HasMany
    {
        return $this->hasMany(ClientSystemFeature::class)->orderByDesc('created_at');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'نشط',
            'on_hold' => 'متوقف مؤقتاً',
            'completed' => 'مكتمل',
            'archived' => 'مؤرشف',
            default => $this->status,
        };
    }

    public static function generateReferenceCode(): string
    {
        $prefix = 'SYS-'.now()->format('Y').'-';
        $last = static::where('reference_code', 'like', $prefix.'%')->orderByDesc('id')->value('reference_code');
        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
