<?php

namespace App\Support;

use App\Models\User;
use App\Policies\DepartmentAccess;
use Illuminate\Database\Eloquent\Builder;

class RecordScope
{
    public const OWN = 'own';
    public const TEAM = 'team';
    public const DEPARTMENT = 'department';
    public const ORGANIZATION = 'organization';

    public static function level(User $user): string
    {
        if ($user->hasRole(['super_admin', 'admin'])) {
            return self::ORGANIZATION;
        }

        if (DepartmentAccess::isDepartmentManager($user) || $user->hasRole('project_manager')) {
            return self::DEPARTMENT;
        }

        if ($user->employee?->is_team_lead) {
            return self::TEAM;
        }

        return self::OWN;
    }

    public static function apply(Builder $query, User $user, string $ownerColumn = 'owner_id', ?string $departmentColumn = 'department_id'): Builder
    {
        return match (self::level($user)) {
            self::ORGANIZATION => $query,
            self::DEPARTMENT, self::TEAM => $departmentColumn && $query->getModel()->isFillable($departmentColumn)
                ? $query->where($departmentColumn, DepartmentAccess::managedDepartmentId($user) ?? DepartmentAccess::userDepartmentId($user))
                : $query->where($ownerColumn, $user->id),
            default => $query->where($ownerColumn, $user->id),
        };
    }
}
