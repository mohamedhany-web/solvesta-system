<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{

    public function index()
    {
        // Get projects based on user permissions
        $query = Project::with(['client', 'projectManager', 'teamMembers']);
        
        // إذا كان المستخدم لديه صلاحية view-own-projects فقط (موظف عادي)
        if (auth()->user()->can('view-own-projects') && !auth()->user()->can('view-all-projects')) {
            // عرض المشاريع التي المستخدم مدير لها أو عضو في فريقها
            $query->where(function($q) {
                $q->where('project_manager_id', auth()->id())
                  ->orWhereHas('teamMembers', function($teamQuery) {
                      $teamQuery->where('user_id', auth()->id());
                  });
            });
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
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate statistics based on user access
        $statsQuery = Project::query();
        if (auth()->user()->can('view-own-projects') && !auth()->user()->can('view-all-projects')) {
            $statsQuery->where(function($q) {
                $q->where('project_manager_id', auth()->id())
                  ->orWhereHas('teamMembers', function($teamQuery) {
                      $teamQuery->where('user_id', auth()->id());
                  });
            });
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
        $featuredQuery = Project::with(['client', 'projectManager', 'teamMembers', 'tasks']);
        if (auth()->user()->can('view-own-projects') && !auth()->user()->can('view-all-projects')) {
            $featuredQuery->where(function($q) {
                $q->where('project_manager_id', auth()->id())
                  ->orWhereHas('teamMembers', function($teamQuery) {
                      $teamQuery->where('user_id', auth()->id());
                  });
            });
        }
        $featuredProjects = $featuredQuery->orderBy('created_at', 'desc')->take(3)->get();

        $clients = Client::where('status', 'active')->get();
        
        return view('projects.index', compact('projects', 'clients', 'stats', 'featuredProjects'));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $users = User::all();
        
        return view('projects.create', compact('clients', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'project_manager_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project = Project::create($request->except('team_members'));

        if ($request->team_members) {
            $project->teamMembers()->attach($request->team_members);
        }

        return redirect()->route('projects.index')
            ->with('success', 'تم إنشاء المشروع بنجاح');
    }

    public function show(Project $project)
    {
        $project->load(['client', 'projectManager', 'teamMembers', 'tasks']);
        
        // إحصائيات المشروع
        $stats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('status', 'completed')->count(),
            'in_progress_tasks' => $project->tasks()->where('status', 'in_progress')->count(),
            'pending_tasks' => $project->tasks()->where('status', 'pending')->count(),
            'team_members_count' => $project->teamMembers()->count(),
            'progress_percentage' => $project->tasks()->count() > 0 
                ? round(($project->tasks()->where('status', 'completed')->count() / $project->tasks()->count()) * 100, 2)
                : 0,
        ];
        
        return view('projects.show', compact('project', 'stats'));
    }

    public function edit(Project $project)
    {
        $clients = Client::where('status', 'active')->get();
        $users = User::all();
        $project->load('teamMembers');
        
        return view('projects.edit', compact('project', 'clients', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'client_id' => 'required|exists:clients,id',
            'project_manager_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'required|numeric|min:0',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:planning,in_progress,on_hold,completed,cancelled',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project->update($request->except('team_members'));

        if ($request->has('team_members')) {
            $project->teamMembers()->sync($request->team_members);
        }

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
