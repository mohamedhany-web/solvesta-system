<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء الحسابات الأساسية
        $accounts = [
            // الأصول
            [
                'name' => 'الأصول المتداولة',
                'code' => '1000',
                'type' => 'asset',
                'parent_id' => null,
                'description' => 'الأصول التي يمكن تحويلها إلى نقد خلال سنة واحدة',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'النقدية',
                'code' => '1100',
                'type' => 'asset',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'النقدية المتاحة في الخزينة',
                'balance' => 50000,
                'is_active' => true,
            ],
            [
                'name' => 'البنوك',
                'code' => '1200',
                'type' => 'asset',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'الودائع البنكية والحسابات الجارية',
                'balance' => 150000,
                'is_active' => true,
            ],
            [
                'name' => 'العملاء',
                'code' => '1300',
                'type' => 'asset',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'المبالغ المستحقة على العملاء',
                'balance' => 75000,
                'is_active' => true,
            ],
            [
                'name' => 'المخزون',
                'code' => '1400',
                'type' => 'asset',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'البضائع والمخزون المتاح للبيع',
                'balance' => 100000,
                'is_active' => true,
            ],
            [
                'name' => 'الأصول الثابتة',
                'code' => '2000',
                'type' => 'asset',
                'parent_id' => null,
                'description' => 'الأصول طويلة الأجل',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'المعدات',
                'code' => '2100',
                'type' => 'asset',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'المعدات والآلات',
                'balance' => 200000,
                'is_active' => true,
            ],
            [
                'name' => 'المباني',
                'code' => '2200',
                'type' => 'asset',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'المباني والمكاتب',
                'balance' => 500000,
                'is_active' => true,
            ],
            [
                'name' => 'المركبات',
                'code' => '2300',
                'type' => 'asset',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'المركبات والسيارات',
                'balance' => 80000,
                'is_active' => true,
            ],

            // الخصوم
            [
                'name' => 'الخصوم المتداولة',
                'code' => '3000',
                'type' => 'liability',
                'parent_id' => null,
                'description' => 'الخصوم المستحقة خلال سنة واحدة',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'الموردون',
                'code' => '3100',
                'type' => 'liability',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'المبالغ المستحقة للموردين',
                'balance' => 45000,
                'is_active' => true,
            ],
            [
                'name' => 'الرواتب المستحقة',
                'code' => '3200',
                'type' => 'liability',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'الرواتب والاستحقاقات المستحقة للموظفين',
                'balance' => 25000,
                'is_active' => true,
            ],
            [
                'name' => 'الضرائب المستحقة',
                'code' => '3300',
                'type' => 'liability',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'الضرائب والرسوم المستحقة',
                'balance' => 15000,
                'is_active' => true,
            ],
            [
                'name' => 'الخصوم طويلة الأجل',
                'code' => '4000',
                'type' => 'liability',
                'parent_id' => null,
                'description' => 'الخصوم المستحقة لأكثر من سنة',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'القروض البنكية',
                'code' => '4100',
                'type' => 'liability',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'القروض البنكية طويلة الأجل',
                'balance' => 300000,
                'is_active' => true,
            ],

            // حقوق الملكية
            [
                'name' => 'حقوق الملكية',
                'code' => '5000',
                'type' => 'equity',
                'parent_id' => null,
                'description' => 'حقوق المالكين في الشركة',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'رأس المال',
                'code' => '5100',
                'type' => 'equity',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'رأس المال المدفوع',
                'balance' => 500000,
                'is_active' => true,
            ],
            [
                'name' => 'الأرباح المحتجزة',
                'code' => '5200',
                'type' => 'equity',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'الأرباح المحتجزة من السنوات السابقة',
                'balance' => 85000,
                'is_active' => true,
            ],

            // الإيرادات
            [
                'name' => 'الإيرادات',
                'code' => '6000',
                'type' => 'revenue',
                'parent_id' => null,
                'description' => 'إيرادات الشركة',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'إيرادات المبيعات',
                'code' => '6100',
                'type' => 'revenue',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'إيرادات مبيعات المنتجات والخدمات',
                'balance' => 350000,
                'is_active' => true,
            ],
            [
                'name' => 'إيرادات الخدمات',
                'code' => '6200',
                'type' => 'revenue',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'إيرادات تقديم الخدمات',
                'balance' => 120000,
                'is_active' => true,
            ],
            [
                'name' => 'إيرادات أخرى',
                'code' => '6300',
                'type' => 'revenue',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'إيرادات أخرى متنوعة',
                'balance' => 15000,
                'is_active' => true,
            ],

            // المصروفات
            [
                'name' => 'المصروفات',
                'code' => '7000',
                'type' => 'expense',
                'parent_id' => null,
                'description' => 'مصروفات التشغيل',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'مصروفات الرواتب',
                'code' => '7100',
                'type' => 'expense',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'رواتب وأجور الموظفين',
                'balance' => 180000,
                'is_active' => true,
            ],
            [
                'name' => 'مصروفات الإيجار',
                'code' => '7200',
                'type' => 'expense',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'إيجار المكاتب والمباني',
                'balance' => 24000,
                'is_active' => true,
            ],
            [
                'name' => 'مصروفات الكهرباء والمياه',
                'code' => '7300',
                'type' => 'expense',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'فواتير الكهرباء والمياه',
                'balance' => 12000,
                'is_active' => true,
            ],
            [
                'name' => 'مصروفات التسويق',
                'code' => '7400',
                'type' => 'expense',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'مصروفات التسويق والإعلان',
                'balance' => 15000,
                'is_active' => true,
            ],
            [
                'name' => 'مصروفات النقل',
                'code' => '7500',
                'type' => 'expense',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'مصروفات النقل والشحن',
                'balance' => 8000,
                'is_active' => true,
            ],
            [
                'name' => 'مصروفات أخرى',
                'code' => '7600',
                'type' => 'expense',
                'parent_id' => null, // سيتم تحديثه لاحقاً
                'description' => 'مصروفات أخرى متنوعة',
                'balance' => 6000,
                'is_active' => true,
            ],
        ];

        // إنشاء الحسابات
        foreach ($accounts as $accountData) {
            $account = Account::create($accountData);
            
            // تحديث parent_id للحسابات الفرعية
            if (str_contains($account->code, '1100') || str_contains($account->code, '1200') || 
                str_contains($account->code, '1300') || str_contains($account->code, '1400')) {
                $parent = Account::where('code', '1000')->first();
                if ($parent) {
                    $account->update(['parent_id' => $parent->id]);
                }
            }
            
            if (str_contains($account->code, '2100') || str_contains($account->code, '2200') || 
                str_contains($account->code, '2300')) {
                $parent = Account::where('code', '2000')->first();
                if ($parent) {
                    $account->update(['parent_id' => $parent->id]);
                }
            }
            
            if (str_contains($account->code, '3100') || str_contains($account->code, '3200') || 
                str_contains($account->code, '3300')) {
                $parent = Account::where('code', '3000')->first();
                if ($parent) {
                    $account->update(['parent_id' => $parent->id]);
                }
            }
            
            if (str_contains($account->code, '4100')) {
                $parent = Account::where('code', '4000')->first();
                if ($parent) {
                    $account->update(['parent_id' => $parent->id]);
                }
            }
            
            if (str_contains($account->code, '5100') || str_contains($account->code, '5200')) {
                $parent = Account::where('code', '5000')->first();
                if ($parent) {
                    $account->update(['parent_id' => $parent->id]);
                }
            }
            
            if (str_contains($account->code, '6100') || str_contains($account->code, '6200') || 
                str_contains($account->code, '6300')) {
                $parent = Account::where('code', '6000')->first();
                if ($parent) {
                    $account->update(['parent_id' => $parent->id]);
                }
            }
            
            if (str_contains($account->code, '7100') || str_contains($account->code, '7200') || 
                str_contains($account->code, '7300') || str_contains($account->code, '7400') || 
                str_contains($account->code, '7500') || str_contains($account->code, '7600')) {
                $parent = Account::where('code', '7000')->first();
                if ($parent) {
                    $account->update(['parent_id' => $parent->id]);
                }
            }
        }

        $this->command->info('تم إنشاء دليل الحسابات بنجاح!');
    }
}
