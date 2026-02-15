<?php

namespace App\Http\Controllers;

use App\Models\Training;
use App\Models\User;
use App\Models\TrainingParticipant;
use App\Models\Department;
use App\Models\Notification;
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

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:internal,external,online,workshop,seminar',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'cost' => 'nullable|numeric|min:0',
            'instructor_id' => 'nullable|exists:users,id',
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

            // إنشاء إشعارات للموظفين في نفس القسم
            $department = Department::find($request->department_id);
            if ($department) {
                $employees = User::whereHas('employee', function($q) use ($request) {
                    $q->where('department_id', $request->department_id)
                      ->where('status', 'active');
                })->get();

                foreach ($employees as $employee) {
                    Notification::create([
                        'user_id' => $employee->id,
                        'type' => 'training',
                        'title' => 'برنامج تدريبي جديد لقسمك',
                        'message' => 'تم إنشاء برنامج تدريبي جديد: ' . $training->title . ' لقسم ' . $department->name,
                        'data' => [
                            'training_id' => $training->id,
                            'department_id' => $request->department_id,
                            'created_by' => auth()->user()->name,
                        ],
                        'is_read' => false,
                    ]);
                }
            }

            return redirect()->route('training.index')
                ->with('success', 'تم إنشاء البرنامج التدريبي بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء البرنامج التدريبي: ' . $e->getMessage()])
                ->withInput();
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

        return view('training.show', compact('training', 'availableEmployees'));
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

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:internal,external,online,workshop,seminar',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_participants' => 'required|integer|min:1',
            'cost' => 'nullable|numeric|min:0',
            'instructor_id' => 'nullable|exists:users,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $training->update($request->all());

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
