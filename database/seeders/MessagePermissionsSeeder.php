<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MessagePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء صلاحيات الرسائل
        $permissions = [
            'view-messages',
            'create-messages',
            'edit-messages',
            'delete-messages',
            'send-messages',
            'reply-messages',
            'mark-messages-important',
            'view-all-messages',
            'send-announcements',
            'send-group-messages',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // تعيين الصلاحيات للأدوار
        $roles = [
            'super_admin' => [
                'view-messages',
                'create-messages',
                'edit-messages',
                'delete-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
                'view-all-messages',
                'send-announcements',
                'send-group-messages',
            ],
            'admin' => [
                'view-messages',
                'create-messages',
                'edit-messages',
                'delete-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
                'view-all-messages',
                'send-announcements',
                'send-group-messages',
            ],
            'project_manager' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
                'send-group-messages',
            ],
            'hr' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
                'send-announcements',
            ],
            'employee' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
            ],
            'developer' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
            ],
            'designer' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
            ],
            'accountant' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
            ],
            'sales_rep' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
            ],
            'support' => [
                'view-messages',
                'create-messages',
                'send-messages',
                'reply-messages',
                'mark-messages-important',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->givePermissionTo($rolePermissions);
            }
        }
    }
}