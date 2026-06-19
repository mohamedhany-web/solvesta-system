<?php

namespace App\Support;

use App\Models\DailyReport;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\User;
use App\Policies\DepartmentAccess;
use Illuminate\Database\Eloquent\Builder;

class ReportingHierarchy
{
    public const STATUS_SUBMITTED = 'submitted';

    public const STATUS_TEAM_LEAD_REVIEWED = 'team_lead_reviewed';

    public const STATUS_DEPT_HEAD_REVIEWED = 'dept_head_reviewed';

    public const STATUS_CLOSED = 'closed';

    public static function statusLabels(): array
    {
        return [
            self::STATUS_SUBMITTED => 'بانتظار Team Lead',
            self::STATUS_TEAM_LEAD_REVIEWED => 'بانتظار رئيس القسم',
            self::STATUS_DEPT_HEAD_REVIEWED => 'بانتظار الإدارة العليا',
            self::STATUS_CLOSED => 'مكتمل',
        ];
    }

    public static function isTeamLead(User $user): bool
    {
        if ($user->employee?->is_team_lead) {
            return true;
        }

        return Project::query()
            ->where('project_manager_id', $user->id)
            ->whereIn('status', ['planning', 'in_progress', 'on_hold'])
            ->exists();
    }

    public static function isDeptHead(User $user): bool
    {
        return DepartmentAccess::isDepartmentManager($user);
    }

    public static function isExecutive(User $user): bool
    {
        return $user->hasRole(['super_admin', 'admin']) || $user->can('view-reports');
    }

    public static function reviewCapabilities(User $user): array
    {
        return [
            'team_lead' => self::isTeamLead($user) || ($user->can('edit-projects') && ! self::isDeptHead($user)),
            'dept_head' => self::isDeptHead($user),
            'executive' => self::isExecutive($user),
        ];
    }

    public static function directReportUserIds(User $supervisor): array
    {
        $fromEmployees = Employee::query()
            ->where('supervisor_user_id', $supervisor->id)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->all();

        $fromProjects = Project::query()
            ->where('project_manager_id', $supervisor->id)
            ->with('teamMembers:id')
            ->get()
            ->flatMap(fn (Project $p) => $p->teamMembers->pluck('id'))
            ->all();

        return array_values(array_unique(array_merge($fromEmployees, $fromProjects)));
    }

    public static function departmentUserIds(?int $deptId): array
    {
        if (! $deptId) {
            return [];
        }

        return Employee::query()
            ->where('department_id', $deptId)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->all();
    }

    public static function employeeHasTeamLead(Employee $employee): bool
    {
        if ($employee->supervisor_user_id) {
            $supervisor = Employee::where('user_id', $employee->supervisor_user_id)->first();

            return $supervisor?->is_team_lead
                || Project::where('project_manager_id', $employee->supervisor_user_id)->exists();
        }

        return false;
    }

    public static function canReviewAsTeamLead(User $user, DailyReport $report): bool
    {
        if ($report->user_id === $user->id) {
            return false;
        }

        if (! self::isTeamLead($user) && ! $user->can('edit-projects')) {
            return false;
        }

        if ($report->review_status !== self::STATUS_SUBMITTED) {
            return false;
        }

        if (in_array($report->user_id, self::directReportUserIds($user), true)) {
            return true;
        }

        return $report->project_id
            && Project::where('id', $report->project_id)->where('project_manager_id', $user->id)->exists();
    }

    public static function canReviewAsDeptHead(User $user, DailyReport $report): bool
    {
        $deptId = DepartmentAccess::managedDepartmentId($user);
        if (! $deptId) {
            return false;
        }

        $employee = $report->user?->employee;
        if (! $employee || (int) $employee->department_id !== (int) $deptId) {
            return false;
        }

        if ($report->review_status === self::STATUS_TEAM_LEAD_REVIEWED) {
            return true;
        }

        if ($report->review_status === self::STATUS_SUBMITTED && ! self::employeeHasTeamLead($employee)) {
            return true;
        }

        return false;
    }

    public static function canAcknowledgeAsExecutive(User $user, DailyReport $report): bool
    {
        return self::isExecutive($user) && $report->review_status === self::STATUS_DEPT_HEAD_REVIEWED;
    }

    public static function applyReviewerScope(Builder $query, User $user, string $view): Builder
    {
        return match ($view) {
            'team' => $query
                ->where('user_id', '!=', $user->id)
                ->where(function (Builder $q) use ($user) {
                    $ids = self::directReportUserIds($user);
                    $q->whereIn('user_id', $ids)
                        ->orWhereHas('project', fn (Builder $p) => $p->where('project_manager_id', $user->id));
                })
                ->where('review_status', self::STATUS_SUBMITTED),
            'department' => $query
                ->whereHas('user.employee', fn (Builder $e) => $e->where('department_id', DepartmentAccess::managedDepartmentId($user)))
                ->whereIn('review_status', [self::STATUS_SUBMITTED, self::STATUS_TEAM_LEAD_REVIEWED]),
            'executive' => $query->where('review_status', self::STATUS_DEPT_HEAD_REVIEWED),
            default => $query->where('user_id', $user->id),
        };
    }

    public static function syncProjectTeamHierarchy(Project $project): void
    {
        if (! $project->project_manager_id || ! $project->department_id) {
            return;
        }

        $pmUserId = (int) $project->project_manager_id;

        Employee::query()
            ->where('user_id', $pmUserId)
            ->update(['is_team_lead' => true]);

        $teamUserIds = $project->teamMembers()->pluck('users.id');

        foreach ($teamUserIds as $memberUserId) {
            if ((int) $memberUserId === $pmUserId) {
                continue;
            }

            Employee::query()
                ->where('user_id', $memberUserId)
                ->where('department_id', $project->department_id)
                ->update(['supervisor_user_id' => $pmUserId]);
        }
    }

    public static function deptHeadUserId(?int $departmentId): ?int
    {
        if (! $departmentId) {
            return null;
        }

        return Department::find($departmentId)?->manager?->user_id;
    }
}
