<?php

namespace App\Http\Controllers;

use App\Models\HrWarning;
use App\Models\User;
use App\Services\HrPenaltyService;
use Illuminate\Http\Request;

class HrWarningController extends Controller
{
    public function index(Request $request)
    {
        $query = HrWarning::with(['user', 'task', 'issuer'])
            ->orderByDesc('created_at');

        if ($request->get('filter') === 'investigations') {
            $query->whereIn('investigation_status', ['pending', 'in_progress']);
        } elseif ($request->get('filter') === 'active') {
            $query->whereIn('status', ['active', 'escalated']);
        }

        $warnings = $query->paginate(20)->withQueryString();

        $stats = [
            'active' => HrWarning::whereIn('status', ['active', 'escalated'])->count(),
            'investigations' => HrWarning::whereIn('investigation_status', ['pending', 'in_progress'])->count(),
            'resolved_month' => HrWarning::where('status', 'resolved')->where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('hr.warnings.index', compact('warnings', 'stats'));
    }

    public function store(Request $request, HrPenaltyService $hr)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:task_delay,kpi_deduction,attendance,conduct,other',
            'reason' => 'required|string|max:500',
            'kpi_deduction_points' => 'nullable|numeric|min:0|max:50',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $hr->issueWarning($user, $validated);

        return back()->with('success', 'تم تسجيل التحذير وخصم KPI.');
    }

    public function resolve(HrWarning $warning, Request $request, HrPenaltyService $hr)
    {
        $validated = $request->validate(['hr_notes' => 'nullable|string|max:2000']);
        $hr->resolveWarning($warning, $validated['hr_notes'] ?? null);

        return back()->with('success', 'تم حل التحذير.');
    }

    public function investigate(HrWarning $warning, HrPenaltyService $hr)
    {
        $hr->startInvestigation($warning);

        return back()->with('success', 'بدأ تحقيق HR.');
    }

    public function scanOverdue(HrPenaltyService $hr)
    {
        $count = $hr->scanOverdueTasks();

        return back()->with('success', "تم فحص المهام المتأخرة — {$count} تحذير جديد.");
    }
}
