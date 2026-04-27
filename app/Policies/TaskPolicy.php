<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can('view-all-tasks')) {
            return true;
        }

        if (DepartmentAccess::isDepartmentManager($user)) {
            return true;
        }

        return $user->can('view-own-tasks');
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can('view-all-tasks')) {
            return true;
        }

        $managedDeptId = DepartmentAccess::managedDepartmentId($user);
        if ($managedDeptId) {
            return (int) $task->project?->department_id === (int) $managedDeptId;
        }

        return (int) $task->assigned_to === (int) $user->id;
    }

    public function create(User $user, ?Project $project = null): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can('create-tasks')) {
            return true;
        }

        // Department manager can create tasks only for department projects
        $managedDeptId = DepartmentAccess::managedDepartmentId($user);
        if ($managedDeptId && $project) {
            return (int) $project->department_id === (int) $managedDeptId;
        }

        return false;
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can('edit-tasks')) {
            return true;
        }

        $managedDeptId = DepartmentAccess::managedDepartmentId($user);
        if ($managedDeptId) {
            return (int) $task->project?->department_id === (int) $managedDeptId;
        }

        // assignee can update own task if allowed
        return $user->can('edit-own-tasks') && (int) $task->assigned_to === (int) $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can('delete-tasks')) {
            return true;
        }

        $managedDeptId = DepartmentAccess::managedDepartmentId($user);
        if ($managedDeptId) {
            return (int) $task->project?->department_id === (int) $managedDeptId;
        }

        return false;
    }
}

