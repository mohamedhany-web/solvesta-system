<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class JobPostingController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view-jobs'), 403);

        $jobs = JobPosting::with('department')
            ->withCount('applications')
            ->when($request->search, fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('summary', 'like', '%'.$request->search.'%');
            }))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('job-postings.index', compact('jobs'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('create-jobs'), 403);

        $departments = Department::orderBy('name')->get();

        return view('job-postings.create', [
            'departments' => $departments,
            'employmentTypes' => JobPosting::EMPLOYMENT_TYPES,
            'statuses' => JobPosting::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('create-jobs'), 403);

        $data = $this->validateJob($request);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : JobPosting::uniqueSlug($data['title']);

        JobPosting::create([
            ...$data,
            'slug' => $slug,
            'created_by' => auth()->id(),
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        return redirect()->route('job-postings.index')
            ->with('success', 'تم إنشاء الوظيفة بنجاح');
    }

    public function show(JobPosting $jobPosting)
    {
        abort_unless(auth()->user()->can('view-jobs'), 403);

        $jobPosting->load(['department', 'creator']);
        $jobPosting->loadCount('applications');

        return view('job-postings.show', compact('jobPosting'));
    }

    public function edit(JobPosting $jobPosting)
    {
        abort_unless(auth()->user()->can('edit-jobs'), 403);

        $departments = Department::orderBy('name')->get();

        return view('job-postings.edit', [
            'jobPosting' => $jobPosting,
            'departments' => $departments,
            'employmentTypes' => JobPosting::EMPLOYMENT_TYPES,
            'statuses' => JobPosting::STATUSES,
        ]);
    }

    public function update(Request $request, JobPosting $jobPosting)
    {
        abort_unless(auth()->user()->can('edit-jobs'), 403);

        $data = $this->validateJob($request, $jobPosting->id);

        $slug = $request->filled('slug')
            ? Str::slug($request->slug)
            : ($request->filled('title') && $request->title !== $jobPosting->title
                ? JobPosting::uniqueSlug($data['title'], $jobPosting->id)
                : $jobPosting->slug);

        if ($data['status'] === 'published' && ! $jobPosting->published_at) {
            $data['published_at'] = now();
        }

        $jobPosting->update([
            ...$data,
            'slug' => $slug,
        ]);

        return redirect()->route('job-postings.show', $jobPosting)
            ->with('success', 'تم تحديث الوظيفة بنجاح');
    }

    public function destroy(JobPosting $jobPosting)
    {
        abort_unless(auth()->user()->can('delete-jobs'), 403);

        $jobPosting->delete();

        return redirect()->route('job-postings.index')
            ->with('success', 'تم حذف الوظيفة');
    }

    public function applications(JobPosting $jobPosting)
    {
        abort_unless(auth()->user()->can('view-jobs'), 403);

        $applications = $jobPosting->applications()
            ->latest()
            ->paginate(20);

        return view('job-postings.applications', compact('jobPosting', 'applications'));
    }

    public function updateApplicationStatus(Request $request, JobApplication $application)
    {
        abort_unless(auth()->user()->can('edit-jobs'), 403);

        $request->validate([
            'status' => 'required|in:'.implode(',', JobApplication::STATUSES),
        ]);

        $application->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    private function validateJob(Request $request, ?int $ignoreId = null): array
    {
        $slugRule = 'nullable|string|max:190|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/';
        if ($ignoreId) {
            $slugRule .= '|unique:job_postings,slug,'.$ignoreId;
        } else {
            $slugRule .= '|unique:job_postings,slug';
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => $slugRule,
            'summary' => 'nullable|string|max:500',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'location' => 'nullable|string|max:190',
            'employment_type' => 'required|in:'.implode(',', array_keys(JobPosting::EMPLOYMENT_TYPES)),
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:'.implode(',', JobPosting::STATUSES),
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ], [], [
            'title' => 'المسمى الوظيفي',
            'description' => 'الوصف',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        return $data;
    }
}
