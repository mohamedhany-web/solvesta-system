<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowDefinition extends Model
{
    protected $fillable = [
        'entity_type',
        'name',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(WorkflowState::class)->orderBy('sort_order');
    }

    public function transitions(): HasMany
    {
        return $this->hasMany(WorkflowTransition::class);
    }

    public function stateByKey(string $key): ?WorkflowState
    {
        return $this->states->firstWhere('key', $key);
    }
}
