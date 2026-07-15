<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $devModules = json_encode(['projects', 'tasks', 'dev_workflow', 'github', 'bugs', 'meetings'], JSON_UNESCAPED_UNICODE);

        DB::table('departments')
            ->where(function ($q) {
                $q->whereIn('code', ['DEV', 'DEV-WEB', 'DEV-MOB'])
                    ->orWhere('name', 'like', '%تطوير%')
                    ->orWhere('name', 'like', '%مبرمج%');
            })
            ->where(function ($q) {
                $q->whereNull('sidebar_modules')
                    ->orWhere('sidebar_modules', '[]')
                    ->orWhere('sidebar_modules', '');
            })
            ->update(['sidebar_modules' => $devModules]);

        $permission = Permission::firstOrCreate([
            'name' => 'view-github-integration',
            'guard_name' => 'web',
        ]);

        $developer = Role::where('name', 'developer')->where('guard_name', 'web')->first();
        if ($developer && ! $developer->hasPermissionTo($permission)) {
            $developer->givePermissionTo($permission);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        //
    }
};
