<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientService extends Model
{
    protected $fillable = [
        'client_id',
        'contract_id',
        'service_number',
        'title',
        'description',
        'monthly_amount',
        'billing_day',
        'tax_rate',
        'currency',
        'start_date',
        'end_date',
        'next_billing_date',
        'payment_terms_days',
        'status',
        'auto_invoice',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'monthly_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_billing_date' => 'date',
        'auto_invoice' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function financialInvoices(): HasMany
    {
        return $this->hasMany(FinancialInvoice::class, 'client_service_id');
    }

    public function scopeBillableToday($query)
    {
        return $query->where('status', 'active')
            ->where('auto_invoice', true)
            ->whereNotNull('next_billing_date')
            ->whereDate('next_billing_date', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', now()->toDateString());
            });
    }

    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'active' => 'نشطة',
            'paused' => 'موقوفة',
            'ended' => 'منتهية',
            default => $this->status,
        };
    }

    public static function generateServiceNumber(): string
    {
        $prefix = 'SRV-'.now()->format('Ym').'-';
        $last = static::where('service_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('service_number');

        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    public function computeNextBillingDate(?\Carbon\Carbon $after = null): \Carbon\Carbon
    {
        $base = $after ? $after->copy() : ($this->next_billing_date ?? $this->start_date ?? now());
        if (! $base instanceof \Carbon\Carbon) {
            $base = \Carbon\Carbon::parse($base);
        }

        $day = min(max((int) $this->billing_day, 1), 28);
        $next = $base->copy()->addMonthNoOverflow()->day($day);

        if ($this->end_date && $next->gt($this->end_date)) {
            return \Carbon\Carbon::parse($this->end_date);
        }

        return $next;
    }
}
