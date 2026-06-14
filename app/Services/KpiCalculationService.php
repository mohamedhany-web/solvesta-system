<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeKpiPeriod;
use App\Models\HrWarning;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class KpiCalculationService
{
    public function roleTemplate(User $user): string
    {
        if ($user->hasRole('developer')) {
            return 'developer';
        }
        if ($user->hasRole('designer')) {
            return 'designer';
        }
        if ($user->hasRole('sales_rep')) {
            return 'sales';
        }

        return 'default';
    }

    public function roleWeights(string $template): array
    {
        return match ($template) {
            'developer' => ['adherence' => 20, 'task_completion' => 40, 'quality' => 20, 'team_lead' => 20],
            'designer' => ['adherence' => 20, 'task_completion' => 35, 'quality' => 25, 'team_lead' => 20],
            'sales' => ['adherence' => 15, 'task_completion' => 45, 'quality' => 15, 'team_lead' => 25],
            default => ['adherence' => 25, 'task_completion' => 35, 'quality' => 20, 'team_lead' => 20],
        };
    }

    public function calculateForUser(User $user, ?int $year = null, ?int $month = null, ?float $teamLeadRating = null): EmployeeKpiPeriod
    {
        $year = $year ?? (int) now()->year;
        $month = $month ?? (int) now()->month;
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $template = $this->roleTemplate($user);
        $weights = $this->roleWeights($template);

        $adherence = $this->calculateAdherence($user, $start, $end);
        $taskCompletion = $this->calculateTaskCompletion($user, $start, $end);
        $existing = EmployeeKpiPeriod::where('user_id', $user->id)
            ->where('period_year', $year)->where('period_month', $month)->first();

        $teamLead = $teamLeadRating ?? $existing?->team_lead_rating ?? 0;
        $quality = $teamLead > 0 ? $teamLead : ($existing?->quality_score ?? 75);

        $rawTotal = (
            ($adherence * $weights['adherence'] / 100)
            + ($taskCompletion * $weights['task_completion'] / 100)
            + ($quality * $weights['quality'] / 100)
            + ($teamLead * $weights['team_lead'] / 100)
        );

        $deductions = (float) HrWarning::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereBetween('created_at', [$start, $end])
            ->sum('kpi_deduction_points');

        $total = max(0, round($rawTotal - $deductions, 2));

        return EmployeeKpiPeriod::updateOrCreate(
            ['user_id' => $user->id, 'period_year' => $year, 'period_month' => $month],
            [
                'role_template' => $template,
                'adherence_score' => $adherence,
                'task_completion_score' => $taskCompletion,
                'quality_score' => $quality,
                'team_lead_rating' => $teamLead ?: null,
                'total_score' => $total,
                'kpi_deductions' => $deductions,
                'rated_by' => $teamLeadRating ? auth()->id() : $existing?->rated_by,
                'calculated_at' => now(),
            ]
        );
    }

    public function calculateTeam(?int $year = null, ?int $month = null): int
    {
        $count = 0;
        $users = User::whereHas('employee')->get();
        foreach ($users as $user) {
            $this->calculateForUser($user, $year, $month);
            $count++;
        }

        return $count;
    }

    protected function calculateAdherence(User $user, Carbon $start, Carbon $end): float
    {
        $employee = Employee::where('user_id', $user->id)->first();
        if (! $employee) {
            return 0;
        }

        $records = Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])->get();

        if ($records->isEmpty()) {
            return 0;
        }

        $present = $records->where('status', 'present')->count();

        return round(($present / $records->count()) * 100, 2);
    }

    protected function calculateTaskCompletion(User $user, Carbon $start, Carbon $end): float
    {
        $tasks = Task::where('assigned_to', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        if ($tasks->isEmpty()) {
            return 100;
        }

        $completed = $tasks->where('status', 'completed')->count();
        $overdue = $tasks->filter(fn ($t) => $t->is_overdue)->count();
        $base = ($completed / $tasks->count()) * 100;
        $penalty = min(30, $overdue * 5);

        return max(0, round($base - $penalty, 2));
    }
}
