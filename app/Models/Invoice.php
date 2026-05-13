<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'contract_id',
        'project_id',
        'sale_id',
        'subtotal',
        'amount',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'status',
        'invoice_date',
        'issue_date',
        'due_date',
        'paid_date',
        'payment_method',
        'payment_date',
        'notes',
        'payment_link',
        'items',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'invoice_date' => 'date',
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'payment_date' => 'datetime',
        'items' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeOverdue($query)
    {
        return $query->where('status', '!=', 'paid')
            ->where('due_date', '<', now())
            ->where('status', '!=', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('status', '!=', 'paid')
            ->where('status', '!=', 'cancelled');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereYear('created_at', now()->year);
    }

    public function scopeLastMonth($query)
    {
        return $query->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year);
    }

    // Helper Methods
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return !$this->isPaid() && 
               $this->status !== 'cancelled' && 
               $this->due_date < now();
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
        ]);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
    }

    public function markAsOverdue(): void
    {
        if ($this->isOverdue() && $this->status === 'sent') {
            $this->update([
                'status' => 'overdue',
            ]);
        }
    }

    // Static Methods
    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $month = now()->format('m');
        
        // Get the last invoice number for this month
        $lastInvoice = static::whereYear('created_at', $year)
            ->whereMonth('created_at', now()->month)
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastInvoice && $lastInvoice->invoice_number) {
            // Extract the sequence number from the last invoice
            $parts = explode('-', $lastInvoice->invoice_number);
            $sequence = isset($parts[2]) ? intval($parts[2]) + 1 : 1;
        } else {
            $sequence = 1;
        }
        
        // Format: INV-YYYYMM-0001
        return sprintf('INV-%s%s-%04d', $year, $month, $sequence);
    }

    public function calculateTotals(): void
    {
        $this->tax_amount = $this->amount * 0.15; // 15% tax
        $this->total_amount = $this->amount + $this->tax_amount;
        $this->save();
    }
}
