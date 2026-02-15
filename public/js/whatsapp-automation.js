/**
 * WhatsApp Automation System
 * نظام إرسال رسائل الواتساب التلقائي
 */

// إعدادات الواتساب
const WHATSAPP_CONFIG = {
    defaultNumber: '201044610510',
    baseUrl: 'https://web.whatsapp.com/send',
    phonePrefix: '20', // مصر
    autoOpen: true,
    delay: 1000 // تأخير 1 ثانية بين العمليات
};

/**
 * فتح الواتساب على جهة اتصال مباشرة (بدون رسالة)
 * @param {string} phoneNumber - رقم الهاتف
 * @param {string} contactName - اسم جهة الاتصال (اختياري)
 */
function openWhatsAppContact(phoneNumber, contactName = 'جهة الاتصال') {
    try {
        // استخدام الرقم الافتراضي إذا لم يتم توفير رقم
        const targetNumber = phoneNumber || WHATSAPP_CONFIG.defaultNumber;
        
        // تنظيف الرقم من المسافات والرموز
        const cleanNumber = targetNumber.replace(/[^\d]/g, '');
        
        // إضافة رمز البلد إذا لم يكن موجوداً
        const formattedNumber = cleanNumber.startsWith('20') ? cleanNumber : '20' + cleanNumber;
        
        // إنشاء رابط الواتساب بدون رسالة
        const whatsappUrl = `${WHATSAPP_CONFIG.baseUrl}?phone=${formattedNumber}`;
        
        // فتح الواتساب مباشرة
        const whatsappWindow = window.open(whatsappUrl, '_blank');
        
        if (whatsappWindow) {
            showSuccess(`تم فتح الواتساب مع ${contactName} (${formattedNumber})`);
        } else {
            showError('لم يتم فتح الواتساب. تأكد من السماح بالنوافذ المنبثقة.');
        }
        
    } catch (error) {
        console.error('خطأ في فتح الواتساب:', error);
        showError('حدث خطأ في فتح الواتساب');
    }
}

/**
 * إرسال رسالة إلى الواتساب
 * @param {string} recipientName - اسم المستقبل
 * @param {string} subject - موضوع الرسالة
 * @param {string} message - محتوى الرسالة
 * @param {string} phoneNumber - رقم الهاتف (اختياري)
 */
function sendToWhatsApp(recipientName, subject, message, phoneNumber = null) {
    try {
        // استخدام الرقم الافتراضي إذا لم يتم توفير رقم
        const targetNumber = phoneNumber || WHATSAPP_CONFIG.defaultNumber;
        
        // تنظيف الرقم من المسافات والرموز
        const cleanNumber = targetNumber.replace(/[^\d]/g, '');
        
        // إضافة رمز البلد إذا لم يكن موجوداً
        const formattedNumber = cleanNumber.startsWith('20') ? cleanNumber : '20' + cleanNumber;
        
        // إنشاء النص المرسل
        const fullMessage = createWhatsAppMessage(recipientName, subject, message);
        
        // تشفير النص للـ URL
        const encodedMessage = encodeURIComponent(fullMessage);
        
        // إنشاء رابط الواتساب
        const whatsappUrl = `${WHATSAPP_CONFIG.baseUrl}?phone=${formattedNumber}&text=${encodedMessage}`;
        
        // إظهار نافذة تأكيد
        showWhatsAppConfirmation(recipientName, formattedNumber, fullMessage, whatsappUrl);
        
    } catch (error) {
        console.error('خطأ في إرسال رسالة الواتساب:', error);
        showError('حدث خطأ في إعداد رسالة الواتساب');
    }
}

/**
 * إنشاء نص الرسالة للواتساب
 */
function createWhatsAppMessage(recipientName, subject, message) {
    const timestamp = new Date().toLocaleString('ar-SA');
    
    return `📧 *رسالة من نظام إدارة الشركة*

👤 *إلى:* ${recipientName}
📅 *التاريخ:* ${timestamp}
📋 *الموضوع:* ${subject}

💬 *المحتوى:*
${message}

---
📱 *مرسلة تلقائياً من نظام إدارة الشركة*
🔗 *رقم المرسل:* ${WHATSAPP_CONFIG.defaultNumber}`;
}

/**
 * إظهار نافذة تأكيد الإرسال
 */
function showWhatsAppConfirmation(recipientName, phoneNumber, message, whatsappUrl) {
    // إنشاء نافذة التأكيد
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 shadow-2xl">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">إرسال رسالة واتساب</h3>
                <p class="text-gray-600">سيتم إرسال الرسالة إلى:</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="text-sm text-gray-600 mb-2">
                    <strong>المستقبل:</strong> ${recipientName}
                </div>
                <div class="text-sm text-gray-600 mb-2">
                    <strong>رقم الهاتف:</strong> ${phoneNumber}
                </div>
                <div class="text-sm text-gray-600">
                    <strong>محتوى الرسالة:</strong>
                </div>
                <div class="mt-2 text-xs text-gray-500 bg-white p-2 rounded border max-h-32 overflow-y-auto">
                    ${message.substring(0, 200)}${message.length > 200 ? '...' : ''}
                </div>
            </div>
            
            <div class="flex space-x-3">
                <button onclick="this.closest('.fixed').remove()" 
                        class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    إلغاء
                </button>
                <button onclick="openWhatsApp('${whatsappUrl}'); this.closest('.fixed').remove();" 
                        class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center">
                    <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    فتح الواتساب
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

/**
 * فتح الواتساب في نافذة جديدة
 */
function openWhatsApp(url) {
    try {
        // فتح الواتساب في نافذة جديدة
        const whatsappWindow = window.open(url, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
        
        if (whatsappWindow) {
            // إظهار رسالة نجاح
            showSuccess('تم فتح الواتساب بنجاح! يمكنك الآن إرسال الرسالة.');
            
            // إضافة تأخير ثم محاولة إرسال الرسالة تلقائياً
            setTimeout(() => {
                try {
                    // محاولة إرسال الرسالة تلقائياً
                    whatsappWindow.focus();
                } catch (error) {
                    console.log('لا يمكن الوصول إلى نافذة الواتساب');
                }
            }, WHATSAPP_CONFIG.delay);
        } else {
            showError('لم يتم فتح الواتساب. تأكد من السماح بالنوافذ المنبثقة.');
        }
    } catch (error) {
        console.error('خطأ في فتح الواتساب:', error);
        showError('حدث خطأ في فتح الواتساب');
    }
}

/**
 * إظهار رسالة نجاح
 */
function showSuccess(message) {
    showNotification(message, 'success');
}

/**
 * إظهار رسالة خطأ
 */
function showError(message) {
    showNotification(message, 'error');
}

/**
 * إظهار إشعار
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // إظهار الإشعار
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // إزالة الإشعار بعد 5 ثوان
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

/**
 * تحديث إعدادات الواتساب من الخادم
 */
function updateWhatsAppSettings() {
    fetch('/api/whatsapp-settings')
        .then(response => response.json())
        .then(data => {
            if (data.defaultNumber) {
                WHATSAPP_CONFIG.defaultNumber = data.defaultNumber;
            }
            if (data.autoOpen !== undefined) {
                WHATSAPP_CONFIG.autoOpen = data.autoOpen;
            }
        })
        .catch(error => {
            console.log('لا يمكن تحديث إعدادات الواتساب:', error);
        });
}

// تحديث الإعدادات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateWhatsAppSettings();
});

// تصدير الدوال للاستخدام العام
window.sendToWhatsApp = sendToWhatsApp;
window.openWhatsApp = openWhatsApp;
window.openWhatsAppContact = openWhatsAppContact;

