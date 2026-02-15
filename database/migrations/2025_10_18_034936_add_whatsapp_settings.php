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
        // إضافة إعدادات الواتساب
        \App\Models\SystemSetting::set('whatsapp_default_number', '201044610510', 'string', 'whatsapp', 'رقم الواتساب الافتراضي للإرسال', true);
        \App\Models\SystemSetting::set('whatsapp_enabled', '1', 'boolean', 'whatsapp', 'تفعيل إرسال رسائل الواتساب', true);
        \App\Models\SystemSetting::set('whatsapp_auto_open', '1', 'boolean', 'whatsapp', 'فتح الواتساب تلقائياً عند الإرسال', true);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\SystemSetting::where('group', 'whatsapp')->delete();
    }
};