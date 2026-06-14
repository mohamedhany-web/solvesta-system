<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'report_date',
        'work_summary',
        'hours_worked',
        'has_blocker',
        'blocker_description',
        'blocker_status',
        'reviewed_by',
        'reviewed_at',
        'team_lead_notes',
    ];

    protected $casts = [
        'report_date' => 'date',
        'hours_worked' => 'decimal:2',
        'has_blocker' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
