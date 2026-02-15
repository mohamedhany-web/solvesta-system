<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'body',
        'type',
        'priority',
        'is_read',
        'read_at',
        'is_important',
        'is_deleted_by_sender',
        'is_deleted_by_receiver',
        'attachments',
        'parent_message_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_important' => 'boolean',
        'is_deleted_by_sender' => 'boolean',
        'is_deleted_by_receiver' => 'boolean',
        'attachments' => 'array',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark message as important
     */
    public function markAsImportant(): void
    {
        $this->update(['is_important' => true]);
    }

    /**
     * Mark message as not important
     */
    public function markAsNotImportant(): void
    {
        $this->update(['is_important' => false]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read messages
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for important messages
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }

    /**
     * Scope for messages by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for messages by priority
     */
    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for messages not deleted by user
     */
    public function scopeNotDeletedBy($query, $userId, $field = 'sender')
    {
        if ($field === 'sender') {
            return $query->where('is_deleted_by_sender', false);
        } else {
            return $query->where('is_deleted_by_receiver', false);
        }
    }

    /**
     * Get priority color
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'normal' => 'blue',
            'low' => 'gray',
            default => 'blue'
        };
    }

    /**
     * Get priority text
     */
    public function getPriorityTextAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'عاجل',
            'high' => 'عالي',
            'normal' => 'عادي',
            'low' => 'منخفض',
            default => 'عادي'
        };
    }

    /**
     * Get type text
     */
    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'direct' => 'مباشر',
            'group' => 'مجموعة',
            'announcement' => 'إعلان',
            default => 'مباشر'
        };
    }
}
