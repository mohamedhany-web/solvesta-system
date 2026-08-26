<?php

namespace App\Http\Controllers;

use App\Models\SalesKpiTarget;
use App\Models\User;
use Illuminate\Http\Request;

class SalesKpiTargetController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);
        $userId = $request->input('user_id');

        $metrics = config('sales-kpis.metrics', []);
        $defaults = config('sales-kpis.default_targets', []);
        $weights = config('sales-kpis.default_weights', []);

        $salesReps = User::role('sales_rep')->with('employee.department')->orderBy('name')->get();

        $selectedUser = $userId ? User::find($userId) : $salesReps->first();
        $existing = collect();

        if ($selectedUser) {
            $existing = SalesKpiTarget::where('user_id', $selectedUser->id)
                ->where('period_year', $year)
                ->where('period_month', $month)
                ->get()
                ->keyBy('metric_key');
        }

        return view('sales.kpi-targets', compact(
            'year', 'month', 'metrics', 'defaults', 'weights',
            'salesReps', 'selectedUser', 'existing'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_year' => 'required|integer|min:2020|max:2100',
            'period_month' => 'required|integer|min:1|max:12',
            'targets' => 'required|array',
            'targets.*.target_value' => 'nullable|numeric|min:0',
            'targets.*.weight' => 'nullable|integer|min:0|max:100',
            'targets.*.monthly_duty' => 'nullable|string|max:1000',
        ]);

        $userId = (int) $validated['user_id'];
        $year = (int) $validated['period_year'];
        $month = (int) $validated['period_month'];

        foreach ($validated['targets'] as $metricKey => $row) {
            $targetValue = $row['target_value'] ?? null;
            if ($targetValue === null || $targetValue === '') {
                SalesKpiTarget::where('user_id', $userId)
                    ->where('metric_key', $metricKey)
                    ->where('period_year', $year)
                    ->where('period_month', $month)
                    ->delete();

                continue;
            }

            SalesKpiTarget::updateOrCreate(
                [
                    'user_id' => $userId,
                    'metric_key' => $metricKey,
                    'period_year' => $year,
                    'period_month' => $month,
                ],
                [
                    'target_value' => $targetValue,
                    'weight' => (int) ($row['weight'] ?? config("sales-kpis.default_weights.{$metricKey}", 10)),
                    'monthly_duty' => $row['monthly_duty'] ?? null,
                    'set_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('sales.kpi-targets.index', [
            'user_id' => $userId,
            'year' => $year,
            'month' => $month,
        ])->with('success', 'تم حفظ أهداف KPI وواجبات الشهر.');
    }
}
