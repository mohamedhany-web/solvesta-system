<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentReport;
use Illuminate\Http\Request;

class DepartmentReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', DepartmentReport::class);

        $reports = DepartmentReport::query()
            ->with(['department.manager.user', 'project', 'creator'])
            ->when($request->filled('department_id'), fn ($q) => $q->where('department_id', $request->department_id))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $departments = Department::query()->orderBy('name')->get();

        return view('department-reports.admin.index', compact('reports', 'departments'));
    }

    public function show(DepartmentReport $departmentReport)
    {
        $this->authorize('view', $departmentReport);

        $departmentReport->load(['department.manager.user', 'project', 'creator']);

        return view('department-reports.admin.show', ['report' => $departmentReport]);
    }
}

