<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'user_id',
        'status',
        'attendance_rate',
        'grade',
        'certificate_issued',
        'certificate_issued_at',
        'notes',
    ];

    protected $casts = [
        'certificate_issued' => 'boolean',
        'certificate_issued_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}








