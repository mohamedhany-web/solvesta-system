<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'subject',
        'description',
        'client_id',
        'assigned_to',
        'created_by',
        'priority',
        'category',
        'status',
        'sla_hours',
        'first_response_time',
        'resolution_time',
        'rating',
        'resolution_notes',
        'attachments',
    ];

    protected $casts = [
        'first_response_time' => 'datetime',
        'resolution_time' => 'datetime',
        'attachments' => 'array',
    ];

    /**
     * Get the client that owns the ticket.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user assigned to the ticket.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created the ticket.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'red',
            'in_progress' => 'yellow',
            'pending_client' => 'orange',
            'resolved' => 'blue',
            'closed' => 'green',
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
            'open' => 'مفتوح',
            'in_progress' => 'قيد التنفيذ',
            'pending_client' => 'في انتظار العميل',
            'resolved' => 'محلول',
            'closed' => 'مغلق',
            default => $this->status
        };
    }

    /**
     * Get category name in Arabic.
     */
    public function getCategoryNameAttribute(): string
    {
        return match($this->category) {
            'technical' => 'تقني',
            'billing' => 'فواتير',
            'general' => 'عام',
            'bug_report' => 'تقرير خطأ',
            'feature_request' => 'طلب ميزة',
            default => $this->category
        };
    }

    /**
     * Check if ticket is open.
     */
    public function getIsOpenAttribute(): bool
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    /**
     * Check if ticket is closed.
     */
    public function getIsClosedAttribute(): bool
    {
        return in_array($this->status, ['resolved', 'closed']);
    }

    /**
     * Calculate response time.
     */
    public function getResponseTimeAttribute(): ?int
    {
        if (!$this->first_response_time) {
            return null;
        }
        
        return $this->created_at->diffInMinutes($this->first_response_time);
    }

    /**
     * Calculate resolution time.
     */
    public function getResolutionTimeAttribute(): ?int
    {
        if (!$this->resolution_time) {
            return null;
        }
        
        return $this->created_at->diffInHours($this->resolution_time);
    }

    /**
     * Check if SLA is breached.
     */
    public function getIsSlaBreachedAttribute(): bool
    {
        if (!$this->sla_hours) {
            return false;
        }
        
        $hoursSinceCreated = $this->created_at->diffInHours(now());
        return $hoursSinceCreated > $this->sla_hours && !$this->is_closed;
    }

    /**
     * Scope for open tickets.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope for closed tickets.
     */
    public function scopeClosed($query)
    {
        return $query->whereIn('status', ['resolved', 'closed']);
    }

    /**
     * Scope for high priority tickets.
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'critical']);
    }

    /**
     * Scope for specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }
}
