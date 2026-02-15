<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserPermission;

class ClearCustomPermissions extends Command
{
    protected $signature = 'permissions:clear-custom';
    protected $description = 'Clear all custom user permissions';

    public function handle()
    {
        $count = UserPermission::count();
        
        if ($count === 0) {
            $this->info('✓ لا توجد صلاحيات مخصصة لحذفها.');
            return 0;
        }
        
        $this->warn("تم العثور على {$count} صلاحية مخصصة.");
        
        if ($this->confirm('هل تريد حذف جميع الصلاحيات المخصصة؟ سيتم استخدام صلاحيات الأدوار فقط.')) {
            UserPermission::truncate();
            $this->info("✓ تم حذف {$count} صلاحية مخصصة بنجاح!");
            $this->comment('جميع المستخدمين الآن يستخدمون صلاحيات أدوارهم فقط.');
            
            // Clear cache
            $this->call('cache:clear');
            $this->call('view:clear');
            
            $this->newLine();
            $this->info('✓ تم مسح الـ Cache أيضاً.');
        } else {
            $this->comment('تم الإلغاء.');
        }
        
        return 0;
    }
}








