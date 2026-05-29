<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'name',
        'type',
        'currency',
        'opening_balance',
        'current_balance',
        'account_number',
        'bank_name',
        'notes',
        'is_active',
        'account_id',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class)->orderByDesc('transaction_date')->orderByDesc('id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTypeNameAttribute(): string
    {
        return match ($this->type) {
            'cash' => 'نقدية / خزينة',
            'bank' => 'حساب بنكي',
            'transfer' => 'تحويل / محفظة إلكترونية',
            default => 'أخرى',
        };
    }
}
