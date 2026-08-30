<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationAtsScore extends Model
{
    protected $fillable = [
        'job_application_id',
        'job_ats_criterion_id',
        'score',
        'max_score',
        'notes',
        'evidence',
        'source',
        'scored_by',
    ];

    protected $casts = [
        'score' => 'integer',
        'max_score' => 'integer',
        'evidence' => 'array',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(JobAtsCriterion::class, 'job_ats_criterion_id');
    }

    public function scorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scored_by');
    }
}
