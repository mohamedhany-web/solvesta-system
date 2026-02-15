<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::with(['user', 'department'])
            ->when(request('search'), function ($query) {
                $query->where('first_name', 'like', '%' . request('search') . '%')
                    ->orWhere('last_name', 'like', '%' . request('search') . '%')
                    ->orWhere('employee_id', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            })
            ->when(request('department_id'), function ($query) {
                $query->where('department_id', request('department_id'));
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $departments = Department::where('is_active', true)->get();
        
        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        if (!auth()->user()->can('create-employees')) {
            abort(403, 'غير مصرح لك بإنشاء موظفين');
        }

        $departments = Department::where('is_active', true)->get();
        $users = User::doesntHave('employee')->get();
        
        return view('employees.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create-employees')) {
            abort(403, 'غير مصرح لك بإنشاء موظفين');
        }
        // قواعد أساسية بدون إجبار كلمة المرور دائماً
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'create_new_user' => 'nullable|boolean',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'daily_hours' => 'required|numeric|min:1|max:12',
            'hire_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
        ]);

        // كلمة المرور مطلوبة فقط إذا تم اختيار إنشاء مستخدم جديد ولم يُحدَّد user_id
        $validator->sometimes('password', 'required|string|min:8|confirmed', function ($input) {
            return (filter_var($input->create_new_user, FILTER_VALIDATE_BOOLEAN) || $input->create_new_user === '1' || $input->create_new_user === 1)
                && empty($input->user_id);
        });

        // إذا لم يتم اختيار مستخدم ولم يتم اختيار إنشاء مستخدم جديد
        if (!$request->user_id && !$request->create_new_user) {
            $validator->errors()->add('user_id', 'يجب اختيار مستخدم موجود أو إنشاء مستخدم جديد');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $userId = $request->user_id;
            $employeeEmail = $request->email; // default to form email

            // إنشاء مستخدم جديد إذا تم اختيار ذلك
            if ($request->create_new_user && !$userId) {
                // التحقق من عدم وجود بريد إلكتروني في جدول المستخدمين
                if (User::where('email', $request->email)->exists()) {
                    return redirect()->back()
                        ->withErrors(['email' => 'البريد الإلكتروني مستخدم بالفعل في جدول المستخدمين'])
                        ->withInput();
                }

                $fullName = trim($request->first_name . ' ' . $request->last_name);
                
                $user = User::create([
                    'name' => $fullName,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                ]);

                // تعيين دور افتراضي للموظف
                $employeeRole = \Spatie\Permission\Models\Role::where('name', 'employee')->first();
                if ($employeeRole) {
                    $user->assignRole($employeeRole);
                }

                $userId = $user->id;
                $employeeEmail = $user->email; // ensure consistency
            }

            // التحقق من أن المستخدم المختار ليس لديه موظف بالفعل
            if ($userId && Employee::where('user_id', $userId)->exists()) {
                return redirect()->back()
                    ->withErrors(['user_id' => 'المستخدم المختار لديه موظف مرتبط به بالفعل'])
                    ->withInput();
            }

            // Generate employee ID automatically
            $employeeId = Employee::generateEmployeeIdBySettings();
            
            // إنشاء الموظف
            $employee = Employee::create([
                'user_id' => $userId,
                'employee_id' => $employeeId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                // لا نقوم بتغيير بريد المستخدم الموجود؛ نستخدم بريده الحالي لحقل الموظف
                'email' => $employeeEmail,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'position' => $request->position,
                'salary' => $request->salary,
                'daily_hours' => $request->daily_hours,
                'hire_date' => $request->hire_date,
                'employment_type' => $request->employment_type,
                'status' => 'active',
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
                'emergency_phone' => $request->emergency_phone,
            ]);

            // مزامنة اسم المستخدم المرتبط فقط (بدون تعديل البريد لحماية فريد المستخدمين)
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $fullName = trim($request->first_name . ' ' . $request->last_name);
                    // تحديث الاسم فقط وعدم تغيير البريد الإلكتروني لتفادي تعارض فريد users.email
                    if ($user->name !== $fullName) {
                        $user->update(['name' => $fullName]);
                    }
                }
            }

            return redirect()->route('employees.index')
                ->with('success', "تم إنشاء الموظف بنجاح. الرقم التوظيفي: $employeeId");
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الموظف: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Employee $employee)
    {
        $employee->load(['user.roles', 'department', 'attendances', 'leaves']);
        
        // إحصائيات الموظف
        $stats = [
            'total_attendance_days' => $employee->attendances()->count(),
            'total_leaves' => $employee->leaves()->count(),
            'pending_leaves' => $employee->leaves()->where('status', 'pending')->count(),
            'approved_leaves' => $employee->leaves()->where('status', 'approved')->count(),
        ];
        
        return view('employees.show', compact('employee', 'stats'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();
        
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        if (!auth()->user()->can('edit-employees')) {
            abort(403, 'غير مصرح لك بتعديل الموظفين');
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|unique:employees,employee_id,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'daily_hours' => 'required|numeric|min:1|max:12',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'status' => 'required|in:active,inactive,terminated',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // تحديث بيانات الموظف
        $employee->update([
            'employee_id' => $request->employee_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department_id' => $request->department_id,
            'position' => $request->position,
            'salary' => $request->salary,
            'daily_hours' => $request->daily_hours,
            'employment_type' => $request->employment_type,
            'status' => $request->status,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
        ]);

        // مزامنة بيانات المستخدم المرتبط
        if ($employee->user_id) {
            $user = User::find($employee->user_id);
            if ($user) {
                $fullName = trim($request->first_name . ' ' . $request->last_name);
                
                // التحقق من عدم استخدام البريد الإلكتروني من قبل مستخدم آخر
                $emailExists = User::where('email', $request->email)
                    ->where('id', '!=', $user->id)
                    ->exists();
                
                if (!$emailExists) {
                    $user->update([
                        'name' => $fullName,
                        'email' => $request->email,
                    ]);
                }
            }
        }

        return redirect()->route('employees.index')
            ->with('success', 'تم تحديث الموظف بنجاح');
    }

    public function destroy(Employee $employee)
    {
        if (!auth()->user()->can('delete-employees')) {
            abort(403, 'غير مصرح لك بحذف الموظفين');
        }

        if ($employee->user && $employee->user->hasRole('super_admin')) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف مدير النظام الأعلى');
        }

        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'تم حذف الموظف بنجاح');
    }

    public function export()
    {
        // يمكن إضافة تصدير البيانات لاحقاً
        return redirect()->back()
            ->with('info', 'ميزة التصدير قيد التطوير');
    }
}