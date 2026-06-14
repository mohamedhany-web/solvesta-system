<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use App\Services\PmoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PmoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();

        $projectsQuery = Project::with(['client', 'projectManager', 'milestones'])
            ->whereIn('status', ['planning', 'in_progress']);

        if ($user->can('view-own-projects') && ! $user->can('view-all-projects')) {
            $projectsQuery->where(function ($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', fn ($t) => $t->where('user_id', $user->id));
            });
        }

        $projects = $projectsQuery->orderByDesc('updated_at')->paginate(12)->withQueryString();

        $stats = [
            'active_projects' => (clone $projectsQuery)->count(),
            'overdue_milestones' => ProjectMilestone::where('due_date', '<', $today)
                ->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'open_blockers' => Task::where('has_blocker', true)
                ->whereNotIn('status', ['completed', 'cancelled'])->count(),
            'reports_today' => DailyReport::whereDate('report_date', $today)->count(),
            'unreviewed_reports' => DailyReport::whereNull('reviewed_at')
                ->whereDate('report_date', '>=', $today->copy()->subDays(7))->count(),
        ];

        $blockers = Task::with(['project', 'assignedTo', 'milestone'])
            ->where('has_blocker', true)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->limit(10)
            ->get();

        $overdueMilestones = ProjectMilestone::with('project')
            ->where('due_date', '<', $today)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        return view('pmo.index', compact('projects', 'stats', 'blockers', 'overdueMilestones'));
    }

    public function seedMilestones(Project $project, PmoService $pmo)
    {
        $this->authorize('update', $project);
        $pmo->seedDefaultMilestones($project);

        return back()->with('success', 'تم إنشاء مراحل المشروع (Milestones) الافتراضية.');
    }

    public function storeMilestone(Request $request, Project $project, PmoService $pmo)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phase_type' => 'required|in:ui_ux,backend,frontend,testing,delivery,other',
            'description' => 'nullable|string|max:5000',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'assigned_lead_id' => 'nullable|exists:users,id',
        ]);

        $sort = $project->milestones()->max('sort_order') + 1;

        ProjectMilestone::create([
            ...$validated,
            'project_id' => $project->id,
            'sort_order' => $sort,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تمت إضافة المرحلة.');
    }

    public function updateMilestone(Request $request, ProjectMilestone $milestone, PmoService $pmo)
    {
        $this->authorize('update', $milestone->project);

        $validated = $request->validate([
            'status' => 'nullable|in:pending,in_progress,completed,blocked,cancelled',
            'assigned_lead_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $pmo->updateMilestone($milestone, array_filter($validated, fn ($v) => $v !== null));

        return back()->with('success', 'تم تحديث المرحلة.');
    }

    public function assignTask(Request $request, Project $project, PmoService $pmo)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'milestone_id' => 'required|exists:project_milestones,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'assigned_to' => 'required|exists:users,id',
            'specialization' => 'nullable|in:backend,frontend,ui_ux,design,qa,pm,other',
            'estimated_hours' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $milestone = ProjectMilestone::where('project_id', $project->id)
            ->findOrFail($validated['milestone_id']);

        $task = $pmo->assignTask($project, $milestone, $validated);
        $pmo->recalculateMilestoneProgress($milestone);

        return back()->with('success', 'تم توزيع المهمة: '.$task->title);
    }

    public function resolveBlocker(Task $task, PmoService $pmo)
    {
        $this->authorize('update', $task->project);
        $pmo->resolveTaskBlocker($task);

        return back()->with('success', 'تم حل العائق (Blocker).');
    }
}
