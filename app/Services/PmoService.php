<?php

namespace App\Services;

use App\Models\DailyReport;
use App\Support\ReportingHierarchy;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PmoService
{
    public function seedDefaultMilestones(Project $project): array
    {
        if ($project->milestones()->exists()) {
            return $project->milestones()->orderBy('sort_order')->get()->all();
        }

        $start = $project->start_date ?? now();
        $totalWeeks = max(4, (int) ceil(($project->start_date && $project->end_date)
            ? $project->start_date->diffInWeeks($project->end_date)
            : 8));

        $phases = [
            ['name' => 'Phase 1 — UI/UX', 'phase_type' => 'ui_ux', 'weeks' => (int) ceil($totalWeeks * 0.2)],
            ['name' => 'Phase 2 — Backend', 'phase_type' => 'backend', 'weeks' => (int) ceil($totalWeeks * 0.35)],
            ['name' => 'Phase 3 — Frontend', 'phase_type' => 'frontend', 'weeks' => (int) ceil($totalWeeks * 0.25)],
            ['name' => 'Phase 4 — Testing & Delivery', 'phase_type' => 'testing', 'weeks' => (int) ceil($totalWeeks * 0.2)],
        ];

        $cursor = Carbon::parse($start);
        $created = [];

        foreach ($phases as $i => $phase) {
            $due = $cursor->copy()->addWeeks($phase['weeks']);
            $created[] = ProjectMilestone::create([
                'project_id' => $project->id,
                'name' => $phase['name'],
                'phase_type' => $phase['phase_type'],
                'sort_order' => $i + 1,
                'start_date' => $cursor,
                'due_date' => $due,
                'status' => $i === 0 ? 'in_progress' : 'pending',
            ]);
            $cursor = $due->copy()->addDay();
        }

        if ($project->status === 'planning') {
            $project->update(['status' => 'in_progress']);
        }

        return $created;
    }

    public function assignTask(
        Project $project,
        ProjectMilestone $milestone,
        array $data
    ): Task {
        return Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'project_id' => $project->id,
            'milestone_id' => $milestone->id,
            'assigned_to' => $data['assigned_to'],
            'created_by' => auth()->id(),
            'start_date' => $data['start_date'] ?? now(),
            'due_date' => $data['due_date'] ?? null,
            'status' => 'todo',
            'priority' => $data['priority'] ?? 'medium',
            'specialization' => $data['specialization'] ?? match ($milestone->phase_type) {
                'ui_ux' => 'ui_ux',
                'backend' => 'backend',
                'frontend' => 'frontend',
                'testing' => 'qa',
                default => 'other',
            },
            'estimated_hours' => $data['estimated_hours'] ?? 0,
            'actual_hours' => 0,
        ]);
    }

    public function recalculateMilestoneProgress(ProjectMilestone $milestone): void
    {
        $tasks = $milestone->tasks();
        $total = $tasks->count();

        if ($total === 0) {
            return;
        }

        $completed = $tasks->where('status', 'completed')->count();
        $progress = (int) round(($completed / $total) * 100);
        $actualHours = (float) $tasks->sum('actual_hours');
        $estimatedHours = (float) $tasks->sum('estimated_hours');
        $hasBlocker = $tasks->where('has_blocker', true)->exists();

        $status = $milestone->status;
        if ($progress >= 100) {
            $status = 'completed';
        } elseif ($hasBlocker) {
            $status = 'blocked';
        } elseif ($progress > 0) {
            $status = 'in_progress';
        }

        $milestone->update([
            'progress_percentage' => $progress,
            'actual_hours' => $actualHours,
            'estimated_hours' => $estimatedHours > 0 ? $estimatedHours : $milestone->estimated_hours,
            'status' => $status,
            'completed_at' => $progress >= 100 ? now() : null,
        ]);

        $this->recalculateProjectProgress($milestone->project);
    }

    public function recalculateProjectProgress(Project $project): void
    {
        $milestones = $project->milestones;
        if ($milestones->isEmpty()) {
            return;
        }

        $progress = (int) round($milestones->avg('progress_percentage'));
        $project->update(['progress_percentage' => $progress]);

        if ($progress >= 100 && $project->status !== 'completed') {
            $project->update(['status' => 'completed']);
            app(ProjectFinanceService::class)->tryCreateDeliveryInvoice($project->fresh());
        }
    }

    public function submitDailyReport(array $data): DailyReport
    {
        return DB::transaction(function () use ($data) {
            $report = DailyReport::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'report_date' => $data['report_date'],
                    'project_id' => $data['project_id'] ?? null,
                ],
                [
                    'task_id' => $data['task_id'] ?? null,
                    'work_summary' => $data['work_summary'],
                    'hours_worked' => $data['hours_worked'],
                    'has_blocker' => $data['has_blocker'] ?? false,
                    'blocker_description' => $data['blocker_description'] ?? null,
                    'blocker_status' => ($data['has_blocker'] ?? false) ? 'open' : null,
                    'review_status' => ReportingHierarchy::STATUS_SUBMITTED,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'team_lead_notes' => null,
                    'dept_head_reviewed_by' => null,
                    'dept_head_reviewed_at' => null,
                    'dept_head_notes' => null,
                    'executive_acknowledged_by' => null,
                    'executive_acknowledged_at' => null,
                ]
            );

            if (! empty($data['task_id']) && ($data['has_blocker'] ?? false)) {
                Task::where('id', $data['task_id'])->update([
                    'has_blocker' => true,
                    'blocker_description' => $data['blocker_description'],
                ]);
            }

            if (! empty($data['task_id'])) {
                $task = Task::find($data['task_id']);
                if ($task && $data['hours_worked'] > 0) {
                    $task->update([
                        'actual_hours' => (float) ($task->actual_hours ?? 0) + (float) $data['hours_worked'],
                    ]);
                    if ($task->milestone_id) {
                        $this->recalculateMilestoneProgress($task->milestone);
                    }
                }
            }

            return $report;
        });
    }

    public function reviewDailyReport(DailyReport $report, ?string $notes = null, string $level = 'team_lead'): DailyReport
    {
        $userId = auth()->id();

        if ($level === 'dept_head') {
            $report->update([
                'review_status' => ReportingHierarchy::STATUS_DEPT_HEAD_REVIEWED,
                'dept_head_reviewed_by' => $userId,
                'dept_head_reviewed_at' => now(),
                'dept_head_notes' => $notes,
            ]);
        } elseif ($level === 'executive') {
            $report->update([
                'review_status' => ReportingHierarchy::STATUS_CLOSED,
                'executive_acknowledged_by' => $userId,
                'executive_acknowledged_at' => now(),
            ]);
        } else {
            $report->update([
                'review_status' => ReportingHierarchy::STATUS_TEAM_LEAD_REVIEWED,
                'reviewed_by' => $userId,
                'reviewed_at' => now(),
                'team_lead_notes' => $notes,
            ]);
        }

        return $report->fresh();
    }

    public function resolveTaskBlocker(Task $task): Task
    {
        $task->update([
            'has_blocker' => false,
            'blocker_description' => null,
        ]);

        DailyReport::where('task_id', $task->id)
            ->where('blocker_status', 'open')
            ->update(['blocker_status' => 'resolved']);

        if ($task->milestone_id) {
            $this->recalculateMilestoneProgress($task->milestone);
        }

        return $task->fresh();
    }

    public function updateMilestone(ProjectMilestone $milestone, array $data): ProjectMilestone
    {
        $milestone->update($data);
        $this->recalculateProjectProgress($milestone->project);

        return $milestone->fresh();
    }
}
