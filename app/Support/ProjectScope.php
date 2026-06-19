<?php

namespace App\Support;

use App\Models\User;
use App\Policies\DepartmentAccess;
use Illuminate\Database\Eloquent\Builder;

class ProjectScope
{
    public static function apply(Builder $query, User $user): Builder
    {
        if ($user->can('view-all-projects')) {
            return $query;
        }

        if ($managedDeptId = DepartmentAccess::managedDepartmentId($user)) {
            return $query->where('department_id', $managedDeptId);
        }

        if ($deptId = DepartmentAccess::userDepartmentId($user)) {
            return $query->where('department_id', $deptId);
        }

        return $query->where(function (Builder $q) use ($user) {
            $q->where('project_manager_id', $user->id)
                ->orWhereHas('teamMembers', fn (Builder $team) => $team->where('user_id', $user->id));
        });
    }

    public static function applyToTasks(Builder $query, User $user): Builder
    {
        if ($user->can('view-all-tasks')) {
            return $query;
        }

        if ($user->can('view-all-projects')) {
            return $query;
        }

        $deptId = DepartmentAccess::managedDepartmentId($user)
            ?? DepartmentAccess::userDepartmentId($user);

        if ($deptId) {
            return $query->whereHas('project', fn (Builder $p) => $p->where('department_id', $deptId));
        }

        return $query->where('assigned_to', $user->id);
    }
}
