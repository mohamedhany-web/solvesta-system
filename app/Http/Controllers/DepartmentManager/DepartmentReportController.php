<?php

namespace App\Http\Controllers\DepartmentManager;

use App\Http\Controllers\Controller;
use App\Models\DepartmentReport;
use App\Models\Project;
use App\Policies\DepartmentAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', DepartmentReport::class);

        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId, 403);

        $reports = DepartmentReport::query()
            ->with(['project', 'creator'])
            ->where('department_id', $managedDeptId)
            ->latest()
            ->paginate(15);

        return view('department-reports.manager.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', DepartmentReport::class);

        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId, 403);

        $projects = Project::query()
            ->where('department_id', $managedDeptId)
            ->orderByDesc('created_at')
            ->get();

        return view('department-reports.manager.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', DepartmentReport::class);

        $managedDeptId = DepartmentAccess::managedDepartmentId($request->user());
        abort_unless($managedDeptId, 403);

        $validator = Validator::make($request->all(), [
            'project_id' => 'nullable|exists:projects,id',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'summary' => 'required|string|max:20000',
            'kpis' => 'nullable|string', // JSON
            'attachments.*' => 'nullable|file|max:10240',
            'submit' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->filled('project_id')) {
            $project = Project::find($request->project_id);
            if (!$project || (int) $project->department_id !== (int) $managedDeptId) {
                return redirect()->back()->with('error', 'المشروع المحدد ليس ضمن قسمك.')->withInput();
            }
        }

        $kpis = null;
        if ($request->filled('kpis')) {
            $decoded = json_decode($request->kpis, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $kpis = $decoded;
            }
        }

        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ((array) $request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('department-reports/attachments', 'public');
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

        $status = $request->boolean('submit') ? 'submitted' : 'draft';
        $submittedAt = $status === 'submitted' ? now() : null;

        DepartmentReport::create([
            'department_id' => $managedDeptId,
            'project_id' => $request->project_id,
            'created_by' => $request->user()->id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'summary' => $request->summary,
            'kpis' => $kpis,
            'attachments' => !empty($attachments) ? $attachments : null,
            'status' => $status,
            'submitted_at' => $submittedAt,
        ]);

        return redirect()->route('department-manager.reports.index')->with('success', 'تم إنشاء التقرير بنجاح');
    }

    public function show(Request $request, DepartmentReport $departmentReport)
    {
        $this->authorize('view', $departmentReport);

        $departmentReport->load(['department', 'project', 'creator']);

        return view('department-reports.manager.show', ['report' => $departmentReport]);
    }
}

