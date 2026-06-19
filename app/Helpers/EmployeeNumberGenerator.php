<?php

namespace App\Helpers;

use App\Models\Employee;

class EmployeeNumberGenerator
{
    /**
     * توليد رقم توظيفي تلقائي
     * 
     * @param string $prefix البادئة (اختيارية)
     * @param int $length طول الرقم (بدون البادئة)
     * @return string
     */
    public static function generate($prefix = 'EMP', $length = 6)
    {
        do {
            // توليد رقم عشوائي
            $number = str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
            $employeeId = $prefix . $number;
            
            // التحقق من عدم وجود الرقم في قاعدة البيانات
        } while (Employee::where('employee_id', $employeeId)->exists());
        
        return $employeeId;
    }
    
    /**
     * توليد رقم توظيفي بتنسيق مختلف
     * 
     * @param int $year السنة
     * @param int $departmentId معرف القسم
     * @return string
     */
    public static function generateWithYearAndDepartment($year = null, $departmentId = null)
    {
        $year = $year ?? date('Y');
        $departmentCode = $departmentId ? str_pad($departmentId, 2, '0', STR_PAD_LEFT) : '00';
        
        do {
            // توليد رقم متسلسل
            $sequence = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $employeeId = $year . $departmentCode . $sequence;
            
            // التحقق من عدم وجود الرقم في قاعدة البيانات
        } while (Employee::where('employee_id', $employeeId)->exists());
        
        return $employeeId;
    }
    
    /**
     * توليد رقم توظيفي متسلسل
     * 
     * @param string $prefix البادئة
     * @return string
     */
    public static function generateSequential($prefix = 'EMP', $length = 6)
    {
        $maxNumber = Employee::query()
            ->where('employee_id', 'like', $prefix.'%')
            ->pluck('employee_id')
            ->map(fn (string $id) => (int) preg_replace('/^'.preg_quote($prefix, '/').'/i', '', $id))
            ->max() ?? 0;

        do {
            $maxNumber++;
            $employeeId = $prefix.str_pad((string) $maxNumber, $length, '0', STR_PAD_LEFT);
        } while (Employee::where('employee_id', $employeeId)->exists());

        return $employeeId;
    }
    
    /**
     * التحقق من صحة رقم الموظف
     * 
     * @param string $employeeId
     * @return bool
     */
    public static function isValid($employeeId)
    {
        // التحقق من التنسيق الأساسي
        if (empty($employeeId) || strlen($employeeId) < 3) {
            return false;
        }
        
        // التحقق من عدم وجود الرقم في قاعدة البيانات
        return !Employee::where('employee_id', $employeeId)->exists();
    }
    
    /**
     * الحصول على إحصائيات الأرقام التوظيفية
     * 
     * @return array
     */
    public static function getStatistics()
    {
        $total = Employee::count();
        $thisYear = Employee::whereYear('created_at', date('Y'))->count();
        $thisMonth = Employee::whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();
        
        return [
            'total_employees' => $total,
            'this_year' => $thisYear,
            'this_month' => $thisMonth,
            'next_sequential' => self::generateSequential()
        ];
    }
}
