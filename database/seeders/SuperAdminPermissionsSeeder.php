<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserPermission;

class SuperAdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحصول على جميع المستخدمين الذين لديهم دور Super Admin
        $superAdmins = User::role('super_admin')->get();
        
        if ($superAdmins->isEmpty()) {
            $this->command->warn('لم يتم العثور على أي مستخدم لديه دور Super Admin!');
            return;
        }

        // جميع الصلاحيات المخصصة المتاحة في النظام
        $allPermissions = [
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
            'sidebar_design_page' => 'التصميم',
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
            
            // Page Access - Users
            'page_users_view' => 'عرض المستخدمين',
            'page_users_create' => 'إنشاء مستخدمين',
            'page_users_edit' => 'تعديل المستخدمين',
            'page_users_delete' => 'حذف المستخدمين',
            
            // Page Access - Employees
            'page_employees_view' => 'عرض الموظفين',
            'page_employees_create' => 'إنشاء موظفين',
            'page_employees_edit' => 'تعديل الموظفين',
            'page_employees_delete' => 'حذف الموظفين',
            
            // Page Access - Projects
            'page_projects_view' => 'عرض المشاريع',
            'page_projects_create' => 'إنشاء مشاريع',
            'page_projects_edit' => 'تعديل المشاريع',
            'page_projects_delete' => 'حذف المشاريع',
            
            // Page Access - Tasks
            'page_tasks_view' => 'عرض المهام',
            'page_tasks_create' => 'إنشاء مهام',
            'page_tasks_edit' => 'تعديل المهام',
            'page_tasks_delete' => 'حذف المهام',
            
            // Page Access - Clients
            'page_clients_view' => 'عرض العملاء',
            'page_clients_create' => 'إنشاء عملاء',
            'page_clients_edit' => 'تعديل العملاء',
            'page_clients_delete' => 'حذف العملاء',
            
            // Page Access - Sales
            'page_sales_view' => 'عرض المبيعات',
            'page_sales_create' => 'إنشاء مبيعات',
            'page_sales_edit' => 'تعديل المبيعات',
            'page_sales_delete' => 'حذف المبيعات',
            
            // Page Access - Attendances
            'page_attendances_view' => 'عرض الحضور',
            'page_attendances_create' => 'تسجيل حضور',
            'page_attendances_edit' => 'تعديل حضور',
            'page_attendances_delete' => 'حذف حضور',
            
            // Page Access - Leaves
            'page_leaves_view' => 'عرض الإجازات',
            'page_leaves_create' => 'إنشاء إجازات',
            'page_leaves_edit' => 'تعديل إجازات',
            'page_leaves_delete' => 'حذف إجازات',
            'page_leaves_approve' => 'اعتماد إجازات',
            
            // Page Access - Salaries
            'page_salaries_view' => 'عرض الرواتب',
            'page_salaries_create' => 'إنشاء رواتب',
            'page_salaries_edit' => 'تعديل رواتب',
            'page_salaries_delete' => 'حذف رواتب',
            
            // Page Access - Accounting
            'page_accounting_view' => 'عرض المحاسبة',
            'page_accounts_create' => 'إنشاء حسابات',
            'page_accounts_edit' => 'تعديل حسابات',
            'page_accounts_delete' => 'حذف حسابات',
            'page_journal_entries_create' => 'إنشاء قيود محاسبية',
            'page_journal_entries_edit' => 'تعديل قيود محاسبية',
            'page_journal_entries_delete' => 'حذف قيود محاسبية',
            'page_journal_entries_approve' => 'اعتماد قيود محاسبية',
            'page_journal_entries_post' => 'ترحيل قيود محاسبية',
            
            // Reports
            'page_financial_reports' => 'التقارير المالية',
            'page_salary_reports' => 'تقارير الرواتب',
            'page_attendance_reports' => 'تقارير الحضور',
            'page_project_reports' => 'تقارير المشاريع',
            'page_sales_reports' => 'تقارير المبيعات',
            
            // Page Access - Tickets
            'page_tickets_view' => 'عرض التذاكر',
            'page_tickets_create' => 'إنشاء تذاكر',
            'page_tickets_edit' => 'تعديل تذاكر',
            'page_tickets_delete' => 'حذف تذاكر',
            
            // Page Access - Invoices
            'page_invoices_view' => 'عرض الفواتير',
            'page_invoices_create' => 'إنشاء فواتير',
            'page_invoices_edit' => 'تعديل فواتير',
            'page_invoices_delete' => 'حذف فواتير',
            
            // Page Access - Contracts
            'page_contracts_view' => 'عرض العقود',
            'page_contracts_create' => 'إنشاء عقود',
            'page_contracts_edit' => 'تعديل عقود',
            'page_contracts_delete' => 'حذف عقود',
            
            // Page Access - Bugs
            'page_bugs_view' => 'عرض الأخطاء',
            'page_bugs_create' => 'إنشاء أخطاء',
            'page_bugs_edit' => 'تعديل أخطاء',
            'page_bugs_delete' => 'حذف أخطاء',
            
            // Page Access - Expenses
            'page_expenses_view' => 'عرض المصروفات',
            'page_expenses_create' => 'إنشاء مصروفات',
            'page_expenses_edit' => 'تعديل مصروفات',
            'page_expenses_delete' => 'حذف مصروفات',
            
            // Page Access - Payments
            'page_payments_view' => 'عرض المدفوعات',
            'page_payments_create' => 'إنشاء مدفوعات',
            'page_payments_edit' => 'تعديل مدفوعات',
            'page_payments_delete' => 'حذف مدفوعات',
            
            // Advanced Permissions
            'system_settings' => 'إعدادات النظام',
            'user_permissions_manage' => 'إدارة صلاحيات المستخدمين',
            'roles_manage' => 'إدارة الأدوار',
            'departments_manage' => 'إدارة الأقسام',
            'export_data' => 'تصدير البيانات',
            'import_data' => 'استيراد البيانات',
            'backup_database' => 'نسخ احتياطي للبيانات',
            'restore_database' => 'استعادة البيانات',
            'view_logs' => 'عرض السجلات',
            'delete_logs' => 'حذف السجلات',
        ];

        // تطبيق الصلاحيات على جميع Super Admins
        foreach ($superAdmins as $superAdmin) {
            // حذف الصلاحيات القديمة إذا وجدت
            UserPermission::where('user_id', $superAdmin->id)->delete();

            // إضافة جميع الصلاحيات للـ Super Admin
            foreach ($allPermissions as $key => $description) {
                UserPermission::create([
                    'user_id' => $superAdmin->id,
                    'permission_key' => $key,
                    'is_enabled' => true,
                    'custom_settings' => [
                        'description' => $description,
                        'granted_at' => now()->toDateTimeString(),
                        'granted_by' => 'System',
                    ]
                ]);
            }
            
            $this->command->info("تم إضافة جميع الصلاحيات (" . count($allPermissions) . " صلاحية) للمستخدم: {$superAdmin->name} ({$superAdmin->email})");
        }

        $this->command->info('✅ تم تحديث جميع الصلاحيات لجميع المستخدمين الذين لديهم دور Super Admin بنجاح!');
    }
}
