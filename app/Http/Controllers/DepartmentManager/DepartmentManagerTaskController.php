<?php

namespace App\Http\Controllers\DepartmentManager;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\DepartmentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DepartmentManagerTaskController extends Controller
{
    public function create(Request $request)
    {
        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId, 403);

        $projects = Project::query()
            ->where('department_id', $managedDeptId)
            ->whereIn('status', ['planning', 'in_progress', 'on_hold'])
            ->orderByDesc('created_at')
            ->get();

        $departmentUserIds = Employee::query()
            ->where('department_id', $managedDeptId)
            ->where('status', 'active')
            ->whereNotNull('user_id')
            ->pluck('user_id');

        $users = User::query()
            ->whereIn('id', $departmentUserIds)
            ->orderBy('name')
            ->get();

        return view('department-manager.tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId, 403);

        $project = Project::findOrFail($request->input('project_id'));
        abort_unless((int) $project->department_id === (int) $managedDeptId, 403);

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
            'attachments.*' => 'nullable|file|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ensure assignee belongs to same department
        $assigneeEmployee = Employee::query()
            ->where('user_id', $request->input('assigned_to'))
            ->where('department_id', $managedDeptId)
            ->where('status', 'active')
            ->first();

        if (!$assigneeEmployee) {
            return redirect()->back()->withInput()->with('error', 'لا يمكن إسناد المهمة إلا لموظف داخل نفس القسم.');
        }

        $tags = [];
        if ($request->filled('tags')) {
            $tagsJson = json_decode($request->tags, true);
            if (is_array($tagsJson)) {
                $tags = array_values(array_filter($tagsJson, fn ($tag) => !empty(trim((string) $tag))));
            }
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
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

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $project->id,
            'assigned_to' => $request->assigned_to,
            'priority' => $request->priority,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'estimated_hours' => $request->estimated_hours,
            'progress_percentage' => $request->progress_percentage,
            'tags' => !empty($tags) ? $tags : null,
            'attachments' => !empty($attachments) ? $attachments : null,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->route('department-manager.dashboard')->with('success', 'تم إنشاء المهمة بنجاح');
    }
}

