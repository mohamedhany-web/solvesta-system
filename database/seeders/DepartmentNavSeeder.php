<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentNavSeeder extends Seeder
{
    public function run(): void
    {
        $devParent = Department::updateOrCreate(
            ['code' => 'DEV'],
            [
                'name' => 'قسم التطوير',
                'description' => 'القسم الرئيسي للمبرمجين وفرق التقنية',
                'parent_id' => null,
                'sidebar_modules' => null,
                'is_active' => true,
            ]
        );

        Department::updateOrCreate(
            ['code' => 'DEV-WEB'],
            [
                'name' => 'تطوير الويب',
                'description' => 'فرق تطوير المواقع والتطبيقات الويب',
                'parent_id' => $devParent->id,
                'sidebar_modules' => ['projects', 'tasks', 'dev_workflow', 'github', 'bugs', 'meetings'],
                'is_active' => true,
            ]
        );

        Department::updateOrCreate(
            ['code' => 'DEV-MOB'],
            [
                'name' => 'تطوير الموبايل',
                'description' => 'فرق تطوير تطبيقات iOS و Android',
                'parent_id' => $devParent->id,
                'sidebar_modules' => ['projects', 'tasks', 'dev_workflow', 'github', 'bugs', 'meetings'],
                'is_active' => true,
            ]
        );

        Department::updateOrCreate(
            ['code' => 'HR'],
            [
                'name' => 'الموارد البشرية',
                'parent_id' => null,
                'sidebar_modules' => ['hr', 'training', 'jobs', 'meetings'],
                'is_active' => true,
            ]
        );
    }
}
