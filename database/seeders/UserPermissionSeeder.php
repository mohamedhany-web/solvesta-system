<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserPermission;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // تعريف جميع الصلاحيات المخصصة المتاحة
        $availablePermissions = [
            // Sidebar Sections
            'sidebar_dashboard' => 'لوحة التحكم',
            'sidebar_administration' => 'قسم الإدارة العليا',
            'sidebar_hr' => 'قسم الموارد البشرية',
            'sidebar_projects' => 'قسم المشاريع',
            'sidebar_operations' => 'قسم العمليات',
            'sidebar_development' => 'قسم التطوير والبرمجة',
            'sidebar_design' => 'قسم التصميم',
            'sidebar_business' => 'قسم المبيعات والتسويق',
            'sidebar_support' => 'قسم الدعم الفني',
            'sidebar_finance' => 'قسم المالية والمحاسبة',
            'sidebar_legal' => 'قسم الشؤون القانونية',
            
            // Sidebar Links
            'sidebar_users' => 'المستخدمين',
            'sidebar_reports' => 'التقارير والتحليل',
            'sidebar_departments' => 'الأقسام',
            'sidebar_system_settings' => 'إعدادات النظام',
            'sidebar_employees' => 'الموظفين',
            'sidebar_attendances' => 'الحضور والانصراف',
            'sidebar_leaves' => 'الإجازات',
            'sidebar_salaries' => 'الرواتب',
            'sidebar_projects_list' => 'المشاريع',
            'sidebar_tasks' => 'المهام',
            'sidebar_bugs' => 'الأخطاء',
            'sidebar_qa' => 'ضمان الجودة',
            'sidebar_design' => 'التصميم',
            'sidebar_clients' => 'العملاء',
            'sidebar_sales' => 'المبيعات',
            'sidebar_marketing' => 'التسويق',
            'sidebar_tickets' => 'التذاكر',
            'sidebar_invoices' => 'الفواتير',
            'sidebar_contracts' => 'العقود',
            'sidebar_training' => 'التدريب والتطوير',
            'sidebar_meetings' => 'الاجتماعات والمؤتمرات',
            'sidebar_accounting_dashboard' => 'لوحة التحكم المالية',
            'sidebar_accounts_tree' => 'شجرة الحسابات',
            'sidebar_journal_entries' => 'القيود المحاسبية',
            'sidebar_financial_invoices' => 'الفواتير المالية',
            'sidebar_payments' => 'المدفوعات',
            'sidebar_expenses' => 'المصروفات',
            'sidebar_financial_reports' => 'التقارير المالية',
            'sidebar_project_invoices' => 'فواتير المشاريع',
            
            // Dashboard Widgets
            'dashboard_employees_count' => 'عدد الموظفين',
            'dashboard_clients_count' => 'عدد العملاء',
            'dashboard_projects_count' => 'عدد المشاريع',
            'dashboard_tasks_count' => 'عدد المهام',
            'dashboard_revenue' => 'الإيرادات',
            'dashboard_attendance_rate' => 'معدل الحضور',
            'dashboard_recent_activities' => 'الأنشطة الأخيرة',
            'dashboard_quick_actions' => 'الإجراءات السريعة',
            'dashboard_charts' => 'الرسوم البيانية',
            
            // Page Access
            'page_users_create' => 'إنشاء مستخدمين',
            'page_users_edit' => 'تعديل المستخدمين',
            'page_users_delete' => 'حذف المستخدمين',
            'page_employees_create' => 'إنشاء موظفين',
            'page_employees_edit' => 'تعديل الموظفين',
            'page_employees_delete' => 'حذف الموظفين',
            'page_projects_create' => 'إنشاء مشاريع',
            'page_projects_edit' => 'تعديل المشاريع',
            'page_projects_delete' => 'حذف المشاريع',
            'page_financial_reports' => 'التقارير المالية',
            'page_salary_reports' => 'تقارير الرواتب',
            'page_attendance_reports' => 'تقارير الحضور',
        ];

        // إنشاء الصلاحيات المخصصة لكل مستخدم موجود
        $users = \App\Models\User::all();
        
        foreach ($users as $user) {
            foreach ($availablePermissions as $key => $description) {
                // تحديد الصلاحيات الافتراضية حسب الدور
                $defaultEnabled = $this->getDefaultPermissionForRole($user, $key);
                
                UserPermission::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'permission_key' => $key,
                    ],
                    [
                        'is_enabled' => $defaultEnabled,
                        'custom_settings' => [
                            'description' => $description,
                            'category' => $this->getPermissionCategory($key),
                        ],
                    ]
                );
            }
        }
    }

    /**
     * تحديد الصلاحية الافتراضية حسب دور المستخدم
     */
    private function getDefaultPermissionForRole($user, $permissionKey)
    {
        $userRoles = $user->roles->pluck('name')->toArray();
        
        // Super Admin - جميع الصلاحيات
        if (in_array('super_admin', $userRoles)) {
            return true;
        }
        
        // Admin - معظم الصلاحيات
        if (in_array('admin', $userRoles)) {
            $restrictedPermissions = [
                'page_users_delete',
                'page_employees_delete',
                'page_projects_delete',
            ];
            return !in_array($permissionKey, $restrictedPermissions);
        }
        
        // Manager - صلاحيات إدارية محدودة
        if (in_array('manager', $userRoles)) {
            $managerPermissions = [
                'sidebar_dashboard',
                'sidebar_projects',
                'sidebar_operations',
                'sidebar_tasks',
                'sidebar_clients',
                'sidebar_sales',
                'sidebar_reports',
                'sidebar_training',
                'sidebar_meetings',
                'dashboard_employees_count',
                'dashboard_projects_count',
                'dashboard_tasks_count',
                'dashboard_recent_activities',
                'page_projects_create',
                'page_projects_edit',
            ];
            return in_array($permissionKey, $managerPermissions);
        }
        
        // HR - صلاحيات موارد بشرية
        if (in_array('hr', $userRoles)) {
            $hrPermissions = [
                'sidebar_dashboard',
                'sidebar_hr',
                'sidebar_users',
                'sidebar_employees',
                'sidebar_attendances',
                'sidebar_leaves',
                'sidebar_salaries',
                'sidebar_reports',
                'sidebar_training',
                'sidebar_meetings',
                'dashboard_employees_count',
                'dashboard_attendance_rate',
                'dashboard_recent_activities',
                'page_users_create',
                'page_users_edit',
                'page_employees_create',
                'page_employees_edit',
                'page_attendance_reports',
                'page_salary_reports',
            ];
            return in_array($permissionKey, $hrPermissions);
        }
        
        // Accountant - صلاحيات مالية
        if (in_array('accountant', $userRoles)) {
            $accountantPermissions = [
                'sidebar_dashboard',
                'sidebar_finance',
                'sidebar_accounting_dashboard',
                'sidebar_accounts_tree',
                'sidebar_journal_entries',
                'sidebar_financial_invoices',
                'sidebar_payments',
                'sidebar_expenses',
                'sidebar_financial_reports',
                'sidebar_project_invoices',
                'dashboard_revenue',
                'dashboard_recent_activities',
                'page_financial_reports',
            ];
            return in_array($permissionKey, $accountantPermissions);
        }
        
        // Sales Rep - صلاحيات مبيعات
        if (in_array('sales_rep', $userRoles)) {
            $salesPermissions = [
                'sidebar_dashboard',
                'sidebar_business',
                'sidebar_clients',
                'sidebar_sales',
                'sidebar_marketing',
                'sidebar_invoices',
                'sidebar_contracts',
                'dashboard_revenue',
                'dashboard_recent_activities',
            ];
            return in_array($permissionKey, $salesPermissions);
        }
        
        // Employee - صلاحيات أساسية
        if (in_array('employee', $userRoles)) {
            $employeePermissions = [
                'sidebar_dashboard',
                'sidebar_tasks',
                'sidebar_projects_list',
                'sidebar_clients',
                'sidebar_training',
                'sidebar_meetings',
                'dashboard_tasks_count',
                'dashboard_recent_activities',
            ];
            return in_array($permissionKey, $employeePermissions);
        }
        
        // افتراضياً - لا توجد صلاحيات
        return false;
    }

    /**
     * تحديد فئة الصلاحية
     */
    private function getPermissionCategory($permissionKey)
    {
        if (str_starts_with($permissionKey, 'sidebar_')) {
            return 'sidebar';
        } elseif (str_starts_with($permissionKey, 'dashboard_')) {
            return 'dashboard';
        } elseif (str_starts_with($permissionKey, 'page_')) {
            return 'pages';
        }
        return 'general';
    }
}