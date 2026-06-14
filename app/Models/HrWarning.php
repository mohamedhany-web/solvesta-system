<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HrWarning extends Model
{
    protected $fillable = [
        'reference_code',
        'user_id',
        'task_id',
        'type',
        'reason',
        'kpi_deduction_points',
        'status',
        'investigation_status',
        'issued_by',
        'resolved_by',
        'resolved_at',
        'hr_notes',
    ];

    protected $casts = [
        'kpi_deduction_points' => 'decimal:2',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'task_delay' => 'تأخير مهمة',
            'kpi_deduction' => 'خصم KPI',
            'attendance' => 'حضور',
            'conduct' => 'سلوك',
            default => 'أخرى',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'نشط',
            'resolved' => 'مُحلّ',
            'escalated' => 'مُصعّد',
            default => $this->status,
        };
    }

    public static function generateReferenceCode(): string
    {
        $year = now()->format('Y');
        $last = static::where('reference_code', 'like', "WRN-{$year}-%")
            ->orderByDesc('id')->value('reference_code');
        $seq = 1;
        if ($last && preg_match('/WRN-\d{4}-(\d+)/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return sprintf('WRN-%s-%04d', $year, $seq);
    }
}
