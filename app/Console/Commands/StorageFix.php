<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class StorageFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix {--force : Force recreation of symlink}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix storage directories and create symbolic link';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 بدء إصلاح مجلدات التخزين والمسارات...');
        $this->newLine();

        // 1. إنشاء المجلدات المطلوبة
        $this->info('📁 التحقق من المجلدات المطلوبة...');
        $this->createRequiredDirectories();

        // 2. إنشاء أو إعادة إنشاء symlink
        $this->info('🔗 إنشاء Symbolic Link...');
        $this->createStorageLink();

        // 3. ضبط الصلاحيات (Linux/Unix فقط)
        if (!$this->isWindows()) {
            $this->info('🔒 ضبط صلاحيات المجلدات...');
            $this->setPermissions();
        }

        // 4. التحقق من وجود الملفات
        $this->info('✅ التحقق من البنية...');
        $this->verifyStructure();

        $this->newLine();
        $this->info('✨ تم إصلاح جميع المسارات بنجاح!');
        $this->newLine();
        
        $this->line('📝 ملاحظات مهمة:');
        $this->line('   • تأكد من رفع مجلد storage/app/public كامل عند رفع النظام');
        $this->line('   • قم بتشغيل هذا الأمر على السيرفر بعد الرفع: php artisan storage:fix');
        $this->line('   • الصور ستعمل تلقائياً حتى بدون symlink بفضل الـ route البديل');

        return 0;
    }

    /**
     * إنشاء المجلدات المطلوبة
     */
    protected function createRequiredDirectories()
    {
        $directories = [
            storage_path('app/public'),
            storage_path('app/public/system'),
            storage_path('app/public/profile-pictures'),
            storage_path('app/public/documents'),
            storage_path('app/private'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->line("   ✓ تم إنشاء: " . str_replace(storage_path(), 'storage', $directory));
            } else {
                $this->line("   ✓ موجود: " . str_replace(storage_path(), 'storage', $directory));
            }
        }
    }

    /**
     * إنشاء Symbolic Link
     */
    protected function createStorageLink()
    {
        $target = storage_path('app/public');
        $link = public_path('storage');

        // إذا كان symlink موجوداً وفعالاً
        if (File::exists($link) && is_link($link)) {
            if ($this->option('force')) {
                $this->line('   ⚠ إزالة symlink القديم...');
                if ($this->isWindows()) {
                    rmdir($link);
                } else {
                    unlink($link);
                }
            } else {
                $this->line('   ✓ Symbolic link موجود بالفعل');
                return;
            }
        } elseif (File::exists($link)) {
            // إذا كان مجلد عادي
            $this->warn('   ⚠ يوجد مجلد عادي باسم storage، يرجى حذفه يدوياً');
            $this->line('   أو استخدم: php artisan storage:fix --force');
            return;
        }

        // إنشاء symlink جديد
        try {
            if ($this->isWindows()) {
                // على Windows استخدم mklink
                $this->line('   ℹ نظام Windows مكتشف - استخدام mklink...');
                $command = 'mklink /D "' . $link . '" "' . $target . '"';
                exec($command, $output, $returnVar);
                
                if ($returnVar === 0) {
                    $this->line('   ✓ تم إنشاء symbolic link بنجاح');
                } else {
                    $this->error('   ✗ فشل إنشاء symlink. قد تحتاج صلاحيات المسؤول');
                    $this->line('   💡 الحل البديل: النظام سيستخدم route مباشر لعرض الصور');
                }
            } else {
                // على Linux/Unix
                symlink($target, $link);
                $this->line('   ✓ تم إنشاء symbolic link بنجاح');
            }
        } catch (\Exception $e) {
            $this->error('   ✗ خطأ في إنشاء symlink: ' . $e->getMessage());
            $this->line('   💡 لا تقلق: النظام سيستخدم route مباشر لعرض الصور');
        }
    }

    /**
     * ضبط الصلاحيات (Linux/Unix فقط)
     */
    protected function setPermissions()
    {
        $directories = [
            storage_path(),
            storage_path('app'),
            storage_path('app/public'),
            storage_path('framework'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($directories as $directory) {
            if (File::exists($directory)) {
                try {
                    chmod($directory, 0755);
                    $this->line("   ✓ تم ضبط صلاحيات: " . basename($directory));
                } catch (\Exception $e) {
                    $this->warn("   ⚠ تعذر ضبط صلاحيات: " . basename($directory));
                }
            }
        }
    }

    /**
     * التحقق من البنية
     */
    protected function verifyStructure()
    {
        $checks = [
            'storage/app/public' => storage_path('app/public'),
            'storage/app/private' => storage_path('app/private'),
            'public/storage (symlink أو route)' => public_path('storage'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        foreach ($checks as $name => $path) {
            if (File::exists($path)) {
                $this->line("   ✓ $name");
            } else {
                $this->warn("   ✗ $name - غير موجود!");
            }
        }
    }

    /**
     * التحقق من نظام التشغيل
     */
    protected function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}








