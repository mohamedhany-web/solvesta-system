<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApprovalRequest extends Model
{
    protected $fillable = [
        'subject_type',
        'subject_id',
        'rule_id',
        'amount',
        'status',
        'current_step',
        'steps',
        'requested_by',
        'decided_by',
        'decided_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'steps' => 'array',
        'decided_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(ApprovalRule::class, 'rule_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function decider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function currentStepDef(): ?array
    {
        $steps = $this->steps ?? [];

        return $steps[$this->current_step] ?? null;
    }
}
