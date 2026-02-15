#!/bin/bash

# سكريبت رفع النظام إلى السيرفر
# Solvesta Management System Deployment Script

echo "🚀 بدء عملية رفع النظام إلى السيرفر..."

# معلومات السيرفر
SERVER="system.growup-studio.com"
REMOTE_PATH="/public_html/system/solvesta"
LOCAL_PATH="."

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

# التحقق من وجود الملفات المطلوبة
check_requirements() {
    print_message "التحقق من المتطلبات..."
    
    if [ ! -f "composer.json" ]; then
        print_error "ملف composer.json غير موجود!"
        exit 1
    fi
    
    if [ ! -f ".env.example" ]; then
        print_error "ملف .env.example غير موجود!"
        exit 1
    fi
    
    print_success "جميع المتطلبات متوفرة"
}

# إعداد البيئة المحلية
setup_local() {
    print_message "إعداد البيئة المحلية..."
    
    # تنظيف الكاش
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    
    # إنشاء الكاش للإنتاج
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    print_success "تم إعداد البيئة المحلية"
}

# رفع الملفات إلى السيرفر
upload_files() {
    print_message "رفع الملفات إلى السيرفر..."
    
    # استبعاد الملفات غير المرغوب فيها
    rsync -avz --progress \
        --exclude '.env' \
        --exclude '.env.local' \
        --exclude 'storage/logs/*' \
        --exclude 'storage/framework/cache/*' \
        --exclude 'storage/framework/sessions/*' \
        --exclude 'storage/framework/views/*' \
        --exclude 'bootstrap/cache/*.php' \
        --exclude '.git' \
        --exclude '.gitignore' \
        --exclude 'node_modules' \
        --exclude '.DS_Store' \
        --exclude 'Thumbs.db' \
        $LOCAL_PATH/ $SERVER:$REMOTE_PATH/
    
    if [ $? -eq 0 ]; then
        print_success "تم رفع الملفات بنجاح"
    else
        print_error "فشل في رفع الملفات"
        exit 1
    fi
}

# إعداد السيرفر
setup_server() {
    print_message "إعداد السيرفر..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # تثبيت التبعيات
        composer install --optimize-autoloader --no-dev --no-interaction
        
        # إنشاء مجلدات التخزين
        mkdir -p storage/logs
        mkdir -p storage/framework/cache
        mkdir -p storage/framework/sessions
        mkdir -p storage/framework/views
        mkdir -p bootstrap/cache
        
        # تعيين الصلاحيات
        chmod -R 755 storage bootstrap/cache
        chown -R www-data:www-data storage bootstrap/cache
        
        # إنشاء ملف .env إذا لم يكن موجوداً
        if [ ! -f .env ]; then
            cp .env.example .env
            echo "تم إنشاء ملف .env"
        fi
        
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
        
        echo "تم إعداد السيرفر بنجاح"
EOF
    
    if [ $? -eq 0 ]; then
        print_success "تم إعداد السيرفر بنجاح"
    else
        print_error "فشل في إعداد السيرفر"
        exit 1
    fi
}

# تشغيل المايجريشن
run_migrations() {
    print_message "تشغيل المايجريشن..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        php artisan migrate --force
        
        # تشغيل السيدرات إذا طُلب ذلك
        read -p "هل تريد تشغيل السيدرات؟ (y/n): " run_seeders
        if [ "\$run_seeders" = "y" ] || [ "\$run_seeders" = "Y" ]; then
            php artisan db:seed --force
            echo "تم تشغيل السيدرات"
        fi
EOF
    
    print_success "تم تشغيل المايجريشن"
}

# اختبار النظام
test_system() {
    print_message "اختبار النظام..."
    
    # اختبار الاتصال
    response=$(curl -s -o /dev/null -w "%{http_code}" https://system.growup-studio.com)
    
    if [ "$response" = "200" ]; then
        print_success "النظام يعمل بشكل صحيح (HTTP $response)"
    else
        print_warning "استجابة غير متوقعة: HTTP $response"
    fi
}

# النسخ الاحتياطي
backup_database() {
    print_message "إنشاء نسخة احتياطية من قاعدة البيانات..."
    
    ssh $SERVER << EOF
        cd $REMOTE_PATH
        
        # قراءة إعدادات قاعدة البيانات من .env
        DB_NAME=\$(grep DB_DATABASE .env | cut -d '=' -f2 | tr -d ' ')
        DB_USER=\$(grep DB_USERNAME .env | cut -d '=' -f2 | tr -d ' ')
        DB_PASS=\$(grep DB_PASSWORD .env | cut -d '=' -f2 | tr -d ' ')
        
        if [ ! -z "\$DB_NAME" ] && [ ! -z "\$DB_USER" ]; then
            mysqldump -u \$DB_USER -p\$DB_PASS \$DB_NAME > backup_\$(date +%Y%m%d_%H%M%S).sql
            echo "تم إنشاء النسخة الاحتياطية"
        else
            echo "تعذر قراءة إعدادات قاعدة البيانات"
        fi
EOF
    
    print_success "تم إنشاء النسخة الاحتياطية"
}

# الدالة الرئيسية
main() {
    echo "=========================================="
    echo "🚀 Solvesta Management System Deployment"
    echo "=========================================="
    echo
    
    # التحقق من المتطلبات
    check_requirements
    
    # إنشاء نسخة احتياطية
    read -p "هل تريد إنشاء نسخة احتياطية من قاعدة البيانات؟ (y/n): " backup_choice
    if [ "$backup_choice" = "y" ] || [ "$backup_choice" = "Y" ]; then
        backup_database
    fi
    
    # إعداد البيئة المحلية
    setup_local
    
    # رفع الملفات
    upload_files
    
    # إعداد السيرفر
    setup_server
    
    # تشغيل المايجريشن
    read -p "هل تريد تشغيل المايجريشن؟ (y/n): " migrate_choice
    if [ "$migrate_choice" = "y" ] || [ "$migrate_choice" = "Y" ]; then
        run_migrations
    fi
    
    # اختبار النظام
    test_system
    
    echo
    echo "=========================================="
    print_success "تم رفع النظام بنجاح!"
    echo "🌐 الموقع: https://system.growup-studio.com"
    echo "=========================================="
}

# تشغيل السكريبت
main "$@"
