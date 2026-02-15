<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = Auth::user();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Get salary statistics
        $stats = $this->getSalaryStats($currentMonth, $currentYear);
        
        // Get salaries based on user role
        $salaries = collect();
        if ($currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            // Admins can see all salaries
            $salaries = Salary::with(['employee.user', 'employee.department', 'approvedBy'])
                ->where('month', $currentMonth)
                ->where('year', $currentYear)
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Regular employees can see their own salaries
            $employee = Employee::where('user_id', $currentUser->id)->first();
            if ($employee) {
                $salaries = Salary::where('employee_id', $employee->id)
                    ->with(['approvedBy'])
                    ->orderBy('created_at', 'desc')
                    ->limit(12) // Last 12 months
                    ->get();
            }
        }
        
        // Get all employees for admin view
        $employees = collect();
        if ($currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            $employees = Employee::with(['user', 'department'])
                ->where('status', 'active')
                ->get();
        }
        
        return view('salaries.index', compact('stats', 'salaries', 'employees', 'currentMonth', 'currentYear'));
    }
    
    /**
     * Get salary statistics for a specific month and year
     */
    private function getSalaryStats($month, $year)
    {
        $totalSalaries = Salary::where('month', $month)
            ->where('year', $year)
            ->sum('net_salary');
            
        $paidSalaries = Salary::where('month', $month)
            ->where('year', $year)
            ->where('status', 'paid')
            ->sum('net_salary');
            
        $pendingSalaries = Salary::where('month', $month)
            ->where('year', $year)
            ->where('status', 'pending')
            ->sum('net_salary');
            
        $averageSalary = Salary::where('month', $month)
            ->where('year', $year)
            ->avg('net_salary') ?? 0;
            
        return [
            'total_salaries' => $totalSalaries,
            'paid_salaries' => $paidSalaries,
            'pending_salaries' => $pendingSalaries,
            'average_salary' => round($averageSalary, 2),
            'payment_rate' => $totalSalaries > 0 ? round(($paidSalaries / $totalSalaries) * 100, 1) : 0
        ];
    }
    
    /**
     * Generate salaries for all employees for a specific month
     */
    public function generateSalaries(Request $request)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030'
        ]);
        
        $month = $request->month;
        $year = $request->year;
        
        // Check if salaries already exist for this month
        $existingSalaries = Salary::where('month', $month)
            ->where('year', $year)
            ->count();
            
        if ($existingSalaries > 0) {
            return response()->json(['error' => 'تم إنشاء المرتبات لهذا الشهر مسبقاً'], 400);
        }
        
        $employees = Employee::where('status', 'active')->get();
        $generatedCount = 0;
        
        foreach ($employees as $employee) {
            $this->generateEmployeeSalary($employee, $month, $year);
            $generatedCount++;
        }
        
        return response()->json([
            'success' => true,
            'message' => "تم إنشاء {$generatedCount} راتب للموظفين",
            'generated_count' => $generatedCount
        ]);
    }
    
    /**
     * Generate salary for a specific employee
     */
    private function generateEmployeeSalary($employee, $month, $year)
    {
        $baseSalary = $employee->salary ?? 0;
        
        // Calculate working hours for the month
        $workingDays = $this->getWorkingDays($employee->id, $month, $year);
        $expectedHours = $workingDays * 8; // 8 hours per day
        
        // Get actual working hours from attendance
        $actualHours = $this->getActualWorkingHours($employee->id, $month, $year);
        
        // Calculate overtime
        $overtimeHours = max(0, $actualHours - $expectedHours);
        $overtimeAmount = $overtimeHours * ($baseSalary / 160); // Hourly rate
        
        // Calculate bonus (example: 10% for good attendance)
        $attendanceRate = $expectedHours > 0 ? ($actualHours / $expectedHours) : 0;
        $bonus = $attendanceRate >= 0.9 ? $baseSalary * 0.1 : 0;
        
        // Calculate allowances (example: 5% of base salary)
        $allowances = $baseSalary * 0.05;
        
        // Calculate deductions (example: 10% tax)
        $grossSalary = $baseSalary + $overtimeAmount + $bonus + $allowances;
        $tax = $grossSalary * 0.1;
        
        // Calculate net salary
        $netSalary = $grossSalary - $tax;
        
        Salary::create([
            'employee_id' => $employee->id,
            'month' => $month,
            'year' => $year,
            'base_salary' => $baseSalary,
            'overtime_amount' => $overtimeAmount,
            'bonus' => $bonus,
            'allowances' => $allowances,
            'deductions' => 0,
            'tax' => $tax,
            'net_salary' => $netSalary,
            'status' => 'pending',
            'notes' => "راتب {$this->getMonthName($month)} {$year}"
        ]);
    }
    
    /**
     * Get working days for an employee in a specific month
     */
    private function getWorkingDays($employeeId, $month, $year)
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        
        $workingDays = 0;
        $current = $startDate->copy();
        
        while ($current->lte($endDate)) {
            // Count weekdays only (Monday to Friday)
            if ($current->isWeekday()) {
                $workingDays++;
            }
            $current->addDay();
        }
        
        return $workingDays;
    }
    
    /**
     * Get actual working hours for an employee in a specific month
     */
    private function getActualWorkingHours($employeeId, $month, $year)
    {
        $totalHours = Attendance::where('employee_id', $employeeId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->whereNotNull('total_hours')
            ->sum('total_hours');
            
        return $totalHours;
    }
    
    /**
     * Get month name in Arabic
     */
    private function getMonthName($month)
    {
        $months = [
            1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
            5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
            9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر'
        ];
        
        return $months[$month] ?? '';
    }
    
    /**
     * Approve a salary
     */
    public function approve($id)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $salary = Salary::findOrFail($id);
        
        if ($salary->status !== 'pending') {
            return response()->json(['error' => 'This salary has already been processed'], 400);
        }
        
        $salary->update([
            'status' => 'approved',
            'approved_by' => $currentUser->id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم الموافقة على الراتب'
        ]);
    }
    
    /**
     * Mark salary as paid
     */
    public function markAsPaid($id)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $salary = Salary::findOrFail($id);
        
        if ($salary->status === 'paid') {
            return response()->json(['error' => 'This salary has already been paid'], 400);
        }
        
        $salary->update([
            'status' => 'paid',
            'payment_date' => Carbon::now(),
            'approved_by' => $currentUser->id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الراتب كمدفوع'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $currentUser = Auth::user();
        $salary = Salary::with(['employee.user', 'employee.department', 'approvedBy'])
            ->findOrFail($id);
        
        // Check if user has permission to view this salary
        if (!$currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            // Regular employees can only see their own salaries
            $employee = Employee::where('user_id', $currentUser->id)->first();
            if (!$employee || $salary->employee_id !== $employee->id) {
                abort(403, 'غير مصرح لك بعرض هذا الراتب');
            }
        }
        
        // Calculate gross salary
        $grossSalary = $salary->base_salary + $salary->overtime_amount + $salary->bonus + $salary->allowances;
        $totalDeductions = $salary->deductions + $salary->tax;
        
        return view('salaries.show', compact('salary', 'grossSalary', 'totalDeductions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
