<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_number',
        'title',
        'description',
        'client_id',
        'project_id',
        'contract_type',
        'start_date',
        'end_date',
        'value',
        'status',
        'terms_conditions',
        'parties',
        'attachments',
        'renewal_notice_days',
        'auto_renewal',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'parties' => 'array',
        'attachments' => 'array',
        'auto_renewal' => 'boolean',
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

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all invoices for this contract.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Generate unique contract number
     */
    public static function generateContractNumber(): string
    {
        $year = date('Y');
        $prefix = 'CNT-' . $year . '-';
        
        $lastContract = self::where('contract_number', 'like', $prefix . '%')
            ->orderBy('contract_number', 'desc')
            ->first();
        
        if ($lastContract) {
            $lastNumber = (int) substr($lastContract->contract_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get contract type name in Arabic
     */
    public function getContractTypeNameAttribute(): string
    {
        return match($this->contract_type) {
            'employment' => 'عقد عمل',
            'service' => 'عقد خدمة',
            'nda' => 'اتفاقية عدم الإفشاء',
            'partnership' => 'عقد شراكة',
            'vendor' => 'عقد مورد',
            default => $this->contract_type
        };
    }

    /**
     * Get status name in Arabic
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'active' => 'نشط',
            'expired' => 'منتهي الصلاحية',
            'terminated' => 'ملغي',
            'renewed' => 'مجدد',
            default => $this->status
        };
    }

    /**
     * Check if contract is expiring soon
     */
    public function getIsExpiringSoonAttribute(): bool
    {
        if (!$this->end_date || $this->status !== 'active') {
            return false;
        }
        
        return $this->end_date->diffInDays(now()) <= $this->renewal_notice_days;
    }

    /**
     * Scope for active contracts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for expired contracts
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope for contracts expiring soon
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('status', 'active')
            ->where('end_date', '<=', now()->addDays($days))
            ->where('end_date', '>=', now());
    }

    /**
     * Get contract duration in days
     */
    public function getDurationDaysAttribute(): int
    {
        if (!$this->end_date) {
            return 0;
        }
        
        return $this->start_date->diffInDays($this->end_date);
    }

    /**
     * Get days until contract expiry
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        if (!$this->end_date) {
            return 0;
        }
        
        return now()->diffInDays($this->end_date, false);
    }
}
