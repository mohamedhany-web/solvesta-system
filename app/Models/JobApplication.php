<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'stage',
        'ats_score',
        'ats_grade',
        'parsed_cv',
        'extracted_skills',
        'years_experience',
        'education_level',
        'cv_text',
        'parse_status',
        'parse_error',
        'parsed_at',
        'evaluated_at',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'parsed_at' => 'datetime',
        'evaluated_at' => 'datetime',
        'parsed_cv' => 'array',
        'extracted_skills' => 'array',
        'ats_score' => 'integer',
        'years_experience' => 'integer',
    ];

    public const STATUSES = ['pending', 'reviewing', 'shortlisted', 'rejected', 'hired'];

    public const STAGES = [
        'applied',
        'screening',
        'technical',
        'interview',
        'offer',
        'hired',
        'rejected',
    ];

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function atsScores(): HasMany
    {
        return $this->hasMany(ApplicationAtsScore::class);
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

    public function stageLabelAr(): string
    {
        return config('ats.stages.'.$this->stage, $this->stage ?: 'تقديم');
    }

    public function cvUrl(): ?string
    {
        return app(\App\Services\CvStorageService::class)->url($this->cv_path);
    }

    public function isPdfCv(): bool
    {
        return $this->cv_path && str_ends_with(strtolower($this->cv_path), '.pdf');
    }
}
