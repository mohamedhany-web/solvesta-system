#!/bin/bash

echo "🚀 بدء الإعداد السريع..."

# الاتصال بالسيرفر وتشغيل الأوامر
ssh user@system.growup-studio.com << 'EOF'
cd public_html/system/solvesta

echo "📁 الانتقال إلى المجلد..."

# إنشاء ملف .env
echo "⚙️ إنشاء ملف .env..."
cat > .env << 'ENVEOF'
APP_NAME="Solvesta Management System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://system.growup-studio.com/solvesta

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

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
MAIL_FROM_NAME="${APP_NAME}"

SESSION_DOMAIN=.growup-studio.com
SESSION_PATH=/solvesta
ENVEOF

echo "✅ تم إنشاء ملف .env"

# إعداد قاعدة البيانات
echo "🗄️ إعداد قاعدة البيانات..."
touch database/database.sqlite
chmod 664 database/database.sqlite
chmod 755 database/

echo "✅ تم إعداد قاعدة البيانات"

# تثبيت التبعيات
echo "📦 تثبيت التبعيات..."
composer install --optimize-autoloader --no-dev --no-interaction

echo "✅ تم تثبيت التبعيات"

# إعداد Laravel
echo "🔧 إعداد Laravel..."
php artisan key:generate
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache storage/app/public
chmod -R 755 storage bootstrap/cache database
chmod 664 database/database.sqlite
chown -R www-data:www-data storage bootstrap/cache database
php artisan storage:link

echo "✅ تم إعداد Laravel"

# تشغيل المايجريشن والسيدرات
echo "🔄 تشغيل المايجريشن والسيدرات..."
php artisan migrate --force
php artisan db:seed --force

echo "✅ تم تشغيل المايجريشن والسيدرات"

# تحسين النظام
echo "⚡ تحسين النظام..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo "✅ تم تحسين النظام"

# إعداد .htaccess
echo "🔒 إعداد .htaccess..."
if [ -f public/.htaccess.subdirectory ]; then
    cp public/.htaccess.subdirectory public/.htaccess
fi

echo "✅ تم إعداد .htaccess"

echo ""
echo "🎉 تم إعداد النظام بنجاح!"
echo "🌐 الموقع: https://system.growup-studio.com/solvesta"
echo "👤 بيانات الدخول:"
echo "   - المدير: admin@solvesta.com / password"
echo "   - الموظف: employee@solvesta.com / password"
echo ""

EOF

echo "✅ تم الانتهاء من الإعداد!"













