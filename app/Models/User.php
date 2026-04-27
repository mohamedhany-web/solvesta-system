<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the employee record associated with the user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function customPermissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Get verification codes for the user.
     */
    public function verificationCodes(): HasMany
    {
        return $this->hasMany(VerificationCode::class);
    }

    /**
     * Override the can() method to check UserPermission first, then fall back to Spatie permissions
     * This ensures that custom permission overrides take precedence
     */
    public function can($permission, $guard = null)
    {
        // أولاً: التحقق من الصلاحيات المخصصة في جدول user_permissions
        $customPermission = $this->customPermissions()
                                  ->where('permission_key', $permission)
                                  ->first();
        
        // إذا كانت هناك صلاحية مخصصة محفوظة، نستخدمها مباشرة
        // إذا كانت معطلة (is_enabled = false)، نمنع الوصول حتى لو كان لديه صلاحية من Spatie
        if ($customPermission) {
            return $customPermission->is_enabled;
        }
        
        // إذا لم تكن هناك صلاحية مخصصة، نستخدم طريقة Spatie الافتراضية
        // نستخدم hasPermissionTo من Spatie مباشرة لضمان التحقق الصحيح
        return $this->hasPermissionTo($permission, $guard);
    }

    /**
     * Check if user has custom permission
     */
    public function hasCustomPermission($permissionKey)
    {
        // التحقق من الصلاحيات المخصصة المحفوظة أولاً
        $permission = $this->customPermissions()
                          ->where('permission_key', $permissionKey)
                          ->first();
        
        // إذا كانت الصلاحية موجودة في user_permissions، نستخدمها مباشرة
        // هذا يعني أن المستخدم قام بتعيين الصلاحية بشكل صريح (مفعّلة أو معطلة)
        if ($permission) {
            return $permission->is_enabled;
        }
        
        // إذا لم تكن موجودة، نتحقق من الصلاحيات الافتراضية للدور
        // نحصل على الصلاحيات الافتراضية من UserPermissionSeeder
        $userRoles = $this->roles->pluck('name')->toArray();
        
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
                'sidebar_dashboard', 'sidebar_projects', 'sidebar_operations', 'sidebar_tasks',
                'sidebar_clients', 'sidebar_sales', 'sidebar_reports', 'sidebar_training', 'sidebar_meetings',
            ];
            return in_array($permissionKey, $managerPermissions);
        }
        
        // HR - صلاحيات موارد بشرية
        if (in_array('hr', $userRoles)) {
            $hrPermissions = [
                'sidebar_dashboard', 'sidebar_hr', 'sidebar_users', 'sidebar_employees',
                'sidebar_attendances', 'sidebar_leaves', 'sidebar_salaries', 'sidebar_reports',
                'sidebar_training', 'sidebar_meetings',
            ];
            return in_array($permissionKey, $hrPermissions);
        }
        
        // Employee - صلاحيات أساسية
        if (in_array('employee', $userRoles)) {
            $employeePermissions = [
                'sidebar_dashboard', 'sidebar_tasks', 'sidebar_projects_list', 'sidebar_clients',
                'sidebar_training', 'sidebar_meetings',
            ];
            return in_array($permissionKey, $employeePermissions);
        }
        
        // Project Manager
        if (in_array('project_manager', $userRoles)) {
            $pmPermissions = [
                'sidebar_dashboard', 'sidebar_projects', 'sidebar_tasks', 'sidebar_bugs', 'sidebar_qa',
                'sidebar_training', 'sidebar_meetings',
            ];
            return in_array($permissionKey, $pmPermissions);
        }
        
        // Developer
        if (in_array('developer', $userRoles)) {
            $devPermissions = [
                'sidebar_dashboard', 'sidebar_tasks', 'sidebar_projects_list', 'sidebar_bugs', 'sidebar_qa',
                'sidebar_training', 'sidebar_meetings',
            ];
            return in_array($permissionKey, $devPermissions);
        }
        
        // Designer
        if (in_array('designer', $userRoles)) {
            $designerPermissions = [
                'sidebar_dashboard', 'sidebar_tasks', 'sidebar_projects_list', 'sidebar_design',
                'sidebar_clients', 'sidebar_training', 'sidebar_meetings',
            ];
            return in_array($permissionKey, $designerPermissions);
        }
        
        // افتراضياً - لا توجد صلاحيات
        return false;
    }

    /**
     * Set custom permission for user
     */
    public function setCustomPermission($permissionKey, $isEnabled = true, $customSettings = null)
    {
        return UserPermission::setPermission($this->id, $permissionKey, $isEnabled, $customSettings);
    }
}
