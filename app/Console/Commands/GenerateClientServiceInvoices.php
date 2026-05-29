<?php

namespace App\Console\Commands;

use App\Services\ClientServiceBillingService;
use Illuminate\Console\Command;

class GenerateClientServiceInvoices extends Command
{
    protected $signature = 'client-services:generate-invoices';

    protected $description = 'إنشاء الفواتير الشهرية لخدمات ما بعد البيع المستحقة';

    public function handle(ClientServiceBillingService $billing): int
    {
        $count = $billing->processDueServices();
        $this->info("تم إنشاء {$count} فاتورة خدمة.");

        return self::SUCCESS;
    }
}
