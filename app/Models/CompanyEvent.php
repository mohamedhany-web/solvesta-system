<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CompanyEvent extends Model
{
    protected $fillable = [
        'actor_type',
        'actor_id',
        'subject_type',
        'subject_id',
        'event',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function actorName(): string
    {
        if ($this->actor_type === 'system') {
            return 'النظام';
        }

        return $this->actor?->name ?? 'مستخدم';
    }

    public function labelAr(): string
    {
        return match ($this->event) {
            'status.changed' => 'تغيير الحالة',
            'lifecycle.bootstrapped' => 'تسجيل دورة الحياة',
            'approved' => 'موافقة',
            'rejected' => 'رفض',
            'sla.breached' => 'تجاوز SLA',
            'work_item.created' => 'إنشاء بند عمل',
            'work_item.completed' => 'إكمال بند عمل',
            'exception.opened' => 'فتح استثناء',
            'exception.resolved' => 'إغلاق استثناء',
            'approval.requested' => 'طلب موافقة',
            default => $this->event,
        };
    }
}
