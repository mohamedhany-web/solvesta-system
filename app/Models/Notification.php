<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'message' => 'رسالة',
            'task' => 'مهمة',
            'project' => 'مشروع',
            'meeting' => 'اجتماع',
            'training' => 'تدريب',
            'official_mail' => 'بريد رسمي',
            'action_required' => 'إجراء مطلوب',
            'critical' => 'حرج',
            'system' => 'نظام',
            default => 'إشعار',
        };
    }

    /**
     * Resolve a related resource URL from notification data when available.
     */
    public function actionUrl(): ?string
    {
        $data = $this->data ?? [];

        if (! empty($data['url']) && is_string($data['url'])) {
            return $data['url'];
        }

        if (! empty($data['action_url']) && is_string($data['action_url'])) {
            return $data['action_url'];
        }

        try {
            if (! empty($data['message_id'])) {
                return route('messages.show', $data['message_id']);
            }
            if (! empty($data['mail_message_id'])) {
                return route('official-mail.show', $data['mail_message_id']);
            }
            if (! empty($data['task_id'])) {
                return route('tasks.show', $data['task_id']);
            }
            if (! empty($data['meeting_id'])) {
                return route('meetings.show', $data['meeting_id']);
            }
            if (! empty($data['training_id'])) {
                return route('training.show', $data['training_id']);
            }
            if (! empty($data['project_id']) && empty($data['task_id'])) {
                return route('projects.show', $data['project_id']);
            }
        } catch (\Throwable) {
            return null;
        }

        return null;
    }

    public function actionLabel(): string
    {
        return match ($this->type) {
            'message' => 'فتح الرسالة',
            'task' => 'فتح المهمة',
            'project' => 'فتح المشروع',
            'meeting' => 'فتح الاجتماع',
            'training' => 'فتح التدريب',
            'official_mail' => 'فتح البريد',
            default => 'عرض التفاصيل المرتبطة',
        };
    }
}
