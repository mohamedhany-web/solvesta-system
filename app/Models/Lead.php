<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    protected $fillable = [
        'reference_code',
        'source',
        'status',
        'name',
        'email',
        'phone',
        'company',
        'service_interest',
        'notes',
        'estimated_budget',
        'assigned_to',
        'created_by',
        'contact_request_id',
        'converted_client_id',
        'converted_sale_id',
        'lost_reason',
    ];

    protected $casts = [
        'estimated_budget' => 'decimal:2',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function contactRequest(): BelongsTo
    {
        return $this->belongsTo(ContactRequest::class);
    }

    public function convertedClient(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'converted_client_id');
    }

    public function convertedSale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'converted_sale_id');
    }

    public function getSourceLabelAttribute(): string
    {
        return match ($this->source) {
            'ads' => 'إعلانات',
            'social_media' => 'سوشيال ميديا',
            'referral' => 'إحالة',
            'website' => 'موقع الشركة',
            'bd_outreach' => 'تطوير أعمال',
            'event' => 'فعالية',
            default => 'أخرى',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'new' => 'جديد',
            'contacted' => 'تم التواصل',
            'qualified' => 'مؤهل',
            'converted' => 'تحوّل لمبيعات',
            'lost' => 'مفقود',
            'on_hold' => 'معلّق',
            default => $this->status,
        };
    }

    public static function generateReferenceCode(): string
    {
        $prefix = 'LEAD-'.now()->format('Ymd').'-';
        $last = static::where('reference_code', 'like', $prefix.'%')->orderByDesc('id')->value('reference_code');
        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
