<?php

namespace App\Console\Commands;

use App\Services\OfficialMailInboxService;
use Illuminate\Console\Command;

class SyncOfficialMailCommand extends Command
{
    protected $signature = 'mail:sync {--limit=40 : Max messages to scan}';

    protected $description = 'Sync official Hostinger inbox into the system';

    public function handle(OfficialMailInboxService $service): int
    {
        $this->info('مزامنة صندوق البريد الرسمي...');

        try {
            $result = $service->sync((int) $this->option('limit'));
            $this->info("جديدة: {$result['synced']} | تحويل: {$result['forwarded']} | إشعارات: {$result['notified']}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
