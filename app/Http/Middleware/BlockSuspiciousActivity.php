<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to block suspicious activity and IPs
 */
class BlockSuspiciousActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Check if IP is temporarily blocked
        if ($this->isIpBlocked($ip)) {
            Log::warning('Blocked IP attempted access', [
                'ip' => $ip,
                'route' => $request->route()->getName(),
                'url' => $request->fullUrl(),
            ]);

            abort(429, 'تم حظر عنوان IP الخاص بك مؤقتاً. يرجى المحاولة لاحقاً.');
        }

        // Detect suspicious patterns
        $this->detectSuspiciousActivity($request);

        // Check for SQL injection attempts
        if ($this->detectSqlInjection($request)) {
            $this->logSecurityEvent('sql_injection_attempt', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
                'inputs' => $this->sanitizeRequestData($request->all()),
            ]);

            // Block IP after SQL injection attempt
            self::blockIp($ip, 120);

            abort(403, 'طلب غير مسموح به.');
        }

        // Check for XSS attempts
        if ($this->detectXss($request)) {
            $this->logSecurityEvent('xss_attempt', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
                'inputs' => $this->sanitizeRequestData($request->all()),
            ]);

            // Block IP after XSS attempt
            self::blockIp($ip, 120);

            abort(403, 'طلب غير مسموح به.');
        }

        // Check for path traversal attempts
        if ($this->detectPathTraversal($request)) {
            $this->logSecurityEvent('path_traversal_attempt', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
            ]);

            abort(403, 'طلب غير مسموح به.');
        }

        // Check for command injection attempts
        if ($this->detectCommandInjection($request)) {
            $this->logSecurityEvent('command_injection_attempt', [
                'ip' => $ip,
                'url' => $request->fullUrl(),
                'inputs' => $this->sanitizeRequestData($request->all()),
            ]);

            self::blockIp($ip, 120);

            abort(403, 'طلب غير مسموح به.');
        }

        return $next($request);
    }

    /**
     * Check if IP is blocked.
     */
    protected function isIpBlocked(string $ip): bool
    {
        $blockKey = 'blocked_ip:' . md5($ip);
        
        return Cache::has($blockKey);
    }

    /**
     * Block an IP address.
     */
    public static function blockIp(string $ip, int $minutes = 60): void
    {
        $blockKey = 'blocked_ip:' . md5($ip);
        Cache::put($blockKey, true, now()->addMinutes($minutes));

        Log::channel('security')->critical('IP blocked', [
            'ip' => $ip,
            'blocked_until' => now()->addMinutes($minutes)->toDateTimeString(),
        ]);
    }

    /**
     * Detect suspicious activity patterns.
     */
    protected function detectSuspiciousActivity(Request $request): void
    {
        $ip = $request->ip();
        $route = $request->route()->getName();

        // Track failed authentication attempts
        if ($route === 'login' && $request->isMethod('post')) {
            $key = 'failed_auth:' . md5($ip);
            $attempts = RateLimiter::attempts($key);

            // If more than 10 failed attempts, block IP
            if ($attempts >= 10) {
                self::blockIp($ip, 60);
            }
        }
    }

    /**
     * Detect SQL injection attempts.
     */
    protected function detectSqlInjection(Request $request): bool
    {
        $patterns = [
            '/(\s|^)(union|select|insert|update|delete|drop|create|alter|exec|execute)\s+/i',
            '/(\s|^)(or|and)\s+\d+\s*=\s*\d+/i',
            '/(\s|^)(or|and)\s+[\'"]\s*=\s*[\'"]/i',
            '/\'\s*or\s*\'\s*=\s*\'/i',
            '#"\s*or\s*"\s*=\s*"#i',
            '/\b(\d+\s*=\s*\d+)\b/i',
            '/--/',
            '#\/\*#',
            '#\*\/#',
            '/waitfor\s+delay/i',
            '/sleep\s*\(/i',
            '/benchmark\s*\(/i',
        ];

        $allInputs = array_merge(
            $request->all(),
            ['url' => $request->fullUrl()]
        );

        foreach ($allInputs as $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Detect XSS attempts.
     */
    protected function detectXss(Request $request): bool
    {
        $patterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/<link/i',
            '/<meta/i',
            '/expression\s*\(/i',
            '/vbscript:/i',
            '/data:text\/html/i',
        ];

        $allInputs = $request->all();

        foreach ($allInputs as $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Sanitize request data for logging (remove sensitive info).
     */
    protected function sanitizeRequestData(array $data): array
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'code', '_token'];

        foreach ($sensitiveKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***REDACTED***';
            }
        }

        return $data;
    }

    /**
     * Detect path traversal attempts.
     */
    protected function detectPathTraversal(Request $request): bool
    {
        $patterns = [
            '/\.\.\//',
            '/\.\.\\\\/',
            '/\.\.\%2f/i',
            '/\.\.\%5c/i',
            '/\.\.\%252f/i',
            '/\.\.\%255c/i',
            '/\.\.\%c0%af/i',
            '/\.\.\%c1%9c/i',
            '/\/etc\/passwd/i',
            '/\/proc\/self\/environ/i',
            '/\/windows\/system32/i',
        ];

        $allInputs = array_merge(
            $request->all(),
            ['url' => $request->fullUrl(), 'path' => $request->path()]
        );

        foreach ($allInputs as $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Detect command injection attempts.
     */
    protected function detectCommandInjection(Request $request): bool
    {
        $patterns = [
            '/;\s*(rm|del|cat|ls|dir|type|copy|move|echo|ping|curl|wget|nc|netcat|bash|sh|cmd|powershell)/i',
            '/\|\s*(rm|del|cat|ls|dir|type|copy|move|echo|ping|curl|wget|nc|netcat|bash|sh|cmd|powershell)/i',
            '/&&\s*(rm|del|cat|ls|dir|type|copy|move|echo|ping|curl|wget|nc|netcat|bash|sh|cmd|powershell)/i',
            '/`[^`]*`/',
            '/\$\([^)]*\)/',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/passthru\s*\(/i',
            '/shell_exec\s*\(/i',
            '/proc_open\s*\(/i',
            '/popen\s*\(/i',
        ];

        $allInputs = $request->all();

        foreach ($allInputs as $value) {
            if (is_string($value)) {
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Log security events.
     */
    protected function logSecurityEvent(string $event, array $data = []): void
    {
        Log::channel('security')->warning($event, array_merge([
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ], $data));
    }
}

