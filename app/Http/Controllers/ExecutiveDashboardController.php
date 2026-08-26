<?php

namespace App\Http\Controllers;

use App\Models\CompanyException;
use App\Models\EntityLifecycle;
use App\Models\FinancialInvoice;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Sale;
use App\Services\CompanyOs\SlaEngine;
use App\Support\RecordScope;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExecutiveDashboardController extends Controller
{
    public function index(Request $request, SlaEngine $sla)
    {
        $user = $request->user();
        if (! $user->hasRole(['super_admin', 'admin'])) {
            abort(403);
        }

        $sla->scan();

        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $exceptions = CompanyException::query()
            ->open()
            ->with(['owner'])
            ->orderByRaw("CASE severity WHEN 'critical' THEN 0 ELSE 1 END")
            ->latest()
            ->limit(60)
            ->get();

        $withSla = EntityLifecycle::query()->whereNotNull('sla_due_at')->count();
        $breached = EntityLifecycle::query()->whereNotNull('sla_breached_at')->count();
        $slaCompliance = $withSla > 0 ? round((($withSla - $breached) / $withSla) * 100, 1) : 100;

        $health = [
            'revenue' => (float) FinancialInvoice::where('status', 'paid')
                ->where('invoice_date', '>=', $thisMonth)
                ->sum('paid_amount'),
            'pipeline' => (float) Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])
                ->selectRaw('COALESCE(SUM(COALESCE(actual_value, estimated_value)), 0) as t')->value('t'),
            'active_projects' => Project::whereIn('status', ['planning', 'in_progress'])->count(),
            'overdue_projects' => Project::where('end_date', '<', $today)->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'open_critical' => $exceptions->where('severity', 'critical')->count(),
            'open_warning' => $exceptions->where('severity', 'warning')->count(),
            'sla_compliance' => $slaCompliance,
            'leads_new' => Lead::where('status', 'new')->count(),
            'scope' => RecordScope::level($user),
        ];

        return view('executive.dashboard', compact('exceptions', 'health'));
    }
}
