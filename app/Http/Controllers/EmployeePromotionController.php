<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeKpiPeriod;
use App\Models\EmployeePromotion;
use App\Policies\DepartmentAccess;
use App\Support\ReportingHierarchy;
use Illuminate\Http\Request;

class EmployeePromotionController extends Controller
{
    public function index(Request $request)
    {
        if (! $request->user()->can('edit-employees') && ! DepartmentAccess::isDepartmentManager($request->user())) {
            abort(403);
        }

        $promotions = EmployeePromotion::with(['employee.department', 'employee.user', 'proposer'])
            ->when($request->get('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->latest()
            ->paginate(20);

        $employees = Employee::where('status', 'active')->with('department')->orderBy('first_name')->get();

        return view('hr.promotions.index', [
            'promotions' => $promotions,
            'employees' => $employees,
            'statusLabels' => EmployeePromotion::statusLabels(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'to_level' => 'required|string|max:128',
            'justification' => 'required|string|max:3000',
        ]);

        $employee = Employee::with('department')->findOrFail($validated['employee_id']);

        $latestKpi = EmployeeKpiPeriod::where('user_id', $employee->user_id)
            ->orderByDesc('period_year')->orderByDesc('period_month')->first();

        EmployeePromotion::create([
            'employee_id' => $employee->id,
            'from_level' => $employee->career_level,
            'to_level' => $validated['to_level'],
            'career_track' => $employee->career_track ?? $employee->department?->career_track ?? 'general',
            'status' => EmployeePromotion::STATUS_PENDING_TEAM_LEAD,
            'kpi_score' => $latestKpi?->total_score,
            'justification' => $validated['justification'],
            'proposed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'تم تقديم طلب الترقية وإرساله لسلسلة المراجعة.');
    }

    public function advance(Request $request, EmployeePromotion $promotion)
    {
        $user = $request->user();
        $notes = $request->validate(['notes' => 'nullable|string|max:2000'])['notes'] ?? null;

        $employee = $promotion->employee;

        if ($promotion->status === EmployeePromotion::STATUS_PENDING_TEAM_LEAD) {
            if (! ReportingHierarchy::isTeamLead($user) && ! ReportingHierarchy::isDeptHead($user) && ! $user->can('edit-employees')) {
                abort(403);
            }
            $promotion->update([
                'status' => EmployeePromotion::STATUS_PENDING_DEPT_HEAD,
                'team_lead_notes' => $notes,
            ]);
        } elseif ($promotion->status === EmployeePromotion::STATUS_PENDING_DEPT_HEAD) {
            if (! ReportingHierarchy::isDeptHead($user) && ! $user->can('edit-employees')) {
                abort(403);
            }
            $promotion->update([
                'status' => EmployeePromotion::STATUS_PENDING_HR,
                'dept_head_notes' => $notes,
            ]);
        } elseif ($promotion->status === EmployeePromotion::STATUS_PENDING_HR) {
            if (! $user->can('edit-employees')) {
                abort(403);
            }
            $promotion->update([
                'status' => EmployeePromotion::STATUS_APPROVED,
                'hr_notes' => $notes,
                'approved_by' => $user->id,
                'approved_at' => now(),
            ]);
            $employee->update(['career_level' => $promotion->to_level]);
        } else {
            abort(422);
        }

        return back()->with('success', 'تم تحديث مسار الترقية.');
    }

    public function reject(Request $request, EmployeePromotion $promotion)
    {
        if (! $request->user()->can('edit-employees') && ! ReportingHierarchy::isDeptHead($request->user())) {
            abort(403);
        }

        $promotion->update([
            'status' => EmployeePromotion::STATUS_REJECTED,
            'hr_notes' => $request->input('notes'),
        ]);

        return back()->with('success', 'تم رفض طلب الترقية.');
    }
}
