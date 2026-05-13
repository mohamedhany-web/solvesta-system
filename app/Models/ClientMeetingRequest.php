<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientMeetingRequest extends Model
{
    protected $fillable = [
        'reference_code',
        'client_id',
        'title',
        'description',
        'preferred_at',
        'alternative_times',
        'participants_count',
        'meeting_format',
        'status',
        'scheduled_at',
        'meeting_link',
        'location_notes',
        'admin_notes',
        'response_message',
        'assigned_to',
        'confirmed_by',
    ];

    protected $casts = [
        'preferred_at' => 'datetime',
        'scheduled_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function confirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'قيد المراجعة',
            'confirmed' => 'تم التأكيد',
            'declined' => 'مرفوض',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغى',
            default => $this->status,
        };
    }

    public function getMeetingFormatLabelAttribute(): string
    {
        return match ($this->meeting_format) {
            'online' => 'عن بُعد',
            'in_person' => 'حضوري',
            'either' => 'الأنسب للفريق',
            default => $this->meeting_format,
        };
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'], true);
    }
}
