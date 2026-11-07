<?php

namespace App\Services\CompanyOs;

use App\Models\ApprovalRequest;
use App\Models\ApprovalRule;
use App\Models\FinancialInvoice;
use App\Models\Task;
use App\Models\User;
use App\Policies\DepartmentAccess;
use App\Services\WorkspaceGovernanceService;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class ApprovalEngine
{
    public function __construct(
        private CompanyEventBus $events,
        private WorkflowEngine $workflow,
        private ExceptionService $exceptions,
        private CompanyOsNotifier $notifier,
        private WorkspaceGovernanceService $workspaceGovernance,
    ) {}

    public function ruleFor(string $entityType, float $amount): ?ApprovalRule
    {
        return ApprovalRule::query()
            ->where('entity_type', $entityType)
            ->where('is_active', true)
            ->where('min_amount', '<=', $amount)
            ->where(function ($q) use ($amount) {
                $q->whereNull('max_amount')->orWhere('max_amount', '>=', $amount);
            })
            ->orderByDesc('min_amount')
            ->first();
    }

    public function request(Model $model, ?User $user = null): ApprovalRequest
    {
        $existing = $model->approvalRequests()->where('status', 'pending')->latest()->first();
        if ($existing) {
            return $existing;
        }

        $amount = (float) ($model->total_amount ?? $model->amount ?? 0);
        $type = WorkflowEngine::entityType($model);
        $rule = $this->ruleFor($type, $amount);
        $steps = $rule?->steps ?? [['role' => 'manager', 'label' => 'مدير القسم']];

        $request = ApprovalRequest::create([
            'subject_type' => $model->getMorphClass(),
            'subject_id' => $model->getKey(),
            'rule_id' => $rule?->id,
            'amount' => $amount,
            'status' => 'pending',
            'current_step' => 0,
            'steps' => $steps,
            'requested_by' => $user?->id ?? auth()->id(),
        ]);

        $this->events->recordUser($model, 'approval.requested', [
            'approval_id' => $request->id,
            'amount' => $amount,
            'steps' => count($steps),
        ], $user);

        $ownerId = $this->workflow->resolveOwnerId($model);
        $this->exceptions->open(
            $model,
            'approval_pending',
            'موافقة معلّقة',
            'warning',
            'بانتظار اعتماد العملية قبل التنفيذ.',
            $ownerId
        );

        $this->notifyCurrentStep($request, $model);

        return $request;
    }

    public function approve(ApprovalRequest $request, User $user, ?string $notes = null): void
    {
        if (! $request->isPending()) {
            throw new RuntimeException('طلب الموافقة لم يعد معلّقًا.');
        }

        if (! $this->userCanAct($request, $user)) {
            throw new RuntimeException('ليست لديك صلاحية اعتماد هذه الخطوة.');
        }

        $steps = $request->steps ?? [];
        $steps[$request->current_step]['acted_by'] = $user->id;
        $steps[$request->current_step]['acted_at'] = now()->toIso8601String();
        $steps[$request->current_step]['decision'] = 'approved';

        $next = $request->current_step + 1;
        $subject = $request->subject;

        if ($next >= count($steps)) {
            $request->update([
                'status' => 'approved',
                'current_step' => $next,
                'steps' => $steps,
                'decided_by' => $user->id,
                'decided_at' => now(),
                'notes' => $notes,
            ]);

            $this->events->recordUser($subject, 'approved', [
                'approval_id' => $request->id,
            ], $user);

            $this->exceptions->resolve($subject, 'approval_pending');
            $this->exceptions->resolve($subject, 'task_approval_pending');

            if ($subject instanceof FinancialInvoice && $subject->lifecycleStateKey() === 'pending_approval') {
                $this->workflow->transition($subject, 'approved', $user, true);
            }

            if ($subject instanceof Task) {
                $target = $steps[0]['target_status'] ?? null;
                if ($target && $subject->status !== $target) {
                    $this->workspaceGovernance->applyApprovedStatus($subject, $target, $user);
                }
            }

            return;
        }

        $request->update([
            'current_step' => $next,
            'steps' => $steps,
            'notes' => $notes,
        ]);

        $this->notifyCurrentStep($request->fresh(), $subject);
    }

    public function reject(ApprovalRequest $request, User $user, ?string $notes = null): void
    {
        if (! $request->isPending()) {
            throw new RuntimeException('طلب الموافقة لم يعد معلّقًا.');
        }

        if (! $this->userCanAct($request, $user)) {
            throw new RuntimeException('ليست لديك صلاحية رفض هذه الخطوة.');
        }

        $request->update([
            'status' => 'rejected',
            'decided_by' => $user->id,
            'decided_at' => now(),
            'notes' => $notes,
        ]);

        $subject = $request->subject;
        $this->events->recordUser($subject, 'rejected', [
            'approval_id' => $request->id,
            'notes' => $notes,
        ], $user);
        $this->exceptions->resolve($subject, 'approval_pending');
        $this->exceptions->resolve($subject, 'task_approval_pending');
    }

    public function userCanAct(ApprovalRequest $request, User $user): bool
    {
        if ($user->hasRole(['super_admin', 'admin'])) {
            return true;
        }

        $step = $request->currentStepDef();
        $role = $step['role'] ?? null;

        return match ($role) {
            'manager' => (bool) DepartmentAccess::isDepartmentManager($user) || $user->hasRole('project_manager'),
            'accountant' => $user->hasRole('accountant') || $user->can('edit-finance'),
            'super_admin' => $user->hasRole(['super_admin', 'admin']),
            default => $user->hasRole($role),
        };
    }

    private function notifyCurrentStep(ApprovalRequest $request, Model $subject): void
    {
        $step = $request->currentStepDef();
        if (! $step) {
            return;
        }

        $role = $step['role'] ?? null;
        try {
            $users = User::role($role === 'manager' ? 'project_manager' : $role)->get();
            if ($role === 'manager') {
                $users = $users->merge(User::role(['super_admin', 'admin'])->get());
            }
        } catch (\Throwable $e) {
            $users = User::role(['super_admin', 'admin'])->get();
        }

        foreach ($users->unique('id') as $user) {
            $this->notifier->actionRequired(
                $user,
                'موافقة مطلوبة',
                ($step['label'] ?? 'اعتماد').' — بانتظار تدخلك.',
                ['approval_id' => $request->id, 'url' => $this->exceptions->subjectUrl($subject)]
            );
        }
    }
}
