<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CompanyException extends Model
{
    protected $fillable = [
        'severity',
        'code',
        'subject_type',
        'subject_id',
        'owner_id',
        'title',
        'message',
        'is_open',
        'resolved_at',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }
}
