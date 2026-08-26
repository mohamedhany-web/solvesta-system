<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Sale;
use App\Models\SalesKpiTarget;
use App\Models\User;
use Carbon\Carbon;

class SalesPerformanceService
{
    public function metricDefinitions(): array
    {
        return config('sales-kpis.metrics', []);
    }

    public function dashboardForUser(User $user, ?int $year = null, ?int $month = null): array
    {
        $year = $year ?? (int) now()->year;
        $month = $month ?? (int) now()->month;
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $actuals = $this->calculateActuals($user, $start, $end);
        $targets = $this->resolveTargets($user, $year, $month);
        $metrics = [];

        foreach ($this->metricDefinitions() as $key => $def) {
            $target = (float) ($targets[$key]['target_value'] ?? config("sales-kpis.default_targets.{$key}", 0));
            $actual = (float) ($actuals[$key] ?? 0);
            $weight = (int) ($targets[$key]['weight'] ?? config("sales-kpis.default_weights.{$key}", 10));
            $attainment = $target > 0 ? min(150, round(($actual / $target) * 100, 1)) : ($actual > 0 ? 100 : 0);

            $metrics[$key] = array_merge($def, [
                'key' => $key,
                'actual' => $actual,
                'target' => $target,
                'weight' => $weight,
                'attainment_pct' => $attainment,
                'monthly_duty' => $targets[$key]['monthly_duty'] ?? null,
            ]);
        }

        return [
            'year' => $year,
            'month' => $month,
            'period_label' => $start->locale('ar')->translatedFormat('F Y'),
            'metrics' => $metrics,
            'overall_score' => $this->overallScoreFromMetrics($metrics),
            'pipeline' => $this->openPipeline($user),
            'recent_wins' => $this->recentWins($user, $start, $end),
            'monthly_duties' => collect($metrics)
                ->filter(fn ($m) => ! empty($m['monthly_duty']))
                ->map(fn ($m) => ['metric' => $m['label'], 'duty' => $m['monthly_duty']])
                ->values()
                ->all(),
        ];
    }

    public function overallScore(User $user, ?int $year = null, ?int $month = null): float
    {
        $data = $this->dashboardForUser($user, $year, $month);

        return $data['overall_score'];
    }

    public function calculateActuals(User $user, Carbon $start, Carbon $end): array
    {
        $userId = $user->id;

        $wonQuery = Sale::query()
            ->where('assigned_to', $userId)
            ->where('stage', 'closed_won')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('actual_close_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->whereNull('actual_close_date')
                            ->whereBetween('updated_at', [$start, $end]);
                    });
            });

        $wonSales = (clone $wonQuery)->get(['actual_value', 'estimated_value']);
        $revenueWon = (float) $wonSales->sum(fn ($s) => (float) ($s->actual_value ?? $s->estimated_value ?? 0));
        $dealsClosed = $wonSales->count();
        $avgDealSize = $dealsClosed > 0 ? round($revenueWon / $dealsClosed, 2) : 0;

        $lostCount = Sale::query()
            ->where('assigned_to', $userId)
            ->where('stage', 'closed_lost')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $winRate = ($dealsClosed + $lostCount) > 0
            ? round(($dealsClosed / ($dealsClosed + $lostCount)) * 100, 1)
            : 0;

        $pipelineValue = (float) Sale::query()
            ->where('assigned_to', $userId)
            ->whereNotIn('stage', ['closed_won', 'closed_lost'])
            ->selectRaw('COALESCE(SUM(COALESCE(estimated_value, actual_value, 0)), 0) as t')
            ->value('t');

        $newOpportunities = Sale::query()
            ->where('assigned_to', $userId)
            ->whereBetween('created_at', [$start, $end])
            ->count();

        $leadsQualified = Lead::query()
            ->where('assigned_to', $userId)
            ->where('status', 'qualified')
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $demosMeetings = Lead::query()
            ->where('assigned_to', $userId)
            ->whereIn('status', ['contacted', 'qualified', 'converted'])
            ->whereBetween('updated_at', [$start, $end])
            ->count();

        $quotaTarget = (float) ($this->resolveTargets($user, (int) $start->year, (int) $start->month)['revenue_won']['target_value']
            ?? config('sales-kpis.default_targets.revenue_won', 0));

        $pipelineCoverage = $quotaTarget > 0 ? round($pipelineValue / $quotaTarget, 2) : 0;

        return [
            'revenue_won' => $revenueWon,
            'deals_closed' => $dealsClosed,
            'pipeline_value' => $pipelineValue,
            'pipeline_coverage' => $pipelineCoverage,
            'leads_qualified' => $leadsQualified,
            'new_opportunities' => $newOpportunities,
            'win_rate' => $winRate,
            'demos_meetings' => $demosMeetings,
            'avg_deal_size' => $avgDealSize,
        ];
    }

    public function resolveTargets(User $user, int $year, int $month): array
    {
        $userTargets = SalesKpiTarget::query()
            ->where('user_id', $user->id)
            ->where('period_year', $year)
            ->where('period_month', $month)
            ->get()
            ->keyBy('metric_key');

        if ($userTargets->isNotEmpty()) {
            return $userTargets->map(fn ($t) => [
                'target_value' => $t->target_value,
                'weight' => $t->weight,
                'monthly_duty' => $t->monthly_duty,
            ])->all();
        }

        $deptId = $user->employee?->department_id;
        if ($deptId) {
            $deptTargets = SalesKpiTarget::query()
                ->whereNull('user_id')
                ->where('department_id', $deptId)
                ->where('period_year', $year)
                ->where('period_month', $month)
                ->get()
                ->keyBy('metric_key');

            if ($deptTargets->isNotEmpty()) {
                return $deptTargets->map(fn ($t) => [
                    'target_value' => $t->target_value,
                    'weight' => $t->weight,
                    'monthly_duty' => $t->monthly_duty,
                ])->all();
            }
        }

        $defaults = config('sales-kpis.default_targets', []);
        $weights = config('sales-kpis.default_weights', []);
        $resolved = [];
        foreach ($defaults as $key => $value) {
            $resolved[$key] = [
                'target_value' => $value,
                'weight' => $weights[$key] ?? 10,
                'monthly_duty' => null,
            ];
        }

        return $resolved;
    }

    protected function overallScoreFromMetrics(array $metrics): float
    {
        $totalWeight = 0;
        $weighted = 0;

        foreach ($metrics as $m) {
            $w = (int) ($m['weight'] ?? 0);
            if ($w <= 0) {
                continue;
            }
            $totalWeight += $w;
            $weighted += min(100, (float) ($m['attainment_pct'] ?? 0)) * $w;
        }

        return $totalWeight > 0 ? round($weighted / $totalWeight, 2) : 0;
    }

    protected function openPipeline(User $user): array
    {
        $deals = Sale::query()
            ->where('assigned_to', $user->id)
            ->whereNotIn('stage', ['closed_won', 'closed_lost'])
            ->with('client:id,name')
            ->orderByDesc('estimated_value')
            ->limit(8)
            ->get();

        return [
            'count' => Sale::query()
                ->where('assigned_to', $user->id)
                ->whereNotIn('stage', ['closed_won', 'closed_lost'])
                ->count(),
            'total_value' => (float) Sale::query()
                ->where('assigned_to', $user->id)
                ->whereNotIn('stage', ['closed_won', 'closed_lost'])
                ->selectRaw('COALESCE(SUM(COALESCE(estimated_value, actual_value, 0)), 0) as t')
                ->value('t'),
            'deals' => $deals,
        ];
    }

    protected function recentWins(User $user, Carbon $start, Carbon $end): \Illuminate\Support\Collection
    {
        return Sale::query()
            ->where('assigned_to', $user->id)
            ->where('stage', 'closed_won')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('actual_close_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->whereNull('actual_close_date')
                            ->whereBetween('updated_at', [$start, $end]);
                    });
            })
            ->with('client:id,name')
            ->latest('updated_at')
            ->limit(5)
            ->get();
    }
}
