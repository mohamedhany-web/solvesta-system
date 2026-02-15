<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Bug;
use App\Models\Ticket;
use App\Models\Sale;
use App\Models\Expense;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [];

        // بيانات عامة للجميع
        $data['user'] = $user;
        $data['user_role'] = $user->roles->first()?->name ?? 'employee';
        
        // تحليلات زمنية
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $thisWeek = Carbon::now()->startOfWeek();
        $lastWeek = Carbon::now()->subWeek()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();
        
        // حسب الدور الوظيفي
        if ($user->hasRole(['super_admin', 'admin', 'project_manager'])) {
            // مديرين: كل البيانات مع تحليلات متقدمة
            
            // إحصائيات المشاريع
            $data['total_projects'] = Project::count();
            $data['active_projects'] = Project::whereIn('status', ['planning', 'in_progress'])->count();
            $data['completed_projects'] = Project::where('status', 'completed')->count();
            $data['overdue_projects'] = Project::where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count();
            
            // تحليلات المشاريع
            $data['project_completion_rate'] = $data['total_projects'] > 0 
                ? round(($data['completed_projects'] / $data['total_projects']) * 100, 1) 
                : 0;
            
            // إحصائيات الموظفين
            $data['total_employees'] = Employee::count();
            $data['active_employees'] = Employee::where('status', 'active')->count();
            $data['inactive_employees'] = Employee::where('status', 'inactive')->count();
            
            // إحصائيات العملاء
            $data['total_clients'] = Client::count();
            $data['active_clients'] = Client::whereHas('projects', function($q) {
                $q->whereIn('status', ['planning', 'in_progress']);
            })->count();
            
            // إحصائيات المهام
            $data['total_tasks'] = Task::count();
            $data['completed_tasks'] = Task::where('status', 'completed')->count();
            $data['pending_tasks'] = Task::where('status', 'pending')->count();
            $data['in_progress_tasks'] = Task::where('status', 'in_progress')->count();
            $data['overdue_tasks'] = Task::where('due_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count();
            
            // تحليلات المهام
            $data['task_completion_rate'] = $data['total_tasks'] > 0 
                ? round(($data['completed_tasks'] / $data['total_tasks']) * 100, 1) 
                : 0;
            
            // تحليلات الحضور المتقدمة
            $data['today_attendance'] = Attendance::whereDate('date', $today)->count();
            $data['today_present'] = Attendance::whereDate('date', $today)->where('status', 'present')->count();
            $data['today_absent'] = Attendance::whereDate('date', $today)->where('status', 'absent')->count();
            $data['today_late'] = Attendance::whereDate('date', $today)->where('status', 'late')->count();
            
            // معدل الحضور اليومي
            $data['attendance_rate'] = $data['today_attendance'] > 0 
                ? round(($data['today_present'] / $data['today_attendance']) * 100, 1) 
                : 0;
            
            // تحليلات شهرية
            $data['this_month_projects'] = Project::where('created_at', '>=', $thisMonth)->count();
            $data['last_month_projects'] = Project::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
            $data['project_growth'] = $data['last_month_projects'] > 0 
                ? round((($data['this_month_projects'] - $data['last_month_projects']) / $data['last_month_projects']) * 100, 1) 
                : 0;
            
            $data['this_month_tasks'] = Task::where('created_at', '>=', $thisMonth)->count();
            $data['this_month_employees'] = Employee::where('created_at', '>=', $thisMonth)->count();
            
            // تحليلات الإيرادات (إذا كانت متاحة)
            $data['total_revenue'] = Sale::where('stage', 'closed_won')->sum('actual_value') ?? 0;
            $data['this_month_revenue'] = Sale::where('stage', 'closed_won')->where('created_at', '>=', $thisMonth)->sum('actual_value') ?? 0;
            $data['last_month_revenue'] = Sale::where('stage', 'closed_won')->whereBetween('created_at', [$lastMonth, $thisMonth])->sum('actual_value') ?? 0;
            
            // الأقسام
            $data['total_departments'] = Department::where('is_active', true)->count();
            
            // تحليلات الأقسام
            $data['department_stats'] = Department::withCount(['employees', 'projects'])
                ->where('is_active', true)
                ->get()
                ->map(function($dept) {
                    return [
                        'name' => $dept->name,
                        'employees_count' => $dept->employees_count,
                        'projects_count' => $dept->projects_count,
                        'efficiency' => $dept->projects_count > 0 ? round(($dept->employees_count / $dept->projects_count), 2) : 0
                    ];
                });
            
            // آخر المشاريع مع تفاصيل أكثر
            $data['recent_projects'] = Project::with(['client', 'projectManager', 'tasks'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // آخر المهام مع تفاصيل أكثر
            $data['recent_tasks'] = Task::with(['project', 'assignedTo'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // تحليلات الأداء
            $data['performance_metrics'] = [
                'project_efficiency' => $data['project_completion_rate'],
                'task_efficiency' => $data['task_completion_rate'],
                'attendance_rate' => $data['attendance_rate'],
                'revenue_growth' => $data['last_month_revenue'] > 0 
                    ? round((($data['this_month_revenue'] - $data['last_month_revenue']) / $data['last_month_revenue']) * 100, 1) 
                    : 0
            ];
            
            // تحليلات زمنية للمشاريع
            $data['project_timeline'] = Project::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // تحليلات زمنية للمهام
            $data['task_timeline'] = Task::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();
                
        } elseif ($user->hasRole('employee') || $user->hasRole('developer') || $user->hasRole('designer')) {
            // موظفين: بياناتهم مع تحليلات متقدمة
            
            // مشاريعي
            $data['my_projects'] = Project::where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('teamMembers', function($teamQuery) use ($user) {
                      $teamQuery->where('user_id', $user->id);
                  });
            })->count();
            
            $data['my_active_projects'] = Project::where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('teamMembers', function($teamQuery) use ($user) {
                      $teamQuery->where('user_id', $user->id);
                  });
            })->whereIn('status', ['planning', 'in_progress'])->count();
            
            $data['my_completed_projects'] = Project::where(function($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                  ->orWhereHas('teamMembers', function($teamQuery) use ($user) {
                      $teamQuery->where('user_id', $user->id);
                  });
            })->where('status', 'completed')->count();
            
            // مهامي
            $data['my_tasks'] = Task::where('assigned_to', $user->id)->count();
            $data['my_pending_tasks'] = Task::where('assigned_to', $user->id)
                ->where('status', 'pending')
                ->count();
            $data['my_in_progress_tasks'] = Task::where('assigned_to', $user->id)
                ->where('status', 'in_progress')
                ->count();
            $data['my_completed_tasks'] = Task::where('assigned_to', $user->id)
                ->where('status', 'completed')
                ->count();
            $data['my_overdue_tasks'] = Task::where('assigned_to', $user->id)
                ->where('due_date', '<', $today)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count();
            
            // تحليلات الأداء الشخصي
            $data['my_task_completion_rate'] = $data['my_tasks'] > 0 
                ? round(($data['my_completed_tasks'] / $data['my_tasks']) * 100, 1) 
                : 0;
            
            $data['my_project_completion_rate'] = $data['my_projects'] > 0 
                ? round(($data['my_completed_projects'] / $data['my_projects']) * 100, 1) 
                : 0;
            
            // تحليلات الحضور الشخصي
            $data['my_attendance_today'] = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();
            
            $data['my_attendance_this_month'] = Attendance::where('user_id', $user->id)
                ->where('date', '>=', $thisMonth)
                ->where('status', 'present')
                ->count();
            
            $data['my_total_attendance_days'] = Attendance::where('user_id', $user->id)
                ->where('date', '>=', $thisMonth)
                ->count();
            
            $data['my_attendance_rate'] = $data['my_total_attendance_days'] > 0 
                ? round(($data['my_attendance_this_month'] / $data['my_total_attendance_days']) * 100, 1) 
                : 0;
                
            // مشاريعي الأخيرة مع تفاصيل
            $data['recent_projects'] = Project::with(['client', 'projectManager', 'tasks'])
                ->where(function($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                      ->orWhereHas('teamMembers', function($teamQuery) use ($user) {
                          $teamQuery->where('user_id', $user->id);
                      });
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // مهامي الأخيرة مع تفاصيل
            $data['recent_tasks'] = Task::with(['project', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            // تحليلات الأداء الشخصي
            $data['my_performance_metrics'] = [
                'task_efficiency' => $data['my_task_completion_rate'],
                'project_efficiency' => $data['my_project_completion_rate'],
                'attendance_rate' => $data['my_attendance_rate'],
                'overdue_tasks' => $data['my_overdue_tasks']
            ];
                
        } elseif ($user->hasRole('hr')) {
            // موارد بشرية مع تحليلات متقدمة
            $data['total_employees'] = Employee::count();
            $data['active_employees'] = Employee::where('status', 'active')->count();
            $data['inactive_employees'] = Employee::where('status', 'inactive')->count();
            $data['new_employees_this_month'] = Employee::where('created_at', '>=', $thisMonth)->count();
            
            // تحليلات الإجازات
            $data['total_leaves'] = \App\Models\Leave::count();
            $data['pending_leaves'] = \App\Models\Leave::where('status', 'pending')->count();
            $data['approved_leaves'] = \App\Models\Leave::where('status', 'approved')->count();
            $data['rejected_leaves'] = \App\Models\Leave::where('status', 'rejected')->count();
            
            // تحليلات الأقسام
            $data['department_stats'] = Department::withCount(['employees'])
                ->where('is_active', true)
                ->get()
                ->map(function($dept) {
                    return [
                        'name' => $dept->name,
                        'employees_count' => $dept->employees_count,
                        'efficiency' => $dept->employees_count > 0 ? 'عالية' : 'منخفضة'
                    ];
                });
            
            // تحليلات الحضور
            $data['attendance_today'] = Attendance::whereDate('date', $today)->count();
            $data['present_today'] = Attendance::whereDate('date', $today)->where('status', 'present')->count();
            $data['absent_today'] = Attendance::whereDate('date', $today)->where('status', 'absent')->count();
            
        } elseif ($user->hasRole('accountant')) {
            // محاسب مع تحليلات مالية متقدمة
            $data['total_expenses'] = Expense::count();
            $data['pending_expenses'] = Expense::where('status', 'pending')->count();
            $data['approved_expenses'] = Expense::where('status', 'approved')->count();
            $data['rejected_expenses'] = Expense::where('status', 'rejected')->count();
            
            // تحليلات مالية
            $data['total_amount'] = Expense::where('status', 'approved')->sum('amount');
            $data['this_month_expenses'] = Expense::where('status', 'approved')
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount');
            $data['last_month_expenses'] = Expense::where('status', 'approved')
                ->whereBetween('created_at', [$lastMonth, $thisMonth])
                ->sum('amount');
            
            // تحليلات الفواتير
            $data['total_invoices'] = \App\Models\Invoice::count();
            $data['paid_invoices'] = \App\Models\Invoice::where('status', 'paid')->count();
            $data['pending_invoices'] = \App\Models\Invoice::where('status', 'pending')->count();
            $data['overdue_invoices'] = \App\Models\Invoice::where('due_date', '<', $today)
                ->where('status', '!=', 'paid')
                ->count();
            
            // إجمالي الإيرادات
            $data['total_revenue'] = \App\Models\Invoice::where('status', 'paid')->sum('total_amount');
            $data['this_month_revenue'] = \App\Models\Invoice::where('status', 'paid')
                ->where('created_at', '>=', $thisMonth)
                ->sum('total_amount');
            
        } elseif ($user->hasRole('sales_rep')) {
            // مبيعات مع تحليلات متقدمة
            $data['total_clients'] = Client::count();
            $data['new_clients_this_month'] = Client::where('created_at', '>=', $thisMonth)->count();
            
            // تحليلات المبيعات
            $data['total_sales'] = Sale::count();
            $data['won_sales'] = Sale::where('stage', 'closed_won')->count();
            $data['lost_sales'] = Sale::where('stage', 'closed_lost')->count();
            $data['in_progress_sales'] = Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])->count();
            
            // معدل التحويل
            $data['conversion_rate'] = $data['total_sales'] > 0 
                ? round(($data['won_sales'] / $data['total_sales']) * 100, 1) 
                : 0;
            
            // إجمالي قيمة المبيعات
            $data['total_sales_value'] = Sale::where('stage', 'closed_won')->sum('actual_value');
            $data['this_month_sales_value'] = Sale::where('stage', 'closed_won')
                ->where('created_at', '>=', $thisMonth)
                ->sum('actual_value');
            
            // تحليلات الأداء
            $data['sales_performance'] = [
                'conversion_rate' => $data['conversion_rate'],
                'total_value' => $data['total_sales_value'],
                'monthly_value' => $data['this_month_sales_value'],
                'new_clients' => $data['new_clients_this_month']
            ];
                
        } elseif ($user->hasRole('support')) {
            // دعم فني مع تحليلات متقدمة
            $data['total_tickets'] = Ticket::count();
            $data['open_tickets'] = Ticket::where('status', 'open')->count();
            $data['in_progress_tickets'] = Ticket::where('status', 'in_progress')->count();
            $data['resolved_tickets'] = Ticket::where('status', 'resolved')->count();
            $data['closed_tickets'] = Ticket::where('status', 'closed')->count();
            
            // تذاكري
            $data['my_tickets'] = Ticket::where('assigned_to', $user->id)->count();
            $data['my_open_tickets'] = Ticket::where('assigned_to', $user->id)
                ->whereIn('status', ['open', 'in_progress'])
                ->count();
            $data['my_resolved_tickets'] = Ticket::where('assigned_to', $user->id)
                ->where('status', 'resolved')
                ->count();
            
            // معدل الحل
            $data['resolution_rate'] = $data['my_tickets'] > 0 
                ? round(($data['my_resolved_tickets'] / $data['my_tickets']) * 100, 1) 
                : 0;
            
            // تحليلات الأداء
            $data['support_performance'] = [
                'total_tickets' => $data['my_tickets'],
                'resolved_tickets' => $data['my_resolved_tickets'],
                'resolution_rate' => $data['resolution_rate'],
                'open_tickets' => $data['my_open_tickets']
            ];
        }

        return view('dashboard', $data);
    }
}
