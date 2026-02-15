<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@solvesta.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@solvesta.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // إعطاء دور Super Admin
        if (!$superAdmin->hasRole('super-admin')) {
            $superAdmin->assignRole('super-admin');
        }

        // التحقق من وجود قسم الإدارة العليا
        $department = Department::firstOrCreate(
            ['name' => 'الإدارة العليا'],
            [
                'name' => 'الإدارة العليا',
                'description' => 'قسم الإدارة العليا للشركة',
            ]
        );

        // إنشاء موظف Super Admin
        $employee = Employee::firstOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'employee_id' => 'EMP001',
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@solvesta.com',
                'phone' => '+966501234567',
                'hire_date' => now()->subYears(2),
                'salary' => 50000,
                'daily_hours' => 8,
                'department_id' => $department->id,
                'position' => 'System Administrator',
                'employment_type' => 'full_time',
                'status' => 'active',
                'user_id' => $superAdmin->id,
            ]
        );

        $this->command->info('تم إنشاء Super Admin بنجاح!');
        $this->command->info('البريد الإلكتروني: admin@solvesta.com');
        $this->command->info('كلمة المرور: password');
    }
}
