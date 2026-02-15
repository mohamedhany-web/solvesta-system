<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'الإدارة العامة',
                'code' => 'ADM',
                'description' => 'الإدارة العامة والتنفيذية للشركة',
                'is_active' => true,
            ],
            [
                'name' => 'المبيعات',
                'code' => 'SAL',
                'description' => 'قسم المبيعات والتسويق',
                'is_active' => true,
            ],
            [
                'name' => 'التطوير',
                'code' => 'DEV',
                'description' => 'قسم تطوير البرمجيات',
                'is_active' => true,
            ],
            [
                'name' => 'الموارد البشرية',
                'code' => 'HR',
                'description' => 'قسم الموارد البشرية',
                'is_active' => true,
            ],
            [
                'name' => 'المحاسبة',
                'code' => 'ACC',
                'description' => 'قسم المحاسبة والشؤون المالية',
                'is_active' => true,
            ],
            [
                'name' => 'دعم العملاء',
                'code' => 'SUP',
                'description' => 'قسم دعم العملاء',
                'is_active' => true,
            ],
            [
                'name' => 'التصميم',
                'code' => 'DES',
                'description' => 'قسم التصميم الجرافيكي',
                'is_active' => true,
            ],
            [
                'name' => 'ضمان الجودة',
                'code' => 'QA',
                'description' => 'قسم ضمان الجودة واختبار المنتجات',
                'is_active' => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['code' => $department['code']],
                $department
            );
        }
    }
}
