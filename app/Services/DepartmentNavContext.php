<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;

class DepartmentNavContext
{
    public function __construct(
        public readonly ?Department $department,
        public readonly array $modules,
        public readonly bool $unrestricted,
        public readonly ?User $user = null,
    ) {}

    public static function forUser(?User $user): self
    {
        if (! $user) {
            return new self(null, [], false, null);
        }

        RoleNavAccessSync::ensure();

        if (self::isUnrestrictedUser($user)) {
            return new self(
                $user->employee?->department,
                array_keys(Department::SIDEBAR_MODULES),
                true,
                $user,
            );
        }

        $department = $user->employee?->department;
        $department?->loadMissing('parent');

        $roleDefaults = DepartmentProfileService::defaultSidebarModulesForUser($user);
        $deptModules = $department?->effectiveSidebarModules() ?? [];

        // Always keep role defaults so staff never lose sidebar access when department modules are empty.
        $modules = array_values(array_unique(array_merge($deptModules, $roleDefaults)));

        return new self($department, $modules, false, $user);
    }

    public static function isUnrestrictedUser(User $user): bool
    {
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        return $user->can('view-all-projects')
            || $user->can('view-departments')
            || $user->can('view-all-tasks');
    }

    public function canShow(string $module): bool
    {
        if ($this->unrestricted) {
            return true;
        }

        if (in_array($module, $this->modules, true)) {
            return true;
        }

        // Permission always wins — no migrate/seed required for visibility.
        return match ($module) {
            'projects' => (bool) ($this->user?->can('view-own-projects') || $this->user?->can('view-all-projects')),
            'tasks' => (bool) ($this->user?->can('view-own-tasks') || $this->user?->can('view-all-tasks')),
            'dev_workflow' => (bool) ($this->user?->can('view-dev-workflow') || $this->user?->can('manage-github-integration')),
            'github' => (bool) ($this->user?->can('view-github-integration') || $this->user?->can('manage-github-integration')),
            'bugs' => (bool) ($this->user?->can('view-bugs') || $this->user?->can('view-qa')),
            'training' => (bool) $this->user?->can('view-training'),
            'meetings' => (bool) $this->user?->can('view-meetings'),
            'jobs' => (bool) $this->user?->can('view-jobs'),
            'hr' => (bool) ($this->user?->can('view-employees') || $this->user?->can('view-attendance')),
            'promotions' => (bool) $this->user?->can('view-employees'),
            'accounting' => (bool) $this->user?->can('view-finance'),
            'clients' => (bool) ($this->user?->can('view-clients') || $this->user?->can('view-sales')),
            'legal' => (bool) $this->user?->can('view-contracts'),
            default => false,
        };
    }

    public function departmentLabel(): ?string
    {
        if (! $this->department) {
            return null;
        }

        if ($this->department->parent) {
            return $this->department->parent->name.' › '.$this->department->name;
        }

        return $this->department->name;
    }
}
