<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;

class DepartmentAccess
{
    /**
     * Returns department_id where the user is manager, otherwise null.
     */
    public static function managedDepartmentId(User $user): ?int
    {
        $employee = $user->employee;
        if (!$employee) {
            return null;
        }

        return Department::query()
            ->where('manager_id', $employee->id)
            ->value('id');
    }

    public static function isDepartmentManager(User $user): bool
    {
        return (bool) self::managedDepartmentId($user);
    }

    public static function userDepartmentId(User $user): ?int
    {
        return $user->employee?->department_id;
    }
}

