<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Services\AccountingDashboardService;
use Illuminate\Console\Command;

class SyncAccountBalancesFromLedger extends Command
{
    protected $signature = 'accounting:sync-balances';

    protected $description = 'مزامنة أرصدة الحسابات من القيود المحاسبية المعتمدة';

    public function handle(AccountingDashboardService $dashboard): int
    {
        $count = 0;
        Account::chunkById(100, function ($accounts) use ($dashboard, &$count) {
            foreach ($accounts as $account) {
                $account->update(['balance' => $dashboard->accountBalance($account)]);
                $count++;
            }
        });

        $this->info("تم تحديث {$count} حساباً من القيود الفعلية.");

        return self::SUCCESS;
    }
}
