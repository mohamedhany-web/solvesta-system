<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentContract extends Model
{
    protected $fillable = [
        'employee_id',
        'contract_number',
        'start_date',
        'end_date',
        'base_salary',
        'employment_type',
        'document_path',
        'status',
        'signed_at',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'base_salary' => 'decimal:2',
        'signed_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateContractNumber(): string
    {
        $prefix = 'EMP-CTR-'.now()->format('Y').'-';
        $last = static::where('contract_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('contract_number');

        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'draft' => 'مسودة',
            'active' => 'ساري',
            'terminated' => 'منتهي',
            'expired' => 'منتهي الصلاحية',
            default => $this->status,
        };
    }

    public function employmentTypeLabel(): string
    {
        return match ($this->employment_type) {
            'full_time' => 'دوام كامل',
            'part_time' => 'دوام جزئي',
            'contract' => 'عقد',
            'intern' => 'متدرب',
            default => $this->employment_type,
        };
    }
}
