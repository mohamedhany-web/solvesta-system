Write-Host "🚀 بدء الإعداد السريع..." -ForegroundColor Green

$commands = @"
cd public_html/system/solvesta
echo "📁 الانتقال إلى المجلد..."

# إنشاء ملف .env
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

# إعداد قاعدة البيانات
touch database/database.sqlite
chmod 664 database/database.sqlite
chmod 755 database/

# تثبيت التبعيات
composer install --optimize-autoloader --no-dev --no-interaction

# إعداد Laravel
php artisan key:generate
mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache storage/app/public
chmod -R 755 storage bootstrap/cache database
chmod 664 database/database.sqlite
chown -R www-data:www-data storage bootstrap/cache database
php artisan storage:link

# تشغيل المايجريشن والسيدرات
php artisan migrate --force
php artisan db:seed --force

# تحسين النظام
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# إعداد .htaccess
if [ -f public/.htaccess.subdirectory ]; then
    cp public/.htaccess.subdirectory public/.htaccess
fi

echo "🎉 تم إعداد النظام بنجاح!"
echo "🌐 الموقع: https://system.growup-studio.com/solvesta"
"@

Write-Host "📡 الاتصال بالسيرفر..." -ForegroundColor Yellow
ssh user@system.growup-studio.com $commands

Write-Host "✅ تم الانتهاء من الإعداد!" -ForegroundColor Green













