<?php

namespace App\Console\Commands;

use App\Services\CompanyOs\SlaEngine;
use Illuminate\Console\Command;

class ScanCompanyOsSlaCommand extends Command
{
    protected $signature = 'solvesta:scan-sla';

    protected $description = 'فحص SLA والاستثناءات المفتوحة في Company OS';

    public function handle(SlaEngine $sla): int
    {
        $opened = $sla->scan();
        $this->info("تم فحص الاستثناءات. عناصر محدَّثة: {$opened}");

        return self::SUCCESS;
    }
}
