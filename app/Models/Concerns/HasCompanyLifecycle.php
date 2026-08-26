<?php

namespace App\Models\Concerns;

use App\Models\ApprovalRequest;
use App\Models\CompanyEvent;
use App\Models\CompanyException;
use App\Models\EntityLifecycle;
use App\Models\WorkItem;
use App\Services\CompanyOs\WorkflowEngine;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasCompanyLifecycle
{
    public static function bootHasCompanyLifecycle(): void
    {
        static::created(function ($model) {
            try {
                $preferred = method_exists($model, 'defaultLifecycleStateOnCreate')
                    ? $model->defaultLifecycleStateOnCreate()
                    : null;
                app(WorkflowEngine::class)->bootstrap($model, auth()->user(), $preferred);
            } catch (\Throwable $e) {
                report($e);
            }
        });
    }

    public function lifecycle(): MorphOne
    {
        return $this->morphOne(EntityLifecycle::class, 'subject');
    }

    public function companyEvents(): MorphMany
    {
        return $this->morphMany(CompanyEvent::class, 'subject')->latest();
    }

    public function workItems(): MorphMany
    {
        return $this->morphMany(WorkItem::class, 'subject');
    }

    public function companyExceptions(): MorphMany
    {
        return $this->morphMany(CompanyException::class, 'subject');
    }

    public function approvalRequests(): MorphMany
    {
        return $this->morphMany(ApprovalRequest::class, 'subject');
    }

    public function lifecycleStateKey(): ?string
    {
        return $this->lifecycle?->currentState?->key;
    }

    public function lifecycleLabelAr(): string
    {
        return $this->lifecycle?->currentState?->label_ar
            ?? (string) ($this->status_name ?? $this->status ?? '—');
    }
}
