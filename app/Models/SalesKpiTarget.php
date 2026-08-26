<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesKpiTarget extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'metric_key',
        'period_year',
        'period_month',
        'target_value',
        'weight',
        'monthly_duty',
        'set_by',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'period_year' => 'integer',
        'period_month' => 'integer',
        'weight' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function setter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'set_by');
    }
}
