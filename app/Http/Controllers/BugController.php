<?php

namespace App\Http\Controllers;

use App\Models\Bug;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BugController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار - الموظف (employee) هو الأدنى
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إنشاء query أساسي
        $query = Bug::with(['project', 'reportedBy', 'assignedTo']);
        
        // إذا كان المستخدم موظف عادي فقط، يعرض التقارير التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly) {
            $query->where(function($q) use ($user) {
                $q->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        
        // تطبيق الفلاتر
        $query->when(request('search'), function ($q) {
            $q->where(function($subQuery) {
                $subQuery->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%')
                    ->orWhere('bug_number', 'like', '%' . request('search') . '%');
            });
        })
        ->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        })
        ->when(request('severity'), function ($q) {
            $q->where('severity', request('severity'));
        })
        ->when(request('priority'), function ($q) {
            $q->where('priority', request('priority'));
        })
        ->when(request('project_id'), function ($q) {
            $q->where('project_id', request('project_id'));
        });
        
        $bugs = $query->orderBy('created_at', 'desc')->paginate(15);

        // حساب الإحصائيات - نفس المنطق للتصفية
        $statsQuery = Bug::query();
        if ($isEmployeeOnly) {
            $statsQuery->where(function($q) use ($user) {
                $q->where('reported_by', $user->id)
                  ->orWhere('assigned_to', $user->id);
            });
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'open' => (clone $statsQuery)->open()->count(),
            'resolved' => (clone $statsQuery)->resolved()->count(),
            'critical' => (clone $statsQuery)->where('severity', 'critical')->count(),
            'percentage_resolved' => (clone $statsQuery)->count() > 0 
                ? round((clone $statsQuery)->resolved()->count() / (clone $statsQuery)->count() * 100) 
                : 0,
        ];

        $projects = Project::all();
        
        return view('bugs.index', compact('bugs', 'stats', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
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
        
        return view('bugs.create', compact('projects', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان الموظف عادي فقط، التحقق من أن المشروع متاح له
        if ($isEmployeeOnly && $request->has('project_id')) {
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'severity' => 'required|in:low,medium,high,critical',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,testing,resolved,closed,duplicate',
            'environment' => 'nullable|string',
            'browser' => 'nullable|string',
            'operating_system' => 'nullable|string',
            'steps_to_reproduce' => 'nullable|array',
            'expected_result' => 'nullable|string',
            'actual_result' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['bug_number'] = Bug::generateBugNumber();
        $data['reported_by'] = auth()->id();

        Bug::create($data);

        return redirect()->route('bugs.index')
            ->with('success', 'تم تقرير الخطأ بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bug $bug)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه رؤية التقارير التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly && $bug->reported_by !== $user->id && $bug->assigned_to !== $user->id) {
            abort(403, 'غير مصرح لك بعرض هذا التقرير');
        }
        
        $bug->load(['project', 'reportedBy', 'assignedTo']);
        
        return view('bugs.show', compact('bug'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bug $bug)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه تعديل التقارير التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly && $bug->reported_by !== $user->id && $bug->assigned_to !== $user->id) {
            abort(403, 'غير مصرح لك بتعديل هذا التقرير');
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
        
        return view('bugs.edit', compact('bug', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bug $bug)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه تحديث التقارير التي أنشأها أو تم تعيينها له
        if ($isEmployeeOnly && $bug->reported_by !== $user->id && $bug->assigned_to !== $user->id) {
            abort(403, 'غير مصرح لك بتحديث هذا التقرير');
        }
        
        // إذا كان الموظف عادي فقط، التحقق من أن المشروع المحدد متاح له
        if ($isEmployeeOnly && $request->has('project_id')) {
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'severity' => 'required|in:low,medium,high,critical',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:open,in_progress,testing,resolved,closed,duplicate',
            'environment' => 'nullable|string',
            'browser' => 'nullable|string',
            'operating_system' => 'nullable|string',
            'steps_to_reproduce' => 'nullable|array',
            'expected_result' => 'nullable|string',
            'actual_result' => 'nullable|string',
            'resolution_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        
        // If status changed to resolved or closed, set resolution date
        if (in_array($request->status, ['resolved', 'closed']) && !$bug->resolution_date) {
            $data['resolution_date'] = now();
        }

        $bug->update($data);

        return redirect()->route('bugs.index')
            ->with('success', 'تم تحديث الخطأ بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bug $bug)
    {
        $user = auth()->user();
        
        // تحديد التسلسل الهرمي للأدوار
        $higherRoles = ['super_admin', 'admin', 'project_manager', 'hr', 'accountant', 'sales_rep', 'support', 'developer', 'designer'];
        $isHigherRole = $user->hasAnyRole($higherRoles);
        $isEmployeeOnly = $user->hasRole('employee') && !$isHigherRole;
        
        // إذا كان المستخدم موظف عادي فقط، يمكنه حذف فقط التقارير التي أنشأها (وليس المعينة له)
        if ($isEmployeeOnly && $bug->reported_by !== $user->id) {
            abort(403, 'غير مصرح لك بحذف هذا التقرير');
        }
        
        $bug->delete();

        return redirect()->route('bugs.index')
            ->with('success', 'تم حذف الخطأ بنجاح');
    }
}
