<?php

namespace App\Services;

use App\Models\Department;
use Spatie\Permission\Models\Role;

class DepartmentProfileService
{
    /** ملفات الأقسام الافتراضية — تُزامَن مع قاعدة البيانات عبر Seeder */
    public const PROFILES = [
        'ADMIN' => [
            'name' => 'الإدارة العامة',
            'default_role' => 'employee',
            'default_position' => 'موظف إداري',
            'kpi_profile' => 'admin',
            'career_track' => 'administration',
            'sidebar_modules' => ['meetings', 'training', 'clients'],
            'levels' => ['موظف', 'أخصائي', 'مدير مساعد', 'مدير'],
        ],
        'LEGAL' => [
            'name' => 'الشؤون القانونية',
            'default_role' => 'employee',
            'default_position' => 'أخصائي قانوني',
            'kpi_profile' => 'legal',
            'career_track' => 'legal',
            'sidebar_modules' => ['clients', 'meetings'],
            'levels' => ['مساعد قانوني', 'أخصائي', 'مستشار', 'رئيس قانوني'],
        ],
        'ACC' => [
            'name' => 'المحاسبة',
            'default_role' => 'accountant',
            'default_position' => 'محاسب',
            'kpi_profile' => 'accountant_strict',
            'career_track' => 'accounting',
            'sidebar_modules' => ['accounting', 'clients', 'meetings'],
            'levels' => ['محاسب junior', 'محاسب', 'أمين صندوق', 'مدير مالي'],
        ],
        'HR' => [
            'name' => 'الموارد البشرية',
            'default_role' => 'hr',
            'default_position' => 'أخصائي موارد بشرية',
            'kpi_profile' => 'hr_strict',
            'career_track' => 'hr',
            'sidebar_modules' => ['hr', 'training', 'jobs', 'promotions', 'meetings'],
            'levels' => ['مساعد HR', 'أخصائي', 'مدير HR', 'مدير أعلى'],
        ],
        'SALES' => [
            'name' => 'المبيعات',
            'default_role' => 'sales_rep',
            'default_position' => 'مسؤول مبيعات',
            'kpi_profile' => 'sales_strict',
            'career_track' => 'sales',
            'sidebar_modules' => ['clients', 'meetings', 'tasks'],
            'levels' => ['مبتدئ', 'مبيعات', 'كبير مبيعات', 'مدير مبيعات'],
        ],
        'PROMO' => [
            'name' => 'الترقيات والتطوير الوظيفي',
            'default_role' => 'hr',
            'default_position' => 'أخصائي ترقيات',
            'kpi_profile' => 'hr_strict',
            'career_track' => 'promotions',
            'sidebar_modules' => ['hr', 'promotions', 'training', 'meetings'],
            'levels' => ['محلل', 'أخصائي ترقيات', 'مدير التطوير الوظيفي'],
        ],
        'DEV' => [
            'name' => 'قسم التطوير',
            'default_role' => 'developer',
            'default_position' => 'مبرمج',
            'kpi_profile' => 'developer_strict',
            'career_track' => 'technical',
            'sidebar_modules' => null,
            'levels' => ['Junior مبرمج', 'مبرمج', 'Senior مبرمج', 'Tech Lead', 'مدير تقني'],
        ],
        'DEV-WEB' => [
            'name' => 'تطوير الويب',
            'default_role' => 'developer',
            'default_position' => 'مبرمج ويب',
            'kpi_profile' => 'developer_strict',
            'career_track' => 'technical',
            'sidebar_modules' => ['projects', 'tasks', 'dev_workflow', 'github', 'bugs', 'meetings'],
            'levels' => ['Junior مبرمج', 'مبرمج ويب', 'Senior', 'Team Lead'],
        ],
        'DEV-MOB' => [
            'name' => 'تطوير الموبايل',
            'default_role' => 'developer',
            'default_position' => 'مبرمج موبايل',
            'kpi_profile' => 'developer_strict',
            'career_track' => 'technical',
            'sidebar_modules' => ['projects', 'tasks', 'dev_workflow', 'github', 'bugs', 'meetings'],
            'levels' => ['Junior مبرمج', 'مبرمج موبايل', 'Senior', 'Team Lead'],
        ],
    ];

    public static function forDepartment(?Department $department): array
    {
        if (! $department) {
            return self::fallback();
        }

        $code = strtoupper($department->code ?? '');

        if (isset(self::PROFILES[$code])) {
            return array_merge(self::PROFILES[$code], [
                'code' => $code,
                'department_id' => $department->id,
                'department_name' => $department->name,
            ]);
        }

        return [
            'code' => $code,
            'department_id' => $department->id,
            'department_name' => $department->name,
            'default_role' => $department->default_role ?? 'employee',
            'default_position' => $department->default_position ?? 'موظف',
            'kpi_profile' => $department->kpi_profile ?? 'default',
            'career_track' => $department->career_track ?? 'general',
            'sidebar_modules' => $department->effectiveSidebarModules(),
            'levels' => self::defaultLevels(),
        ];
    }

    public static function assignableRoles(): array
    {
        return Role::query()->where('guard_name', 'web')
            ->whereNotIn('name', ['super_admin', 'client'])
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }

    public static function roleLabels(): array
    {
        return [
            'employee' => 'موظف عام',
            'developer' => 'مبرمج',
            'designer' => 'مصمم',
            'sales_rep' => 'مبيعات',
            'accountant' => 'محاسب',
            'hr' => 'موارد بشرية',
            'project_manager' => 'مدير مشروع',
            'support' => 'دعم فني',
            'admin' => 'مدير نظام',
        ];
    }

    public static function kpiProfileLabels(): array
    {
        return [
            'developer_strict' => 'KPI صارم — مبرمجين (إنجاز 40% + جودة 25%)',
            'sales_strict' => 'KPI صارم — مبيعات (إنجاز 45% + تقييم TL 25%)',
            'accountant_strict' => 'KPI صارم — محاسبة (دقة 35% + التزام 30%)',
            'hr_strict' => 'KPI صارم — HR (التزام 30% + جودة 30%)',
            'legal' => 'KPI — قانوني (جودة 40% + التزام 25%)',
            'admin' => 'KPI — إدارة عامة',
            'default' => 'KPI افتراضي',
        ];
    }

    public static function applyRoleToUser(\App\Models\User $user, string $roleName): void
    {
        if (! Role::where('name', $roleName)->where('guard_name', 'web')->exists()) {
            $roleName = 'employee';
        }

        $user->syncRoles([$roleName]);
    }

    private static function fallback(): array
    {
        return [
            'default_role' => 'employee',
            'default_position' => 'موظف',
            'kpi_profile' => 'default',
            'career_track' => 'general',
            'sidebar_modules' => [],
            'levels' => self::defaultLevels(),
        ];
    }

    private static function defaultLevels(): array
    {
        return ['مبتدئ', 'متوسط', 'متقدم', 'خبير'];
    }
}
