<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'requirements',
        'location',
        'employment_type',
        'department_id',
        'status',
        'is_featured',
        'sort_order',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public const STATUSES = ['draft', 'published', 'closed'];

    public const EMPLOYMENT_TYPES = [
        'full_time' => 'Full-time',
        'part_time' => 'Part-time',
        'remote' => 'Remote',
        'hybrid' => 'Hybrid',
        'contract' => 'Contract',
    ];

    protected static function booted(): void
    {
        static::creating(function (JobPosting $job) {
            if (empty($job->slug)) {
                $job->slug = static::uniqueSlug($job->title);
            }
            if ($job->status === 'published' && ! $job->published_at) {
                $job->published_at = now();
            }
        });

        static::updating(function (JobPosting $job) {
            if ($job->isDirty('title') && ! $job->isDirty('slug')) {
                $job->slug = static::uniqueSlug($job->title, $job->id);
            }
            if ($job->isDirty('status') && $job->status === 'published' && ! $job->published_at) {
                $job->published_at = now();
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'job';
        $slug = $base;
        $n = 1;

        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function employmentTypeLabel(): string
    {
        return self::EMPLOYMENT_TYPES[$this->employment_type] ?? $this->employment_type;
    }

    public function statusLabelAr(): string
    {
        return match ($this->status) {
            'published' => 'منشورة',
            'closed' => 'مغلقة',
            default => 'مسودة',
        };
    }
}
