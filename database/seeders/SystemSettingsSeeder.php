<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // إعدادات عامة
            [
                'key' => 'system_name',
                'value' => 'نظام إدارة سولفيستا',
                'type' => 'string',
                'group' => 'general',
                'description' => 'اسم النظام',
                'is_public' => true
            ],
            [
                'key' => 'system_description',
                'value' => 'نظام شامل لإدارة العمليات التجارية والموارد البشرية',
                'type' => 'text',
                'group' => 'general',
                'description' => 'وصف النظام',
                'is_public' => true
            ],
            [
                'key' => 'company_name',
                'value' => 'اسم شركتك',
                'type' => 'string',
                'group' => 'general',
                'description' => 'اسم الشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_address',
                'value' => 'عنوان الشركة، مصر',
                'type' => 'text',
                'group' => 'general',
                'description' => 'عنوان الشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_phone',
                'value' => '+20',
                'type' => 'string',
                'group' => 'general',
                'description' => 'هاتف الشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_email',
                'value' => 'info@company.com',
                'type' => 'string',
                'group' => 'general',
                'description' => 'البريد الإلكتروني للشركة',
                'is_public' => true
            ],

            // إعدادات المظهر
            [
                'key' => 'logo',
                'value' => 'system/logo.png',
                'type' => 'file',
                'group' => 'appearance',
                'description' => 'شعار الشركة',
                'is_public' => true
            ],
            [
                'key' => 'favicon',
                'value' => 'system/favicon.ico',
                'type' => 'file',
                'group' => 'appearance',
                'description' => 'أيقونة المتصفح',
                'is_public' => true
            ],
            [
                'key' => 'theme_color',
                'value' => '#2563eb',
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'اللون الرئيسي للنظام',
                'is_public' => true
            ],
            [
                'key' => 'sidebar_color',
                'value' => '#1f2937',
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'لون الشريط الجانبي',
                'is_public' => true
            ],
            [
                'key' => 'logo_size',
                'value' => 'medium',
                'type' => 'string',
                'group' => 'appearance',
                'description' => 'حجم اللوجو في الشريط الجانبي',
                'is_public' => true
            ],

            // إعدادات النظام
            [
                'key' => 'timezone',
                'value' => 'Africa/Cairo',
                'type' => 'string',
                'group' => 'system',
                'description' => 'المنطقة الزمنية',
                'is_public' => false
            ],
            [
                'key' => 'language',
                'value' => 'ar',
                'type' => 'string',
                'group' => 'system',
                'description' => 'لغة النظام',
                'is_public' => false
            ],
            [
                'key' => 'date_format',
                'value' => 'Y-m-d',
                'type' => 'string',
                'group' => 'system',
                'description' => 'تنسيق التاريخ',
                'is_public' => false
            ],
            [
                'key' => 'time_format',
                'value' => 'H:i',
                'type' => 'string',
                'group' => 'system',
                'description' => 'تنسيق الوقت',
                'is_public' => false
            ],

            // إعدادات الإشعارات
            [
                'key' => 'email_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'تفعيل إشعارات البريد الإلكتروني',
                'is_public' => false
            ],
            [
                'key' => 'sms_notifications',
                'value' => '0',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'تفعيل إشعارات الرسائل النصية',
                'is_public' => false
            ],
            [
                'key' => 'push_notifications',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'notifications',
                'description' => 'تفعيل الإشعارات الفورية',
                'is_public' => false
            ],

            // إعدادات مالية
            [
                'key' => 'bank_name',
                'value' => 'البنك الأهلي المصري',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'اسم البنك الأساسي',
                'is_public' => true
            ],
            [
                'key' => 'bank_account_number',
                'value' => '12345678901234567890',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'رقم الحساب البنكي',
                'is_public' => true
            ],
            [
                'key' => 'bank_iban',
                'value' => '',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'IBAN',
                'is_public' => true
            ],
            [
                'key' => 'bank_account_holder',
                'value' => '',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'اسم صاحب الحساب',
                'is_public' => true
            ],
            [
                'key' => 'bank_swift',
                'value' => '',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'Swift Code',
                'is_public' => true
            ],
            [
                'key' => 'bank_branch',
                'value' => '',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'فرع البنك',
                'is_public' => true
            ],
            [
                'key' => 'payment_methods',
                'value' => 'تحويل بنكي أو شيك',
                'type' => 'text',
                'group' => 'financial',
                'description' => 'طرق الدفع المقبولة',
                'is_public' => true
            ],
            [
                'key' => 'default_payment_period',
                'value' => '30',
                'type' => 'number',
                'group' => 'financial',
                'description' => 'مدة السداد الافتراضية بالأيام',
                'is_public' => false
            ],
            [
                'key' => 'default_tax_rate',
                'value' => '0',
                'type' => 'number',
                'group' => 'financial',
                'description' => 'نسبة الضريبة الافتراضية',
                'is_public' => false
            ],
            [
                'key' => 'tax_number',
                'value' => '',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'الرقم الضريبي',
                'is_public' => true
            ],
            [
                'key' => 'commercial_registration',
                'value' => '',
                'type' => 'string',
                'group' => 'financial',
                'description' => 'السجل التجاري',
                'is_public' => true
            ],
            [
                'key' => 'invoice_financial_notes',
                'value' => '',
                'type' => 'text',
                'group' => 'financial',
                'description' => 'ملاحظات مالية للفواتير',
                'is_public' => true
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}