<?php

namespace App\Http\Controllers;

use App\Services\ProjectFinanceService;
use Illuminate\Http\Request;

class ExecutiveFinanceController extends Controller
{
    public function index(Request $request, ProjectFinanceService $finance)
    {
        $user = $request->user();
        if (! $user->hasRole(['super_admin', 'admin'])) {
            abort(403);
        }

        $summary = $finance->getExecutiveFinancialSummary();

        return view('executive.finance', compact('summary'));
    }
}
