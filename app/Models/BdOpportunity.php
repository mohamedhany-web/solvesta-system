<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BdOpportunity extends Model
{
    protected $fillable = [
        'reference_code',
        'partner_id',
        'title',
        'description',
        'estimated_value',
        'status',
        'lead_id',
        'assigned_to',
        'expected_close_date',
        'lost_reason',
        'created_by',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'expected_close_date' => 'date',
    ];

    public function partner(): BelongsTo
    {
        return $this->belongsTo(BdPartner::class, 'partner_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'prospecting' => 'استكشاف',
            'contacted' => 'تم التواصل',
            'qualified' => 'مؤهّلة',
            'converted' => 'تحوّلت لـ Lead',
            'lost' => 'مفقودة',
            default => $this->status,
        };
    }

    public static function generateReferenceCode(): string
    {
        $year = now()->format('Y');
        $last = static::where('reference_code', 'like', "OPP-{$year}-%")
            ->orderByDesc('id')->value('reference_code');
        $seq = 1;
        if ($last && preg_match('/OPP-\d{4}-(\d+)/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return sprintf('OPP-%s-%04d', $year, $seq);
    }
}
