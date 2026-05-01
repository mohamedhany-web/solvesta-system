<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\Department;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'status',
        'start_date',
        'end_date',
        'max_participants',
        'cost',
        'instructor_id',
        'department_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function internshipApplications(): HasMany
    {
        return $this->hasMany(InternshipApplication::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}








