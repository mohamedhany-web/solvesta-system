<?php

namespace App\Http\Controllers\DepartmentManager;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Policies\DepartmentAccess;
use App\Support\ReportingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentManagerTeamController extends Controller
{
    public function index(Request $request)
    {
        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId, 403);

        $department = Department::with('manager.user')->findOrFail($managedDeptId);

        $employees = Employee::query()
            ->where('department_id', $managedDeptId)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->with(['user', 'supervisor'])
            ->orderBy('first_name')
            ->get();

        $supervisorOptions = User::query()
            ->whereIn('id', $employees->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('department-manager.team.index', compact('department', 'employees', 'supervisorOptions'));
    }

    public function update(Request $request, Employee $employee)
    {
        abort(403, 'تعديل هيكل الفريق يتم من صفحة القسم بواسطة الإدارة فقط.');
    }
}
