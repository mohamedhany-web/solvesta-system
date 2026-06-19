<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Project;
use App\Models\Task;
use App\Services\PmoService;
use App\Support\ProjectScope;
use App\Support\ReportingHierarchy;
use Illuminate\Http\Request;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $caps = ReportingHierarchy::reviewCapabilities($user);
        $view = $request->get('view', 'my');

        if ($view === 'team' && ! $caps['team_lead']) {
            abort(403);
        }
        if ($view === 'department' && ! $caps['dept_head']) {
            abort(403);
        }
        if ($view === 'executive' && ! $caps['executive']) {
            abort(403);
        }

        $query = DailyReport::with(['user', 'project', 'task', 'reviewer', 'deptHeadReviewer']);

        if (in_array($view, ['team', 'department', 'executive'], true)) {
            ReportingHierarchy::applyReviewerScope($query, $user, $view);
            $query->whereDate('report_date', '>=', now()->subDays(14));
        } else {
            $query->where('user_id', $user->id);
        }

        $reports = $query->orderByDesc('report_date')->paginate(20)->withQueryString();

        $stats = [
            'today' => DailyReport::where('user_id', $user->id)->whereDate('report_date', today())->exists(),
            'week_hours' => (float) DailyReport::where('user_id', $user->id)
                ->whereDate('report_date', '>=', now()->subDays(7))->sum('hours_worked'),
            'open_blockers' => DailyReport::where('user_id', $user->id)
                ->where('has_blocker', true)->where('blocker_status', 'open')->count(),
            'pending_team' => $caps['team_lead']
                ? ReportingHierarchy::applyReviewerScope(DailyReport::query(), $user, 'team')->count()
                : 0,
            'pending_dept' => $caps['dept_head']
                ? ReportingHierarchy::applyReviewerScope(DailyReport::query(), $user, 'department')->count()
                : 0,
            'pending_executive' => $caps['executive']
                ? DailyReport::where('review_status', ReportingHierarchy::STATUS_DEPT_HEAD_REVIEWED)->count()
                : 0,
        ];

        $statusLabels = ReportingHierarchy::statusLabels();

        return view('daily-reports.index', compact('reports', 'stats', 'caps', 'view', 'statusLabels'));
    }

    public function create(Request $request)
    {
        $user = $request->user();

        $projectsQuery = Project::whereIn('status', ['planning', 'in_progress']);

        if (! $user->can('view-all-projects')) {
            ProjectScope::apply($projectsQuery, $user);
        }

        $projects = $projectsQuery->orderBy('name')->get(['id', 'name']);

        $tasks = Task::where('assigned_to', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with('project')
            ->get();

        $existing = DailyReport::where('user_id', $user->id)
            ->whereDate('report_date', today())
            ->when($request->filled('project_id'), fn ($q) => $q->where('project_id', $request->get('project_id')))
            ->when(! $request->filled('project_id'), fn ($q) => $q->whereNull('project_id'))
            ->first();

        $stats = [
            'week_hours' => (float) DailyReport::where('user_id', $user->id)
                ->whereDate('report_date', '>=', now()->subDays(7))->sum('hours_worked'),
            'open_blockers' => DailyReport::where('user_id', $user->id)
                ->where('has_blocker', true)->where('blocker_status', 'open')->count(),
        ];

        return view('daily-reports.create', compact('projects', 'tasks', 'existing', 'stats'));
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
            ->with('success', 'تم تسجيل التقرير اليومي وإرساله للمراجعة.');
    }

    public function review(Request $request, DailyReport $dailyReport, PmoService $pmo)
    {
        $user = $request->user();
        $level = $request->input('level', 'team_lead');

        $validated = $request->validate([
            'level' => 'required|in:team_lead,dept_head,executive',
            'notes' => 'nullable|string|max:2000',
        ]);

        $level = $validated['level'];

        $allowed = match ($level) {
            'team_lead' => ReportingHierarchy::canReviewAsTeamLead($user, $dailyReport),
            'dept_head' => ReportingHierarchy::canReviewAsDeptHead($user, $dailyReport),
            'executive' => ReportingHierarchy::canAcknowledgeAsExecutive($user, $dailyReport),
            default => false,
        };

        if (! $allowed) {
            abort(403, 'غير مصرح لك بمراجعة هذا التقرير في هذه المرحلة.');
        }

        $pmo->reviewDailyReport($dailyReport, $validated['notes'] ?? null, $level);

        $message = match ($level) {
            'dept_head' => 'تمت مراجعة التقرير وإرساله للإدارة العليا.',
            'executive' => 'تم إغلاق التقرير.',
            default => 'تمت مراجعة التقرير وإرساله لرئيس القسم.',
        };

        return back()->with('success', $message);
    }
}
