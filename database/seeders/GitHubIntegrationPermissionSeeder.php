<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GitHubIntegrationPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view-github-integration',
            'manage-github-integration',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $map = [
            'super_admin' => $permissions,
            'admin' => $permissions,
            'project_manager' => ['view-github-integration', 'manage-github-integration'],
            'developer' => ['view-github-integration'],
            'designer' => ['view-github-integration'],
        ];

        foreach ($map as $roleName => $perms) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($perms);
            }
        }
    }
}
