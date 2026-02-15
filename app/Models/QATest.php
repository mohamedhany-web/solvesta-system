<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QATest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_number',
        'name',
        'description',
        'project_id',
        'created_by',
        'assigned_to',
        'type',
        'status',
        'priority',
        'test_steps',
        'expected_result',
        'actual_result',
        'preconditions',
        'test_data',
        'environment',
        'notes',
        'execution_time',
        'executed_at',
    ];

    protected $casts = [
        'executed_at' => 'datetime',
        'execution_time' => 'integer',
    ];

    /**
     * Get the project that owns the test.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who created the test.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user assigned to the test.
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
            'pending' => 'yellow',
            'running' => 'blue',
            'passed' => 'green',
            'failed' => 'red',
            'skipped' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get type badge color.
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'unit' => 'blue',
            'integration' => 'purple',
            'functional' => 'green',
            'performance' => 'orange',
            'security' => 'red',
            'usability' => 'pink',
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
            'critical' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get status name in Arabic.
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'pending' => 'معلق',
            'running' => 'قيد التنفيذ',
            'passed' => 'نجح',
            'failed' => 'فشل',
            'skipped' => 'تم التخطي',
            default => $this->status
        };
    }

    /**
     * Get type name in Arabic.
     */
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'unit' => 'وحدة',
            'integration' => 'تكامل',
            'functional' => 'وظيفي',
            'performance' => 'أداء',
            'security' => 'أمان',
            'usability' => 'سهولة الاستخدام',
            default => $this->type
        };
    }

    /**
     * Get priority name in Arabic.
     */
    public function getPriorityNameAttribute(): string
    {
        return match($this->priority) {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            'critical' => 'حرجة',
            default => $this->priority
        };
    }

    /**
     * Check if test is passed.
     */
    public function getIsPassedAttribute(): bool
    {
        return $this->status === 'passed';
    }

    /**
     * Check if test is failed.
     */
    public function getIsFailedAttribute(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get execution time in human readable format.
     */
    public function getExecutionTimeHumanAttribute(): ?string
    {
        if (!$this->execution_time) {
            return null;
        }

        if ($this->execution_time < 60) {
            return $this->execution_time . ' ثانية';
        }

        $minutes = floor($this->execution_time / 60);
        $seconds = $this->execution_time % 60;

        return $minutes . ' دقيقة و ' . $seconds . ' ثانية';
    }

    /**
     * Generate test number.
     */
    public static function generateTestNumber(): string
    {
        $year = now()->year;
        $lastTest = self::whereYear('created_at', $year)
                      ->orderBy('id', 'desc')
                      ->first();
        
        $number = $lastTest ? (int)substr($lastTest->test_number, -4) + 1 : 1;
        
        return "QA-{$year}-" . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Scope for pending tests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for passed tests.
     */
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    /**
     * Scope for failed tests.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for specific project.
     */
    public function scopeForProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope for specific type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
