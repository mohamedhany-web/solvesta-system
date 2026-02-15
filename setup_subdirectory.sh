#!/bin/bash

# سكريبت إعداد النظام في المجلد الفرعي مع SQLite
# Solvesta Management System Subdirectory Setup Script

echo "🚀 بدء إعداد النظام في المجلد الفرعي..."

# معلومات السيرفر
SERVER="system.growup-studio.com"
REMOTE_PATH="/public_html/system/solvesta"

# ألوان للنصوص
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# دالة طباعة الرسائل
print_message() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# إعداد البيئة على السيرفر
setup_environment() {
    print_message "إعداد البيئة على السيرفر..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # إنشاء ملف .env
        if [ ! -f .env ]; then
            cp .env.example .env
            print_message "تم إنشاء ملف .env"
        fi
        
        # تحديث ملف .env للإنتاج
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
MAIL_FROM_NAME="\${APP_NAME}"

SESSION_DOMAIN=.growup-studio.com
SESSION_PATH=/solvesta
ENVEOF
        
        echo "تم تحديث ملف .env"
EOF
    
    if [ $? -eq 0 ]; then
        print_success "تم إعداد البيئة بنجاح"
    else
        print_error "فشل في إعداد البيئة"
        exit 1
    fi
}

# إعداد قاعدة البيانات
setup_database() {
    print_message "إعداد قاعدة بيانات SQLite..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # إنشاء ملف قاعدة البيانات
        touch database/database.sqlite
        
        # تعيين الصلاحيات
        chmod 664 database/database.sqlite
        chmod 755 database/
        
        echo "تم إنشاء قاعدة البيانات"
EOF
    
    if [ $? -eq 0 ]; then
        print_success "تم إعداد قاعدة البيانات بنجاح"
    else
        print_error "فشل في إعداد قاعدة البيانات"
        exit 1
    fi
}

# تثبيت التبعيات
install_dependencies() {
    print_message "تثبيت التبعيات..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # تثبيت Composer dependencies
        composer install --optimize-autoloader --no-dev --no-interaction
        
        # تثبيت NPM dependencies إذا لزم الأمر
        if [ -f package.json ]; then
            npm install --production
            npm run build
        fi
        
        echo "تم تثبيت التبعيات"
EOF
    
    if [ $? -eq 0 ]; then
        print_success "تم تثبيت التبعيات بنجاح"
    else
        print_error "فشل في تثبيت التبعيات"
        exit 1
    fi
}

# إعداد Laravel
setup_laravel() {
    print_message "إعداد Laravel..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # إنشاء مفتاح التطبيق
        php artisan key:generate
        
        # إنشاء مجلدات التخزين
        mkdir -p storage/logs
        mkdir -p storage/framework/cache
        mkdir -p storage/framework/sessions
        mkdir -p storage/framework/views
        mkdir -p bootstrap/cache
        mkdir -p storage/app/public
        
        # تعيين الصلاحيات
        chmod -R 755 storage bootstrap/cache database
        chmod 664 database/database.sqlite
        chown -R www-data:www-data storage bootstrap/cache database
        
        # إنشاء الرابط الرمزي للتخزين
        php artisan storage:link
        
        echo "تم إعداد Laravel"
EOF
    
    if [ $? -eq 0 ]; then
        print_success "تم إعداد Laravel بنجاح"
    else
        print_error "فشل في إعداد Laravel"
        exit 1
    fi
}

# تشغيل المايجريشن والسيدرات
run_migrations() {
    print_message "تشغيل المايجريشن والسيدرات..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # تشغيل المايجريشن
        php artisan migrate --force
        
        # تشغيل السيدرات
        php artisan db:seed --force
        
        echo "تم تشغيل المايجريشن والسيدرات"
EOF
    
    if [ $? -eq 0 ]; then
        print_success "تم تشغيل المايجريشن والسيدرات بنجاح"
    else
        print_error "فشل في تشغيل المايجريشن والسيدرات"
        exit 1
    fi
}

# تحسين النظام
optimize_system() {
    print_message "تحسين النظام..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # تنظيف الكاش
        php artisan config:clear
        php artisan route:clear
        php artisan view:clear
        php artisan cache:clear
        
        # إنشاء الكاش للإنتاج
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        php artisan optimize
        
        # إعداد ملف .htaccess
        if [ -f public/.htaccess.subdirectory ]; then
            cp public/.htaccess.subdirectory public/.htaccess
        fi
        
        echo "تم تحسين النظام"
EOF
    
    if [ $? -eq 0 ]; then
        print_success "تم تحسين النظام بنجاح"
    else
        print_error "فشل في تحسين النظام"
        exit 1
    fi
}

# اختبار النظام
test_system() {
    print_message "اختبار النظام..."
    
    # اختبار الاتصال
    response=$(curl -s -o /dev/null -w "%{http_code}" https://system.growup-studio.com/solvesta)
    
    if [ "$response" = "200" ]; then
        print_success "النظام يعمل بشكل صحيح (HTTP $response)"
    else
        print_warning "استجابة غير متوقعة: HTTP $response"
    fi
    
    # اختبار قاعدة البيانات
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        php artisan tinker --execute="echo 'Users: ' . App\Models\User::count() . PHP_EOL; echo 'Employees: ' . App\Models\Employee::count() . PHP_EOL;"
EOF
}

# النسخ الاحتياطي
backup_database() {
    print_message "إنشاء نسخة احتياطية من قاعدة البيانات..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # نسخ احتياطي لقاعدة البيانات
        cp database/database.sqlite database/backup_\$(date +%Y%m%d_%H%M%S).sqlite
        
        # نسخ احتياطي للملفات
        tar -czf files_backup_\$(date +%Y%m%d).tar.gz storage/ public/
        
        echo "تم إنشاء النسخة الاحتياطية"
EOF
    
    print_success "تم إنشاء النسخة الاحتياطية"
}

# الدالة الرئيسية
main() {
    echo "=========================================="
    echo "🚀 Solvesta Subdirectory Setup"
    echo "=========================================="
    echo
    
    # إنشاء نسخة احتياطية
    read -p "هل تريد إنشاء نسخة احتياطية؟ (y/n): " backup_choice
    if [ "$backup_choice" = "y" ] || [ "$backup_choice" = "Y" ]; then
        backup_database
    fi
    
    # إعداد البيئة
    setup_environment
    
    # إعداد قاعدة البيانات
    setup_database
    
    # تثبيت التبعيات
    install_dependencies
    
    # إعداد Laravel
    setup_laravel
    
    # تشغيل المايجريشن والسيدرات
    run_migrations
    
    # تحسين النظام
    optimize_system
    
    # اختبار النظام
    test_system
    
    echo
    echo "=========================================="
    print_success "تم إعداد النظام بنجاح!"
    echo "🌐 الموقع: https://system.growup-studio.com/solvesta"
    echo "👤 بيانات الدخول:"
    echo "   - المدير: admin@solvesta.com / password"
    echo "   - الموظف: employee@solvesta.com / password"
    echo "=========================================="
}

# تشغيل السكريبت
main "$@"













