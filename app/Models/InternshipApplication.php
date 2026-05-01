<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipApplication extends Model
{
    protected $fillable = [
        'training_id',
        'full_name',
        'email',
        'phone',
        'university',
        'major',
        'level',
        'linkedin_url',
        'portfolio_url',
        'cv_path',
        'message',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

