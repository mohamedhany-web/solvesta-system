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
    ) {}

    public static function forUser(?User $user): self
    {
        if (! $user) {
            return new self(null, [], false);
        }

        if (self::isUnrestrictedUser($user)) {
            return new self(
                $user->employee?->department,
                array_keys(Department::SIDEBAR_MODULES),
                true,
            );
        }

        $department = $user->employee?->department;
        if (! $department) {
            return new self(null, [], false);
        }

        $department->loadMissing('parent');

        return new self(
            $department,
            $department->effectiveSidebarModules(),
            false,
        );
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

        return in_array($module, $this->modules, true);
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
