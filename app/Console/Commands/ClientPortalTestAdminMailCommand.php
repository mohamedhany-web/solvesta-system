<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class ClientPortalTestAdminMailCommand extends Command
{
    protected $signature = 'client-portal:test-admin-mail {email? : عنوان الاختبار (افتراضي: أول عنوان في CLIENT_PORTAL_ALERT_EMAIL)}';

    protected $description = 'إرسال بريد تجريبي للتحقق من إعدادات تنبيهات بوابة العميل';

    public function handle(): int
    {
        $fromConfig = config('client_portal.alert_emails', []);
        $to = $this->argument('email') ?: ($fromConfig[0] ?? null);

        if (! $to || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->error('حدد بريداً صالحاً أو عيّن CLIENT_PORTAL_ALERT_EMAIL في ملف .env');

            return self::FAILURE;
        }

        $subject = 'اختبار Solvesta — تنبيه بوابة العميل';
        $body = '<p dir="rtl">إذا وصلت هذه الرسالة، فإعداد البريد لتنبيهات الإدارة يعمل.</p>';

        $ok = (new EmailService)->sendEmail($to, $subject, $body, 'Solvesta');

        if ($ok) {
            $this->info('تم الإرسال إلى: '.$to);

            return self::SUCCESS;
        }

        $this->error('فشل الإرسال — راجع سجل Laravel وإعدادات SMTP في EmailService.');

        return self::FAILURE;
    }
}
