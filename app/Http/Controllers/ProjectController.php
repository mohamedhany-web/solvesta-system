<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\Task;
use App\Support\ProjectScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

    public function index()
    {
        $this->authorize('viewAny', Project::class);
        // Get projects based on user permissions
        $query = Project::with(['client', 'projectManager', 'teamMembers', 'department']);

        if (! auth()->user()->can('view-all-projects')) {
            ProjectScope::apply($query, auth()->user());
        }
        
        $projects = $query
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('priority'), function ($query) {
                $query->where('priority', request('priority'));
            })
            ->when(request('client_id'), function ($query) {
                $query->where('client_id', request('client_id'));
            })
            ->when(request('department_id'), function ($query) {
                $query->where('department_id', request('department_id'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate statistics based on user access
        $statsQuery = Project::query();
        if (! auth()->user()->can('view-all-projects')) {
            ProjectScope::apply($statsQuery, auth()->user());
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'active' => (clone $statsQuery)->whereIn('status', ['planning', 'in_progress'])->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'on_hold' => (clone $statsQuery)->where('status', 'on_hold')->count(),
            'percentage_active' => (clone $statsQuery)->count() > 0 
                ? round(((clone $statsQuery)->whereIn('status', ['planning', 'in_progress'])->count() / (clone $statsQuery)->count()) * 100) 
                : 0,
            'percentage_completed' => (clone $statsQuery)->count() > 0 
                ? round(((clone $statsQuery)->where('status', 'completed')->count() / (clone $statsQuery)->count()) * 100) 
                : 0,
        ];

        // Get recent/featured projects (latest 3) based on user access
        $featuredQuery = Project::with(['client', 'projectManager', 'teamMembers', 'tasks', 'department']);
        if (! auth()->user()->can('view-all-projects')) {
            ProjectScope::apply($featuredQuery, auth()->user());
        }
        $featuredProjects = $featuredQuery->orderBy('created_at', 'desc')->take(3)->get();

        $clients = Client::where('status', 'active')->get();
        $departments = Department::active()->orderBy('name')->get();

        return view('projects.index', compact('projects', 'clients', 'departments', 'stats', 'featuredProjects'));
    }

    public function departmentStaff(Department $department)
    {
        $department->load('manager.user');

        $employees = Employee::query()
            ->where('department_id', $department->id)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->with('user:id,name')
            ->orderBy('first_name')
            ->get();

        $manager = $department->manager;

        return response()->json([
            'department' => [
                'id' => $department->id,
                'name' => $department->name,
            ],
            'manager' => $manager ? [
                'name' => $manager->user?->name ?? trim($manager->first_name.' '.$manager->last_name),
                'user_id' => $manager->user_id,
                'position' => $manager->position,
            ] : null,
            'staff' => $employees->map(fn ($employee) => [
                'user_id' => $employee->user_id,
                'name' => $employee->user?->name ?? trim($employee->first_name.' '.$employee->last_name),
                'position' => $employee->position,
            ])->values(),
            'employees_count' => $employees->count(),
        ]);
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $departments = Department::active()->orderBy('name')->get();

        return view('projects.create', compact('clients', 'departments'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'project_type' => 'nullable|string|max:64',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::create([
            ...$request->only([
                'name', 'description', 'client_id', 'department_id',
                'start_date', 'end_date', 'budget', 'priority', 'status', 'project_type',
            ]),
            'project_manager_id' => null,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'تم إنشاء المشروع — بانتظار رئيس القسم لتعيين قائد الفريق والفريق.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        $project->load(['client', 'projectManager', 'teamMembers', 'tasks.milestone', 'milestones.tasks', 'milestones.assignedLead', 'contract', 'expenses', 'repositories']);
        
        // إحصائيات المشروع
        $stats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('status', 'completed')->count(),
            'in_progress_tasks' => $project->tasks()->where('status', 'in_progress')->count(),
            'pending_tasks' => $project->tasks()->whereIn('status', ['todo', 'review', 'pending'])->count(),
            'team_members_count' => $project->teamMembers()->count(),
            'milestones_count' => $project->milestones()->count(),
            'blockers_count' => $project->tasks()->where('has_blocker', true)->count(),
            'progress_percentage' => $project->milestones()->exists()
                ? (int) round($project->milestones()->avg('progress_percentage'))
                : ($project->tasks()->count() > 0
                    ? round(($project->tasks()->where('status', 'completed')->count() / $project->tasks()->count()) * 100, 2)
                    : ($project->progress_percentage ?? 0)),
        ];

        $teamUsers = $project->teamMembers;
        if ($project->projectManager) {
            $teamUsers = $teamUsers->contains('id', $project->projectManager->id)
                ? $teamUsers
                : $teamUsers->concat([$project->projectManager]);
        }

        $financials = app(\App\Services\ProjectFinanceService::class)->getProjectFinancials($project);
        $projectExpenses = $project->expenses()->with('creator')->orderByDesc('expense_date')->limit(10)->get();

        return view('projects.show', compact('project', 'stats', 'teamUsers', 'financials', 'projectExpenses'));
    }

    public function edit(Project $project)
    {
        $clients = Client::where('status', 'active')->get();
        $departments = Department::active()->orderBy('name')->get();
        $project->load(['teamMembers', 'department.manager.user', 'projectManager']);

        return view('projects.edit', compact('project', 'clients', 'departments'));
    }

    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'department_id' => 'required|exists:departments,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'project_type' => 'nullable|string|max:64',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'name', 'description', 'client_id', 'department_id',
            'start_date', 'end_date', 'budget', 'priority', 'status', 'project_type',
        ]);

        if ((int) $request->department_id !== (int) $project->department_id) {
            $data['project_manager_id'] = null;
            $project->teamMembers()->detach();
        }

        $project->update($data);

        return redirect()->route('projects.index')
            ->with('success', 'تم تحديث المشروع بنجاح');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'تم حذف المشروع بنجاح');
    }

    public function assignTeam(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if (!$project->teamMembers->contains($request->user_id)) {
            $project->teamMembers()->attach($request->user_id);
            
            return redirect()->back()
                ->with('success', 'تم تعيين المستخدم للمشروع بنجاح');
        }

        return redirect()->back()
            ->with('warning', 'المستخدم موجود بالفعل في فريق المشروع');
    }

    public function removeTeam(Project $project, User $user)
    {
        $project->teamMembers()->detach($user->id);

        return redirect()->back()
            ->with('success', 'تم إزالة المستخدم من فريق المشروع بنجاح');
    }

    public function generateReport(Project $project)
    {
        // يمكن إضافة تقرير المشروع لاحقاً
        return redirect()->back()
            ->with('info', 'ميزة التقرير قيد التطوير');
    }
}
