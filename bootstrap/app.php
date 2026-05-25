<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'verified.code' => \App\Http\Middleware\EnsureVerifiedCode::class,
            'secure.auth' => \App\Http\Middleware\SecureAuth::class,
            'block.suspicious' => \App\Http\Middleware\BlockSuspiciousActivity::class,
            'department.manager' => \App\Http\Middleware\EnsureDepartmentManager::class,
            'client.section' => \App\Http\Middleware\EnsureClientPortalSection::class,
        ]);
        
        // Global security middleware
        $middleware->web([
            \App\Http\Middleware\VerifyCsrfToken::class,
            \App\Http\Middleware\SecureAuth::class,
            \App\Http\Middleware\BlockSuspiciousActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'انتهت صلاحية الصفحة. حدّث الصفحة وحاول مرة أخرى.'], 419);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'انتهت صلاحية الصفحة (جلسة منتهية). حدّث الصفحة ثم أعد الإرسال.');
        });
    })->create();
