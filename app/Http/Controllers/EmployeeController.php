<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use App\Services\DepartmentProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::with(['user', 'department'])
            ->when(request('search'), function ($query) {
                $term = request('search');
                $query->where(function ($q) use ($term) {
                    $q->where('first_name', 'like', '%'.$term.'%')
                        ->orWhere('last_name', 'like', '%'.$term.'%')
                        ->orWhere('full_name_ar', 'like', '%'.$term.'%')
                        ->orWhere('employee_id', 'like', '%'.$term.'%')
                        ->orWhere('email', 'like', '%'.$term.'%')
                        ->orWhere('national_id', 'like', '%'.$term.'%')
                        ->orWhere('phone', 'like', '%'.$term.'%')
                        ->orWhere('phone_secondary', 'like', '%'.$term.'%')
                        ->orWhere('whatsapp_phone', 'like', '%'.$term.'%');
                });
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

        $departments = Department::where('is_active', true)->with('parent')->orderBy('name')->get();
        $users = User::doesntHave('employee')->get();
        $roleLabels = DepartmentProfileService::roleLabels();
        $assignableRoles = DepartmentProfileService::assignableRoles();
        $preselectedDepartmentId = request('department_id');

        return view('employees.create', compact('departments', 'users', 'roleLabels', 'assignableRoles', 'preselectedDepartmentId'));
    }

    public function departmentProfile(Department $department)
    {
        return response()->json(DepartmentProfileService::forDepartment($department));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('create-employees')) {
            abort(403, 'غير مصرح لك بإنشاء موظفين');
        }

        $this->normalizeProfileInput($request);

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
            'system_role' => 'required|string|max:64',
            'career_level' => 'nullable|string|max:128',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
        ] + $this->profileRules() + $this->photoRules(), $this->profileMessages());

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

                $userId = $user->id;
                $employeeEmail = $user->email;
            }

            // التحقق من أن المستخدم المختار ليس لديه موظف بالفعل
            if ($userId && Employee::where('user_id', $userId)->exists()) {
                return redirect()->back()
                    ->withErrors(['user_id' => 'المستخدم المختار لديه موظف مرتبط به بالفعل'])
                    ->withInput();
            }

            // Generate employee ID automatically
            $employeeId = Employee::generateEmployeeIdBySettings();

            $department = Department::find($request->department_id);
            $profile = DepartmentProfileService::forDepartment($department);
            $roleName = $request->system_role ?: ($profile['default_role'] ?? 'employee');
            
            // إنشاء الموظف
            $employee = Employee::create([
                'user_id' => $userId,
                'employee_id' => $employeeId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $employeeEmail,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'position' => $request->position,
                'salary' => $request->salary,
                'daily_hours' => $request->daily_hours,
                'hire_date' => $request->hire_date,
                'employment_type' => $request->employment_type,
                'status' => 'onboarding',
                'career_level' => $request->career_level ?: ($profile['levels'][0] ?? null),
                'career_track' => $profile['career_track'] ?? null,
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
                'emergency_phone' => $request->emergency_phone,
            ] + $this->profilePayload($request) + $this->handlePhotoUploads($request));

            // مزامنة اسم المستخدم والدور حسب القسم
            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $fullName = trim($request->first_name . ' ' . $request->last_name);
                    if ($user->name !== $fullName) {
                        $user->update(['name' => $fullName]);
                    }
                    DepartmentProfileService::applyRoleToUser($user, $roleName);
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
        $employee->load([
            'user.roles', 'department', 'attendances', 'leaves',
            'employmentContracts.creator',
            'lifecycle.currentState', 'lifecycle.definition.states',
            'workItems.assignee', 'companyEvents.actor',
        ]);
        
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
        if (! auth()->user()->can('edit-employees')) {
            abort(403, 'غير مصرح لك بتعديل الموظفين');
        }

        $employee->load(['user', 'supervisor', 'department']);
        $departments = Department::where('is_active', true)->with('parent')->orderBy('name')->get();
        $supervisorOptions = User::query()
            ->whereHas('employee', fn ($q) => $q->where('department_id', $employee->department_id)->where('status', 'active'))
            ->orderBy('name')
            ->get(['id', 'name']);
        $roleLabels = DepartmentProfileService::roleLabels();
        $assignableRoles = DepartmentProfileService::assignableRoles();
        $currentRole = $employee->user?->roles->first()?->name ?? 'employee';
        $deptProfile = DepartmentProfileService::forDepartment($employee->department);

        return view('employees.edit', compact(
            'employee', 'departments', 'supervisorOptions',
            'roleLabels', 'assignableRoles', 'currentRole', 'deptProfile'
        ));
    }

    public function update(Request $request, Employee $employee)
    {
        if (! auth()->user()->can('edit-employees')) {
            abort(403, 'غير مصرح لك بتعديل الموظفين');
        }

        $this->normalizeProfileInput($request);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|string|unique:employees,employee_id,'.$employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,'.$employee->id,
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'daily_hours' => 'required|numeric|min:1|max:12',
            'hire_date' => 'required|date',
            'employment_type' => 'required|in:full_time,part_time,contract,intern',
            'status' => 'required|in:onboarding,active,inactive,terminated,on_leave',
            'supervisor_user_id' => 'nullable|exists:users,id',
            'is_team_lead' => 'nullable|boolean',
            'system_role' => 'nullable|string|max:64',
            'career_level' => 'nullable|string|max:128',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'emergency_phone' => 'nullable|string',
        ] + $this->profileRules($employee->id) + $this->photoRules(), $this->profileMessages());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $department = Department::find($request->department_id);
        $profile = DepartmentProfileService::forDepartment($department);

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
            'hire_date' => $request->hire_date,
            'employment_type' => $request->employment_type,
            'status' => $request->status,
            'supervisor_user_id' => $request->input('supervisor_user_id') ?: null,
            'is_team_lead' => $request->boolean('is_team_lead'),
            'career_level' => $request->input('career_level') ?: $employee->career_level,
            'career_track' => $profile['career_track'] ?? $employee->career_track,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
        ] + $this->profilePayload($request) + $this->handlePhotoUploads($request, $employee));

        if ($employee->user_id) {
            $user = User::find($employee->user_id);
            if ($user) {
                $fullName = trim($request->first_name.' '.$request->last_name);

                $emailExists = User::where('email', $request->email)
                    ->where('id', '!=', $user->id)
                    ->exists();

                if (! $emailExists) {
                    $user->update([
                        'name' => $fullName,
                        'email' => $request->email,
                    ]);
                }

                if ($request->filled('system_role')) {
                    DepartmentProfileService::applyRoleToUser($user, $request->system_role);
                }
            }
        }

        return redirect()->route('employees.show', $employee)
            ->with('success', 'تم تحديث بيانات الموظف بنجاح');
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

    private function normalizeProfileInput(Request $request): void
    {
        $nid = preg_replace('/\D/', '', (string) $request->input('national_id'));
        $emptyToNull = [
            'full_name_ar', 'date_of_birth', 'gender', 'religion', 'marital_status',
            'nationality', 'birth_place', 'national_id_address', 'id_issue_date',
            'id_expiry_date', 'military_status', 'phone_secondary', 'whatsapp_phone',
            'education_level', 'university', 'faculty', 'major', 'graduation_year',
            'education_notes',
        ];

        $merged = ['national_id' => $nid !== '' ? $nid : null];
        foreach ($emptyToNull as $field) {
            if ($request->exists($field) && $request->input($field) === '') {
                $merged[$field] = null;
            }
        }

        $request->merge($merged);
    }

    private function profileRules(?int $ignoreEmployeeId = null): array
    {
        $nidUnique = 'nullable|regex:/^[23][0-9]{13}$/|unique:employees,national_id';
        if ($ignoreEmployeeId) {
            $nidUnique .= ','.$ignoreEmployeeId;
        }

        return [
            'national_id' => $nidUnique,
            'full_name_ar' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'religion' => 'nullable|in:muslim,christian,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'nationality' => 'nullable|string|max:80',
            'birth_place' => 'nullable|string|max:255',
            'national_id_address' => 'nullable|string',
            'id_issue_date' => 'nullable|date',
            'id_expiry_date' => 'nullable|date|after_or_equal:id_issue_date',
            'military_status' => 'nullable|in:completed,exempted,postponed,not_applicable',
            'phone_secondary' => 'nullable|string|max:30',
            'whatsapp_phone' => 'nullable|string|max:30',
            'education_level' => 'nullable|in:high_school,diploma,bachelor,master,phd,other',
            'university' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'major' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1970|max:'.(now()->year + 1),
            'education_notes' => 'nullable|string',
        ];
    }

    private function profileMessages(): array
    {
        return [
            'national_id.regex' => 'الرقم القومي يجب أن يكون 14 رقماً ويبدأ بـ 2 أو 3.',
            'national_id.unique' => 'الرقم القومي مسجّل لموظف آخر.',
            'id_expiry_date.after_or_equal' => 'تاريخ انتهاء البطاقة يجب أن يكون بعد تاريخ الإصدار أو مساوياً له.',
            'graduation_year.min' => 'سنة التخرج غير صحيحة.',
            'graduation_year.max' => 'سنة التخرج غير صحيحة.',
        ];
    }

    private function profilePayload(Request $request): array
    {
        return [
            'national_id' => $request->input('national_id') ?: null,
            'full_name_ar' => $request->input('full_name_ar') ?: null,
            'date_of_birth' => $request->input('date_of_birth') ?: null,
            'gender' => $request->input('gender') ?: null,
            'religion' => $request->input('religion') ?: null,
            'marital_status' => $request->input('marital_status') ?: null,
            'nationality' => $request->input('nationality') ?: null,
            'birth_place' => $request->input('birth_place') ?: null,
            'national_id_address' => $request->input('national_id_address') ?: null,
            'id_issue_date' => $request->input('id_issue_date') ?: null,
            'id_expiry_date' => $request->input('id_expiry_date') ?: null,
            'military_status' => $request->input('military_status') ?: null,
            'phone_secondary' => $request->input('phone_secondary') ?: null,
            'whatsapp_phone' => $request->input('whatsapp_phone') ?: null,
            'education_level' => $request->input('education_level') ?: null,
            'university' => $request->input('university') ?: null,
            'faculty' => $request->input('faculty') ?: null,
            'major' => $request->input('major') ?: null,
            'graduation_year' => $request->input('graduation_year') ?: null,
            'education_notes' => $request->input('education_notes') ?: null,
        ];
    }

    private function photoRules(): array
    {
        return [
            'id_photo_front' => 'nullable|image|mimes:jpeg,jpg,png|max:4096',
            'id_photo_back' => 'nullable|image|mimes:jpeg,jpg,png|max:4096',
        ];
    }

    private function handlePhotoUploads(Request $request, ?Employee $employee = null): array
    {
        $payload = [];

        foreach (['id_photo_front' => 'id_photo_front_path', 'id_photo_back' => 'id_photo_back_path'] as $input => $column) {
            if (! $request->hasFile($input)) {
                continue;
            }

            if ($employee && $employee->{$column} && Storage::disk('public')->exists($employee->{$column})) {
                Storage::disk('public')->delete($employee->{$column});
            }

            $payload[$column] = $request->file($input)->store('employee-id-cards', 'public');
        }

        return $payload;
    }
}