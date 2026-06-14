<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Project;
use App\Models\Task;
use App\Services\PmoService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $canReview = $user->can('edit-projects') || $user->hasRole(['super_admin', 'admin', 'project_manager']);

        if ($canReview && $request->get('view') === 'team') {
            $reports = DailyReport::with(['user', 'project', 'task', 'reviewer'])
                ->whereDate('report_date', '>=', now()->subDays(14))
                ->orderByDesc('report_date')
                ->paginate(20);
        } else {
            $reports = DailyReport::with(['project', 'task'])
                ->where('user_id', $user->id)
                ->orderByDesc('report_date')
                ->paginate(20);
        }

        $stats = [
            'today' => DailyReport::where('user_id', $user->id)->whereDate('report_date', today())->exists(),
            'week_hours' => (float) DailyReport::where('user_id', $user->id)
                ->whereDate('report_date', '>=', now()->subDays(7))->sum('hours_worked'),
            'open_blockers' => DailyReport::where('user_id', $user->id)
                ->where('has_blocker', true)->where('blocker_status', 'open')->count(),
        ];

        return view('daily-reports.index', compact('reports', 'stats', 'canReview'));
    }

    public function create(Request $request)
    {
        $user = $request->user();

        $projectsQuery = Project::whereIn('status', ['planning', 'in_progress']);

        if (! $user->can('view-all-projects')) {
            $projectsQuery->where(function ($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', fn ($t) => $t->where('user_id', $user->id));
            });
        }

        $projects = $projectsQuery->orderBy('name')->get(['id', 'name']);

        $tasks = Task::where('assigned_to', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with('project')
            ->get();

        $existing = DailyReport::where('user_id', $user->id)
            ->whereDate('report_date', today())
            ->where('project_id', $request->get('project_id'))
            ->first();

        return view('daily-reports.create', compact('projects', 'tasks', 'existing'));
    }

    public function store(Request $request, PmoService $pmo)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'report_date' => 'required|date|before_or_equal:today',
            'work_summary' => 'required|string|max:10000',
            'hours_worked' => 'required|numeric|min:0.25|max:24',
            'has_blocker' => 'nullable|boolean',
            'blocker_description' => 'required_if:has_blocker,1|nullable|string|max:2000',
        ]);

        $validated['has_blocker'] = $request->boolean('has_blocker');

        $pmo->submitDailyReport($validated);

        return redirect()->route('daily-reports.index')
            ->with('success', 'تم تسجيل التقرير اليومي.');
    }

    public function review(Request $request, DailyReport $dailyReport, PmoService $pmo)
    {
        if (! auth()->user()->can('edit-projects') && ! auth()->user()->hasRole(['super_admin', 'admin', 'project_manager'])) {
            abort(403);
        }

        $validated = $request->validate([
            'team_lead_notes' => 'nullable|string|max:2000',
        ]);

        $pmo->reviewDailyReport($dailyReport, $validated['team_lead_notes'] ?? null);

        return back()->with('success', 'تمت مراجعة التقرير.');
    }
}
