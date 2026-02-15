<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Leave;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ملاحظة: هذا الـ Seeder يحتوي على بيانات تجريبية فقط لأغراض الاختبار
     */
    public function run(): void
    {
        // لا توجد بيانات افتراضية
        // سيتم تقديم طلبات الإجازات من خلال النظام
        $this->command->info('LeaveSeeder: لا توجد بيانات تجريبية للإضافة');
    }
}
