<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        
        $stats = [
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
            'total_users' => User::count(),
        ];

        return view('roles.index', compact('roles', 'permissions', 'stats'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // حذف جميع الصلاحيات المخصصة عند تغيير الدور
        // لأن الصلاحيات الجديدة ستأتي من الدور الجديد
        \App\Models\UserPermission::where('user_id', $user->id)->delete();

        $user->syncRoles([$request->role]);
        
        // مسح الكاش للتأكد من تطبيق التغييرات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->back()->with('success', 'تم تعيين الدور الوظيفي بنجاح');
    }

    public function assignCustomPermissions(Request $request, User $user)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        // الحصول على جميع الصلاحيات المتاحة
        $allPermissions = Permission::pluck('name')->toArray();
        $selectedPermissions = $request->permissions ?? [];
        
        // الحصول على صلاحيات الدور الافتراضية
        $rolePermissions = [];
        if ($user->roles->first()) {
            $rolePermissions = $user->roles->first()->permissions->pluck('name')->toArray();
        }
        
        // حذف جميع الصلاحيات المخصصة القديمة من user_permissions
        \App\Models\UserPermission::where('user_id', $user->id)->delete();
        
        // معالجة كل صلاحية
        foreach ($allPermissions as $permission) {
            $isSelected = in_array($permission, $selectedPermissions);
            $isFromRole = in_array($permission, $rolePermissions);
            
            if ($isSelected) {
                // الصلاحية مُحددة (مفعلة)
                if ($isFromRole) {
                    // الصلاحية من الدور وتم تحديدها
                    // لا نحتاج لحفظها في user_permissions لأنها تأتي تلقائياً من الدور
                    // لكن إذا كانت معطلة سابقاً، نحذف السجل المعطل
                    \App\Models\UserPermission::where('user_id', $user->id)
                                             ->where('permission_key', $permission)
                                             ->delete();
                    
                    // نضمن أنها موجودة في Spatie (يجب أن تكون موجودة من الدور)
                    if (!$user->hasPermissionTo($permission)) {
                        $user->givePermissionTo($permission);
                    }
                } else {
                    // الصلاحية ليست من الدور ولكن تم تحديدها
                    // نضيفها مباشرة إلى Spatie
                    $user->givePermissionTo($permission);
                    
                    // نحفظها في user_permissions كمفعلة (للتحكم الإضافي)
                    \App\Models\UserPermission::create([
                        'user_id' => $user->id,
                        'permission_key' => $permission,
                        'is_enabled' => true,
                    ]);
                }
            } else {
                // الصلاحية غير مُحددة (معطلة)
                if ($isFromRole) {
                    // الصلاحية من الدور ولكن تم إلغاء تحديدها
                    // نحفظها كمعطلة في user_permissions لتجاوز صلاحيات الدور
                    \App\Models\UserPermission::create([
                        'user_id' => $user->id,
                        'permission_key' => $permission,
                        'is_enabled' => false,
                    ]);
                } else {
                    // الصلاحية ليست من الدور وتم إلغاء تحديدها
                    // نزيلها من Spatie إذا كانت موجودة
                    if ($user->hasPermissionTo($permission)) {
                        $user->revokePermissionTo($permission);
                    }
                    
                    // نحذفها من user_permissions إذا كانت موجودة
                    \App\Models\UserPermission::where('user_id', $user->id)
                                             ->where('permission_key', $permission)
                                             ->delete();
                }
            }
        }
        
        // مسح الكاش للتأكد من تطبيق التغييرات
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->back()->with('success', 'تم تحديث الصلاحيات بنجاح');
    }

    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->back()->with('success', 'تم تحديث صلاحيات الدور بنجاح');
    }

    public function userPermissions(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRole = $user->roles->first();
        
        // الحصول على صلاحيات الدور الافتراضية
        $rolePermissions = $userRole ? $userRole->permissions->pluck('name')->toArray() : [];
        
        // الحصول على جميع الصلاحيات الفعلية للمستخدم (من Spatie مباشرة)
        $userDirectPermissions = $user->getAllPermissions()->pluck('name')->toArray();
        
        // الحصول على الصلاحيات المخصصة من جدول user_permissions (للتحكم في إلغاء التفعيل)
        $allCustomPermissions = \App\Models\UserPermission::where('user_id', $user->id)->get();
        $customPermissionsMap = [];
        
        foreach ($allCustomPermissions as $cp) {
            // التحقق من أن الصلاحية موجودة في جدول permissions (Spatie permissions)
            $isSpatiePermission = Permission::where('name', $cp->permission_key)->exists();
            
            if ($isSpatiePermission) {
                // إذا كانت الصلاحية من صلاحيات Spatie
                $customPermissionsMap[$cp->permission_key] = $cp->is_enabled;
            }
        }
        
        // دمج الصلاحيات: نستخدم الصلاحيات الفعلية من Spatie
        // الصلاحيات المخصصة (user_permissions) تتجاوز الصلاحيات الفعلية
        $userPermissions = [];
        foreach ($permissions as $permission) {
            $permName = $permission->name;
            
            // إذا كانت هناك صلاحية مخصصة (user_permissions)، نستخدمها
            if (isset($customPermissionsMap[$permName])) {
                if ($customPermissionsMap[$permName]) {
                    $userPermissions[] = $permName;
                }
                // إذا كانت معطلة في user_permissions، لا نضيفها
            } 
            // إذا لم توجد صلاحية مخصصة، نستخدم الصلاحية الفعلية من Spatie
            elseif (in_array($permName, $userDirectPermissions)) {
                $userPermissions[] = $permName;
            }
        }

        return view('roles.user-permissions', compact('user', 'roles', 'permissions', 'userRole', 'userPermissions', 'rolePermissions', 'customPermissionsMap'));
    }

    private function getDefaultPermissionForRole($user, $permissionKey)
    {
        $userRoles = $user->roles->pluck('name')->toArray();
        
        // Super Admin - جميع الصلاحيات
        if (in_array('super_admin', $userRoles)) {
            return true;
        }
        
        // Admin - معظم الصلاحيات
        if (in_array('admin', $userRoles)) {
            return true;
        }
        
        // Manager - صلاحيات إدارية محدودة
        if (in_array('manager', $userRoles)) {
            $managerPermissions = [
                'sidebar_dashboard',
                'sidebar_projects',
                'sidebar_operations',
                'sidebar_tasks',
                'sidebar_clients',
                'sidebar_sales',
                'sidebar_reports',
                'sidebar_training',
                'sidebar_meetings',
            ];
            return in_array($permissionKey, $managerPermissions);
        }
        
        // HR - صلاحيات موارد بشرية
        if (in_array('hr', $userRoles)) {
            $hrPermissions = [
                'sidebar_dashboard',
                'sidebar_hr',
                'sidebar_users',
                'sidebar_employees',
                'sidebar_attendances',
                'sidebar_leaves',
                'sidebar_salaries',
                'sidebar_reports',
                'sidebar_training',
                'sidebar_meetings',
            ];
            return in_array($permissionKey, $hrPermissions);
        }
        
        // Employee - صلاحيات أساسية
        if (in_array('employee', $userRoles)) {
            $employeePermissions = [
                'sidebar_dashboard',
                'sidebar_tasks',
                'sidebar_projects_list',
                'sidebar_clients',
                'sidebar_training',
                'sidebar_meetings',
            ];
            return in_array($permissionKey, $employeePermissions);
        }
        
        // افتراضياً - لا توجد صلاحيات
        return false;
    }
}
