<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salary;

class SalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ملاحظة: هذا الـ Seeder يحتوي على بيانات تجريبية فقط لأغراض الاختبار
     */
    public function run(): void
    {
        // لا توجد بيانات افتراضية
        // سيتم إنشاء الرواتب من خلال النظام
        $this->command->info('SalarySeeder: لا توجد بيانات تجريبية للإضافة');
    }
}
