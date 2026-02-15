# تعليمات إعداد اللوجو

## تم إصلاح نظام رفع وعرض اللوجو

### التغييرات التي تمت:

1. **حفظ اللوجو في مجلد مخصص:**
   - يتم حفظ اللوجو في مجلد `storage/app/public/logos/`
   - يتم حفظ الفافيكون في مجلد `storage/app/public/favicons/`
   - يتم حفظ الملفات الأخرى في `storage/app/public/system/`

2. **تخزين المسار فقط في قاعدة البيانات:**
   - يتم حفظ المسار النسبي فقط (مثل: `logos/1234567890_abc123.png`)
   - لا يتم حفظ المسار الكامل

3. **عرض اللوجو:**
   - يتم استخدام `SettingsHelper::getLogoUrl()` للحصول على URL كامل
   - يتم التحقق من وجود الملف قبل العرض

### خطوات التأكد من أن كل شيء يعمل:

1. **إنشاء الرابط الرمزي (Symlink):**
   ```bash
   php artisan storage:link
   ```
   هذا الأمر ينشئ رابط رمزي من `public/storage` إلى `storage/app/public`

2. **التأكد من الصلاحيات:**
   ```bash
   chmod -R 775 storage
   chmod -R 775 public/storage
   ```

3. **رفع اللوجو:**
   - اذهب إلى إعدادات النظام
   - ارفع اللوجو من صفحة الإعدادات
   - سيتم حفظه تلقائياً في مجلد `logos`

### ملاحظات مهمة:

- يتم إنشاء اسم فريد للملف تلقائياً (Timestamp + ID فريد)
- يتم التحقق من نوع الملف (jpg, jpeg, png, gif, svg, ico, webp)
- يتم حذف الملف القديم تلقائياً عند رفع ملف جديد
- المسار يُحفظ في قاعدة البيانات في جدول `system_settings`

### استكشاف الأخطاء:

إذا لم يظهر اللوجو:

1. تأكد من وجود الرابط الرمزي:
   ```bash
   ls -la public/storage
   ```

2. تأكد من وجود الملف:
   ```bash
   ls -la storage/app/public/logos/
   ```

3. تحقق من الصلاحيات:
   ```bash
   ls -la storage/app/public/
   ```

4. امسح الكاش:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

