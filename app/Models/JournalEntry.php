<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    protected $fillable = [
        'date',
        'reference',
        'description',
        'total_debit',
        'total_credit',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'total_debit' => 'decimal:2',
        'total_credit' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the journal entry lines.
     */
    public function lines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    /**
     * Get the user who created this entry.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who approved this entry.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for entries by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for entries by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Get status in Arabic.
     */
    public function getStatusInArabicAttribute()
    {
        $statuses = [
            'draft' => 'مسودة',
            'approved' => 'معتمد',
            'posted' => 'مرحل',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'draft' => 'bg-gray-100 text-gray-800',
            'approved' => 'bg-yellow-100 text-yellow-800',
            'posted' => 'bg-green-100 text-green-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Check if entry is balanced.
     */
    public function getIsBalancedAttribute()
    {
        return abs($this->total_debit - $this->total_credit) < 0.01;
    }

    /**
     * Get entry balance difference.
     */
    public function getBalanceDifferenceAttribute()
    {
        return $this->total_debit - $this->total_credit;
    }
}

