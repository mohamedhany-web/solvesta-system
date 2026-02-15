<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.max' => 'البريد الإلكتروني يجب ألا يتجاوز 255 حرفاً.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 8 أحرف.',
            'password.max' => 'كلمة المرور يجب ألا تتجاوز 255 حرفاً.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize and normalize email
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->input('email'))),
            ]);
        }

        // Sanitize password (remove extra spaces)
        if ($this->has('password')) {
            $this->merge([
                'password' => $this->input('password'),
            ]);
        }
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        // Enhanced rate limiting with IP and email
        $this->ensureIsNotRateLimited();

        // Additional security: Check if IP is blocked
        if ($this->isIpBlocked()) {
            Log::warning('Blocked IP attempted login', [
                'ip' => $this->ip(),
                'email' => $this->input('email'),
            ]);

            throw ValidationException::withMessages([
                'email' => 'تم حظر عنوان IP الخاص بك مؤقتاً. يرجى المحاولة لاحقاً.',
            ]);
        }

        // Get credentials securely
        $credentials = [
            'email' => $this->input('email'),
            'password' => $this->input('password'),
        ];

        // Attempt authentication
        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            // Log failed attempt
            Log::warning('Failed login attempt', [
                'email' => $credentials['email'],
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent(),
            ]);

            // Increment rate limiter for email+IP
            RateLimiter::hit($this->throttleKey());

            // Increment rate limiter for IP only
            RateLimiter::hit($this->ipThrottleKey());

            // Increment block counter for IP (20 attempts per hour)
            $blockKey = 'login:blocked:' . md5($this->ip());
            RateLimiter::hit($blockKey);

            // Check if IP should be explicitly blocked (after 10 failed attempts)
            $failedAttempts = RateLimiter::attempts('failed_auth:' . md5($this->ip()));
            if ($failedAttempts >= 10) {
                \App\Http\Middleware\BlockSuspiciousActivity::blockIp($this->ip(), 60);
            } else {
                RateLimiter::hit('failed_auth:' . md5($this->ip()));
            }

            // Log security event
            $this->logSecurityEvent('failed_login', [
                'email' => $credentials['email'],
                'ip' => $this->ip(),
            ]);

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Clear rate limiter on success
        RateLimiter::clear($this->throttleKey());
        
        // Clear IP-based rate limiter
        RateLimiter::clear($this->ipThrottleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        // Email + IP based rate limiting (5 attempts per 15 minutes)
        $emailIpKey = $this->throttleKey();
        if (RateLimiter::tooManyAttempts($emailIpKey, 5)) {
            event(new Lockout($this));
            $seconds = RateLimiter::availableIn($emailIpKey);

            Log::warning('Rate limit exceeded for login', [
                'email' => $this->input('email'),
                'ip' => $this->ip(),
                'seconds_remaining' => $seconds,
            ]);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // IP-based rate limiting (10 attempts per 15 minutes from same IP)
        $ipKey = $this->ipThrottleKey();
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            $seconds = RateLimiter::availableIn($ipKey);

            Log::warning('IP rate limit exceeded for login', [
                'ip' => $this->ip(),
                'seconds_remaining' => $seconds,
            ]);

            throw ValidationException::withMessages([
                'email' => 'تم تجاوز عدد المحاولات المسموح به من هذا العنوان. يرجى المحاولة بعد ' . ceil($seconds / 60) . ' دقيقة.',
            ]);
        }
    }

    /**
     * Get the rate limiting throttle key for the request (email + IP).
     */
    public function throttleKey(): string
    {
        $email = Str::transliterate(Str::lower($this->string('email')));
        $ip = $this->ip();
        return 'login:' . md5($email . '|' . $ip);
    }

    /**
     * Get the IP-based rate limiting throttle key.
     */
    public function ipThrottleKey(): string
    {
        return 'login:ip:' . md5($this->ip());
    }

    /**
     * Check if the IP is blocked.
     */
    protected function isIpBlocked(): bool
    {
        // Check if IP has exceeded maximum attempts (20 per hour)
        $blockKey = 'login:blocked:' . md5($this->ip());
        
        // Use RateLimiter to check if IP has exceeded 20 attempts in 60 minutes
        if (RateLimiter::tooManyAttempts($blockKey, 20)) {
            return true;
        }

        // Also check if IP is explicitly blocked by BlockSuspiciousActivity middleware
        $explicitBlockKey = 'blocked_ip:' . md5($this->ip());
        if (\Illuminate\Support\Facades\Cache::has($explicitBlockKey)) {
            return true;
        }

        return false;
    }

    /**
     * Log security events.
     */
    protected function logSecurityEvent(string $event, array $data = []): void
    {
        Log::channel('security')->warning($event, array_merge([
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent(),
            'timestamp' => now()->toDateTimeString(),
        ], $data));
    }
}
