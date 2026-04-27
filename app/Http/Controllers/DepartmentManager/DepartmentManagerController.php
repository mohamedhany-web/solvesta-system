<?php

namespace App\Http\Controllers\DepartmentManager;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Project;
use App\Models\Task;
use App\Policies\DepartmentAccess;
use Illuminate\Http\Request;

class DepartmentManagerController extends Controller
{
    public function dashboard(Request $request)
    {
        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId, 403);

        $department = Department::with(['manager.user'])->findOrFail($managedDeptId);

        $projects = Project::query()
            ->withCount(['tasks'])
            ->with(['client'])
            ->where('department_id', $managedDeptId)
            ->latest()
            ->paginate(10);

        $tasksQuery = Task::query()
            ->whereHas('project', fn ($q) => $q->where('department_id', $managedDeptId));

        $stats = [
            'projects_total' => Project::where('department_id', $managedDeptId)->count(),
            'tasks_total' => (clone $tasksQuery)->count(),
            'tasks_in_progress' => (clone $tasksQuery)->where('status', 'in_progress')->count(),
            'tasks_completed' => (clone $tasksQuery)->where('status', 'completed')->count(),
            'tasks_overdue' => (clone $tasksQuery)->whereNotIn('status', ['completed', 'cancelled'])->whereDate('due_date', '<', now())->count(),
        ];

        $recentTasks = Task::with(['project', 'assignedTo'])
            ->whereHas('project', fn ($q) => $q->where('department_id', $managedDeptId))
            ->latest()
            ->take(8)
            ->get();

        return view('department-manager.dashboard', compact('department', 'projects', 'stats', 'recentTasks'));
    }
}

