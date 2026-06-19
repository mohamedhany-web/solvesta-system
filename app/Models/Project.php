<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'client_id',
        'contract_id',
        'sale_id',
        'department_id',
        'project_manager_id',
        'start_date',
        'end_date',
        'budget',
        'status',
        'kickoff_status',
        'priority',
        'progress_percentage',
        'team_members',
        'technologies',
        'project_type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'team_members' => 'array',
        'technologies' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the department that owns the project.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the project manager for the project.
     */
    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }
    
    /**
     * Get team members as an alias for teamMembers.
     */
    public function team(): BelongsToMany
    {
        return $this->teamMembers();
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class)->orderBy('sort_order');
    }

    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyReport::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the invoices for the project.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the contracts for the project.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the bugs for the project.
     */
    public function bugs(): HasMany
    {
        return $this->hasMany(Bug::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function repositories(): HasMany
    {
        return $this->hasMany(ProjectRepository::class);
    }

    public function activeRepository(): ?ProjectRepository
    {
        return $this->repositories()->where('is_active', true)->first();
    }

    /**
     * Get team members users.
     */
    public function teamMembers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_team_members', 'project_id', 'user_id');
    }

    public function needsTeamAssignment(): bool
    {
        return $this->department_id && ! $this->project_manager_id;
    }

    /**
     * Get project status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'planning' => 'yellow',
            'in_progress' => 'blue',
            'on_hold' => 'orange',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get project status name in Arabic.
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'planning' => 'قيد التخطيط',
            'in_progress' => 'قيد التنفيذ',
            'on_hold' => 'متوقف',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => $this->status
        };
    }

    /**
     * Get priority badge color.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'green',
            'medium' => 'yellow',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray'
        };
    }

    /**
     * Scope for active projects.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['planning', 'in_progress']);
    }

    /**
     * Scope for completed projects.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}