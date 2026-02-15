<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bug extends Model
{
    use HasFactory;

    protected $fillable = [
        'bug_number',
        'title',
        'description',
        'project_id',
        'reported_by',
        'assigned_to',
        'severity',
        'priority',
        'status',
        'environment',
        'browser',
        'operating_system',
        'steps_to_reproduce',
        'expected_result',
        'actual_result',
        'attachments',
        'resolution_date',
        'resolution_notes',
    ];

    protected $casts = [
        'steps_to_reproduce' => 'array',
        'attachments' => 'array',
        'resolution_date' => 'datetime',
    ];

    /**
     * Get the project that owns the bug.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who reported the bug.
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the user assigned to the bug.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'red',
            'in_progress' => 'yellow',
            'testing' => 'blue',
            'resolved' => 'green',
            'closed' => 'gray',
            'duplicate' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Get severity badge color.
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'critical' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get status name in Arabic.
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'open' => 'مفتوح',
            'in_progress' => 'قيد التنفيذ',
            'testing' => 'قيد الاختبار',
            'resolved' => 'محلول',
            'closed' => 'مغلق',
            'duplicate' => 'مكرر',
            default => $this->status
        };
    }

    /**
     * Get severity name in Arabic.
     */
    public function getSeverityNameAttribute(): string
    {
        return match($this->severity) {
            'low' => 'منخفض',
            'medium' => 'متوسط',
            'high' => 'عالي',
            'critical' => 'حرج',
            default => $this->severity
        };
    }

    /**
     * Check if bug is open.
     */
    public function getIsOpenAttribute(): bool
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    /**
     * Check if bug is resolved.
     */
    public function getIsResolvedAttribute(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    /**
     * Calculate resolution time.
     */
    public function getResolutionTimeAttribute(): ?int
    {
        if (!$this->resolution_date) {
            return null;
        }
        
        return $this->created_at->diffInHours($this->resolution_date);
    }

    /**
     * Get days since reported.
     */
    public function getDaysSinceReportedAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Generate bug number.
     */
    public static function generateBugNumber(): string
    {
        $year = now()->year;
        $lastBug = self::whereYear('created_at', $year)
                      ->orderBy('id', 'desc')
                      ->first();
        
        $number = $lastBug ? (int)substr($lastBug->bug_number, -4) + 1 : 1;
        
        return "BUG-{$year}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope for open bugs.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope for resolved bugs.
     */
    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    /**
     * Scope for high severity bugs.
     */
    public function scopeHighSeverity($query)
    {
        return $query->whereIn('severity', ['high', 'critical']);
    }

    /**
     * Scope for specific project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope for specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }
}
