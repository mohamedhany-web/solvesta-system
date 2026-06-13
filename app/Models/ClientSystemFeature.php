<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientSystemFeature extends Model
{
    protected $fillable = [
        'reference_code',
        'client_system_project_id',
        'type',
        'title',
        'description',
        'status',
        'priority',
        'submitted_by_client_account_id',
        'assigned_to',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientSystemProject::class, 'client_system_project_id');
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(ClientAccount::class, 'submitted_by_client_account_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ClientSystemFeatureUpdate::class)->orderByDesc('created_at');
    }

    public function clientVisibleUpdates(): HasMany
    {
        return $this->hasMany(ClientSystemFeatureUpdate::class)
            ->where('visibility', 'client')
            ->orderByDesc('created_at');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'feature' => 'ميزة جديدة',
            'bug' => 'خطأ / مشكلة',
            'improvement' => 'تحسين',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'مُرسَل',
            'reviewing' => 'قيد المراجعة',
            'approved' => 'معتمد',
            'in_progress' => 'قيد التنفيذ',
            'testing' => 'اختبار',
            'completed' => 'مكتمل',
            'rejected' => 'مرفوض',
            'cancelled' => 'ملغى',
            default => $this->status,
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'منخفضة',
            'medium' => 'متوسطة',
            'high' => 'عالية',
            'urgent' => 'عاجلة',
            default => $this->priority,
        };
    }

    public static function generateReferenceCode(): string
    {
        $prefix = 'FEAT-'.now()->format('Ymd').'-';
        $last = static::where('reference_code', 'like', $prefix.'%')->orderByDesc('id')->value('reference_code');
        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
