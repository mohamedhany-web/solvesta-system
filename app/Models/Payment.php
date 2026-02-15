<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'payment_number',
        'payment_type',
        'invoice_id',
        'employee_id',
        'client_id',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'bank_account_id',
        'description',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        // Use FinancialInvoice since migration points to financial_invoices table
        return $this->belongsTo(\App\Models\FinancialInvoice::class, 'invoice_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'bank_account_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Set invoice_id attribute - convert empty to null
     */
    public function setInvoiceIdAttribute($value)
    {
        $this->attributes['invoice_id'] = ($value === '' || $value === null) ? null : (int)$value;
    }

    /**
     * Set employee_id attribute - convert empty to null
     */
    public function setEmployeeIdAttribute($value)
    {
        $this->attributes['employee_id'] = ($value === '' || $value === null) ? null : (int)$value;
    }

    /**
     * Set client_id attribute - convert empty to null
     */
    public function setClientIdAttribute($value)
    {
        $this->attributes['client_id'] = ($value === '' || $value === null) ? null : (int)$value;
    }

    /**
     * Set bank_account_id attribute - convert empty to null
     */
    public function setBankAccountIdAttribute($value)
    {
        $this->attributes['bank_account_id'] = ($value === '' || $value === null) ? null : (int)$value;
    }
}

