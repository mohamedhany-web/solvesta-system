<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'full_name',
        'email',
        'phone',
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

    public const STATUSES = ['pending', 'reviewing', 'shortlisted', 'rejected', 'hired'];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusLabelAr(): string
    {
        return match ($this->status) {
            'reviewing' => 'قيد المراجعة',
            'shortlisted' => 'قائمة مختصرة',
            'rejected' => 'مرفوض',
            'hired' => 'تم التوظيف',
            default => 'جديد',
        };
    }
}
