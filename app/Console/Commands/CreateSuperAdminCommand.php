<?php

namespace App\Console\Commands;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\PermissionRegistrar;

class CreateSuperAdminCommand extends Command
{
    protected $signature = 'solvesta:create-super-admin
                            {email=loransmogay@gmail.com : البريد الإلكتروني}
                            {--password=password123 : كلمة المرور}
                            {--name=Super Admin : اسم المستخدم}';

    protected $description = 'إنشاء أو استعادة حساب Super Admin';

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $password = (string) $this->option('password');
        $name = (string) $this->option('name');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );

        $user->syncRoles(['super_admin']);

        $department = Department::query()->where('id', 1)->first()
            ?? Department::query()->where('name', 'الإدارة العليا')->first()
            ?? Department::firstOrCreate(
                ['code' => 'EXEC'],
                [
                    'name' => 'الإدارة العليا',
                    'description' => 'قسم الإدارة العليا للشركة',
                    'is_active' => true,
                ]
            );

        $employee = Employee::where('employee_id', 'EMP001')->first();

        if ($employee) {
            $employee->update([
                'user_id' => $user->id,
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => $email,
                'department_id' => $department->id,
                'status' => 'active',
            ]);
        } else {
            Employee::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_id' => 'EMP001',
                    'first_name' => 'Super',
                    'last_name' => 'Admin',
                    'email' => $email,
                    'phone' => '+966501234567',
                    'hire_date' => now()->subYears(2),
                    'salary' => 50000,
                    'daily_hours' => 8,
                    'department_id' => $department->id,
                    'position' => 'System Administrator',
                    'employment_type' => 'full_time',
                    'status' => 'active',
                ]
            );
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info("تم بنجاح — user #{$user->id}");
        $this->line("البريد: {$email}");
        $this->line('الدور: super_admin');
        $this->warn('غيّر كلمة المرور بعد تسجيل الدخول.');

        return self::SUCCESS;
    }
}
