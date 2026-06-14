<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BdPartner extends Model
{
    protected $fillable = [
        'reference_code',
        'name',
        'company',
        'email',
        'phone',
        'partner_type',
        'country',
        'notes',
        'status',
        'assigned_to',
        'created_by',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(BdOpportunity::class, 'partner_id');
    }

    public function getPartnerTypeLabelAttribute(): string
    {
        return match ($this->partner_type) {
            'agency' => 'وكالة',
            'vendor' => 'مورّد',
            'referrer' => 'مُحيل',
            'strategic' => 'استراتيجي',
            default => 'أخرى',
        };
    }

    public static function generateReferenceCode(): string
    {
        $year = now()->format('Y');
        $last = static::where('reference_code', 'like', "PTR-{$year}-%")
            ->orderByDesc('id')->value('reference_code');
        $seq = 1;
        if ($last && preg_match('/PTR-\d{4}-(\d+)/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return sprintf('PTR-%s-%04d', $year, $seq);
    }
}
