<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalRule extends Model
{
    protected $fillable = [
        'entity_type',
        'min_amount',
        'max_amount',
        'steps',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'steps' => 'array',
        'is_active' => 'boolean',
    ];
}
