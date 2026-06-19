<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            DevWorkflowPermissionSeeder::class,
            GitHubIntegrationPermissionSeeder::class,
            DepartmentSeeder::class,
            UserEmployeeSeeder::class,
            ClientSeeder::class,
            ChartOfAccountsSeeder::class,
            WalletSeeder::class,
            UserPermissionSeeder::class,
            SystemSettingsSeeder::class,
            ProjectSeeder::class,
            AttendanceSeeder::class,
            LeaveSeeder::class,
            SalarySeeder::class,
            QATestSeeder::class,
        ]);
    }
}