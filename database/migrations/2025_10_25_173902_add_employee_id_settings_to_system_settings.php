<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة إعدادات الرقم التوظيفي
        \App\Models\SystemSetting::set('employee_id_prefix', 'EMP', 'string', 'employee', 'بادئة الرقم التوظيفي', true);
        \App\Models\SystemSetting::set('employee_id_length', '6', 'integer', 'employee', 'طول الرقم التوظيفي', true);
        \App\Models\SystemSetting::set('employee_id_type', 'sequential', 'string', 'employee', 'نوع توليد الرقم التوظيفي (sequential/random)', true);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف إعدادات الرقم التوظيفي
        \App\Models\SystemSetting::where('key', 'employee_id_prefix')->delete();
        \App\Models\SystemSetting::where('key', 'employee_id_length')->delete();
        \App\Models\SystemSetting::where('key', 'employee_id_type')->delete();
    }
};
