<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\TrainingParticipant;
use App\Models\Department;
use App\Models\Notification;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->can('view-training')) {
            abort(403, 'غير مصرح لك بعرض برامج التدريب');
        }

        $user = auth()->user();
        $trainings = Training::with(['instructor', 'participants', 'department'])
            ->withCount('internshipApplications')
            ->when(request('search'), function ($query) {
                $query->where('title', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('type'), function ($query) {
                $query->where('type', request('type'));
            })
            // فلترة حسب القسم للموظفين العاديين
            ->when($user->hasRole('employee') && $user->employee, function ($query) use ($user) {
                $query->where('department_id', $user->employee->department_id);
            })
            ->orderBy('start_date', 'desc')
            ->paginate(15);

        $statuses = ['planned', 'ongoing', 'completed', 'cancelled'];
        $types = ['internal', 'external', 'online', 'workshop', 'seminar'];

        return view('training.index', compact('trainings', 'statuses', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create-training')) {
            abort(403, 'غير مصرح لك بإنشاء برامج تدريبية');
        }

        // جلب المدربين (يمكن أن يكونوا موظفين أو مستخدمين)
        $instructors = User::whereHas('employee')
            ->orWhereHas('roles', function($q) {
                $q->whereIn('name', ['super_admin', 'admin', 'hr', 'project_manager']);
            })
            ->get();

        // جلب الأقسام
        $departments = Department::all();

        return view('training.create', compact('instructors', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create-training')) {
            abort(403, 'غير مصرح لك بإنشاء برامج تدريبية');
        }

        $request->merge([
            'instructor_id' => $request->filled('instructor_id') ? (int) $request->input('instructor_id') : null,
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:internal,external,online,workshop,seminar',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'cost' => 'nullable|numeric|min:0',
            'instructor_id' => 'nullable|integer|exists:users,id',
            'department_id' => 'required|exists:departments,id',
        ], [
            'title.required' => 'يجب إدخال عنوان البرنامج التدريبي',
            'description.required' => 'يجب إدخال وصف البرنامج',
            'type.required' => 'يجب اختيار نوع التدريب',
            'status.required' => 'يجب تحديد حالة البرنامج',
            'start_date.required' => 'يجب تحديد تاريخ البدء',
            'end_date.required' => 'يجب تحديد تاريخ الانتهاء',
            'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ البدء',
            'max_participants.required' => 'يجب تحديد الحد الأقصى للمشاركين',
            'max_participants.min' => 'الحد الأقصى للمشاركين يجب أن يكون على الأقل 1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'تعذر الحفظ: يرجى مراجعة الحقول المظللة وتصحيح الأخطاء.')
                ->withInput();
        }

        try {
            $training = Training::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'max_participants' => $request->max_participants,
                'cost' => $request->cost ?? 0,
                'instructor_id' => $request->instructor_id,
                'department_id' => $request->department_id,
            ]);

            $this->notifyDepartmentEmployeesNewTraining($training, (int) $request->department_id);

            return redirect()->route('training.index')
                ->with('success', 'تم إنشاء البرنامج التدريبي بنجاح');
        } catch (\Throwable $e) {
            \Log::error('Training store failed', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء البرنامج التدريبي: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * إنشاء ثلاثة برامج تدريبية تجريبية دفعة واحدة (للاختبار والعرض).
     */
    public function storeDemo(Request $request)
    {
        if (! auth()->user()->can('create-training')) {
            abort(403, 'غير مصرح لك بإنشاء برامج تدريبية');
        }

        $department = Department::query()->orderBy('id')->first();
        if (! $department) {
            return redirect()->route('training.create')
                ->with('error', 'لا يوجد أي قسم في النظام. أضف قسمًا من «الأقسام» ثم أعد المحاولة.');
        }

        $instructorId = User::query()
            ->where(function ($q) {
                $q->whereHas('employee')
                    ->orWhereHas('roles', function ($r) {
                        $r->whereIn('name', ['super_admin', 'admin', 'hr', 'project_manager']);
                    });
            })
            ->value('id');

        $base = now()->addWeek()->startOfDay();
        $rows = [
            [
                'title' => 'برنامج تجريبي — أساسيات التواصل في العمل',
                'description' => 'برنامج تجريبي لاختبار النظام. يمكنك تعديل البيانات أو حذف البرنامج لاحقًا.',
                'type' => 'workshop',
                'status' => 'planned',
                'start_shift' => 0,
                'end_shift' => 2,
                'max_participants' => 15,
                'cost' => 0,
            ],
            [
                'title' => 'برنامج تجريبي — أمن المعلومات للموظفين',
                'description' => 'مقدمة في ممارسات الأمان الرقمي داخل المؤسسة (بيانات تجريبية).',
                'type' => 'internal',
                'status' => 'planned',
                'start_shift' => 10,
                'end_shift' => 12,
                'max_participants' => 20,
                'cost' => 0,
            ],
            [
                'title' => 'برنامج تجريبي — ورشة أدوات الإنتاجية',
                'description' => 'جلسة أونلاين تجريبية لملء قائمة التدريب والواجهات.',
                'type' => 'online',
                'status' => 'ongoing',
                'start_shift' => 20,
                'end_shift' => 22,
                'max_participants' => 30,
                'cost' => 100,
            ],
        ];

        try {
            DB::transaction(function () use ($rows, $base, $department, $instructorId) {
                foreach ($rows as $row) {
                    $start = $base->copy()->addDays((int) $row['start_shift']);
                    $end = $base->copy()->addDays((int) $row['end_shift']);
                    unset($row['start_shift'], $row['end_shift']);
                    $training = Training::create(array_merge($row, [
                        'start_date' => $start->toDateString(),
                        'end_date' => $end->toDateString(),
                        'department_id' => $department->id,
                        'instructor_id' => $instructorId,
                    ]));
                    $this->notifyDepartmentEmployeesNewTraining($training, (int) $department->id);
                }
            });
        } catch (\Throwable $e) {
            \Log::error('Training demo seed failed', ['exception' => $e->getMessage()]);

            return redirect()->route('training.create')
                ->with('error', 'تعذر إنشاء البرامج التجريبية: ' . $e->getMessage());
        }

        return redirect()->route('training.index')
            ->with('success', 'تم إنشاء 3 برامج تدريبية تجريبية بنجاح.');
    }

    /**
     * إشعار موظفي القسم دون إلغاء حفظ البرنامج إذا فشل أحد الإشعارات.
     */
    private function notifyDepartmentEmployeesNewTraining(Training $training, int $departmentId): void
    {
        $department = Department::find($departmentId);
        if (! $department) {
            return;
        }

        $employees = User::whereHas('employee', function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId)
                ->where('status', 'active');
        })->get();

        $creatorName = auth()->user()->name ?? 'النظام';

        foreach ($employees as $employee) {
            try {
                Notification::create([
                    'user_id' => $employee->id,
                    'type' => 'training',
                    'title' => 'برنامج تدريبي جديد لقسمك',
                    'message' => 'تم إنشاء برنامج تدريبي جديد: '.$training->title.' لقسم '.$department->name,
                    'data' => [
                        'training_id' => $training->id,
                        'department_id' => $departmentId,
                        'created_by' => $creatorName,
                    ],
                    'is_read' => false,
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Training notification skipped', [
                    'user_id' => $employee->id,
                    'training_id' => $training->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Training $training)
    {
        // التحقق من الصلاحيات والوصول حسب القسم
        if (!auth()->user()->can('view-training')) {
            abort(403, 'غير مصرح لك بعرض برامج التدريب');
        }

        $user = auth()->user();
        // الموظفون العاديون يمكنهم فقط رؤية التدريبات الخاصة بقسمهم
        if ($user->hasRole('employee') && $user->employee && $training->department_id != $user->employee->department_id) {
            abort(403, 'غير مصرح لك بعرض هذا البرنامج التدريبي');
        }

        $training->load(['instructor', 'participants.user.employee', 'department']);

        $internshipApplicationsCount = $training->internshipApplications()->count();
        $pendingInternshipApplicationsCount = $training->internshipApplications()->where('status', 'pending')->count();
        
        // جلب الموظفين المتاحين للانضمام (من نفس القسم إذا كان محدود)
        $availableEmployees = User::whereHas('employee', function($q) use ($training) {
                $q->where('status', 'active');
                // إذا كان البرنامج خاص بقسم معين، نجلب فقط موظفي ذلك القسم
                if ($training->department_id) {
                    $q->where('department_id', $training->department_id);
                }
            })
            ->whereDoesntHave('trainingParticipants', function($q) use ($training) {
                $q->where('training_id', $training->id);
            })
            ->get();

        return view('training.show', compact(
            'training',
            'availableEmployees',
            'internshipApplicationsCount',
            'pendingInternshipApplicationsCount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Training $training)
    {
        if (!auth()->user()->can('edit-training')) {
            abort(403, 'غير مصرح لك بتعديل برامج تدريبية');
        }

        $instructors = User::whereHas('employee')
            ->orWhereHas('roles', function($q) {
                $q->whereIn('name', ['super_admin', 'admin', 'hr', 'project_manager']);
            })
            ->get();

        // جلب الأقسام
        $departments = Department::all();

        return view('training.edit', compact('training', 'instructors', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Training $training)
    {
        if (!auth()->user()->can('edit-training')) {
            abort(403, 'غير مصرح لك بتعديل برامج تدريبية');
        }

        $request->merge([
            'instructor_id' => $request->filled('instructor_id') ? (int) $request->input('instructor_id') : null,
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:internal,external,online,workshop,seminar',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'cost' => 'nullable|numeric|min:0',
            'instructor_id' => 'nullable|integer|exists:users,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'تعذر الحفظ: يرجى مراجعة الحقول وتصحيح الأخطاء.')
                ->withInput();
        }

        $training->update($request->only([
            'title', 'description', 'type', 'status', 'start_date', 'end_date',
            'max_participants', 'cost', 'instructor_id', 'department_id',
        ]));

        return redirect()->route('training.index')
            ->with('success', 'تم تحديث البرنامج التدريبي بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Training $training)
    {
        if (!auth()->user()->can('delete-training')) {
            abort(403, 'غير مصرح لك بحذف برامج تدريبية');
        }

        $training->delete();

        return redirect()->route('training.index')
            ->with('success', 'تم حذف البرنامج التدريبي بنجاح');
    }

    /**
     * List public internship applications for a training (admin / HR).
     */
    public function applications(Training $training)
    {
        if (!auth()->user()->can('view-training')) {
            abort(403, 'غير مصرح لك بعرض طلبات التدريب');
        }

        $applications = InternshipApplication::query()
            ->where('training_id', $training->id)
            ->latest()
            ->paginate(20);

        return view('training.applications', compact('training', 'applications'));
    }

    public function updateApplicationStatus(Request $request, InternshipApplication $application)
    {
        if (!auth()->user()->can('edit-training')) {
            abort(403, 'غير مصرح لك بتعديل طلبات التدريب');
        }

        $data = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $application->update([
            'status' => $data['status'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب');
    }

    /**
     * Add participant to training
     */
    public function addParticipant(Request $request, Training $training)
    {
        if (!auth()->user()->can('edit-training')) {
            abort(403, 'غير مصرح لك بإضافة مشاركين');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // التحقق من عدم تجاوز الحد الأقصى للمشاركين
        $currentParticipants = $training->participants()->count();
        if ($currentParticipants >= $training->max_participants) {
            return redirect()->back()
                ->withErrors(['error' => 'تم الوصول للحد الأقصى للمشاركين']);
        }

        // التحقق من عدم وجود المستخدم بالفعل
        if ($training->participants()->where('user_id', $request->user_id)->exists()) {
            return redirect()->back()
                ->withErrors(['error' => 'المستخدم مسجل بالفعل في هذا البرنامج']);
        }

        TrainingParticipant::create([
            'training_id' => $training->id,
            'user_id' => $request->user_id,
            'status' => 'registered',
        ]);

        return redirect()->back()
            ->with('success', 'تم إضافة المشارك بنجاح');
    }

    /**
     * Remove participant from training
     */
    public function removeParticipant(Training $training, $participantId)
    {
        if (!auth()->user()->can('edit-training')) {
            abort(403, 'غير مصرح لك بإزالة مشاركين');
        }

        $participant = TrainingParticipant::where('training_id', $training->id)
            ->where('id', $participantId)
            ->firstOrFail();

        $participant->delete();

        return redirect()->back()
            ->with('success', 'تم إزالة المشارك بنجاح');
    }
}
