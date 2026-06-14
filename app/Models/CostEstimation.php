<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CostEstimation extends Model
{
    protected $fillable = [
        'sale_id',
        'reference_code',
        'screen_count',
        'developers_count',
        'dev_hours',
        'design_hours',
        'qa_hours',
        'pm_hours',
        'hourly_rate_dev',
        'hourly_rate_design',
        'hourly_rate_qa',
        'hourly_rate_pm',
        'subtotal',
        'margin_percent',
        'total_cost',
        'duration_weeks',
        'scope_notes',
        'technical_notes',
        'status',
        'estimated_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'dev_hours' => 'decimal:2',
        'design_hours' => 'decimal:2',
        'qa_hours' => 'decimal:2',
        'pm_hours' => 'decimal:2',
        'hourly_rate_dev' => 'decimal:2',
        'hourly_rate_design' => 'decimal:2',
        'hourly_rate_qa' => 'decimal:2',
        'hourly_rate_pm' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'margin_percent' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function estimator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estimated_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function proposal(): HasOne
    {
        return $this->hasOne(Proposal::class);
    }

    public function getTotalHoursAttribute(): float
    {
        return (float) $this->dev_hours + (float) $this->design_hours
            + (float) $this->qa_hours + (float) $this->pm_hours;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'submitted' => 'مُرسل للمراجعة',
            'approved' => 'معتمد',
            default => $this->status,
        };
    }

    public static function generateReferenceCode(): string
    {
        $year = now()->format('Y');
        $last = static::where('reference_code', 'like', "EST-{$year}-%")
            ->orderByDesc('id')
            ->value('reference_code');

        $seq = 1;
        if ($last && preg_match('/EST-\d{4}-(\d+)/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return sprintf('EST-%s-%04d', $year, $seq);
    }
}
