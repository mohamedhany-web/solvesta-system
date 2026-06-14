<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectMilestone extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'phase_type',
        'description',
        'sort_order',
        'start_date',
        'due_date',
        'estimated_hours',
        'actual_hours',
        'progress_percentage',
        'status',
        'assigned_lead_id',
        'completed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedLead(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_lead_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'milestone_id');
    }

    public function getPhaseLabelAttribute(): string
    {
        return match ($this->phase_type) {
            'ui_ux' => 'UI/UX',
            'backend' => 'Backend',
            'frontend' => 'Frontend',
            'testing' => 'Testing',
            'delivery' => 'التسليم',
            default => 'أخرى',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'بانتظار البدء',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
            'blocked' => 'محجوب',
            'cancelled' => 'ملغي',
            default => $this->status,
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->status !== 'completed';
    }
}
