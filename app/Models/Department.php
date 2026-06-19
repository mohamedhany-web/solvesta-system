<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    public const SIDEBAR_MODULES = [
        'projects' => 'المشاريع و PMO',
        'tasks' => 'المهام ومساحة العمل',
        'dev_workflow' => 'بيئة التطوير',
        'github' => 'تكامل GitHub',
        'bugs' => 'الأخطاء والجودة',
        'clients' => 'العملاء (CRM)',
        'training' => 'التدريب والتطوير',
        'meetings' => 'الاجتماعات',
        'jobs' => 'التوظيف',
        'hr' => 'الموارد البشرية',
        'accounting' => 'المحاسبة والمالية',
        'promotions' => 'الترقيات والتطوير الوظيفي',
        'legal' => 'الشؤون القانونية',
    ];

    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'parent_id',
        'sidebar_modules',
        'default_role',
        'default_position',
        'kpi_profile',
        'career_track',
        'location',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sidebar_modules' => 'array',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getActiveEmployeesCountAttribute()
    {
        return $this->employees()->where('status', 'active')->count();
    }

    public function getActiveProjectsCountAttribute()
    {
        return $this->projects()->where('status', 'active')->count();
    }

    public function getTotalBudgetAttribute()
    {
        return $this->projects()->sum('budget');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Modules visible in sidebar: own config, else inherited from parent chain.
     */
    public function effectiveSidebarModules(): array
    {
        $modules = $this->sidebar_modules;
        if (is_array($modules) && count($modules) > 0) {
            return array_values($modules);
        }

        if ($this->relationLoaded('parent') ? $this->parent : $this->parent()->first()) {
            return $this->parent->effectiveSidebarModules();
        }

        return [];
    }

    public function descendantIds(): array
    {
        $ids = [];
        foreach ($this->children()->get() as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->descendantIds());
        }

        return $ids;
    }

    public function selfAndDescendantIds(): array
    {
        return array_merge([$this->id], $this->descendantIds());
    }
}
