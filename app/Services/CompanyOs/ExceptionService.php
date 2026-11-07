<?php

namespace App\Services\CompanyOs;

use App\Models\CompanyException;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ExceptionService
{
    public function __construct(
        private CompanyEventBus $events,
        private CompanyOsNotifier $notifier,
    ) {}

    public function open(
        Model $subject,
        string $code,
        string $title,
        string $severity = 'warning',
        ?string $message = null,
        ?int $ownerId = null
    ): CompanyException {
        $existing = CompanyException::query()
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey())
            ->where('code', $code)
            ->where('is_open', true)
            ->first();

        if ($existing) {
            $existing->update([
                'title' => $title,
                'message' => $message ?? $existing->message,
                'severity' => $severity,
                'owner_id' => $ownerId ?? $existing->owner_id,
            ]);

            return $existing;
        }

        $exception = CompanyException::create([
            'severity' => $severity,
            'code' => $code,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'owner_id' => $ownerId,
            'title' => $title,
            'message' => $message,
            'is_open' => true,
        ]);

        $this->events->record($subject, 'exception.opened', [
            'code' => $code,
            'title' => $title,
            'severity' => $severity,
        ]);

        if ($ownerId && $owner = User::find($ownerId)) {
            if ($severity === 'critical') {
                $this->notifier->critical($owner, $title, $message ?: $title, [
                    'exception_id' => $exception->id,
                    'url' => $this->subjectUrl($subject),
                ]);
            } else {
                $this->notifier->actionRequired($owner, $title, $message ?: $title, [
                    'exception_id' => $exception->id,
                    'url' => $this->subjectUrl($subject),
                ]);
            }
        }

        return $exception;
    }

    public function resolve(Model $subject, string $code): void
    {
        $open = CompanyException::query()
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey())
            ->where('code', $code)
            ->where('is_open', true)
            ->get();

        foreach ($open as $exception) {
            $exception->update([
                'is_open' => false,
                'resolved_at' => now(),
            ]);
        }

        if ($open->isNotEmpty()) {
            $this->events->record($subject, 'exception.resolved', ['code' => $code]);
        }
    }

    public function resolveAllFor(Model $subject): void
    {
        CompanyException::query()
            ->where('subject_type', $subject->getMorphClass())
            ->where('subject_id', $subject->getKey())
            ->where('is_open', true)
            ->update([
                'is_open' => false,
                'resolved_at' => now(),
            ]);
    }

    public function subjectUrl(?Model $subject, ?string $type = null, $id = null): ?string
    {
        $type = $type ?: $subject?->getMorphClass();
        $id = $id ?: $subject?->getKey();
        if (! $type || ! $id) {
            return null;
        }

        return match ($type) {
            \App\Models\Project::class => route('projects.show', $id),
            \App\Models\Employee::class => route('employees.show', $id),
            \App\Models\FinancialInvoice::class => route('financial-invoices.show', $id),
            \App\Models\Task::class => route('tasks.show', $id),
            default => null,
        };
    }
}
