<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Client;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Leave;
use App\Models\Project;
use App\Models\Sale;
use App\Models\Task;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $data = [
            'user' => $user,
            'user_role' => $user->roles->first()?->name ?? 'employee',
        ];

        if ($user->hasRole(['super_admin', 'admin', 'project_manager'])) {
            $data = array_merge($data, $this->adminDashboardData());
        } elseif ($user->hasRole(['employee', 'developer', 'designer'])) {
            $data = array_merge($data, $this->employeeDashboardData($user));
        } elseif ($user->hasRole('hr')) {
            $data = array_merge($data, $this->hrDashboardData());
        } elseif ($user->hasRole('accountant')) {
            $data = array_merge($data, $this->accountantDashboardData());
        } elseif ($user->hasRole('sales_rep')) {
            $data = array_merge($data, $this->salesDashboardData());
        } elseif ($user->hasRole('support')) {
            $data = array_merge($data, $this->supportDashboardData($user));
        }

        return view('dashboard', $data);
    }

    private function adminDashboardData(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $totalProjects = Project::count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();

        $thisMonthRevenue = (float) Sale::where('stage', 'closed_won')
            ->where('updated_at', '>=', $thisMonth)
            ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')->value('t');
        $lastMonthRevenue = (float) Sale::where('stage', 'closed_won')
            ->whereBetween('updated_at', [$lastMonth, $lastMonthEnd])
            ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')->value('t');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : ($thisMonthRevenue > 0 ? 100 : 0);

        $pipeline = [
            'leads_new' => Lead::where('status', 'new')->count(),
            'leads_qualified' => Lead::where('status', 'qualified')->count(),
            'sales_open' => Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])->count(),
            'closed_won' => Sale::where('stage', 'closed_won')->count(),
            'pipeline_value' => (float) Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])
                ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')->value('t'),
            'won_value' => (float) Sale::where('stage', 'closed_won')
                ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')->value('t'),
        ];

        $alerts = [
            'overdue_tasks' => Task::where('due_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'overdue_projects' => Project::where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'pending_team' => Project::whereNotNull('department_id')->whereNull('project_manager_id')->count(),
            'open_blockers' => Task::where('has_blocker', true)->whereNotIn('status', ['completed', 'cancelled'])->count(),
        ];

        $insights = $this->buildInsights($revenueGrowth, $alerts, $pipeline, $thisMonth, $lastMonth);

        return [
            'is_admin_dashboard' => true,
            'kpis' => [
                ['label' => 'مشاريع نشطة', 'value' => Project::whereIn('status', ['planning', 'in_progress'])->count(), 'sub' => $totalProjects.' إجمالي', 'trend' => null, 'color' => 'primary'],
                ['label' => 'إيراد الشهر', 'value' => number_format($thisMonthRevenue, 0), 'sub' => 'ج.م', 'trend' => $revenueGrowth, 'color' => 'emerald'],
                ['label' => 'Pipeline', 'value' => number_format($pipeline['pipeline_value'], 0), 'sub' => 'ج.م مفتوح', 'trend' => null, 'color' => 'blue'],
                ['label' => 'Leads جديدة', 'value' => $pipeline['leads_new'], 'sub' => $pipeline['leads_qualified'].' مؤهلة', 'trend' => null, 'color' => 'amber'],
                ['label' => 'مهام مفتوحة', 'value' => Task::whereIn('status', ['pending', 'in_progress', 'todo', 'review'])->count(), 'sub' => $completedTasks.' مكتملة', 'trend' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0, 'color' => 'violet'],
                ['label' => 'حضور اليوم', 'value' => Attendance::whereDate('date', $today)->where('status', 'present')->count(), 'sub' => Employee::where('status', 'active')->count().' موظف', 'trend' => null, 'color' => 'cyan'],
            ],
            'pipeline' => $pipeline,
            'alerts' => $alerts,
            'insights' => $insights,
            'charts' => [
                'revenue_monthly' => $this->monthlySeries(Sale::query()->where('stage', 'closed_won'), 'updated_at', 'COALESCE(actual_value, estimated_value)'),
                'leads_monthly' => $this->monthlyCountSeries(Lead::query()),
                'tasks_monthly' => $this->monthlyCountSeries(Task::query()->where('status', 'completed'), 'updated_at'),
                'activity_daily' => $this->dailyActivitySeries(30),
                'project_status' => $this->statusBreakdown(Project::class, 'status', [
                    'planning' => 'تخطيط',
                    'in_progress' => 'تنفيذ',
                    'on_hold' => 'معلق',
                    'completed' => 'مكتمل',
                    'cancelled' => 'ملغي',
                ]),
                'task_status' => $this->statusBreakdown(Task::class, 'status', [
                    'completed' => 'مكتملة',
                    'in_progress' => 'قيد التنفيذ',
                    'pending' => 'معلقة',
                    'todo' => 'للعمل',
                    'review' => 'مراجعة',
                    'cancelled' => 'ملغاة',
                ]),
                'sales_funnel' => [
                    'labels' => ['Leads', 'مؤهّلة', 'فرص مفتوحة', 'صفقات رابحة'],
                    'values' => [
                        Lead::count(),
                        Lead::where('status', 'qualified')->count(),
                        $pipeline['sales_open'],
                        $pipeline['closed_won'],
                    ],
                ],
                'department_load' => Department::active()
                    ->withCount(['employees', 'projects'])
                    ->orderByDesc('projects_count')
                    ->take(8)
                    ->get()
                    ->map(fn ($d) => [
                        'name' => $d->name,
                        'employees' => $d->employees_count,
                        'projects' => $d->projects_count,
                    ]),
            ],
            'recent_projects' => Project::with(['client', 'department'])->latest()->take(6)->get(),
            'recent_tasks' => Task::with(['project', 'assignedTo'])->latest()->take(8)->get(),
            'recent_leads' => Lead::latest()->take(5)->get(),
        ];
    }

    private function buildInsights(float $revenueGrowth, array $alerts, array $pipeline, Carbon $thisMonth, Carbon $lastMonth): array
    {
        $insights = [];

        if ($revenueGrowth > 0) {
            $insights[] = ['type' => 'positive', 'text' => "الإيرادات المغلقة ارتفعت {$revenueGrowth}% عن الشهر الماضي."];
        } elseif ($revenueGrowth < 0) {
            $insights[] = ['type' => 'warning', 'text' => 'الإيرادات المغلقة أقل من الشهر الماضي — راجع فرص التفاوض والعروض المعلقة.'];
        }

        $lastMonthEnd = $lastMonth->copy()->endOfMonth();
        $leadsThisMonth = Lead::where('created_at', '>=', $thisMonth)->count();
        $leadsLastMonth = Lead::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->count();
        if ($leadsThisMonth > $leadsLastMonth && $leadsLastMonth > 0) {
            $pct = round((($leadsThisMonth - $leadsLastMonth) / $leadsLastMonth) * 100);
            $insights[] = ['type' => 'positive', 'text' => "تدفق Leads أقوى بنسبة {$pct}% هذا الشهر ({$leadsThisMonth} lead)."];
        }

        if ($alerts['overdue_tasks'] > 0) {
            $insights[] = ['type' => 'danger', 'text' => "{$alerts['overdue_tasks']} مهمة متأخرة — أولوية للمتابعة من PMO."];
        }
        if ($alerts['pending_team'] > 0) {
            $insights[] = ['type' => 'warning', 'text' => "{$alerts['pending_team']} مشروع بانتظار تعيين فريق من رؤساء الأقسام."];
        }
        if ($alerts['open_blockers'] > 0) {
            $insights[] = ['type' => 'danger', 'text' => "{$alerts['open_blockers']} Blocker مفتوح يعيق التسليم."];
        }
        if ($pipeline['pipeline_value'] > 0 && $pipeline['closed_won'] === 0) {
            $insights[] = ['type' => 'info', 'text' => 'يوجد pipeline نشط — ركّز على تحويل العروض إلى عقود.'];
        }

        return array_slice($insights, 0, 5);
    }

    private function monthlySeries($query, string $dateCol, string $sumExpr): array
    {
        $labels = [];
        $values = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = Carbon::now()->subMonths($i)->endOfMonth();
            $labels[] = $start->locale('ar')->translatedFormat('M Y');
            $values[] = (float) (clone $query)
                ->whereBetween($dateCol, [$start, $end])
                ->selectRaw("COALESCE(SUM({$sumExpr}), 0) as t")
                ->value('t');
        }

        return compact('labels', 'values');
    }

    private function monthlyCountSeries($query, string $dateCol = 'created_at'): array
    {
        $labels = [];
        $values = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->subMonths($i)->startOfMonth();
            $end = Carbon::now()->subMonths($i)->endOfMonth();
            $labels[] = $start->locale('ar')->translatedFormat('M Y');
            $values[] = (clone $query)->whereBetween($dateCol, [$start, $end])->count();
        }

        return compact('labels', 'values');
    }

    private function dailyActivitySeries(int $days): array
    {
        $start = Carbon::today()->subDays($days - 1);
        $projects = Project::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')->pluck('c', 'd');
        $tasks = Task::selectRaw('DATE(created_at) as d, COUNT(*) as c')
            ->where('created_at', '>=', $start)
            ->groupBy('d')->pluck('c', 'd');

        $labels = [];
        $projectValues = [];
        $taskValues = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->locale('ar')->translatedFormat('d M');
            $projectValues[] = (int) ($projects[$date] ?? 0);
            $taskValues[] = (int) ($tasks[$date] ?? 0);
        }

        return [
            'labels' => $labels,
            'projects' => $projectValues,
            'tasks' => $taskValues,
        ];
    }

    private function statusBreakdown(string $model, string $column, array $labels): array
    {
        $counts = $model::select($column, DB::raw('COUNT(*) as c'))->groupBy($column)->pluck('c', $column);
        $outLabels = [];
        $values = [];
        foreach ($labels as $key => $label) {
            $outLabels[] = $label;
            $values[] = (int) ($counts[$key] ?? 0);
        }

        return ['labels' => $outLabels, 'values' => $values];
    }

    private function employeeDashboardData($user): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $projectQuery = fn () => Project::where(function ($q) use ($user) {
            $q->where('project_manager_id', $user->id)
                ->orWhereHas('teamMembers', fn ($t) => $t->where('user_id', $user->id));
        });

        return [
            'my_projects' => $projectQuery()->count(),
            'my_active_projects' => $projectQuery()->whereIn('status', ['planning', 'in_progress'])->count(),
            'my_tasks' => Task::where('assigned_to', $user->id)->count(),
            'my_pending_tasks' => Task::where('assigned_to', $user->id)->where('status', 'pending')->count(),
            'my_overdue_tasks' => Task::where('assigned_to', $user->id)->where('due_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'my_performance_metrics' => [
                'task_efficiency' => Task::where('assigned_to', $user->id)->count() > 0
                    ? round((Task::where('assigned_to', $user->id)->where('status', 'completed')->count() / Task::where('assigned_to', $user->id)->count()) * 100, 1) : 0,
                'attendance_rate' => 0,
                'overdue_tasks' => Task::where('assigned_to', $user->id)->where('due_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            ],
            'recent_projects' => $projectQuery()->with('client')->latest()->take(5)->get(),
            'recent_tasks' => Task::with('project')->where('assigned_to', $user->id)->latest()->take(5)->get(),
        ];
    }

    private function hrDashboardData(): array
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_employees' => Employee::count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'department_stats' => Department::withCount('employees')->active()->get(),
        ];
    }

    private function accountantDashboardData(): array
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_amount' => Expense::where('status', 'approved')->sum('amount'),
            'this_month_expenses' => Expense::where('status', 'approved')->where('created_at', '>=', $thisMonth)->sum('amount'),
            'pending_invoices' => Invoice::where('status', 'pending')->count(),
        ];
    }

    private function salesDashboardData(): array
    {
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'total_clients' => Client::count(),
            'won_sales' => Sale::where('stage', 'closed_won')->count(),
            'conversion_rate' => Sale::count() > 0 ? round((Sale::where('stage', 'closed_won')->count() / Sale::count()) * 100, 1) : 0,
            'this_month_sales_value' => Sale::where('stage', 'closed_won')->where('created_at', '>=', $thisMonth)->sum('actual_value'),
        ];
    }

    private function supportDashboardData($user): array
    {
        return [
            'my_tickets' => Ticket::where('assigned_to', $user->id)->count(),
            'my_open_tickets' => Ticket::where('assigned_to', $user->id)->whereIn('status', ['open', 'in_progress'])->count(),
        ];
    }
}
