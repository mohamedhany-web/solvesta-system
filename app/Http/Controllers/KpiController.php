<?php

namespace App\Http\Controllers;

use App\Models\EmployeeKpiPeriod;
use App\Models\User;
use App\Services\KpiCalculationService;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    public function index(Request $request, KpiCalculationService $kpi)
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        if ($request->boolean('recalculate')) {
            $kpi->calculateTeam($year, $month);
        }

        $periods = EmployeeKpiPeriod::with('user.employee')
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->orderByDesc('total_score')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'avg_score' => round((float) EmployeeKpiPeriod::where('period_year', $year)->where('period_month', $month)->avg('total_score'), 1),
            'excellent' => EmployeeKpiPeriod::where('period_year', $year)->where('period_month', $month)->where('total_score', '>=', 90)->count(),
            'needs_improvement' => EmployeeKpiPeriod::where('period_year', $year)->where('period_month', $month)->where('total_score', '<', 60)->count(),
            'employees' => User::whereHas('employee')->count(),
        ];

        return view('kpi.index', compact('periods', 'stats', 'year', 'month'));
    }

    public function show(User $user, Request $request, KpiCalculationService $kpi)
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        $period = $kpi->calculateForUser($user, $year, $month);
        $history = EmployeeKpiPeriod::where('user_id', $user->id)->orderByDesc('period_year')->orderByDesc('period_month')->limit(6)->get();
        $weights = $kpi->roleWeights($period->role_template);

        return view('kpi.show', compact('user', 'period', 'history', 'weights', 'year', 'month'));
    }

    public function rate(Request $request, User $user, KpiCalculationService $kpi)
    {
        $validated = $request->validate([
            'team_lead_rating' => 'required|numeric|min:0|max:100',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'notes' => 'nullable|string|max:2000',
        ]);

        $period = $kpi->calculateForUser($user, $validated['year'], $validated['month'], (float) $validated['team_lead_rating']);
        if (! empty($validated['notes'])) {
            $period->update(['notes' => $validated['notes']]);
        }

        return back()->with('success', 'تم تسجيل تقييم Team Lead وتحديث KPI.');
    }

    public function recalculateTeam(Request $request, KpiCalculationService $kpi)
    {
        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $count = $kpi->calculateTeam($year, $month);

        return redirect()->route('kpi.index', ['year' => $year, 'month' => $month])
            ->with('success', "تم تحديث KPI لـ {$count} موظف.");
    }
}
