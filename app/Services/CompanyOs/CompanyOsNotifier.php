<?php

namespace App\Services\CompanyOs;

use App\Models\Notification;
use App\Models\User;

class CompanyOsNotifier
{
    public function actionRequired(User $user, string $title, string $message, array $data = []): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'action_required',
            'title' => $title,
            'message' => $message,
            'data' => array_merge(['severity' => 'action_required'], $data),
            'is_read' => false,
        ]);
    }

    public function critical(User $user, string $title, string $message, array $data = []): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'critical',
            'title' => $title,
            'message' => $message,
            'data' => array_merge(['severity' => 'critical'], $data),
            'is_read' => false,
        ]);
    }
}
