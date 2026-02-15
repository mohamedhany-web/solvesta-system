<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'كود التحقق مطلوب.',
            'code.size' => 'كود التحقق يجب أن يكون 6 أرقام.',
            'code.regex' => 'كود التحقق يجب أن يحتوي على أرقام فقط.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize code: remove any spaces or non-numeric characters
        if ($this->has('code')) {
            $code = preg_replace('/[^0-9]/', '', $this->input('code'));
            $this->merge([
                'code' => $code,
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Ensure not rate limited
            if (!$this->ensureIsNotRateLimited()) {
                $validator->errors()->add('code', 'تم تجاوز عدد المحاولات المسموح به. يرجى المحاولة لاحقاً.');
            }
        });
    }

    /**
     * Ensure the verification request is not rate limited.
     *
     * @return bool
     */
    protected function ensureIsNotRateLimited(): bool
    {
        $userId = auth()->id();
        $key = 'verification:' . $userId . ':' . md5($this->ip());

        // 5 attempts per 10 minutes for verification code
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            Log::warning('Rate limit exceeded for verification code', [
                'user_id' => $userId,
                'ip' => $this->ip(),
                'seconds_remaining' => $seconds,
            ]);

            // Log security event
            Log::channel('security')->warning('verification_rate_limit_exceeded', [
                'user_id' => $userId,
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent(),
                'timestamp' => now()->toDateTimeString(),
            ]);

            return false;
        }

        return true;
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $userId = auth()->id();
        $key = 'verification:' . $userId . ':' . md5($this->ip());
        
        // Increment rate limiter on failed validation
        RateLimiter::hit($key);

        // Log failed verification attempt
        Log::warning('Failed verification code attempt', [
            'user_id' => $userId,
            'ip' => $this->ip(),
            'code_length' => strlen($this->input('code', '')),
        ]);

        parent::failedValidation($validator);
    }
}









