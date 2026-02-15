<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$email = 'codermohamedhany@gmail.com';
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ المستخدم غير موجود: $email\n";
    exit(1);
}

// إعطاء دور super_admin
$superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
$user->syncRoles([$superAdminRole]);

// إعطاء جميع الصلاحيات
$allPermissions = Permission::all();
$user->syncPermissions($allPermissions);

echo "✅ تم منح جميع الصلاحيات للمستخدم: {$user->name} ({$user->email})\n";
echo "   - الدور: super_admin\n";
echo "   - عدد الصلاحيات: " . $allPermissions->count() . "\n\n";

// التحقق من الصلاحيات الثلاثة
$permissionsToCheck = ['view-clients', 'view-bugs', 'view-tickets'];

echo "🔍 التحقق من الصلاحيات:\n";
foreach ($permissionsToCheck as $permission) {
    $hasPermission = $user->can($permission);
    $status = $hasPermission ? '✅' : '❌';
    echo "   $status $permission: " . ($hasPermission ? 'لديه الصلاحية' : 'لا يملك الصلاحية') . "\n";
}

echo "\n📊 إجمالي الصلاحيات: " . $user->getAllPermissions()->count() . "\n";

