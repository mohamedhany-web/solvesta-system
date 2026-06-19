<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePromotion extends Model
{
    public const STATUS_PENDING_TEAM_LEAD = 'pending_team_lead';

    public const STATUS_PENDING_DEPT_HEAD = 'pending_dept_head';

    public const STATUS_PENDING_HR = 'pending_hr';

    public const STATUS_APPROVED = 'approved';

    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'employee_id',
        'from_level',
        'to_level',
        'career_track',
        'status',
        'kpi_score',
        'justification',
        'team_lead_notes',
        'dept_head_notes',
        'hr_notes',
        'proposed_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'kpi_score' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING_TEAM_LEAD => 'بانتظار Team Lead',
            self::STATUS_PENDING_DEPT_HEAD => 'بانتظار رئيس القسم',
            self::STATUS_PENDING_HR => 'بانتظار HR / الترقيات',
            self::STATUS_APPROVED => 'تمت الترقية',
            self::STATUS_REJECTED => 'مرفوض',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function proposer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
