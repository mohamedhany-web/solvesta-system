<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'parent_id',
        'description',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent account.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    /**
     * Get the child accounts.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    /**
     * Get the journal entry lines for the account.
     */
    public function journalEntryLines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    /**
     * Scope for active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for accounts by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get account type in Arabic.
     */
    public function getTypeInArabicAttribute()
    {
        $types = [
            'asset' => 'أصول',
            'liability' => 'خصوم',
            'equity' => 'حقوق ملكية',
            'revenue' => 'إيرادات',
            'expense' => 'مصروفات',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * Get account balance with proper sign.
     */
    public function getFormattedBalanceAttribute()
    {
        if ($this->type === 'liability' || $this->type === 'equity' || $this->type === 'revenue') {
            return $this->balance >= 0 ? $this->balance : abs($this->balance);
        } else {
            return $this->balance >= 0 ? $this->balance : abs($this->balance);
        }
    }

    /**
     * Update account balance.
     */
    public function updateBalance($amount, $type = 'debit')
    {
        if ($this->type === 'asset' || $this->type === 'expense') {
            $this->balance += $type === 'debit' ? $amount : -$amount;
        } else {
            $this->balance += $type === 'credit' ? $amount : -$amount;
        }
        $this->save();
    }

    /**
     * Get all descendants of this account.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get total balance including children.
     */
    public function getTotalBalanceAttribute()
    {
        $total = $this->balance;
        foreach ($this->children as $child) {
            $total += $child->total_balance;
        }
        return $total;
    }
}
