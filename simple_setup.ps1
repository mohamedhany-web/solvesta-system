Write-Host "🔧 تنظيم الملفات على السيرفر..." -ForegroundColor Green

# أوامر بسيطة لتنظيم الملفات
$commands = "cd public_html/system/solvesta && pwd && ls -la && echo '📁 فحص الملفات الموجودة...' && if [ ! -f .env ]; then echo '⚙️ إنشاء ملف .env...'; cat > .env << 'ENVEOF'
APP_NAME=\"Solvesta Management System\"
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
MAIL_FROM_ADDRESS=\"noreply@system.growup-studio.com\"
MAIL_FROM_NAME=\"\${APP_NAME}\"
SESSION_DOMAIN=.growup-studio.com
SESSION_PATH=/solvesta
ENVEOF
echo '✅ تم إنشاء ملف .env'; else echo '✅ ملف .env موجود بالفعل'; fi && echo '🗄️ إعداد قاعدة البيانات...' && touch database/database.sqlite && chmod 664 database/database.sqlite && chmod 755 database/ && echo '🔐 تعيين الصلاحيات...' && chmod -R 755 storage bootstrap/cache database && mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache storage/app/public && echo '✅ تم تنظيم الملفات بنجاح!'"

Write-Host "📡 الاتصال بالسيرفر..." -ForegroundColor Yellow
ssh user@system.growup-studio.com $commands

Write-Host "✅ تم تنظيم الملفات!" -ForegroundColor Green













