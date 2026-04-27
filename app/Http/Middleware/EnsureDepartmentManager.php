<?php

namespace App\Http\Middleware;

use App\Policies\DepartmentAccess;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDepartmentManager
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !DepartmentAccess::isDepartmentManager($user)) {
            abort(403, 'غير مصرح: هذه الصفحة لمدير القسم فقط.');
        }

        return $next($request);
    }
}

