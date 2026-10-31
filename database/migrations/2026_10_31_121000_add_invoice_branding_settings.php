<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            ['invoice_header_title', 'فاتورة', 'string', 'عنوان هيدر الفاتورة'],
            ['invoice_header_subtitle', 'Solvesta Invoice', 'string', 'عنوان فرعي لهيدر الفاتورة'],
            ['invoice_accent_color', '#0f172a', 'string', 'لون تمييز تصميم الفاتورة'],
            ['invoice_show_logo', '1', 'boolean', 'إظهار الشعار في الفاتورة'],
            ['invoice_show_tax_number', '1', 'boolean', 'إظهار الرقم الضريبي في الفاتورة'],
            ['invoice_show_qr', '0', 'boolean', 'إظهار QR لرابط الفاتورة'],
            ['invoice_footer_text', '', 'text', 'نص فوتر مخصص للفاتورة'],
        ];

        foreach ($rows as [$key, $value, $type, $description]) {
            $exists = DB::table('system_settings')->where('key', $key)->exists();
            if ($exists) {
                continue;
            }
            DB::table('system_settings')->insert([
                'key' => $key,
                'value' => $value,
                'type' => $type,
                'group' => 'financial',
                'description' => $description,
                'is_public' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('system_settings')->whereIn('key', [
            'invoice_header_title',
            'invoice_header_subtitle',
            'invoice_accent_color',
            'invoice_show_logo',
            'invoice_show_tax_number',
            'invoice_show_qr',
            'invoice_footer_text',
        ])->delete();
    }
};
