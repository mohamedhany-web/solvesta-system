<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\UserPermission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // التحقق من الصلاحيات المخصصة على مستوى المستخدم قبل صلاحيات الأدوار
        Gate::before(function ($user, $ability) {
            // التحقق من وجود صلاحية مخصصة للمستخدم في جدول user_permissions
            $customPermission = UserPermission::where('user_id', $user->id)
                ->where('permission_key', $ability)
                ->first();
            
            // إذا كان هناك صلاحية مخصصة، نستخدمها (سواء كانت مفعلة أو معطلة)
            if ($customPermission) {
                return $customPermission->is_enabled ? true : false;
            }
            
            // إذا لم توجد صلاحية مخصصة، نترك Spatie يتعامل مع صلاحيات الأدوار
            return null; // null يعني متابعة الفحص العادي
        });
    }
}