<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SystemSettingsController extends Controller
{
    /**
     * عرض صفحة إعدادات النظام
     */
    public function index()
    {
        $settings = SystemSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        
        // التأكد من وجود المجموعات الأساسية
        $defaultGroups = ['general', 'appearance', 'system', 'notifications', 'financial'];
        foreach ($defaultGroups as $group) {
            if (!$settings->has($group)) {
                $settings[$group] = collect();
            }
        }
        
        return view('system-settings.index', compact('settings'));
    }

    /**
     * تحديث الإعدادات
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*' => 'nullable',
            'whatsapp_default_number' => 'nullable|string|max:20',
            'whatsapp_enabled' => 'nullable|in:0,1',
            'whatsapp_auto_open' => 'nullable|in:0,1'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // تحديث الإعدادات العادية
            foreach ($request->settings as $key => $value) {
                $setting = SystemSetting::where('key', $key)->first();
                
                if ($setting) {
                    // التعامل مع رفع الملفات
                    if ($setting->type === 'file' && $request->hasFile("settings.{$key}")) {
                        $file = $request->file("settings.{$key}");
                        
                        // التحقق من نوع الملف (للوجو والفافيكون)
                        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'webp'];
                        $extension = strtolower($file->getClientOriginalExtension());
                        
                        if (!in_array($extension, $allowedExtensions)) {
                            return back()->with('error', 'نوع الملف غير مدعوم. يُرجى رفع ملف صورة.');
                        }
                        
                        // تحديد المجلد حسب نوع الملف
                        $folder = ($key === 'logo') ? 'logos' : (($key === 'favicon') ? 'favicons' : 'system');
                        
                        // حذف الملف القديم
                        if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                            Storage::disk('public')->delete($setting->value);
                        }
                        
                        // إنشاء اسم فريد للملف
                        $fileName = time() . '_' . uniqid() . '.' . $extension;
                        
                        // التأكد من وجود المجلد
                        $folderPath = storage_path('app/public/' . $folder);
                        if (!file_exists($folderPath)) {
                            \File::makeDirectory($folderPath, 0755, true);
                        }
                        
                        // حفظ الملف الجديد في المجلد المخصص
                        $path = $file->storeAs($folder, $fileName, 'public');
                        
                        // التأكد من حفظ الملف بنجاح
                        if (!$path || !Storage::disk('public')->exists($path)) {
                            return back()->with('error', 'فشل في حفظ الملف. يرجى المحاولة مرة أخرى.');
                        }
                        
                        // حفظ المسار فقط في قاعدة البيانات
                        $value = $path;
                        
                        // Log للمساعدة في debugging
                        \Log::info('Logo uploaded successfully', [
                            'path' => $path,
                            'full_path' => storage_path('app/public/' . $path),
                            'exists' => Storage::disk('public')->exists($path),
                            'url' => asset('storage/' . $path)
                        ]);
                    }
                    
                    // تحديث القيمة باستخدام updateOrCreate لضمان مسح الكاش
                    $updatedSetting = SystemSetting::updateOrCreate(
                        ['key' => $key],
                        [
                            'value' => $value,
                            'type' => $setting->type,
                            'group' => $setting->group,
                            'description' => $setting->description,
                            'is_public' => $setting->is_public
                        ]
                    );
                    
                    // مسح الكاش صراحة للتأكد
                    \Cache::forget("system_setting_{$key}");
                    \Cache::forget('system_settings');
                } else {
                    // إنشاء إعداد جديد إذا لم يكن موجوداً
                    SystemSetting::create([
                        'key' => $key,
                        'value' => $value,
                        'type' => 'string',
                        'group' => 'general',
                        'description' => 'إعداد مخصص',
                        'is_public' => true
                    ]);
                }
            }

            // تحديث إعدادات الواتساب
            if ($request->has('whatsapp_default_number')) {
                SystemSetting::set('whatsapp_default_number', $request->whatsapp_default_number, 'string', 'whatsapp', 'رقم الواتساب الافتراضي للإرسال', true);
            }

            if ($request->has('whatsapp_enabled')) {
                SystemSetting::set('whatsapp_enabled', $request->whatsapp_enabled, 'boolean', 'whatsapp', 'تفعيل إرسال رسائل الواتساب', true);
            }

            if ($request->has('whatsapp_auto_open')) {
                SystemSetting::set('whatsapp_auto_open', $request->whatsapp_auto_open, 'boolean', 'whatsapp', 'فتح الواتساب تلقائياً عند الإرسال', true);
            }

            // مسح الكاش بشكل شامل
            SystemSetting::clearCache();
            \Cache::flush();
            
            // مسح كاش الكنفيج والـ views أيضاً
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('cache:clear');

            return back()->with('success', 'تم تحديث الإعدادات بنجاح')->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث الإعدادات: ' . $e->getMessage());
        }
    }

    /**
     * إعادة تعيين الإعدادات للقيم الافتراضية
     */
    public function reset()
    {
        try {
            // حذف جميع الإعدادات
            SystemSetting::truncate();
            
            // إعادة إنشاء الإعدادات الافتراضية
            $this->createDefaultSettings();

            return back()->with('success', 'تم إعادة تعيين الإعدادات بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إعادة تعيين الإعدادات: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء الإعدادات الافتراضية
     */
    private function createDefaultSettings()
    {
        $defaultSettings = [
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
                'value' => 'عنوان الشركة',
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

            // إعدادات النظام
            [
                'key' => 'timezone',
                'value' => 'Asia/Riyadh',
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

        foreach ($defaultSettings as $setting) {
            SystemSetting::create($setting);
        }
    }
}