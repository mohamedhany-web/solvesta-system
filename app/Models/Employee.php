<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
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
        'national_id',
        'address',
        'emergency_contact',
        'emergency_phone',
        'hire_date',
        'salary',
        'daily_hours',
        'employment_type',
        'status',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'is_team_lead' => 'boolean',
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
        return $this->hasMany(Sale::class, 'sales_rep_id');
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
}
