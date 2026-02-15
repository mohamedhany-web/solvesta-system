<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * ملاحظة: هذا الـ Seeder يحتوي على بيانات تجريبية فقط لأغراض الاختبار
     * يمكن تفعيله أو تعطيله من DatabaseSeeder
     */
    public function run(): void
    {
        // لا توجد بيانات افتراضية
        // يمكن إضافة بيانات المشاريع من خلال واجهة النظام
        $this->command->info('ProjectSeeder: لا توجد بيانات تجريبية للإضافة');
    }
}
