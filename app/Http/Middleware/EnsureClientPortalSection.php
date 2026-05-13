<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClientPortalSection
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $section  billing|technical_requests
     */
    public function handle(Request $request, Closure $next, string $section): Response
    {
        $account = $request->user('client');
        if (! $account) {
            abort(403);
        }

        $allowed = match ($section) {
            'billing' => $account->canAccessBilling(),
            'technical_requests' => $account->canAccessTechnicalRequests(),
            default => true,
        };

        if (! $allowed) {
            abort(403, 'لا تملك صلاحية الوصول لهذا القسم.');
        }

        return $next($request);
    }
}
