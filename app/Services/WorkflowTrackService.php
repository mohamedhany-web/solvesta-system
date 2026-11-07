<?php

namespace App\Services;

use App\Models\DailyReport;
use App\Models\Employee;
use App\Models\PullRequest;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\User;
use Illuminate\Support\Collection;

class WorkflowTrackService
{
    public function tracks(): array
    {
        return config('workflow_tracks.tracks', []);
    }

    public function specialties(): array
    {
        return config('workflow_tracks.specialties', []);
    }

    public function specialtyOptions(): array
    {
        $out = [];
        foreach ($this->specialties() as $key => $meta) {
            $out[$key] = $meta['label_ar'] ?? $meta['label'] ?? $key;
        }

        return $out;
    }

    /** Specialty keys that belong to a given track (e.g. design). */
    public function specialtyKeysForTrack(string $track): array
    {
        $keys = [];
        foreach ($this->specialties() as $key => $meta) {
            if (($meta['track'] ?? null) === $track) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    public function specialtyLabel(?string $key, bool $arabic = true): string
    {
        if (! $key) {
            return '—';
        }
        $meta = $this->specialties()[$key] ?? null;
        if (! $meta) {
            return (string) $key;
        }

        return $arabic
            ? ($meta['label_ar'] ?? $meta['label'] ?? $key)
            : ($meta['label'] ?? $meta['label_ar'] ?? $key);
    }

    public function track(string $key): array
    {
        $tracks = $this->tracks();

        return $tracks[$key] ?? $tracks[config('workflow_tracks.default', 'general')] ?? [];
    }

    public function resolve(?User $user = null, ?Task $task = null, ?string $forced = null): string
    {
        if ($forced && isset($this->tracks()[$forced])) {
            return $forced;
        }

        if ($task) {
            $specialization = strtolower((string) ($task->specialization ?? ''));
            $specialties = $this->specialties();
            if ($specialization !== '' && isset($specialties[$specialization]['track'])) {
                return $specialties[$specialization]['track'];
            }

            $projectType = strtolower((string) optional($task->project)->project_type);
            foreach ($this->tracks() as $key => $track) {
                if (in_array($projectType, $track['project_types'] ?? [], true)) {
                    return $key;
                }
            }
        }

        $user = $user ?? auth()->user();
        if (! $user) {
            return config('workflow_tracks.default', 'general');
        }

        // Prefer explicit employee career track / department over generic roles like admin.
        $employee = $user->employee;
        if ($employee) {
            $deptCode = strtoupper((string) optional($employee->department)->code);
            foreach ($this->tracks() as $key => $track) {
                if (in_array($deptCode, $track['department_codes'] ?? [], true)) {
                    return $key;
                }
            }

            $career = strtolower((string) (optional($employee->department)->career_track
                ?? $employee->career_track
                ?? ''));
            foreach ($this->tracks() as $key => $track) {
                if ($career !== '' && in_array($career, $track['career_tracks'] ?? [], true)) {
                    return $key;
                }
            }
        }

        foreach ($this->tracks() as $key => $track) {
            $roles = $track['roles'] ?? [];
            if ($roles && $user->hasAnyRole($roles) && ! in_array($key, ['general'], true)) {
                // Skip broad general roles when a more specific role matches.
                if ($key === 'development' && $user->hasRole('developer')) {
                    return $key;
                }
                if ($key === 'design' && $user->hasRole('designer')) {
                    return $key;
                }
                if ($key === 'sales' && $user->hasRole('sales_rep')) {
                    return $key;
                }
                if ($key === 'qa' && $user->hasAnyRole(['qa', 'support'])) {
                    return $key;
                }
            }
        }

        return config('workflow_tracks.default', 'general');
    }

    public function statusLabel(string $status, string $track, bool $arabic = true): string
    {
        $normalized = Task::normalizeStatus($status);
        $labels = $this->track($track)['status_labels'][$normalized] ?? null;
        if (! $labels) {
            return (string) $status;
        }

        return $arabic ? ($labels[1] ?? $labels[0]) : ($labels[0] ?? $labels[1]);
    }

    public function statLabel(string $key, string $track): array
    {
        return $this->track($track)['stat_labels'][$key]
            ?? [$key, $key];
    }

    public function hasWidget(string $track, string $widget): bool
    {
        return in_array($widget, $this->track($track)['widgets'] ?? [], true);
    }

    /**
     * Validate delivery proof before moving to a gated status.
     *
     * @return string|null error message
     */
    public function validateDelivery(Task $task, string $newStatus, ?User $user = null): ?string
    {
        $trackKey = $this->resolve($user, $task);
        $rules = $this->track($trackKey)['delivery_rules'][$newStatus] ?? [];
        if ($rules === [] || $rules === null) {
            return null;
        }

        if (! empty($rules['file_upload'])) {
            $hasFile = $this->taskHasFileDelivery($task);
            if (! $hasFile) {
                return 'لا يمكن نقل المهمة إلى «'.$this->statusLabel($newStatus, $trackKey).'» بدون رفع تسليم (ملف).';
            }
        }

        if (! empty($rules['pull_request'])) {
            $hasPr = $task->pullRequests()
                ->whereIn('status', ['open', 'changes_requested', 'approved', 'merged'])
                ->exists();
            if (! $hasPr) {
                return 'لا يمكن إكمال المهمة بدون Pull Request مرتبط.';
            }
        }

        return null;
    }

    public function taskHasFileDelivery(Task $task): bool
    {
        if (is_array($task->attachments) && count($task->attachments) > 0) {
            return true;
        }

        return TaskUpdate::query()
            ->where('task_id', $task->id)
            ->where(function ($q) {
                $q->where('type', 'file_upload')
                    ->orWhereNotNull('attachments');
            })
            ->exists();
    }

    /**
     * Team capacity rows for managers / team view.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function teamCapacity(?int $departmentId = null, ?int $projectId = null): Collection
    {
        $open = Task::openStatuses();

        $employees = Employee::query()
            ->with(['user', 'department'])
            ->where('status', 'active')
            ->when($departmentId, fn ($q) => $q->where('department_id', $departmentId))
            ->whereNotNull('user_id')
            ->orderBy('full_name_ar')
            ->orderBy('id')
            ->get();

        return $employees->map(function (Employee $employee) use ($open, $projectId) {
            $userId = (int) $employee->user_id;
            $base = Task::query()
                ->where('assigned_to', $userId)
                ->where('status', '!=', 'cancelled')
                ->when($projectId, fn ($q) => $q->where('project_id', $projectId));

            $openCount = (clone $base)->whereIn('status', $open)->count();
            $inProgress = (clone $base)->where('status', 'in_progress')->count();
            $review = (clone $base)->whereIn('status', ['code_review', 'qa_testing', 'client_review'])->count();
            $overdue = (clone $base)->overdue()->count();
            $hours = (float) (clone $base)->whereIn('status', $open)->sum('estimated_hours');
            $weekHours = (float) DailyReport::where('user_id', $userId)
                ->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('hours_worked');

            $load = match (true) {
                $openCount >= 8 || $overdue >= 3 => 'high',
                $openCount >= 4 || $overdue >= 1 => 'medium',
                default => 'low',
            };

            return [
                'employee_id' => $employee->id,
                'user_id' => $userId,
                'name' => $employee->user?->name ?? $employee->full_name_ar ?? '—',
                'department' => $employee->department?->name,
                'open' => $openCount,
                'in_progress' => $inProgress,
                'review' => $review,
                'overdue' => $overdue,
                'estimated_hours' => $hours,
                'week_hours' => $weekHours,
                'load' => $load,
            ];
        })->values();
    }

    public function recentDeliveriesForUser(User $user, bool $teamView = false, int $limit = 8): Collection
    {
        return TaskUpdate::with(['user', 'task.project'])
            ->where('type', 'file_upload')
            ->when(! $teamView, function ($q) use ($user) {
                $q->where(function ($inner) use ($user) {
                    $inner->where('user_id', $user->id)
                        ->orWhereHas('task', fn ($t) => $t->where('assigned_to', $user->id));
                });
            })
            ->latest()
            ->take($limit)
            ->get();
    }

    public function awaitingReview(User $user, bool $teamView = false, int $limit = 6): Collection
    {
        return Task::with(['project', 'assignedTo'])
            ->whereIn('status', ['code_review', 'qa_testing', 'client_review'])
            ->when(! $teamView, fn ($q) => $q->where('assigned_to', $user->id))
            ->orderByDesc('updated_at')
            ->take($limit)
            ->get();
    }
}
