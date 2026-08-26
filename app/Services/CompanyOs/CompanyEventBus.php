<?php

namespace App\Services\CompanyOs;

use App\Models\CompanyEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompanyEventBus
{
    public function record(
        Model $subject,
        string $event,
        array $payload = [],
        string $actorType = 'system',
        ?int $actorId = null
    ): CompanyEvent {
        return CompanyEvent::create([
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'subject_type' => $subject->getMorphClass(),
            'subject_id' => $subject->getKey(),
            'event' => $event,
            'payload' => $payload,
        ]);
    }

    public function recordUser(Model $subject, string $event, array $payload = [], ?User $user = null): CompanyEvent
    {
        $user = $user ?? auth()->user();

        return $this->record(
            $subject,
            $event,
            $payload,
            $user ? 'user' : 'system',
            $user?->id
        );
    }
}
