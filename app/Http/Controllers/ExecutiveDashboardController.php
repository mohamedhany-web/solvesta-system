<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\EmployeeKpiPeriod;
use App\Models\FinancialInvoice;
use App\Models\HrWarning;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Proposal;
use App\Models\Sale;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExecutiveDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user->hasRole(['super_admin', 'admin'])) {
            abort(403);
        }

        $today = Carbon::today();

        $pipeline = [
            'leads_new' => Lead::where('status', 'new')->count(),
            'leads_qualified' => Lead::where('status', 'qualified')->count(),
            'sales_open' => Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])->count(),
            'sales_qualified' => Sale::where('qualification_status', 'qualified')->whereNotIn('stage', ['closed_won', 'closed_lost'])->count(),
            'proposal' => Sale::where('stage', 'proposal')->count(),
            'negotiation' => Sale::where('stage', 'negotiation')->count(),
            'closed_won' => Sale::where('stage', 'closed_won')->count(),
            'closed_lost' => Sale::where('stage', 'closed_lost')->count(),
            'pipeline_value' => (float) Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])
                ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')->value('t'),
            'won_value' => (float) Sale::where('stage', 'closed_won')
                ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')->value('t'),
        ];

        $finance = [
            'revenue_paid' => (float) Invoice::where('status', 'paid')->sum('paid_amount')
                + (float) FinancialInvoice::where('payment_status', 'paid')->sum('paid_amount'),
            'outstanding' => (float) Invoice::where('status', '!=', 'paid')->where('status', '!=', 'cancelled')->sum('balance_amount')
                + (float) FinancialInvoice::where('payment_status', '!=', 'paid')->sum('balance_due'),
        ];

        $projects = [
            'active' => Project::whereIn('status', ['planning', 'in_progress'])->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'overdue' => Project::where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'blocked_payment' => Project::where('kickoff_status', 'blocked_pending_payment')->count(),
        ];

        $operations = [
            'overdue_tasks' => Task::where('due_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'open_tasks' => Task::whereIn('status', ['pending', 'in_progress', 'todo', 'review'])->count(),
            'open_blockers' => Task::where('has_blocker', true)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'attendance_today' => Attendance::whereDate('date', $today)->where('status', 'present')->count(),
        ];

        $recentLeads = Lead::orderByDesc('created_at')->limit(8)->get();
        $recentWins = Sale::with('client')->where('stage', 'closed_won')->orderByDesc('updated_at')->limit(6)->get();

        $presales = [
            'sent' => Proposal::where('status', 'sent')->count(),
            'accepted' => Proposal::where('status', 'accepted')->count(),
            'draft' => Proposal::where('status', 'draft')->count(),
        ];

        $financeSummary = app(\App\Services\ProjectFinanceService::class)->getExecutiveFinancialSummary();

        $performance = [
            'avg_kpi' => round((float) EmployeeKpiPeriod::where('period_year', now()->year)->where('period_month', now()->month)->avg('total_score'), 1),
            'hr_investigations' => HrWarning::whereIn('investigation_status', ['pending', 'in_progress'])->count(),
            'active_warnings' => HrWarning::whereIn('status', ['active', 'escalated'])->count(),
        ];

        return view('executive.dashboard', compact(
            'pipeline',
            'finance',
            'projects',
            'operations',
            'recentLeads',
            'recentWins',
            'presales',
            'financeSummary',
            'performance',
        ));
    }
}
