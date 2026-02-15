<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class CleanTestNotifications extends Command
{
    protected $signature = 'notifications:clean';
    protected $description = 'Clean test notifications';

    public function handle()
    {
        $count = Notification::where('data->test', true)->count();
        
        if ($count === 0) {
            $this->info('لا توجد إشعارات تجريبية لحذفها.');
            return 0;
        }
        
        $this->warn("تم العثور على {$count} إشعار تجريبي.");
        
        if ($this->confirm('هل تريد حذف جميع الإشعارات التجريبية؟')) {
            Notification::where('data->test', true)->delete();
            $this->info("✓ تم حذف {$count} إشعار تجريبي بنجاح!");
        } else {
            $this->comment('تم الإلغاء.');
        }
        
        return 0;
    }
}