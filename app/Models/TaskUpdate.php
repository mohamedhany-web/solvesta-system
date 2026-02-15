<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskUpdate extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
        'type',
        'metadata',
        'attachments',
    ];

    protected $casts = [
        'metadata' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Get the task that owns this update.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who made this update.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
