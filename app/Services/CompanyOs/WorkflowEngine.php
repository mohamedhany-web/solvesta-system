<?php

namespace App\Services\CompanyOs;

use App\Models\Employee;
use App\Models\FinancialInvoice;
use App\Models\Project;
use App\Models\User;
use App\Models\WorkflowDefinition;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use App\Models\WorkItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WorkflowEngine
{
    public function __construct(
        private CompanyEventBus $events,
        private WorkItemService $workItems,
        private ExceptionService $exceptions,
    ) {}

    public static function entityType(Model $model): string
    {
        $map = config('company-os.entities', []);
        $class = $model::class;

        foreach ($map as $type => $mapped) {
            if ($mapped === $class) {
                return $type;
            }
        }

        return strtolower(class_basename($model));
    }

    public static function modelClass(string $type): string
    {
        $class = config('company-os.entities.'.$type);
        if (! $class) {
            throw new RuntimeException("Unknown company OS entity: {$type}");
        }

        return $class;
    }

    public function definitionFor(Model $model): WorkflowDefinition
    {
        $type = self::entityType($model);
        $definition = WorkflowDefinition::with(['states', 'transitions.fromState', 'transitions.toState'])
            ->where('entity_type', $type)
            ->where('is_active', true)
            ->first();

        if (! $definition) {
            throw new RuntimeException("لا توجد دورة حياة معرفة لهذا الكيان ({$type}). شغّل: php artisan db:seed --class=CompanyOsSeeder");
        }

        return $definition;
    }

    public function bootstrap(Model $model, ?User $actor = null, ?string $preferredState = null, bool $fireEnter = true): void
    {
        if ($model->lifecycle()->exists()) {
            return;
        }

        $definition = $this->definitionFor($model);
        $key = $preferredState ?: $this->mapExistingStatus($model, $definition);
        $state = $definition->stateByKey($key) ?? $definition->states->firstWhere('is_initial');

        if (! $state) {
            return;
        }

        $ownerId = $this->resolveOwnerId($model);

        $lifecycle = $model->lifecycle()->create([
            'workflow_definition_id' => $definition->id,
            'current_state_id' => $state->id,
            'owner_id' => $ownerId,
            'entered_at' => now(),
            'sla_due_at' => $state->sla_hours ? now()->addHours($state->sla_hours) : null,
        ]);

        $this->syncModelStatus($model, $state);

        $this->events->record($model, 'lifecycle.bootstrapped', [
            'state' => $state->key,
            'lifecycle_id' => $lifecycle->id,
        ], $actor ? 'user' : 'system', $actor?->id);

        if ($fireEnter) {
            $this->workItems->onEnter($model, $state, $ownerId);
        }
    }

    public function ensureLifecycle(Model $model): void
    {
        $this->bootstrap($model);
        $model->load('lifecycle.currentState', 'lifecycle.definition.states', 'lifecycle.owner');
    }

    public function availableTransitions(Model $model, ?User $user = null): array
    {
        $this->ensureLifecycle($model);
        $lifecycle = $model->lifecycle;
        if (! $lifecycle?->currentState) {
            return [];
        }

        $user = $user ?? auth()->user();
        $out = [];

        foreach ($lifecycle->currentState->outgoing()->with('toState')->get() as $transition) {
            $blocked = $this->transitionBlockReason($model, $transition, $user, false);
            $out[] = [
                'transition' => $transition,
                'to' => $transition->toState,
                'blocked' => $blocked,
            ];
        }

        return $out;
    }

    public function transition(Model $model, string $toStateKey, ?User $actor = null, bool $asSystem = false): void
    {
        $this->ensureLifecycle($model);
        $lifecycle = $model->lifecycle->load('currentState', 'definition.states');
        $from = $lifecycle->currentState;
        $to = $lifecycle->definition->stateByKey($toStateKey);

        if (! $to) {
            throw new RuntimeException("الحالة «{$toStateKey}» غير معرفة.");
        }

        if ($from->key === $to->key) {
            return;
        }

        $transition = WorkflowTransition::query()
            ->where('from_state_id', $from->id)
            ->where('to_state_id', $to->id)
            ->first();

        if (! $transition) {
            throw new RuntimeException("لا يمكن الانتقال من «{$from->label_ar}» إلى «{$to->label_ar}».");
        }

        $reason = $this->transitionBlockReason($model, $transition, $actor, $asSystem);
        if ($reason) {
            throw new RuntimeException($reason);
        }

        DB::transaction(function () use ($model, $lifecycle, $from, $to, $actor, $asSystem) {
            $ownerId = $this->resolveOwnerId($model) ?: $lifecycle->owner_id;

            $lifecycle->update([
                'current_state_id' => $to->id,
                'owner_id' => $ownerId,
                'entered_at' => now(),
                'sla_due_at' => $to->sla_hours ? now()->addHours($to->sla_hours) : null,
                'sla_breached_at' => null,
            ]);

            $this->syncModelStatus($model, $to);

            $this->events->recordUser($model, 'status.changed', [
                'from' => $from->key,
                'to' => $to->key,
                'from_label' => $from->label_ar,
                'to_label' => $to->label_ar,
            ], $asSystem ? null : $actor);

            $this->exceptions->resolve($model, 'sla_breached');
            if ($to->is_terminal) {
                $this->exceptions->resolveAllFor($model);
            }

            $this->workItems->onEnter($model, $to, $ownerId);
        });
    }

    public function forceState(Model $model, string $toStateKey, ?User $actor = null): void
    {
        $this->ensureLifecycle($model);
        $lifecycle = $model->lifecycle->load('currentState', 'definition.states');
        $from = $lifecycle->currentState;
        $to = $lifecycle->definition->stateByKey($toStateKey);

        if (! $to || $from->key === $to->key) {
            $this->syncModelStatus($model, $to ?? $from);

            return;
        }

        try {
            $this->transition($model, $toStateKey, $actor, true);
        } catch (RuntimeException $e) {
            $ownerId = $this->resolveOwnerId($model) ?: $lifecycle->owner_id;
            $lifecycle->update([
                'current_state_id' => $to->id,
                'owner_id' => $ownerId,
                'entered_at' => now(),
                'sla_due_at' => $to->sla_hours ? now()->addHours($to->sla_hours) : null,
                'sla_breached_at' => null,
            ]);
            $this->syncModelStatus($model, $to);
            $this->events->recordUser($model, 'status.changed', [
                'from' => $from->key,
                'to' => $to->key,
                'forced' => true,
            ], $actor);
            if ($to->is_terminal) {
                $this->exceptions->resolveAllFor($model);
            }
        }
    }

    public function transitionBlockReason(Model $model, WorkflowTransition $transition, ?User $user, bool $asSystem = false): ?string
    {
        $transition->loadMissing('toState', 'fromState');

        if (! $asSystem && $transition->required_permission && $user && ! $user->can($transition->required_permission)) {
            return 'ليست لديك صلاحية هذا الانتقال.';
        }

        if ($transition->guard_key) {
            $guardReason = GuardRegistry::check($transition->guard_key, $model);
            if ($guardReason) {
                return $guardReason;
            }
        }

        $blockedItems = WorkItem::query()
            ->where('subject_type', $model->getMorphClass())
            ->where('subject_id', $model->getKey())
            ->where('is_required', true)
            ->where('status', 'open')
            ->where('blocks_state', $transition->toState->key)
            ->count();

        if ($blockedItems > 0) {
            return "هناك {$blockedItems} بند عمل مطلوب قبل الانتقال إلى «{$transition->toState->label_ar}».";
        }

        if (! $asSystem && $transition->fromState?->key === 'pending_approval' && $transition->toState?->key === 'approved') {
            $approved = $model->morphMany(\App\Models\ApprovalRequest::class, 'subject')
                ->where('status', 'approved')
                ->exists();
            if (! $approved) {
                return 'لا يمكن الاعتماد قبل اكتمال سلسلة الموافقات.';
            }
        }

        return null;
    }

    public function resolveOwnerId(Model $model): ?int
    {
        return match (true) {
            $model instanceof Project => $model->project_manager_id,
            $model instanceof Employee => $model->user_id,
            $model instanceof FinancialInvoice => $model->created_by,
            default => null,
        };
    }

    private function mapExistingStatus(Model $model, WorkflowDefinition $definition): string
    {
        $type = self::entityType($model);
        $map = config("company-os.workflows.{$type}.status_map", []);
        $status = (string) ($model->status ?? '');

        if ($status !== '' && isset($map[$status])) {
            return $map[$status];
        }

        return $definition->states->firstWhere('is_initial')?->key ?? 'planning';
    }

    private function syncModelStatus(Model $model, WorkflowState $state): void
    {
        if (! $state->maps_to_status || ! $model->isFillable('status')) {
            return;
        }

        if ((string) $model->status === $state->maps_to_status) {
            return;
        }

        $model->forceFill(['status' => $state->maps_to_status])->saveQuietly();
    }
}
