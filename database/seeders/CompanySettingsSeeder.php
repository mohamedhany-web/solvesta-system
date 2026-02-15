<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class CompanySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Company Basic Info
            [
                'key' => 'company_name',
                'value' => 'اسم شركتك',
                'type' => 'string',
                'group' => 'company',
                'description' => 'اسم الشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'company',
                'description' => 'شعار الشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_phone',
                'value' => '+20',
                'type' => 'string',
                'group' => 'company',
                'description' => 'رقم هاتف الشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_email',
                'value' => 'info@company.com',
                'type' => 'string',
                'group' => 'company',
                'description' => 'البريد الإلكتروني للشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_address',
                'value' => 'عنوان الشركة، مصر',
                'type' => 'string',
                'group' => 'company',
                'description' => 'عنوان الشركة',
                'is_public' => true
            ],
            [
                'key' => 'company_website',
                'value' => 'www.company.com',
                'type' => 'string',
                'group' => 'company',
                'description' => 'موقع الشركة الإلكتروني',
                'is_public' => true
            ],
            
            // Company Registration Info
            [
                'key' => 'tax_number',
                'value' => '',
                'type' => 'string',
                'group' => 'company',
                'description' => 'الرقم الضريبي',
                'is_public' => true
            ],
            [
                'key' => 'commercial_register',
                'value' => '',
                'type' => 'string',
                'group' => 'company',
                'description' => 'رقم السجل التجاري',
                'is_public' => true
            ],
        ];

        foreach ($settings as $setting) {
            SystemSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✓ تم إضافة إعدادات الشركة بنجاح');
    }
}



