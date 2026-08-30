<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobAtsCriterion extends Model
{
    protected $fillable = [
        'job_posting_id',
        'code',
        'label',
        'description',
        'type',
        'weight',
        'config',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'weight' => 'integer',
        'sort_order' => 'integer',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(ApplicationAtsScore::class, 'job_ats_criterion_id');
    }
}
