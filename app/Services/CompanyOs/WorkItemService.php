<?php

namespace App\Services\CompanyOs;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkItem;
use App\Models\WorkflowState;
use Illuminate\Database\Eloquent\Model;

class WorkItemService
{
    public function __construct(private CompanyEventBus $events) {}

    public function onEnter(Model $model, WorkflowState $state, ?int $ownerId): void
    {
        $assigneeId = $ownerId ?: $this->fallbackAssigneeId();

        match ($state->key) {
            'development' => $this->openBundle($model, 'development', $assigneeId, $ownerId, 'qa', [
                ['title' => 'تجهيز بيئة التطوير والمستودع', 'hours' => 24],
                ['title' => 'تنفيذ نطاق المرحلة الحالية', 'hours' => 120],
            ]),
            'qa' => $this->openBundle($model, 'qa', $assigneeId, $ownerId, 'delivery', [
                ['title' => 'تنفيذ قائمة فحص QA', 'hours' => 24],
                ['title' => 'إغلاق الأخطاء الحرجة', 'hours' => 48],
            ]),
            'onboarding' => $this->openBundle($model, 'onboarding', $assigneeId, $ownerId, 'active', [
                ['title' => 'استكمال ملف الموظف وبيانات البطاقة', 'hours' => 48],
                ['title' => 'تفعيل صلاحيات القسم وحساب الدخول', 'hours' => 72],
            ]),
            default => null,
        };
    }

    public function complete(WorkItem $item, ?User $user = null): void
    {
        if ($item->isDone()) {
            return;
        }

        $item->update(['status' => 'done']);

        if ($item->related_type === Task::class && $item->related_id) {
            Task::where('id', $item->related_id)->whereNotIn('status', ['done', 'completed', 'cancelled'])
                ->update(['status' => 'completed', 'progress_percentage' => 100]);
        }

        $subject = $item->subject;
        if ($subject) {
            $this->events->recordUser($subject, 'work_item.completed', [
                'work_item_id' => $item->id,
                'title' => $item->title,
            ], $user);
        }
    }

    private function openBundle(Model $model, string $bundle, ?int $assigneeId, ?int $ownerId, string $blocksState, array $items): void
    {
        $exists = WorkItem::query()
            ->where('subject_type', $model->getMorphClass())
            ->where('subject_id', $model->getKey())
            ->where('metadata->bundle', $bundle)
            ->exists();

        if ($exists) {
            return;
        }

        foreach ($items as $item) {
            $due = now()->addHours($item['hours']);
            $workItem = WorkItem::create([
                'type' => 'checklist',
                'title' => $item['title'],
                'subject_type' => $model->getMorphClass(),
                'subject_id' => $model->getKey(),
                'owner_id' => $ownerId,
                'assignee_id' => $assigneeId,
                'priority' => 'high',
                'status' => 'open',
                'blocks_state' => $blocksState,
                'is_required' => true,
                'due_at' => $due,
                'sla_due_at' => $due,
                'metadata' => ['bundle' => $bundle],
            ]);

            if ($model instanceof Project && $assigneeId) {
                $task = Task::create([
                    'title' => $item['title'],
                    'description' => 'بند إلزامي من دورة حياة المشروع — '.$bundle,
                    'project_id' => $model->id,
                    'assigned_to' => $assigneeId,
                    'created_by' => $assigneeId,
                    'due_date' => $due->toDateString(),
                    'status' => 'todo',
                    'priority' => 'high',
                ]);
                $workItem->update([
                    'related_type' => Task::class,
                    'related_id' => $task->id,
                ]);
            }

            $this->events->record($model, 'work_item.created', [
                'work_item_id' => $workItem->id,
                'title' => $workItem->title,
                'bundle' => $bundle,
            ]);
        }
    }

    private function fallbackAssigneeId(): ?int
    {
        return auth()->id() ?: User::query()->orderBy('id')->value('id');
    }
}
