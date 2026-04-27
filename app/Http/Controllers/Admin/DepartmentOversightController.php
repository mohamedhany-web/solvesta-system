<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentReport;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DepartmentOversightController extends Controller
{
    public function index(Request $request)
    {
        // Use same permission as other reports overview
        abort_unless($request->user()?->can('view-reports') || $request->user()?->can('view-departments'), 403);

        $departments = Department::query()
            ->with(['manager.user'])
            ->withCount(['employees', 'projects'])
            ->orderBy('name')
            ->get();

        $deptIds = $departments->pluck('id');

        $taskCountsByDept = Task::query()
            ->selectRaw('projects.department_id as department_id, count(tasks.id) as total')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->whereIn('projects.department_id', $deptIds)
            ->groupBy('projects.department_id')
            ->pluck('total', 'department_id');

        $overdueCountsByDept = Task::query()
            ->selectRaw('projects.department_id as department_id, count(tasks.id) as total')
            ->join('projects', 'projects.id', '=', 'tasks.project_id')
            ->whereIn('projects.department_id', $deptIds)
            ->whereNotIn('tasks.status', ['completed', 'cancelled'])
            ->whereDate('tasks.due_date', '<', now())
            ->groupBy('projects.department_id')
            ->pluck('total', 'department_id');

        $latestReportByDept = DepartmentReport::query()
            ->selectRaw('department_id, max(created_at) as latest_created_at')
            ->whereIn('department_id', $deptIds)
            ->groupBy('department_id')
            ->pluck('latest_created_at', 'department_id');

        $recentReports = DepartmentReport::query()
            ->with(['department.manager.user', 'project', 'creator'])
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'departments_total' => $departments->count(),
            'projects_total' => Project::whereIn('department_id', $deptIds)->count(),
            'reports_total' => DepartmentReport::count(),
        ];

        return view('admin.department-oversight.index', compact(
            'departments',
            'taskCountsByDept',
            'overdueCountsByDept',
            'latestReportByDept',
            'recentReports',
            'stats'
        ));
    }
}

