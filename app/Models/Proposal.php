<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proposal extends Model
{
    protected $fillable = [
        'sale_id',
        'cost_estimation_id',
        'reference_code',
        'title',
        'project_description',
        'scope',
        'timeline',
        'pricing_breakdown',
        'payment_terms',
        'total_price',
        'valid_until',
        'status',
        'generated_content',
        'sent_at',
        'accepted_at',
        'rejected_at',
        'rejection_reason',
        'created_by',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'valid_until' => 'date',
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function costEstimation(): BelongsTo
    {
        return $this->belongsTo(CostEstimation::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'sent' => 'مُرسل للعميل',
            'accepted' => 'موافق عليه',
            'rejected' => 'مرفوض',
            'expired' => 'منتهي',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'sent' => 'bg-blue-100 text-blue-800',
            'accepted' => 'bg-emerald-100 text-emerald-800',
            'rejected' => 'bg-red-100 text-red-800',
            'expired' => 'bg-amber-100 text-amber-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public static function generateReferenceCode(): string
    {
        $year = now()->format('Y');
        $last = static::where('reference_code', 'like', "PROP-{$year}-%")
            ->orderByDesc('id')
            ->value('reference_code');

        $seq = 1;
        if ($last && preg_match('/PROP-\d{4}-(\d+)/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return sprintf('PROP-%s-%04d', $year, $seq);
    }
}
