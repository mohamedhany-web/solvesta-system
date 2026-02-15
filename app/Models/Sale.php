<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_source',
        'client_id',
        'assigned_to',
        'product_service',
        'estimated_value',
        'actual_value',
        'stage',
        'probability_percentage',
        'expected_close_date',
        'actual_close_date',
        'notes',
        'competitors',
        'decision_makers',
        'project_id',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'actual_value' => 'decimal:2',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
        'competitors' => 'array',
        'decision_makers' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function salesRep(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to', 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
