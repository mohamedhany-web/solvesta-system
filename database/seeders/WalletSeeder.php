<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'الخزينة — نقدية', 'type' => 'cash', 'opening_balance' => 0],
            ['name' => 'الحساب البنكي الرئيسي', 'type' => 'bank', 'opening_balance' => 0, 'bank_name' => 'البنك'],
            ['name' => 'محفظة تحويل / إلكتروني', 'type' => 'transfer', 'opening_balance' => 0],
        ];

        foreach ($defaults as $row) {
            Wallet::firstOrCreate(
                ['name' => $row['name']],
                array_merge($row, ['current_balance' => $row['opening_balance'], 'currency' => 'EGP', 'is_active' => true])
            );
        }
    }
}
