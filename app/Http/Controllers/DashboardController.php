<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Client;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Sale;
use App\Models\Task;
use App\Models\Ticket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [
            'user' => $user,
            'user_role' => $user->roles->first()?->name ?? 'employee',
        ];

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        if ($user->hasRole(['super_admin', 'admin', 'project_manager'])) {
            $data = array_merge($data, $this->adminData($today, $thisMonth, $lastMonth, $lastMonthEnd));
        } elseif ($user->hasRole(['employee', 'developer', 'designer'])) {
            $data = array_merge($data, $this->employeeData($user, $today, $thisMonth));
        } elseif ($user->hasRole('hr')) {
            $data = array_merge($data, $this->hrData($today, $thisMonth));
        } elseif ($user->hasRole('accountant')) {
            $data = array_merge($data, $this->accountantData($today, $thisMonth, $lastMonth, $lastMonthEnd));
        } elseif ($user->hasRole('sales_rep')) {
            $data = array_merge($data, $this->salesData($thisMonth));
        } elseif ($user->hasRole('support')) {
            $data = array_merge($data, $this->supportData($user));
        }

        return view('dashboard', $data);
    }

    private function adminData(Carbon $today, Carbon $thisMonth, Carbon $lastMonth, Carbon $lastMonthEnd): array
    {
        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();
        $activeProjects = Project::whereIn('status', ['planning', 'in_progress'])->count();

        $totalTasks = Task::count();
        $completedTasks = Task::completed()->count();
        $pendingTasks = Task::whereIn('status', ['pending', 'todo', 'backlog'])->count();
        $inProgressTasks = Task::where('status', 'in_progress')->count();
        $overdueTasks = Task::overdue()->count();

        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();

        $todayAttendance = Attendance::whereDate('date', $today)->count();
        $todayPresent = Attendance::whereDate('date', $today)->where('status', 'present')->count();
        $todayAbsent = Attendance::whereDate('date', $today)->where('status', 'absent')->count();
        $todayLate = Attendance::whereDate('date', $today)->where('status', 'late')->count();

        // Rate vs active employees when records exist, else vs attendance rows
        $attendanceDenominator = max($activeEmployees, $todayAttendance);
        $attendanceRate = $attendanceDenominator > 0
            ? round(($todayPresent / $attendanceDenominator) * 100, 1)
            : 0;

        $thisMonthProjects = Project::where('created_at', '>=', $thisMonth)->count();
        $lastMonthProjects = Project::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        $projectGrowth = $lastMonthProjects > 0
            ? round((($thisMonthProjects - $lastMonthProjects) / $lastMonthProjects) * 100, 1)
            : ($thisMonthProjects > 0 ? 100 : 0);

        $totalRevenue = $this->sumRevenue();
        $thisMonthRevenue = $this->sumRevenue($thisMonth);
        $lastMonthRevenue = $this->sumRevenue($lastMonth, $lastMonthEnd);

        $projectCompletionRate = $totalProjects > 0
            ? round(($completedProjects / $totalProjects) * 100, 1)
            : 0;
        $taskCompletionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100, 1)
            : 0;

        return [
            'total_projects' => $totalProjects,
            'active_projects' => $activeProjects,
            'completed_projects' => $completedProjects,
            'overdue_projects' => Project::where('end_date', '<', $today)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->count(),
            'project_completion_rate' => $projectCompletionRate,

            'total_employees' => $totalEmployees,
            'active_employees' => $activeEmployees,
            'inactive_employees' => Employee::where('status', 'inactive')->count(),

            'total_clients' => Client::count(),
            'active_clients' => Client::whereHas('projects', function ($q) {
                $q->whereIn('status', ['planning', 'in_progress']);
            })->count(),

            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
            'in_progress_tasks' => $inProgressTasks,
            'overdue_tasks' => $overdueTasks,
            'task_completion_rate' => $taskCompletionRate,

            'today_attendance' => $todayAttendance,
            'today_present' => $todayPresent,
            'today_absent' => $todayAbsent,
            'today_late' => $todayLate,
            'attendance_rate' => $attendanceRate,

            'this_month_projects' => $thisMonthProjects,
            'last_month_projects' => $lastMonthProjects,
            'project_growth' => $projectGrowth,
            'this_month_tasks' => Task::where('created_at', '>=', $thisMonth)->count(),
            'this_month_employees' => Employee::where('created_at', '>=', $thisMonth)->count(),

            'total_revenue' => $totalRevenue,
            'this_month_revenue' => $thisMonthRevenue,
            'last_month_revenue' => $lastMonthRevenue,

            'total_departments' => Department::where('is_active', true)->count(),
            'department_stats' => Department::withCount(['employees', 'projects'])
                ->where('is_active', true)
                ->get()
                ->map(function ($dept) {
                    return [
                        'name' => $dept->name,
                        'employees_count' => $dept->employees_count,
                        'projects_count' => $dept->projects_count,
                        'efficiency' => $dept->projects_count > 0
                            ? round($dept->employees_count / $dept->projects_count, 2)
                            : 0,
                    ];
                }),

            'recent_projects' => Project::with(['client', 'projectManager', 'tasks'])
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),
            'recent_tasks' => Task::with(['project', 'assignedTo'])
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),

            'performance_metrics' => [
                'project_efficiency' => $projectCompletionRate,
                'task_efficiency' => $taskCompletionRate,
                'attendance_rate' => $attendanceRate,
                'revenue_growth' => $lastMonthRevenue > 0
                    ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
                    : ($thisMonthRevenue > 0 ? 100 : 0),
            ],

            'project_timeline' => Project::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'task_timeline' => Task::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];
    }

    /**
     * Revenue prefers paid financial invoices; falls back to closed-won sales.
     */
    private function sumRevenue(?Carbon $from = null, ?Carbon $to = null): float
    {
        $invoiceQuery = FinancialInvoice::query()
            ->where('status', '!=', 'cancelled');

        $salesQuery = Sale::query()->where('stage', 'closed_won');

        if ($from && $to) {
            $invoiceQuery->whereBetween('invoice_date', [$from->toDateString(), $to->toDateString()]);
            $salesQuery->whereBetween('updated_at', [$from, $to]);
        } elseif ($from) {
            $invoiceQuery->where('invoice_date', '>=', $from->toDateString());
            $salesQuery->where('updated_at', '>=', $from);
        }

        $invoices = (float) $invoiceQuery->sum('paid_amount');
        if ($invoices > 0) {
            return round($invoices, 2);
        }

        return round((float) $salesQuery
            ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')
            ->value('t'), 2);
    }

    private function employeeData($user, Carbon $today, Carbon $thisMonth): array
    {
        $myProjectsQuery = Project::where(function ($q) use ($user) {
            $q->where('project_manager_id', $user->id)
                ->orWhereHas('teamMembers', function ($teamQuery) use ($user) {
                    $teamQuery->where('user_id', $user->id);
                });
        });

        $myProjects = (clone $myProjectsQuery)->count();
        $myActiveProjects = (clone $myProjectsQuery)->whereIn('status', ['planning', 'in_progress'])->count();
        $myCompletedProjects = (clone $myProjectsQuery)->where('status', 'completed')->count();

        $myTasks = Task::where('assigned_to', $user->id)->count();
        $myCompletedTasks = Task::where('assigned_to', $user->id)->completed()->count();
        $myPendingTasks = Task::where('assigned_to', $user->id)
            ->whereIn('status', ['pending', 'todo', 'backlog'])
            ->count();
        $myInProgressTasks = Task::where('assigned_to', $user->id)->where('status', 'in_progress')->count();
        $myOverdueTasks = Task::where('assigned_to', $user->id)->overdue()->count();

        $myAttendanceThisMonth = Attendance::where('user_id', $user->id)
            ->where('date', '>=', $thisMonth)
            ->where('status', 'present')
            ->count();
        $myTotalAttendanceDays = Attendance::where('user_id', $user->id)
            ->where('date', '>=', $thisMonth)
            ->count();
        $myAttendanceRate = $myTotalAttendanceDays > 0
            ? round(($myAttendanceThisMonth / $myTotalAttendanceDays) * 100, 1)
            : 0;

        $myTaskCompletionRate = $myTasks > 0
            ? round(($myCompletedTasks / $myTasks) * 100, 1)
            : 0;
        $myProjectCompletionRate = $myProjects > 0
            ? round(($myCompletedProjects / $myProjects) * 100, 1)
            : 0;

        return [
            'my_projects' => $myProjects,
            'my_active_projects' => $myActiveProjects,
            'my_completed_projects' => $myCompletedProjects,
            'my_tasks' => $myTasks,
            'my_pending_tasks' => $myPendingTasks,
            'my_in_progress_tasks' => $myInProgressTasks,
            'my_completed_tasks' => $myCompletedTasks,
            'my_overdue_tasks' => $myOverdueTasks,
            'my_task_completion_rate' => $myTaskCompletionRate,
            'my_project_completion_rate' => $myProjectCompletionRate,
            'my_attendance_today' => Attendance::where('user_id', $user->id)->whereDate('date', $today)->first(),
            'my_attendance_this_month' => $myAttendanceThisMonth,
            'my_total_attendance_days' => $myTotalAttendanceDays,
            'my_attendance_rate' => $myAttendanceRate,
            'recent_projects' => Project::with(['client', 'projectManager', 'tasks'])
                ->where(function ($q) use ($user) {
                    $q->where('project_manager_id', $user->id)
                        ->orWhereHas('teamMembers', function ($teamQuery) use ($user) {
                            $teamQuery->where('user_id', $user->id);
                        });
                })
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),
            'recent_tasks' => Task::with(['project', 'assignedTo'])
                ->where('assigned_to', $user->id)
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),
            'my_performance_metrics' => [
                'task_efficiency' => $myTaskCompletionRate,
                'project_efficiency' => $myProjectCompletionRate,
                'attendance_rate' => $myAttendanceRate,
                'overdue_tasks' => $myOverdueTasks,
            ],
        ];
    }

    private function hrData(Carbon $today, Carbon $thisMonth): array
    {
        return [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'inactive_employees' => Employee::where('status', 'inactive')->count(),
            'new_employees_this_month' => Employee::where('created_at', '>=', $thisMonth)->count(),
            'total_leaves' => Leave::count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'approved_leaves' => Leave::where('status', 'approved')->count(),
            'rejected_leaves' => Leave::where('status', 'rejected')->count(),
            'department_stats' => Department::withCount(['employees'])
                ->where('is_active', true)
                ->get()
                ->map(function ($dept) {
                    return [
                        'name' => $dept->name,
                        'employees_count' => $dept->employees_count,
                        'efficiency' => $dept->employees_count > 0 ? '???' : '????',
                    ];
                }),
            'attendance_today' => Attendance::whereDate('date', $today)->count(),
            'present_today' => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
            'absent_today' => Attendance::whereDate('date', $today)->where('status', 'absent')->count(),
        ];
    }

    private function accountantData(Carbon $today, Carbon $thisMonth, Carbon $lastMonth, Carbon $lastMonthEnd): array
    {
        $paidInvoices = FinancialInvoice::where('status', 'paid')->count();
        $pendingInvoices = FinancialInvoice::whereIn('status', ['draft', 'sent', 'partial', 'overdue'])->count();
        $overdueInvoices = FinancialInvoice::overdue()->count();

        return [
            'total_expenses' => Expense::count(),
            'pending_expenses' => Expense::where('status', 'pending')->count(),
            'approved_expenses' => Expense::where('status', 'approved')->count(),
            'rejected_expenses' => Expense::where('status', 'rejected')->count(),
            'total_amount' => (float) Expense::where('status', 'approved')->sum('amount'),
            'this_month_expenses' => (float) Expense::where('status', 'approved')
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount'),
            'last_month_expenses' => (float) Expense::where('status', 'approved')
                ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                ->sum('amount'),
            'total_invoices' => FinancialInvoice::count() ?: Invoice::count(),
            'paid_invoices' => $paidInvoices ?: Invoice::where('status', 'paid')->count(),
            'pending_invoices' => $pendingInvoices ?: Invoice::where('status', 'pending')->count(),
            'overdue_invoices' => $overdueInvoices ?: Invoice::where('due_date', '<', $today)
                ->where('status', '!=', 'paid')
                ->count(),
            'total_revenue' => $this->sumRevenue(),
            'this_month_revenue' => $this->sumRevenue($thisMonth),
        ];
    }

    private function salesData(Carbon $thisMonth): array
    {
        $totalSales = Sale::count();
        $wonSales = Sale::where('stage', 'closed_won')->count();
        $totalSalesValue = (float) Sale::where('stage', 'closed_won')
            ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')
            ->value('t');
        $thisMonthSalesValue = (float) Sale::where('stage', 'closed_won')
            ->where('updated_at', '>=', $thisMonth)
            ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')
            ->value('t');
        $conversionRate = $totalSales > 0 ? round(($wonSales / $totalSales) * 100, 1) : 0;
        $newClients = Client::where('created_at', '>=', $thisMonth)->count();

        return [
            'total_clients' => Client::count(),
            'new_clients_this_month' => $newClients,
            'total_sales' => $totalSales,
            'won_sales' => $wonSales,
            'lost_sales' => Sale::where('stage', 'closed_lost')->count(),
            'in_progress_sales' => Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])->count(),
            'conversion_rate' => $conversionRate,
            'total_sales_value' => $totalSalesValue,
            'this_month_sales_value' => $thisMonthSalesValue,
            'sales_performance' => [
                'conversion_rate' => $conversionRate,
                'total_value' => $totalSalesValue,
                'monthly_value' => $thisMonthSalesValue,
                'new_clients' => $newClients,
            ],
        ];
    }

    private function supportData($user): array
    {
        $myTickets = Ticket::where('assigned_to', $user->id)->count();
        $myResolved = Ticket::where('assigned_to', $user->id)->where('status', 'resolved')->count();
        $myOpen = Ticket::where('assigned_to', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->count();
        $resolutionRate = $myTickets > 0 ? round(($myResolved / $myTickets) * 100, 1) : 0;

        return [
            'total_tickets' => Ticket::count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'in_progress_tickets' => Ticket::where('status', 'in_progress')->count(),
            'resolved_tickets' => Ticket::where('status', 'resolved')->count(),
            'closed_tickets' => Ticket::where('status', 'closed')->count(),
            'my_tickets' => $myTickets,
            'my_open_tickets' => $myOpen,
            'my_resolved_tickets' => $myResolved,
            'resolution_rate' => $resolutionRate,
            'support_performance' => [
                'total_tickets' => $myTickets,
                'resolved_tickets' => $myResolved,
                'resolution_rate' => $resolutionRate,
                'open_tickets' => $myOpen,
            ],
        ];
    }
}
