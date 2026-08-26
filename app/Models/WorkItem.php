<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WorkItem extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'subject_type',
        'subject_id',
        'related_type',
        'related_id',
        'owner_id',
        'assignee_id',
        'priority',
        'status',
        'blocks_state',
        'is_required',
        'due_at',
        'sla_due_at',
        'parent_id',
        'metadata',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'due_at' => 'datetime',
        'sla_due_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isDone(): bool
    {
        return in_array($this->status, ['done', 'completed'], true);
    }
}
