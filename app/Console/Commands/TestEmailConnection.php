<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EmailService;

class TestEmailConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email connection and send a test verification code';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            $this->error('يجب توفير البريد الإلكتروني');
            return 1;
        }
        
        $this->info('جارٍ إرسال كود اختبار إلى: ' . $email);
        
        try {
            $emailService = new EmailService();
            $testCode = '123456';
            $sent = $emailService->sendVerificationCode($email, $testCode, 'مستخدم اختبار');
            
            if ($sent) {
                $this->info('تم إرسال الرسالة بنجاح!');
                $this->info('الكود: ' . $testCode);
            } else {
                $this->error('فشل إرسال الرسالة. راجع ملف storage/logs/laravel.log');
            }
            
            return $sent ? 0 : 1;
        } catch (\Exception $e) {
            $this->error('حدث خطأ: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}

