<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncJobPermissions extends Command
{
    protected $signature = 'permissions:sync-jobs';

    protected $description = 'إنشاء صلاحيات التوظيف وربطها بأدوار super_admin و admin و hr';

    public function handle(): int
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $jobPermissions = [
            'view-jobs',
            'create-jobs',
            'edit-jobs',
            'delete-jobs',
        ];

        foreach ($jobPermissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            $this->line("✓ {$name}");
        }

        foreach (['super_admin', 'admin', 'hr'] as $roleName) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if (! $role) {
                $this->warn("تخطي الدور: {$roleName} (غير موجود)");

                continue;
            }
            $role->givePermissionTo($jobPermissions);
            $this->info("تم ربط الصلاحيات بدور: {$roleName}");
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $this->info('تم. سجّل خروجاً ثم ادخل من جديد إذا لم يظهر القسم.');

        return self::SUCCESS;
    }
}
