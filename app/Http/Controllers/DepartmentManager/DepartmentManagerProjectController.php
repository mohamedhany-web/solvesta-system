<?php

namespace App\Http\Controllers\DepartmentManager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Policies\DepartmentAccess;
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

        return view('department-manager.projects.assign-team', compact('project', 'users'));
    }

    public function updateTeam(Request $request, Project $project)
    {
        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId && (int) $project->department_id === (int) $managedDeptId, 403);

        $departmentUserIds = $this->departmentUserIds($managedDeptId);

        $validator = Validator::make($request->all(), [
            'project_manager_id' => 'required|exists:users,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (! $departmentUserIds->contains((int) $request->project_manager_id)) {
            return redirect()->back()->withInput()->with('error', 'قائد الفريق يجب أن يكون من موظفي القسم.');
        }

        $teamIds = collect($request->input('team_members', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $invalidMember = $teamIds->first(fn ($id) => ! $departmentUserIds->contains($id));
        if ($invalidMember) {
            return redirect()->back()->withInput()->with('error', 'جميع أعضاء الفريق يجب أن يكونوا من موظفي القسم.');
        }

        $teamIds = $teamIds->reject(fn ($id) => $id === (int) $request->project_manager_id)->values();

        $project->update(['project_manager_id' => $request->project_manager_id]);
        $project->teamMembers()->sync($teamIds->all());

        return redirect()
            ->route('department-manager.dashboard')
            ->with('success', 'تم تعيين قائد الفريق وأعضاء الفريق للمشروع «'.$project->name.'».');
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
