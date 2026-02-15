<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Notification;

class TestNotifications extends Command
{
    protected $signature = 'notifications:test {user_id?}';
    protected $description = 'Test notifications encoding';

    public function handle()
    {
        $userId = $this->argument('user_id') ?: 3; // Default to user ID 3
        
        $user = User::find($userId);
        if (!$user) {
            $this->error("المستخدم بـ ID {$userId} غير موجود!");
            return 1;
        }

        $this->info("اختبار الإشعارات للمستخدم: {$user->name}");
        $this->newLine();

        // إنشاء إشعار تجريبي
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => 'message',
            'title' => 'رسالة تجريبية',
            'message' => 'هذه رسالة تجريبية لاختبار الترميز العربي',
            'data' => ['test' => true],
            'is_read' => false
        ]);

        $this->info("تم إنشاء إشعار تجريبي:");
        $this->line("  العنوان: {$notification->title}");
        $this->line("  الرسالة: {$notification->message}");
        $this->line("  النوع: {$notification->type}");
        $this->newLine();

        // اختبار JSON encoding
        $this->info("اختبار JSON Encoding:");
        $jsonData = json_encode([
            'notifications' => [$notification],
            'unread_count' => 1
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        $this->line($jsonData);
        $this->newLine();

        // اختبار API endpoint
        $this->info("اختبار API endpoint:");
        $this->comment("يمكنك الآن زيارة: /notifications");
        $this->comment("أو استخدام curl:");
        $this->comment("curl -H 'Accept: application/json' -H 'Content-Type: application/json; charset=utf-8' http://localhost/notifications");

        return 0;
    }
}