<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use Concerns\HasCompanyLifecycle;
    protected $fillable = [
        'user_id',
        'employee_id',
        'department_id',
        'supervisor_user_id',
        'is_team_lead',
        'career_level',
        'career_track',
        'first_name',
        'last_name',
        'email',
        'position',
        'phone',
        'phone_secondary',
        'whatsapp_phone',
        'national_id',
        'full_name_ar',
        'date_of_birth',
        'gender',
        'religion',
        'marital_status',
        'nationality',
        'birth_place',
        'national_id_address',
        'id_photo_front_path',
        'id_photo_back_path',
        'id_issue_date',
        'id_expiry_date',
        'military_status',
        'address',
        'emergency_contact',
        'emergency_phone',
        'education_level',
        'university',
        'faculty',
        'major',
        'graduation_year',
        'education_notes',
        'hire_date',
        'salary',
        'preferred_payout_method',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'vodafone_cash_number',
        'instapay_identifier',
        'payout_notes',
        'daily_hours',
        'employment_type',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'date_of_birth' => 'date',
        'id_issue_date' => 'date',
        'id_expiry_date' => 'date',
        'salary' => 'decimal:2',
        'is_team_lead' => 'boolean',
        'graduation_year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_user_id');
    }

    public function directReports(): HasMany
    {
        return $this->hasMany(self::class, 'supervisor_user_id', 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'assigned_to', 'user_id');
    }

    public function employmentContracts(): HasMany
    {
        return $this->hasMany(EmploymentContract::class);
    }

    public function activeEmploymentContract(): ?EmploymentContract
    {
        return $this->employmentContracts()->where('status', 'active')->latest('start_date')->first();
    }

    public function effectiveSalary(): float
    {
        return (float) ($this->activeEmploymentContract()?->base_salary ?? $this->salary ?? 0);
    }

    public function isOnboardingProfileComplete(): bool
    {
        return filled($this->national_id)
            && filled($this->full_name_ar)
            && filled($this->id_photo_front_path)
            && filled($this->id_photo_back_path);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(Salary::class);
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(EmployeePromotion::class);
    }

    /**
     * توليد رقم توظيفي تلقائي
     */
    public static function generateEmployeeId($prefix = null, $length = null)
    {
        $prefix = $prefix ?? \App\Helpers\SettingsHelper::getEmployeeIdPrefix();
        $length = $length ?? \App\Helpers\SettingsHelper::getEmployeeIdLength();
        
        do {
            // توليد رقم عشوائي
            $number = str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
            $employeeId = $prefix . $number;
            
            // التحقق من عدم وجود الرقم في قاعدة البيانات
        } while (static::where('employee_id', $employeeId)->exists());
        
        return $employeeId;
    }

    /**
     * توليد رقم توظيفي متسلسل
     */
    public static function generateSequentialEmployeeId($prefix = null)
    {
        $prefix = $prefix ?? \App\Helpers\SettingsHelper::getEmployeeIdPrefix();
        $length = \App\Helpers\SettingsHelper::getEmployeeIdLength();

        $maxNumber = static::query()
            ->where('employee_id', 'like', $prefix.'%')
            ->pluck('employee_id')
            ->map(fn (string $id) => (int) preg_replace('/^'.preg_quote($prefix, '/').'/i', '', $id))
            ->max() ?? 0;

        do {
            $maxNumber++;
            $employeeId = $prefix.str_pad((string) $maxNumber, $length, '0', STR_PAD_LEFT);
        } while (static::where('employee_id', $employeeId)->exists());

        return $employeeId;
    }

    /**
     * توليد رقم توظيفي حسب الإعدادات
     */
    public static function generateEmployeeIdBySettings()
    {
        $type = \App\Helpers\SettingsHelper::getEmployeeIdType();
        
        if ($type === 'random') {
            return static::generateEmployeeId();
        } else {
            return static::generateSequentialEmployeeId();
        }
    }

    public function displayName(): string
    {
        return trim((string) ($this->full_name_ar ?: ($this->first_name.' '.$this->last_name)));
    }

    public static function genderLabels(): array
    {
        return ['male' => 'ذكر', 'female' => 'أنثى'];
    }

    public static function religionLabels(): array
    {
        return ['muslim' => 'مسلم', 'christian' => 'مسيحي', 'other' => 'أخرى'];
    }

    public static function maritalStatusLabels(): array
    {
        return [
            'single' => 'أعزب / عزباء',
            'married' => 'متزوج / متزوجة',
            'divorced' => 'مطلق / مطلقة',
            'widowed' => 'أرمل / أرملة',
        ];
    }

    public static function militaryStatusLabels(): array
    {
        return [
            'completed' => 'أدى الخدمة',
            'exempted' => 'معافى',
            'postponed' => 'مؤجل',
            'not_applicable' => 'لا ينطبق',
        ];
    }

    public static function educationLevelLabels(): array
    {
        return [
            'high_school' => 'ثانوية عامة / دبلوم ثانوي',
            'diploma' => 'دبلوم / معهد',
            'bachelor' => 'بكالوريوس / ليسانس',
            'master' => 'ماجستير',
            'phd' => 'دكتوراه',
            'other' => 'أخرى',
        ];
    }

    public function genderLabel(): string
    {
        return self::genderLabels()[$this->gender] ?? ($this->gender ?: '—');
    }

    public function religionLabel(): string
    {
        return self::religionLabels()[$this->religion] ?? ($this->religion ?: '—');
    }

    public function maritalStatusLabel(): string
    {
        return self::maritalStatusLabels()[$this->marital_status] ?? ($this->marital_status ?: '—');
    }

    public function militaryStatusLabel(): string
    {
        return self::militaryStatusLabels()[$this->military_status] ?? ($this->military_status ?: '—');
    }

    public function educationLevelLabel(): string
    {
        return self::educationLevelLabels()[$this->education_level] ?? ($this->education_level ?: '—');
    }

    public static function payoutMethodLabels(): array
    {
        return [
            'bank' => 'حساب بنكي',
            'vodafone_cash' => 'فودافون كاش',
            'instapay' => 'InstaPay',
        ];
    }

    public function preferredPayoutMethodLabel(): string
    {
        return self::payoutMethodLabels()[$this->preferred_payout_method] ?? '—';
    }

    public function educationSummary(): string
    {
        $parts = array_filter([
            $this->educationLevelLabel() !== '—' ? $this->educationLevelLabel() : null,
            $this->faculty,
            $this->university,
            $this->major,
            $this->graduation_year ? 'دفعة '.$this->graduation_year : null,
        ]);

        return $parts ? implode(' · ', $parts) : '—';
    }

    public function defaultLifecycleStateOnCreate(): string
    {
        return 'onboarding';
    }
}
