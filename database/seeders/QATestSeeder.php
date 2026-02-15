<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QATest;

class QATestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ملاحظة: هذا الـ Seeder يحتوي على بيانات تجريبية فقط لأغراض الاختبار
     */
    public function run(): void
    {
        // لا توجد بيانات افتراضية
        // سيتم إضافة اختبارات QA من خلال النظام
        $this->command->info('QATestSeeder: لا توجد بيانات تجريبية للإضافة');
    }
}
