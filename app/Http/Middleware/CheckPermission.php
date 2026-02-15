<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // دعم صلاحيات متعددة باستخدام | (OR logic)
        if (str_contains($permission, '|')) {
            $permissions = explode('|', $permission);
            $hasPermission = false;
            
            foreach ($permissions as $perm) {
                if (auth()->user()->can(trim($perm))) {
                    $hasPermission = true;
                    break;
                }
            }
            
            if (!$hasPermission) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }
        } else {
            // صلاحية واحدة فقط
            if (!auth()->user()->can($permission)) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }
        }

        return $next($request);
    }
}