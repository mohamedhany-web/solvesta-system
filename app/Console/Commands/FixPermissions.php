<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserPermission;
use Spatie\Permission\Models\Permission;

class FixPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:fix {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix and display user permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $this->checkUserPermissions($userId);
        } else {
            $this->info('عرض جميع المستخدمين:');
            $users = User::with('roles')->get();
            
            $tableData = [];
            foreach ($users as $user) {
                $tableData[] = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->roles->pluck('name')->join(', ') ?: 'لا يوجد',
                ];
            }
            
            $this->table(['ID', 'الاسم', 'البريد', 'الأدوار'], $tableData);
            
            $this->newLine();
            $this->info('لعرض صلاحيات مستخدم معين، استخدم:');
            $this->comment('php artisan permissions:fix {user_id}');
        }

        return 0;
    }

    private function checkUserPermissions($userId)
    {
        $user = User::with(['roles.permissions', 'customPermissions'])->find($userId);

        if (!$user) {
            $this->error("المستخدم بـ ID {$userId} غير موجود!");
            return;
        }

        $this->info("═══════════════════════════════════════");
        $this->info("معلومات المستخدم:");
        $this->info("═══════════════════════════════════════");
        $this->line("الاسم: {$user->name}");
        $this->line("البريد: {$user->email}");
        $this->newLine();

        // عرض الأدوار
        $this->info("الأدوار الوظيفية:");
        if ($user->roles->count() > 0) {
            foreach ($user->roles as $role) {
                $this->line("  ✓ {$role->name}");
            }
        } else {
            $this->warn("  لا يوجد أدوار");
        }
        $this->newLine();

        // عرض صلاحيات الدور
        $this->info("صلاحيات الدور:");
        $rolePermissions = [];
        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                $rolePermissions[] = $permission->name;
            }
        }
        
        if (count($rolePermissions) > 0) {
            foreach (array_unique($rolePermissions) as $perm) {
                $this->line("  ✓ {$perm}");
            }
        } else {
            $this->warn("  لا توجد صلاحيات من الدور");
        }
        $this->newLine();

        // عرض الصلاحيات المخصصة
        $this->info("الصلاحيات المخصصة (تجاوزات):");
        $customPerms = UserPermission::where('user_id', $userId)->get();
        
        if ($customPerms->count() > 0) {
            foreach ($customPerms as $perm) {
                $status = $perm->is_enabled ? '✓ مفعلة' : '✗ معطلة';
                $color = $perm->is_enabled ? 'info' : 'error';
                $this->line("  {$status}: {$perm->permission_key}");
            }
        } else {
            $this->comment("  لا توجد صلاحيات مخصصة");
        }
        $this->newLine();

        // عرض جميع الصلاحيات الفعلية
        $this->info("═══════════════════════════════════════");
        $this->info("جميع الصلاحيات الفعلية (النهائية):");
        $this->info("═══════════════════════════════════════");
        
        $allPermissions = $user->getAllPermissions();
        if ($allPermissions->count() > 0) {
            foreach ($allPermissions as $perm) {
                $this->line("  ✓ {$perm->name}");
            }
            $this->newLine();
            $this->info("المجموع: " . $allPermissions->count() . " صلاحية");
        } else {
            $this->warn("لا توجد صلاحيات فعلية!");
        }
        
        $this->newLine();
        
        // خيار لحذف الصلاحيات المخصصة
        if ($customPerms->count() > 0) {
            if ($this->confirm('هل تريد حذف جميع الصلاحيات المخصصة لهذا المستخدم؟')) {
                UserPermission::where('user_id', $userId)->delete();
                $this->info('✓ تم حذف جميع الصلاحيات المخصصة بنجاح!');
                $this->comment('الآن المستخدم سيستخدم صلاحيات الدور فقط.');
            }
        }
    }
}
