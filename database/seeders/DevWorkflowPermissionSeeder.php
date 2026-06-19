<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DevWorkflowPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-dev-workflow',
            'manage-project-repos',
            'create-git-branches',
            'create-pull-requests',
            'review-code',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $rolePermissions = [
            'super_admin' => $permissions,
            'admin' => $permissions,
            'project_manager' => $permissions,
            'developer' => ['view-dev-workflow', 'create-git-branches', 'create-pull-requests'],
            'designer' => ['view-dev-workflow', 'create-git-branches', 'create-pull-requests'],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($perms);
            }
        }
    }
}
