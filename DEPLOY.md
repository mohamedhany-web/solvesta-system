# نشر Solvesta (Laravel) — تجنب كسر التصميم

## هل تحتاج `npm run build`؟

| الجزء | يحتاج build؟ |
|--------|----------------|
| **الموقع العام** (Home, About, Services…) | **لا** — ملفات جاهزة: `public/css/cinematic-home.css` و `public/js/cinematic-home.js` |
| **لوحة الإدارة** (Dashboard) | اختياري — `npm run build` يبني `resources/css/app.css` عبر Vite |

`npm run build` **لن يصلح** الصفحة الرئيسية إذا كان التصميم مكسوراً بعد الرفع.

---

إذا ظهر الموقع **بدون ألوان وتنسيق** (نص فقط)، السبب غالباً أن ملفات CSS لا تُحمّل.

## 1) جذر الموقع (Document Root)

يجب أن يشير الدومين إلى مجلد **`public`** وليس جذر المشروع:

```
your-domain.com  →  /path/to/solvesta/public
```

## 2) ملف `.env` على السيرفر

```env
APP_URL=https://your-domain.com
APP_ENV=production
APP_DEBUG=false

# اختياري إذا كان الموقع في مجلد فرعي:
# ASSET_URL=https://your-domain.com/subfolder
```

بعد التعديل:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## 3) ارفع هذه المجلدات/الملفات

تأكد من رفع:

- `public/css/cinematic-home.css`
- `public/css/client-login.css`
- `public/js/cinematic-home.js`
- `public/js/client-login.js`
- `public/index.php`
- `public/.htaccess`

## 4) تحقق سريع

افتح في المتصفح:

- `https://your-domain.com/css/cinematic-home.css`

إذا ظهر **404** → المشكلة في Document Root أو الملفات لم تُرفع.

## 5) محلي (XAMPP / artisan serve)

في `.env`:

```env
APP_URL=http://127.0.0.1:8000
```

ثم `php artisan serve` أو اضبط Virtual Host على `public`.

---

التطبيق يضبط رابط الأصول تلقائياً من عنوان المتصفح إذا كان `APP_URL` خاطئاً (مثل `localhost` والفتح من `127.0.0.1`).
