<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Employee;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Task::with(['project', 'assignedTo']);
        
        // إذا كان المستخدم لديه صلاحية view-own-tasks فقط
        if (auth()->user()->can('view-own-tasks') && !auth()->user()->can('view-all-tasks')) {
            // عرض المهام المخصصة للمستخدم فقط
            $query->where('assigned_to', auth()->id());
        }
        
        $tasks = $query
            ->when(request('search'), function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('project_id'), function ($query) {
                $query->where('project_id', request('project_id'));
            })
            ->paginate(15);

        $projectsQuery = Project::where('status', 'in_progress');
        
        // Filter projects based on permissions
        if (auth()->user()->can('view-own-projects') && !auth()->user()->can('view-all-projects')) {
            $projectsQuery->where(function($q) {
                $q->where('project_manager_id', auth()->id())
                  ->orWhereHas('teamMembers', function($teamQuery) {
                      $teamQuery->where('user_id', auth()->id());
                  });
            });
        }
        
        $projects = $projectsQuery->get();
        
        // Calculate statistics based on permissions
        $statsQuery = Task::query();
        if (auth()->user()->can('view-own-tasks') && !auth()->user()->can('view-all-tasks')) {
            $statsQuery->where('assigned_to', auth()->id());
        }
        
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'in_progress' => (clone $statsQuery)->where('status', 'in_progress')->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
        ];

        return view('tasks.index', compact('tasks', 'projects', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('viewAny', Task::class);
        $projects = Project::where('status', 'in_progress')->get();
        // جلب المستخدمين من جميع المشاريع المتاحة للمستخدم الحالي (أعضاء الفريق ومديري المشاريع)
        $users = $this->getProjectMembers($projects);

        return view('tasks.create', compact('projects', 'users'));
    }
    
    /**
     * Get project members for task assignment
     */
    private function getProjectMembers($projects = null, $projectId = null)
    {
        $user = auth()->user();
        
        // Super admin and admin can see all active employees
        if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
            return User::whereHas('employee', function($q) {
                $q->where('status', 'active');
            })->get();
        }
        
        // For other users: get members from projects they have access to
        if ($projectId) {
            // Get members of a specific project
            $project = Project::find($projectId);
            if (!$project) {
                return collect();
            }
            
            $teamMemberIds = DB::table('project_team_members')
                ->where('project_id', $projectId)
                ->pluck('user_id');
            
            $managerId = $project->project_manager_id ? [$project->project_manager_id] : [];
            
            $allowedIds = $teamMemberIds->merge($managerId)->unique()->filter(function($id) use ($user) {
                return (int)$id !== (int)$user->id;
            })->values();
            
            return User::whereIn('id', $allowedIds)
                ->whereHas('employee', function($q) {
                    $q->where('status', 'active');
                })->get();
        }
        
        // Get members from all available projects
        if ($projects && $projects->count() > 0) {
            $projectIds = $projects->pluck('id');
            
            $teamMemberIds = DB::table('project_team_members')
                ->whereIn('project_id', $projectIds)
                ->pluck('user_id');
            
            $managerIds = Project::whereIn('id', $projectIds)
                ->whereNotNull('project_manager_id')
                ->pluck('project_manager_id');
            
            $allowedIds = $teamMemberIds->merge($managerIds)
                ->unique()
                ->filter(function($id) use ($user) {
                    return (int)$id !== (int)$user->id;
                })->values();
            
            return User::whereIn('id', $allowedIds)
                ->whereHas('employee', function($q) {
                    $q->where('status', 'active');
                })->get();
        }
        
        return collect();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $project = Project::find($request->input('project_id'));
        $this->authorize('create', [Task::class, $project]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,completed,cancelled',
            'start_date' => 'nullable|date',
            'due_date' => 'required|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240', // 10MB per file
        ], [
            'title.required' => 'يجب إدخال عنوان المهمة',
            'description.required' => 'يجب إدخال وصف المهمة',
            'project_id.required' => 'يجب اختيار المشروع',
            'assigned_to.required' => 'يجب اختيار المسؤول',
            'priority.required' => 'يجب تحديد الأولوية',
            'status.required' => 'يجب تحديد الحالة',
            'due_date.required' => 'يجب تحديد تاريخ الاستحقاق',
            'attachments.*.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // معالجة التسميات (Tags)
            $tags = [];
            if ($request->filled('tags')) {
                $tagsJson = json_decode($request->tags, true);
                if (is_array($tagsJson)) {
                    $tags = array_filter($tagsJson, function($tag) {
                        return !empty(trim($tag));
                    });
                }
            }

            // معالجة المرفقات (Attachments)
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        // حفظ الملف في مجلد tasks
                        $path = $file->store('tasks/attachments', 'public');
                        $attachments[] = [
                            'path' => $path,
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'uploaded_at' => now()->toDateTimeString(),
                        ];
                    }
                }
            }

            // إضافة created_by تلقائياً
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $data['tags'] = !empty($tags) ? $tags : null;
            $data['attachments'] = !empty($attachments) ? $attachments : null;

            $task = Task::create($data);

            // إنشاء إشعار للمستخدم المعين
            $assignedUser = User::find($request->assigned_to);
            if ($assignedUser) {
                // إنشاء إشعار في النظام
                Notification::create([
                    'user_id' => $assignedUser->id,
                    'type' => 'task',
                    'title' => 'مهمة جديدة مخصصة لك',
                    'message' => 'تم تعيين مهمة جديدة لك: ' . $task->title,
                    'data' => [
                        'task_id' => $task->id,
                        'project_id' => $task->project_id,
                        'created_by' => auth()->user()->name,
                    ],
                    'is_read' => false,
                ]);

                // إرسال بريد إلكتروني للمستخدم المعين
                try {
                    $projectName = $task->project ? $task->project->name : 'غير محدد';
                    $dueDate = $task->due_date ? $task->due_date->format('Y-m-d') : 'غير محدد';
                    $priorityNames = [
                        'low' => 'منخفضة',
                        'medium' => 'متوسطة',
                        'high' => 'عالية',
                        'urgent' => 'عاجلة'
                    ];
                    $priorityName = $priorityNames[$task->priority] ?? $task->priority;

                    $emailData = [
                        'task' => $task,
                        'assignedUser' => $assignedUser,
                        'createdBy' => auth()->user(),
                        'projectName' => $projectName,
                        'dueDate' => $dueDate,
                        'priorityName' => $priorityName,
                        'taskUrl' => route('tasks.show', $task->id),
                    ];

                    Mail::send('emails.task-assigned', $emailData, function($message) use ($assignedUser, $task) {
                        $message->to($assignedUser->email, $assignedUser->name)
                                ->subject('مهمة جديدة مخصصة لك: ' . $task->title);
                    });
                } catch (\Exception $e) {
                    // تسجيل الخطأ ولكن لا نوقف العملية
                    \Log::error('Failed to send task assignment email: ' . $e->getMessage());
                }
            }

            return redirect()->route('tasks.index')
                ->with('success', 'تم إنشاء المهمة بنجاح وإرسال إشعار للمستخدم المعين');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء المهمة: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['project', 'assignedTo', 'createdBy', 'updates.user']);
        
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $projects = Project::where('status', 'in_progress')->get();
        // جلب المستخدمين من المشروع المحدد في المهمة (أعضاء الفريق ومدير المشروع)
        $users = $this->getProjectMembers(null, $task->project_id);

        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:todo,in_progress,review,completed,cancelled',
            'due_date' => 'required|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'actual_hours' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $task->update($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'تم تحديث المهمة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'تم حذف المهمة بنجاح');
    }

    /**
     * Add a comment/update to the task.
     */
    public function addUpdate(Request $request, Task $task)
    {
        // Allow: admin/management, department manager (via policy), or assignee to add updates
        $this->authorize('view', $task);

        $user = auth()->user();
        $isAssignee = (int) $task->assigned_to === (int) auth()->id();
        $isPrivileged = $user && ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->can('view-all-tasks'));
        $isDeptManager = \App\Policies\DepartmentAccess::isDepartmentManager($user);

        if (!$isPrivileged && !$isDeptManager && !$isAssignee) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'comment' => 'nullable|string|max:5000',
            'type' => 'nullable|in:comment,update,progress_update,file_upload',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $type = $request->input('type', 'comment');

        if ($type === 'progress_update' && $request->input('progress_percentage') === null) {
            return response()->json(['error' => 'الرجاء إدخال نسبة الإنجاز'], 422);
        }
        if ($type === 'file_upload' && !$request->hasFile('attachments')) {
            return response()->json(['error' => 'الرجاء اختيار ملف للتسليم'], 422);
        }
        if (!$request->filled('comment') && !$request->hasFile('attachments') && $request->input('progress_percentage') === null) {
            return response()->json(['error' => 'أدخل تعليق/تحديث أو ارفع ملف أو حدّث نسبة الإنجاز'], 422);
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ((array) $request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('tasks/updates', 'public');
                    $attachments[] = [
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                }
            }
        }

        $metadata = [];
        if ($type === 'progress_update') {
            $metadata['progress_percentage'] = (int) $request->input('progress_percentage');
            // keep task progress in sync (soft requirement)
            $task->update(['progress_percentage' => (int) $request->input('progress_percentage')]);
        }

        \App\Models\TaskUpdate::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'comment' => (string) ($request->comment ?? ''),
            'type' => $type,
            'metadata' => !empty($metadata) ? $metadata : null,
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        $message = match ($type) {
            'update' => 'تم إضافة التحديث بنجاح',
            'progress_update' => 'تم تحديث نسبة الإنجاز بنجاح',
            'file_upload' => 'تم رفع التسليم بنجاح',
            default => 'تم إضافة التعليق بنجاح',
        };

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Get project members for task assignment via AJAX
     */
    public function getProjectMembersAjax(Request $request)
    {
        $projectId = $request->input('project_id');
        
        if (!$projectId) {
            return response()->json(['users' => []], 200);
        }

        $users = $this->getProjectMembers(null, $projectId);
        
        $usersArray = $users->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'position' => $user->employee ? $user->employee->position : null,
                'display_text' => $user->name . ($user->employee ? ' - ' . $user->employee->position : '')
            ];
        })->values();

        return response()->json(['users' => $usersArray], 200);
    }
}
