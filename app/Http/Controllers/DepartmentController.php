<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Services\DepartmentProfileService;
use App\Support\ReportingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['employees', 'projects'])
            ->with(['manager.user', 'parent', 'children'])
            ->orderByRaw('COALESCE(parent_id, id)')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $employees = Employee::with('user')->where('status', 'active')->get();
        $department = new Department;
        $parentDepartments = Department::whereNull('parent_id')->orderBy('name')->get();

        return view('departments.create', compact('employees', 'department', 'parentDepartments'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateDepartment($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Department::create($this->departmentPayload($request));

        return redirect()->route('departments.index')
            ->with('success', 'تم إنشاء القسم بنجاح');
    }

    public function show(Department $department)
    {
        $department->load([
            'manager.user',
            'parent',
            'children',
            'employees' => fn ($query) => $query
                ->with(['user', 'supervisor'])
                ->where('status', 'active')
                ->orderBy('first_name'),
            'projects' => fn ($query) => $query
                ->with(['tasks', 'client', 'projectManager', 'teamMembers'])
                ->latest(),
        ]);

        $deptUserIds = $department->employees->pluck('user_id')->filter()->values();

        $availableEmployees = Employee::with(['user', 'department'])
            ->where('status', 'active')
            ->where(fn ($q) => $q->where('department_id', '!=', $department->id)->orWhereNull('department_id'))
            ->orderBy('first_name')
            ->get();

        $supervisorOptions = User::query()
            ->whereIn('id', $deptUserIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        $unassignedProjects = Project::query()
            ->where(fn ($q) => $q->whereNull('department_id')->orWhere('department_id', '!=', $department->id))
            ->orderBy('name')
            ->take(50)
            ->get(['id', 'name']);

        $otherDepartments = Department::where('id', '!=', $department->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $stats = [
            'total_employees' => $department->employees->count(),
            'team_leads' => $department->employees->where('is_team_lead', true)->count(),
            'active_projects' => $department->projects->where('status', 'active')->count(),
            'completed_projects' => $department->projects->where('status', 'completed')->count(),
            'pending_team_projects' => $department->projects->whereNull('project_manager_id')->count(),
            'total_tasks' => $department->projects->sum(fn ($project) => $project->tasks->count()),
            'pending_tasks' => $department->projects->sum(fn ($project) => $project->tasks->where('status', 'pending')->count()),
            'total_budget' => $department->projects->sum('budget'),
            'average_salary' => $department->employees->avg('salary') ?? 0,
        ];

        return view('departments.show', compact(
            'department', 'stats', 'availableEmployees', 'supervisorOptions',
            'unassignedProjects', 'otherDepartments'
        ));
    }

    public function edit(Department $department)
    {
        $department->load('parent');
        $employees = Employee::with('user')->where('status', 'active')->get();
        $excludeIds = array_merge([$department->id], $department->descendantIds());
        $parentDepartments = Department::whereNull('parent_id')
            ->whereNotIn('id', $excludeIds)
            ->orderBy('name')
            ->get();

        return view('departments.edit', compact('department', 'employees', 'parentDepartments'));
    }

    public function update(Request $request, Department $department)
    {
        $validator = $this->validateDepartment($request, $department);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $department->update($this->departmentPayload($request));

        return redirect()->route('departments.show', $department)
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف القسم لوجود موظفين مرتبطين به');
        }

        if ($department->projects()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف القسم لوجود مشاريع مرتبطة به');
        }

        if ($department->children()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف القسم لوجود أقسام فرعية تحته');
        }

        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'تم حذف القسم بنجاح');
    }

    public function assignEmployee(Request $request, Department $department)
    {
        if (! $request->user()->can('edit-departments')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $employee = Employee::with('user')->findOrFail($request->employee_id);
        $profile = DepartmentProfileService::forDepartment($department);
        $deptHeadUserId = ReportingHierarchy::deptHeadUserId($department->id);

        $employee->update([
            'department_id' => $department->id,
            'career_track' => $profile['career_track'] ?? $employee->career_track,
            'position' => $employee->position ?: ($profile['default_position'] ?? $employee->position),
            'supervisor_user_id' => $employee->is_team_lead ? null : ($deptHeadUserId ?: $employee->supervisor_user_id),
        ]);

        if ($employee->user && $profile['default_role'] ?? null) {
            DepartmentProfileService::applyRoleToUser($employee->user, $profile['default_role']);
        }

        return redirect()->route('departments.show', $department)
            ->with('success', 'تم إضافة '.$employee->first_name.' '.$employee->last_name.' إلى القسم.');
    }

    public function removeEmployee(Request $request, Department $department, Employee $employee)
    {
        if (! $request->user()->can('edit-departments')) {
            abort(403);
        }

        if ((int) $employee->department_id !== (int) $department->id) {
            abort(404);
        }

        if ((int) $department->manager_id === (int) $employee->id) {
            return redirect()->back()->with('error', 'لا يمكن إزالة مدير القسم. عيّن مديراً آخر أولاً.');
        }

        $validator = Validator::make($request->all(), [
            'target_department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $employee->update([
            'department_id' => $request->target_department_id,
            'supervisor_user_id' => null,
            'is_team_lead' => false,
        ]);

        return redirect()->route('departments.show', $department)
            ->with('success', 'تم نقل الموظف إلى قسم آخر.');
    }

    public function setManager(Request $request, Department $department)
    {
        if (! $request->user()->can('edit-departments')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'manager_id' => 'nullable|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if ($request->manager_id) {
            $employee = Employee::findOrFail($request->manager_id);
            if ((int) $employee->department_id !== (int) $department->id) {
                $employee->update(['department_id' => $department->id]);
            }
        }

        $department->update(['manager_id' => $request->manager_id ?: null]);

        return redirect()->route('departments.show', $department)
            ->with('success', $request->manager_id ? 'تم تعيين رئيس القسم بنجاح.' : 'تم إلغاء تعيين رئيس القسم.');
    }

    public function updateTeamMember(Request $request, Department $department, Employee $employee)
    {
        if (! $request->user()->can('edit-departments')) {
            abort(403);
        }

        if ((int) $employee->department_id !== (int) $department->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'supervisor_user_id' => 'nullable|exists:users,id',
            'is_team_lead' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $supervisorId = $request->input('supervisor_user_id') ?: null;
        $deptHeadUserId = ReportingHierarchy::deptHeadUserId($department->id);

        if ($supervisorId && (int) $supervisorId === (int) $employee->user_id) {
            return redirect()->back()->with('error', 'لا يمكن أن يكون الموظف مشرفاً على نفسه.');
        }

        if ($request->boolean('is_team_lead')) {
            $supervisorId = $deptHeadUserId;
        }

        $employee->update([
            'is_team_lead' => $request->boolean('is_team_lead'),
            'supervisor_user_id' => $supervisorId,
        ]);

        return redirect()->route('departments.show', $department)
            ->with('success', 'تم تحديث هيكل الفريق للموظف.');
    }

    public function updateProjectTeam(Request $request, Department $department, Project $project)
    {
        if (! $request->user()->can('edit-departments')) {
            abort(403);
        }

        if ((int) $project->department_id !== (int) $department->id) {
            abort(404);
        }

        $departmentUserIds = Employee::query()
            ->where('department_id', $department->id)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->pluck('user_id');

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
            ->reject(fn ($id) => $id === (int) $request->project_manager_id)
            ->values();

        $invalidMember = $teamIds->first(fn ($id) => ! $departmentUserIds->contains($id));
        if ($invalidMember) {
            return redirect()->back()->withInput()->with('error', 'جميع أعضاء الفريق يجب أن يكونوا من موظفي القسم.');
        }

        $project->update(['project_manager_id' => $request->project_manager_id]);
        $project->teamMembers()->sync($teamIds->all());
        $project->load('teamMembers');
        ReportingHierarchy::syncProjectTeamHierarchy($project);

        return redirect()->route('departments.show', $department)
            ->with('success', 'تم تعيين فريق المشروع «'.$project->name.'» لرئيس القسم.');
    }

    public function assignProject(Request $request, Department $department)
    {
        if (! $request->user()->can('edit-departments')) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $project = Project::findOrFail($request->project_id);
        $project->update(['department_id' => $department->id]);

        return redirect()->route('departments.show', $department)
            ->with('success', 'تم ربط المشروع بالقسم.');
    }

    private function validateDepartment(Request $request, ?Department $department = null)
    {
        $moduleKeys = implode(',', array_keys(Department::SIDEBAR_MODULES));

        $rules = [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:20|unique:departments,code'.($department ? ','.$department->id : ''),
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:employees,id',
            'parent_id' => 'nullable|exists:departments,id',
            'sidebar_modules' => 'nullable|array',
            'sidebar_modules.*' => 'in:'.$moduleKeys,
            'default_role' => 'nullable|string|max:64',
            'default_position' => 'nullable|string|max:255',
            'kpi_profile' => 'nullable|string|max:64',
            'career_track' => 'nullable|string|max:64',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'is_active' => 'nullable|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        $validator->after(function ($validator) use ($request, $department) {
            if (! $request->parent_id) {
                return;
            }

            if ($department && (int) $request->parent_id === (int) $department->id) {
                $validator->errors()->add('parent_id', 'لا يمكن أن يكون القسم تابعاً لنفسه');

                return;
            }

            if ($department) {
                $invalidParents = array_merge([$department->id], $department->descendantIds());
                if (in_array((int) $request->parent_id, $invalidParents, true)) {
                    $validator->errors()->add('parent_id', 'لا يمكن اختيار قسم فرعي كقسم رئيسي');
                }
            }
        });

        return $validator;
    }

    private function departmentPayload(Request $request): array
    {
        $modules = $request->input('sidebar_modules', []);

        return [
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'manager_id' => $request->manager_id ?: null,
            'parent_id' => $request->parent_id ?: null,
            'sidebar_modules' => is_array($modules) && count($modules) > 0 ? array_values($modules) : null,
            'default_role' => $request->input('default_role') ?: null,
            'default_position' => $request->input('default_position') ?: null,
            'kpi_profile' => $request->input('kpi_profile') ?: null,
            'career_track' => $request->input('career_track') ?: null,
            'location' => $request->location,
            'phone' => $request->phone,
            'email' => $request->email,
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
