<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeKpiPeriod extends Model
{
    protected $fillable = [
        'user_id',
        'period_year',
        'period_month',
        'role_template',
        'adherence_score',
        'task_completion_score',
        'quality_score',
        'team_lead_rating',
        'total_score',
        'kpi_deductions',
        'notes',
        'rated_by',
        'calculated_at',
    ];

    protected $casts = [
        'adherence_score' => 'decimal:2',
        'task_completion_score' => 'decimal:2',
        'quality_score' => 'decimal:2',
        'team_lead_rating' => 'decimal:2',
        'total_score' => 'decimal:2',
        'kpi_deductions' => 'decimal:2',
        'calculated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_by');
    }

    public function getPeriodLabelAttribute(): string
    {
        return sprintf('%d/%02d', $this->period_year, $this->period_month);
    }

    public function getGradeLabelAttribute(): string
    {
        return match (true) {
            $this->total_score >= 90 => 'ممتاز',
            $this->total_score >= 75 => 'جيد',
            $this->total_score >= 60 => 'مقبول',
            default => 'يحتاج تحسين',
        };
    }
}
