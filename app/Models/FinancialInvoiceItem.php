<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialInvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_name',
        'description',
        'quantity',
        'unit_price',
        'amount',
        'billable_type',
        'billable_id',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(FinancialInvoice::class, 'invoice_id');
    }

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }
}

