<?php

namespace App\Http\Controllers\DepartmentManager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Policies\DepartmentAccess;
use App\Support\ReportingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentManagerProjectController extends Controller
{
    public function assignTeam(Request $request, Project $project)
    {
        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId && (int) $project->department_id === (int) $managedDeptId, 403);

        $project->load(['client', 'department.manager.user', 'projectManager', 'teamMembers']);

        $departmentUserIds = $this->departmentUserIds($managedDeptId);
        $users = User::query()
            ->whereIn('id', $departmentUserIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('department-manager.projects.assign-team', compact('project', 'users'))
            ->with('readOnly', true);
    }

    public function updateTeam(Request $request, Project $project)
    {
        abort(403, 'تعيين فريق المشروع يتم من صفحة القسم بواسطة الإدارة. يمكنك توزيع المهام من لوحة القسم.');
    }

    private function departmentUserIds(int $departmentId)
    {
        return Employee::query()
            ->where('department_id', $departmentId)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->pluck('user_id');
    }
}
