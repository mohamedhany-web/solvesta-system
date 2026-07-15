<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleNavAccessSync
{
    public const CACHE_KEY = 'solvesta.role_nav_access_synced_v2';

    /**
     * Ensure sidebar-related permissions exist and are assigned to staff roles.
     * Runs once (cached) so production never depends on manual artisan seed/migrate.
     */
    public static function ensure(): void
    {
        try {
            Cache::remember(self::CACHE_KEY, now()->addDays(7), function () {
                self::sync();

                return true;
            });
        } catch (\Throwable) {
            // Never break page loads if permissions tables are unavailable.
        }
    }

    public static function sync(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $defs = [
            'developer' => [
                'view-dashboard',
                'view-own-projects',
                'view-own-tasks',
                'edit-tasks',
                'view-bugs',
                'create-bugs',
                'edit-bugs',
                'view-qa',
                'create-qa',
                'edit-qa',
                'view-dev-workflow',
                'create-git-branches',
                'create-pull-requests',
                'view-github-integration',
                'view-training',
                'view-meetings',
            ],
            'designer' => [
                'view-dashboard',
                'view-own-projects',
                'view-own-tasks',
                'edit-tasks',
                'view-design',
                'create-design',
                'edit-design',
                'view-dev-workflow',
                'view-github-integration',
                'view-training',
                'view-meetings',
            ],
            'employee' => [
                'view-dashboard',
                'view-own-projects',
                'view-own-tasks',
                'edit-tasks',
                'view-training',
                'view-meetings',
            ],
        ];

        $allNames = collect($defs)->flatten()->unique()->values();

        foreach ($allNames as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        foreach ($defs as $roleName => $perms) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->givePermissionTo($perms);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
