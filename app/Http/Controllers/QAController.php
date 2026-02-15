<?php

namespace App\Http\Controllers;

use App\Models\QATest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QAController extends Controller
{
    /**
     * Display a listing of the QA tests.
     */
    public function index()
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار - الموظف (employee) هو الأدنى
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إنشاء query أساسي
        $query = QATest::with(['project', 'createdBy', 'assignedTo']);
        
        // إذا كان المستخدم موظف عادي فقط، يعرض الاختبارات التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly) {
            $query->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        
        // تطبيق الفلاتر
        $query->when(request('search'), function ($q) {
            $q->where(function($subQuery) {
                $subQuery->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')
                    ->orWhere('test_number', 'like', '%' . request('search') . '%');
            });
        })
        ->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        })
        ->when(request('type'), function ($q) {
            $q->where('type', request('type'));
        })
        ->when(request('priority'), function ($q) {
            $q->where('priority', request('priority'));
        })
        ->when(request('project_id'), function ($q) {
            $q->where('project_id', request('project_id'));
        });
        
        $tests = $query->orderBy('created_at', 'desc')->paginate(15);

        // حساب الإحصائيات - نفس المنطق للتصفية
        $statsQuery = QATest::query();
        if ($isEmployeeOnly) {
            $statsQuery->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'passed' => (clone $statsQuery)->passed()->count(),
            'failed' => (clone $statsQuery)->failed()->count(),
            'pending' => (clone $statsQuery)->pending()->count(),
            'pass_rate' => (clone $statsQuery)->count() > 0 
                ? round((clone $statsQuery)->passed()->count() / (clone $statsQuery)->count() * 100) 
                : 0,
        ];

        $projects = Project::all();
        
        return view('qa.index', compact('tests', 'stats', 'projects'));
    }

    /**
     * Show the form for creating a new test.
     */
    public function create()
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        
        // التحقق من الأدوار الحالية للمستخدم
        $userRoles = $user->roles->pluck('name')->toArray();
        $hasHigherRole = !empty(array_intersect($userRoles, $higherRoles));
        $hasOnlyEmployeeRole = count($userRoles) === 1 && in_array('employee', $userRoles);
        
        // جلب المشاريع حسب الدور
        if ($hasOnlyEmployeeRole && !$hasHigherRole) {
            // الموظف العادي: فقط المشاريع التي هو مديرها أو عضو في فريقها
            $projects = Project::where(function($query) use ($user) {
                $query->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', function($q) use ($user) {
                        $q->where('project_team_members.user_id', $user->id);
                    });
            })->get();
        } else {
            // المستويات الأعلى: جميع المشاريع
            $projects = Project::all();
        }
        
        $users = User::all();
        
        return view('qa.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created test in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان الموظف عادي فقط، التحقق من أن المشروع متاح له
        if ($isEmployeeOnly && $request->has('project_id') && $request->project_id) {
            $availableProject = Project::where(function($query) use ($user) {
                $query->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', function($q) use ($user) {
                        $q->where('project_team_members.user_id', $user->id);
                    });
            })->where('id', $request->project_id)->exists();
            
            if (!$availableProject) {
                return redirect()->back()
                    ->withErrors(['project_id' => 'المشروع المحدد غير متاح لك'])
                    ->withInput();
            }
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'type' => 'required|in:unit,integration,functional,performance,security,usability',
            'status' => 'required|in:pending,running,passed,failed,skipped',
            'priority' => 'required|in:low,medium,high,critical',
            'test_steps' => 'nullable|string',
            'expected_result' => 'nullable|string',
            'actual_result' => 'nullable|string',
            'preconditions' => 'nullable|string',
            'test_data' => 'nullable|string',
            'environment' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['test_number'] = QATest::generateTestNumber();
        $data['created_by'] = auth()->id();

        QATest::create($data);

        return redirect()->route('qa.index')
            ->with('success', 'تم إنشاء الاختبار بنجاح');
    }

    /**
     * Display the specified test.
     */
    public function show(QATest $qa)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه رؤية الاختبارات التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly && $qa->created_by !== $user->id && $qa->assigned_to !== $user->id) {
            abort(403, 'غير مصرح لك بعرض هذا الاختبار');
        }
        
        $qa->load(['project', 'createdBy', 'assignedTo']);
        
        return view('qa.show', compact('qa'));
    }

    /**
     * Show the form for editing the specified test.
     */
    public function edit(QATest $qa)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه تعديل الاختبارات التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly && $qa->created_by !== $user->id && $qa->assigned_to !== $user->id) {
            abort(403, 'غير مصرح لك بتعديل هذا الاختبار');
        }
        
        // جلب المشاريع حسب الدور
        // التحقق من الأدوار الحالية للمستخدم
        $userRoles = $user->roles->pluck('name')->toArray();
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $hasHigherRole = !empty(array_intersect($userRoles, $higherRoles));
        $hasOnlyEmployeeRole = count($userRoles) === 1 && in_array('employee', $userRoles);
        
        if ($hasOnlyEmployeeRole && !$hasHigherRole) {
            // الموظف العادي: فقط المشاريع التي هو مديرها أو عضو في فريقها
            $projects = Project::where(function($query) use ($user) {
                $query->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', function($q) use ($user) {
                        $q->where('project_team_members.user_id', $user->id);
                    });
            })->get();
        } else {
            // المستويات الأعلى: جميع المشاريع
            $projects = Project::all();
        }
        
        $users = User::all();
        
        return view('qa.edit', compact('qa', 'projects', 'users'));
    }

    /**
     * Update the specified test in storage.
     */
    public function update(Request $request, QATest $qa)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه تحديث الاختبارات التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly && $qa->created_by !== $user->id && $qa->assigned_to !== $user->id) {
            abort(403, 'غير مصرح لك بتحديث هذا الاختبار');
        }
        
        // إذا كان الموظف عادي فقط، التحقق من أن المشروع المحدد متاح له
        if ($isEmployeeOnly && $request->has('project_id') && $request->project_id) {
            $availableProject = Project::where(function($query) use ($user) {
                $query->where('project_manager_id', $user->id)
                    ->orWhereHas('teamMembers', function($q) use ($user) {
                        $q->where('project_team_members.user_id', $user->id);
                    });
            })->where('id', $request->project_id)->exists();
            
            if (!$availableProject) {
                return redirect()->back()
                    ->withErrors(['project_id' => 'المشروع المحدد غير متاح لك'])
                    ->withInput();
            }
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'type' => 'required|in:unit,integration,functional,performance,security,usability',
            'status' => 'required|in:pending,running,passed,failed,skipped',
            'priority' => 'required|in:low,medium,high,critical',
            'test_steps' => 'nullable|string',
            'expected_result' => 'nullable|string',
            'actual_result' => 'nullable|string',
            'preconditions' => 'nullable|string',
            'test_data' => 'nullable|string',
            'environment' => 'nullable|string',
            'notes' => 'nullable|string',
            'execution_time' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // If status changed to passed/failed and no execution date, set it
        if (in_array($request->status, ['passed', 'failed']) && !$qa->executed_at) {
            $data['executed_at'] = now();
        }

        $qa->update($data);

        return redirect()->route('qa.index')
            ->with('success', 'تم تحديث الاختبار بنجاح');
    }

    /**
     * Remove the specified test from storage.
     */
    public function destroy(QATest $qa)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه حذف فقط الاختبارات التي أنشأها (وليس المعينة له)
        if ($isEmployeeOnly && $qa->created_by !== $user->id) {
            abort(403, 'غير مصرح لك بحذف هذا الاختبار');
        }
        
        $qa->delete();

        return redirect()->route('qa.index')
            ->with('success', 'تم حذف الاختبار بنجاح');
    }

    /**
     * Execute/run a test.
     */
    public function execute(QATest $qa)
    {
        $qa->update([
            'status' => 'running',
            'executed_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'تم بدء تنفيذ الاختبار');
    }
}
