<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SalesPerformanceService;
use App\Services\SalesRepScope;
use Illuminate\Http\Request;

class SalesDashboardController extends Controller
{
    public function __construct(protected SalesPerformanceService $salesPerformance) {}

    public function index(Request $request)
    {
        $user = auth()->user();
        $canViewAll = $user->can('edit-employees') || $user->hasAnyRole(['super_admin', 'admin', 'hr']);

        $year = (int) $request->input('year', now()->year);
        $month = (int) $request->input('month', now()->month);

        $repUser = $user;
        if (SalesRepScope::isRestricted($user)) {
            $repUser = $user;
        } elseif ($canViewAll && $request->filled('user_id')) {
            $repUser = User::findOrFail((int) $request->input('user_id'));
        } elseif (! $user->hasRole('sales_rep') && ! $canViewAll) {
            abort(403, 'غير مصرح لك بعرض لوحة المبيعات.');
        }

        $dashboard = $this->salesPerformance->dashboardForUser($repUser, $year, $month);

        $salesReps = $canViewAll
            ? User::role('sales_rep')->with('employee.department')->orderBy('name')->get()
            : collect();

        return view('sales.dashboard', compact('dashboard', 'repUser', 'salesReps', 'canViewAll', 'year', 'month'));
    }
}
