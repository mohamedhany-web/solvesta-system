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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        
        // الحصول على آخر رقم توظيفي
        $lastEmployee = static::where('employee_id', 'like', $prefix . '%')
            ->orderBy('employee_id', 'desc')
            ->first();
        
        if ($lastEmployee) {
            // استخراج الرقم من آخر employee_id
            $lastNumber = (int) str_replace($prefix, '', $lastEmployee->employee_id);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // تنسيق الرقم مع padding
        $employeeId = $prefix . str_pad($newNumber, $length, '0', STR_PAD_LEFT);
        
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
