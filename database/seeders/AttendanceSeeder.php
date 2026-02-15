<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ملاحظة: هذا الـ Seeder يحتوي على بيانات تجريبية فقط لأغراض الاختبار
     */
    public function run(): void
    {
        // لا توجد بيانات افتراضية
        // سيتم تسجيل الحضور من خلال النظام
        $this->command->info('AttendanceSeeder: لا توجد بيانات تجريبية للإضافة');
    }
}
