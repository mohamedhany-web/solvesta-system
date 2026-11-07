<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\User;
use App\Services\WorkflowTrackService;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    public function index(Request $request, WorkflowTrackService $tracks)
    {
        $user = auth()->user();
        $designSpecialties = $tracks->specialtyKeysForTrack('design');
        // Legacy aliases still seen in older tasks
        $designSpecialties = array_values(array_unique(array_merge($designSpecialties, ['ui', 'ux'])));

        $designProjectQuery = Project::query()->where(function ($query) {
            $query->where('project_type', 'design')
                ->orWhere('name', 'like', '%تصميم%')
                ->orWhere('description', 'like', '%تصميم%');
        });

        if (! $user->can('view-all-projects') && ! $user->can('manage-design')) {
            $designProjectQuery->where(function ($q) use ($user) {
                $q->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', fn ($tm) => $tm->where('user_id', $user->id));
            });
        }

        $projects = (clone $designProjectQuery)
            ->with(['client', 'projectManager', 'teamMembers', 'tasks'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total' => (clone $designProjectQuery)->count(),
            'active' => (clone $designProjectQuery)->whereIn('status', ['planning', 'in_progress'])->count(),
            'completed' => (clone $designProjectQuery)->where('status', 'completed')->count(),
            'on_hold' => (clone $designProjectQuery)->where('status', 'on_hold')->count(),
        ];

        $designers = User::role('designer')->with('employee')->orderBy('name')->get();

        $myTasksQuery = Task::with(['project', 'assignedTo'])
            ->where(function ($q) use ($designSpecialties) {
                $q->whereIn('specialization', $designSpecialties)
                    ->orWhereHas('project', function ($pq) {
                        $pq->where('project_type', 'design')
                            ->orWhere('name', 'like', '%تصميم%');
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->when(
                ! $user->can('view-all-tasks') && ! $user->can('manage-design'),
                fn ($q) => $q->where('assigned_to', $user->id)
            )
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')")
            ->orderBy('due_date');

        $myTasks = (clone $myTasksQuery)->take(8)->get();
        $awaitingDelivery = (clone $myTasksQuery)
            ->whereIn('status', ['code_review', 'qa_testing', 'client_review'])
            ->take(6)
            ->get();

        $recentDeliveries = TaskUpdate::with(['user', 'task.project'])
            ->where('type', 'file_upload')
            ->whereHas('task', function ($q) use ($user, $designSpecialties) {
                $q->where(function ($inner) use ($designSpecialties) {
                    $inner->whereIn('specialization', $designSpecialties)
                        ->orWhereHas('project', fn ($pq) => $pq->where('project_type', 'design'));
                })->when(
                    ! $user->can('view-all-tasks') && ! $user->can('manage-design'),
                    fn ($tq) => $tq->where('assigned_to', $user->id)
                );
            })
            ->latest()
            ->take(8)
            ->get();

        $workflowTrack = 'design';

        return view('design.index', compact(
            'projects',
            'stats',
            'designers',
            'myTasks',
            'awaitingDelivery',
            'recentDeliveries',
            'workflowTrack'
        ));
    }
}
