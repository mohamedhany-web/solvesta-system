<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Get user's employee record
        $employee = Employee::where('user_id', $currentUser->id)->first();
        
        // Get leave statistics
        $stats = $this->getLeaveStats();
        
        // Get leave requests based on user role
        $leaves = collect();
        if ($currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            // Admins can see all leaves
            $leaves = Leave::with(['employee.user', 'approvedBy'])
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
        } elseif ($employee) {
            // Regular employees can see their own leaves
            $leaves = Leave::where('employee_id', $employee->id)
                ->with(['approvedBy'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Get all employees for admin view
        $employees = collect();
        if ($currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            $employees = Employee::with(['user', 'department'])
                ->where('status', 'active')
                ->get();
        }
        
        return view('leaves.index', compact('stats', 'leaves', 'employees', 'employee'));
    }
    
    /**
     * Get leave statistics
     */
    private function getLeaveStats()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $approvedLeaves = Leave::where('status', 'approved')
            ->whereMonth('start_date', $currentMonth)
            ->whereYear('start_date', $currentYear)
            ->count();
        $rejectedLeaves = Leave::where('status', 'rejected')
            ->whereMonth('start_date', $currentMonth)
            ->whereYear('start_date', $currentYear)
            ->count();
        $totalLeaveDays = Leave::where('status', 'approved')
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');
            
        return [
            'pending_leaves' => $pendingLeaves,
            'approved_leaves' => $approvedLeaves,
            'rejected_leaves' => $rejectedLeaves,
            'total_leave_days' => $totalLeaveDays
        ];
    }
    
    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string|in:annual,sick,emergency,maternity,paternity,unpaid,compensatory',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500'
        ]);
        
        $currentUser = Auth::user();
        $employee = Employee::where('user_id', $currentUser->id)->first();
        
        if (!$employee) {
            return response()->json(['error' => 'Employee record not found'], 404);
        }
        
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $currentYear = Carbon::now()->year;
        
        // Check for duplicate requests (same dates, same employee, pending status)
        $duplicateCheck = Leave::where('employee_id', $employee->id)
            ->where('start_date', $startDate->format('Y-m-d'))
            ->where('end_date', $endDate->format('Y-m-d'))
            ->where('status', 'pending')
            ->where('leave_type', $request->leave_type)
            ->first();
            
        if ($duplicateCheck) {
            return response()->json([
                'error' => 'يوجد طلب إجازة مقدم مسبقاً لهذه التواريخ في انتظار الموافقة'
            ], 400);
        }
        
        // Check if employee has enough leave balance (for annual leaves)
        if ($request->leave_type === 'annual') {
            $usedAnnualLeaves = Leave::where('employee_id', $employee->id)
                ->where('leave_type', 'annual')
                ->where('status', 'approved')
                ->whereYear('start_date', $currentYear)
                ->sum('total_days');
                
            $annualLeaveLimit = 21; // Default annual leave limit
            if (($usedAnnualLeaves + $totalDays) > $annualLeaveLimit) {
                return response()->json(['error' => 'لا يمكن طلب هذا العدد من أيام الإجازة السنوية'], 400);
            }
        }
        
        DB::beginTransaction();
        try {
            $leave = Leave::create([
                'employee_id' => $employee->id,
                'leave_type' => $request->leave_type,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $totalDays,
                'reason' => $request->reason,
                'status' => 'pending',
                'applied_date' => Carbon::now()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'تم تقديم طلب الإجازة بنجاح',
                'leave' => $leave
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Check if it's a duplicate key error (race condition)
            if (str_contains($e->getMessage(), 'Duplicate') || str_contains($e->getMessage(), 'UNIQUE')) {
                return response()->json([
                    'error' => 'تم تقديم طلب الإجازة مسبقاً. يرجى تحديث الصفحة.'
                ], 400);
            }
            
            return response()->json([
                'error' => 'حدث خطأ أثناء تقديم طلب الإجازة: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Approve a leave request
     */
    public function approve($id)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $leave = Leave::findOrFail($id);
        
        if ($leave->status !== 'pending') {
            return response()->json(['error' => 'This leave request has already been processed'], 400);
        }
        
        $leave->update([
            'status' => 'approved',
            'approved_by' => $currentUser->id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم الموافقة على طلب الإجازة'
        ]);
    }
    
    /**
     * Reject a leave request
     */
    public function reject(Request $request, $id)
    {
        $currentUser = Auth::user();
        
        if (!$currentUser->hasRole(['admin', 'hr', 'super_admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);
        
        $leave = Leave::findOrFail($id);
        
        if ($leave->status !== 'pending') {
            return response()->json(['error' => 'This leave request has already been processed'], 400);
        }
        
        $leave->update([
            'status' => 'rejected',
            'approved_by' => $currentUser->id,
            'rejection_reason' => $request->rejection_reason
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم رفض طلب الإجازة'
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
