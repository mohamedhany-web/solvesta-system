<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $devPermissions = [
            'view-dev-workflow',
            'manage-project-repos',
            'create-git-branches',
            'create-pull-requests',
            'review-code',
        ];

        $githubPermissions = [
            'view-github-integration',
            'manage-github-integration',
        ];

        $all = array_merge($devPermissions, $githubPermissions);

        foreach ($all as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $roleMap = [
            'super_admin' => $all,
            'admin' => $all,
            'project_manager' => $all,
            'developer' => [
                'view-dev-workflow',
                'create-git-branches',
                'create-pull-requests',
                'view-github-integration',
            ],
            'designer' => [
                'view-dev-workflow',
                'create-git-branches',
                'create-pull-requests',
                'view-github-integration',
            ],
        ];

        foreach ($roleMap as $roleName => $perms) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->givePermissionTo($perms);
            }
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        // Permissions remain — revoking would break existing installs.
    }
};
