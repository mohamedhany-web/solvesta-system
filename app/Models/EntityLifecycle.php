<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EntityLifecycle extends Model
{
    protected $fillable = [
        'subject_type',
        'subject_id',
        'workflow_definition_id',
        'current_state_id',
        'owner_id',
        'entered_at',
        'sla_due_at',
        'sla_breached_at',
    ];

    protected $casts = [
        'entered_at' => 'datetime',
        'sla_due_at' => 'datetime',
        'sla_breached_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function currentState(): BelongsTo
    {
        return $this->belongsTo(WorkflowState::class, 'current_state_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function isSlaBreached(): bool
    {
        if ($this->sla_breached_at) {
            return true;
        }

        return $this->sla_due_at && $this->sla_due_at->isPast();
    }
}
