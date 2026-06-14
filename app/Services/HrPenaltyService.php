<?php

namespace App\Services;

use App\Models\HrWarning;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HrPenaltyService
{
    public function issueWarning(User $user, array $data): HrWarning
    {
        return DB::transaction(function () use ($user, $data) {
            $warning = HrWarning::create([
                'reference_code' => HrWarning::generateReferenceCode(),
                'user_id' => $user->id,
                'task_id' => $data['task_id'] ?? null,
                'type' => $data['type'] ?? 'other',
                'reason' => $data['reason'],
                'kpi_deduction_points' => $data['kpi_deduction_points'] ?? 5,
                'status' => 'active',
                'issued_by' => auth()->id(),
            ]);

            $this->checkEscalation($user);

            app(KpiCalculationService::class)->calculateForUser($user);

            return $warning;
        });
    }

    public function issueWarningForOverdueTask(Task $task): ?HrWarning
    {
        if (! $task->is_overdue || ! $task->assigned_to) {
            return null;
        }

        $exists = HrWarning::where('task_id', $task->id)
            ->where('type', 'task_delay')
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            return null;
        }

        $user = User::find($task->assigned_to);

        return $this->issueWarning($user, [
            'task_id' => $task->id,
            'type' => 'task_delay',
            'reason' => 'تأخير تسليم المهمة: '.$task->title,
            'kpi_deduction_points' => 5,
        ]);
    }

    public function scanOverdueTasks(): int
    {
        $count = 0;
        Task::with('assignedTo')
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->each(function (Task $task) use (&$count) {
                if ($this->issueWarningForOverdueTask($task)) {
                    $count++;
                }
            });

        return $count;
    }

    public function checkEscalation(User $user): void
    {
        $activeCount = HrWarning::where('user_id', $user->id)->where('status', 'active')->count();

        if ($activeCount >= 3) {
            HrWarning::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status' => 'escalated',
                    'investigation_status' => 'pending',
                ]);
        }
    }

    public function resolveWarning(HrWarning $warning, ?string $notes = null): HrWarning
    {
        $warning->update([
            'status' => 'resolved',
            'investigation_status' => $warning->investigation_status === 'pending' ? 'closed' : $warning->investigation_status,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
            'hr_notes' => $notes ?? $warning->hr_notes,
        ]);

        app(KpiCalculationService::class)->calculateForUser($warning->user);

        return $warning->fresh();
    }

    public function startInvestigation(HrWarning $warning): HrWarning
    {
        $warning->update(['investigation_status' => 'in_progress']);

        return $warning->fresh();
    }
}
