Write-Host "🔧 تنظيم الملفات على السيرفر..." -ForegroundColor Green

# سكريبت بسيط لتنظيم الملفات الموجودة
$serverCommands = @"
cd public_html/system/solvesta
pwd
ls -la
echo "📁 فحص الملفات الموجودة..."

# إنشاء ملف .env إذا لم يكن موجوداً
if [ ! -f .env ]; then
    echo "⚙️ إنشاء ملف .env..."
    cat > .env << 'ENVEOF'
APP_NAME="Solvesta Management System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://system.growup-studio.com/solvesta

DB_CONNECTION=sqlite
DB_DATABASE=/public_html/system/solvesta/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=noreply@system.growup-studio.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@system.growup-studio.com"
MAIL_FROM_NAME="\${APP_NAME}"

SESSION_DOMAIN=.growup-studio.com
SESSION_PATH=/solvesta
ENVEOF
    echo "✅ تم إنشاء ملف .env"
else
    echo "✅ ملف .env موجود بالفعل"
fi

# إعداد قاعدة البيانات
echo "🗄️ إعداد قاعدة البيانات..."
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
    chmod 664 database/database.sqlite
    echo "✅ تم إنشاء قاعدة البيانات"
else
    echo "✅ قاعدة البيانات موجودة بالفعل"
fi

# تعيين الصلاحيات
echo "🔐 تعيين الصلاحيات..."
chmod -R 755 storage bootstrap/cache database
chmod 664 database/database.sqlite
chown -R www-data:www-data storage bootstrap/cache database 2>/dev/null || true

# إنشاء المجلدات المطلوبة
echo "📁 إنشاء المجلدات المطلوبة..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache
mkdir -p storage/app/public

echo "✅ تم تنظيم الملفات بنجاح!"
echo "📊 حالة النظام:"
ls -la database/
ls -la storage/
"@

Write-Host "📡 الاتصال بالسيرفر وتنظيم الملفات..." -ForegroundColor Yellow
ssh user@system.growup-studio.com $serverCommands

Write-Host "✅ تم تنظيم الملفات!" -ForegroundColor Green













