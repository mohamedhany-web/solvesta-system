<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowState extends Model
{
    protected $fillable = [
        'workflow_definition_id',
        'key',
        'label_ar',
        'sort_order',
        'is_initial',
        'is_terminal',
        'is_side',
        'sla_hours',
        'severity_if_breached',
        'maps_to_status',
    ];

    protected $casts = [
        'is_initial' => 'boolean',
        'is_terminal' => 'boolean',
        'is_side' => 'boolean',
        'sla_hours' => 'integer',
    ];

    public function definition(): BelongsTo
    {
        return $this->belongsTo(WorkflowDefinition::class, 'workflow_definition_id');
    }

    public function outgoing(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class, 'from_state_id');
    }
}
