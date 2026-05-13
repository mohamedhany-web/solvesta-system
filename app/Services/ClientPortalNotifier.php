<?php

namespace App\Services;

use App\Models\ClientNotification;

class ClientPortalNotifier
{
    public static function notify(
        int $clientId,
        string $type,
        string $title,
        ?string $body = null,
        ?string $actionUrl = null,
        ?array $meta = null
    ): ClientNotification {
        return ClientNotification::create([
            'client_id' => $clientId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'action_url' => $actionUrl,
            'meta' => $meta,
        ]);
    }
}
