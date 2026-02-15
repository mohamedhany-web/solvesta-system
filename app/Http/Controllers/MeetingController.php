<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\MeetingParticipant;
use App\Models\Department;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // التحقق من الصلاحيات
        if (!auth()->user()->can('view-meetings')) {
            abort(403, 'غير مصرح لك بعرض الاجتماعات');
        }

        $user = auth()->user();
        $meetings = Meeting::with(['organizer', 'participants', 'department'])
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
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        $statuses = ['scheduled', 'ongoing', 'completed', 'cancelled'];
        $types = ['internal', 'external', 'online', 'in-person', 'hybrid'];

        return view('meetings.index', compact('meetings', 'statuses', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create-meetings')) {
            abort(403, 'غير مصرح لك بإنشاء اجتماعات');
        }

        // جلب الأقسام
        $departments = Department::all();

        return view('meetings.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('create-meetings')) {
            abort(403, 'غير مصرح لك بإنشاء اجتماعات');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:internal,external,online,in-person,hybrid',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'department_id' => 'required|exists:departments,id',
        ], [
            'title.required' => 'يجب إدخال عنوان الاجتماع',
            'type.required' => 'يجب اختيار نوع الاجتماع',
            'status.required' => 'يجب تحديد حالة الاجتماع',
            'start_time.required' => 'يجب تحديد وقت البدء',
            'end_time.required' => 'يجب تحديد وقت الانتهاء',
            'end_time.after' => 'وقت الانتهاء يجب أن يكون بعد وقت البدء',
            'meeting_link.url' => 'رابط الاجتماع يجب أن يكون رابطاً صحيحاً',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $meeting = Meeting::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'status' => $request->status,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'meeting_link' => $request->meeting_link,
                'organizer_id' => auth()->id(),
                'department_id' => $request->department_id,
            ]);

            // إضافة المشاركين إذا تم تحديدهم
            if ($request->has('participants') && is_array($request->participants)) {
                foreach ($request->participants as $userId) {
                    MeetingParticipant::create([
                        'meeting_id' => $meeting->id,
                        'user_id' => $userId,
                        'status' => 'invited',
                    ]);
                }
            }

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
                        'type' => 'meeting',
                        'title' => 'اجتماع جديد لقسمك',
                        'message' => 'تم إنشاء اجتماع جديد: ' . $meeting->title . ' لقسم ' . $department->name,
                        'data' => [
                            'meeting_id' => $meeting->id,
                            'department_id' => $request->department_id,
                            'created_by' => auth()->user()->name,
                            'start_time' => $meeting->start_time->format('Y-m-d H:i'),
                        ],
                        'is_read' => false,
                    ]);
                }
            }

            return redirect()->route('meetings.index')
                ->with('success', 'تم إنشاء الاجتماع بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الاجتماع: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Meeting $meeting)
    {
        // التحقق من الصلاحيات والوصول حسب القسم
        if (!auth()->user()->can('view-meetings')) {
            abort(403, 'غير مصرح لك بعرض الاجتماعات');
        }

        $user = auth()->user();
        // الموظفون العاديون يمكنهم فقط رؤية الاجتماعات الخاصة بقسمهم
        if ($user->hasRole('employee') && $user->employee && $meeting->department_id != $user->employee->department_id) {
            abort(403, 'غير مصرح لك بعرض هذا الاجتماع');
        }

        $meeting->load(['organizer', 'participants.user.employee', 'department']);
        
        // جلب الموظفين المتاحين للانضمام (من نفس القسم إذا كان محدود)
        $availableEmployees = User::whereHas('employee', function($q) use ($meeting) {
                $q->where('status', 'active');
                // إذا كان الاجتماع خاص بقسم معين، نجلب فقط موظفي ذلك القسم
                if ($meeting->department_id) {
                    $q->where('department_id', $meeting->department_id);
                }
            })
            ->whereDoesntHave('meetingParticipants', function($q) use ($meeting) {
                $q->where('meeting_id', $meeting->id);
            })
            ->where('id', '!=', $meeting->organizer_id)
            ->get();

        return view('meetings.show', compact('meeting', 'availableEmployees'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Meeting $meeting)
    {
        if (!auth()->user()->can('edit-meetings')) {
            abort(403, 'غير مصرح لك بتعديل الاجتماعات');
        }

        $meeting->load('participants');
        
        // جلب الأقسام
        $departments = Department::all();
        
        return view('meetings.edit', compact('meeting', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Meeting $meeting)
    {
        if (!auth()->user()->can('edit-meetings')) {
            abort(403, 'غير مصرح لك بتعديل الاجتماعات');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:internal,external,online,in-person,hybrid',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url|max:500',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $meeting->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'meeting_link' => $request->meeting_link,
            'department_id' => $request->department_id,
        ]);

        // تحديث المشاركين
        if ($request->has('participants')) {
            // حذف المشاركين الحاليين
            $meeting->participants()->delete();
            
            // إضافة المشاركين الجدد
            if (is_array($request->participants)) {
                foreach ($request->participants as $userId) {
                    MeetingParticipant::create([
                        'meeting_id' => $meeting->id,
                        'user_id' => $userId,
                        'status' => 'invited',
                    ]);
                }
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'تم تحديث الاجتماع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Meeting $meeting)
    {
        if (!auth()->user()->can('delete-meetings')) {
            abort(403, 'غير مصرح لك بحذف الاجتماعات');
        }

        $meeting->delete();

        return redirect()->route('meetings.index')
            ->with('success', 'تم حذف الاجتماع بنجاح');
    }

    /**
     * Add participant to meeting
     */
    public function addParticipant(Request $request, Meeting $meeting)
    {
        if (!auth()->user()->can('edit-meetings')) {
            abort(403, 'غير مصرح لك بإضافة مشاركين');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // التحقق من عدم وجود المستخدم بالفعل
        if ($meeting->participants()->where('user_id', $request->user_id)->exists()) {
            return redirect()->back()
                ->withErrors(['error' => 'المستخدم مدعو بالفعل لهذا الاجتماع']);
        }

        MeetingParticipant::create([
            'meeting_id' => $meeting->id,
            'user_id' => $request->user_id,
            'status' => 'invited',
        ]);

        return redirect()->back()
            ->with('success', 'تم إضافة المشارك بنجاح');
    }

    /**
     * Remove participant from meeting
     */
    public function removeParticipant(Meeting $meeting, $participantId)
    {
        if (!auth()->user()->can('edit-meetings')) {
            abort(403, 'غير مصرح لك بإزالة مشاركين');
        }

        $participant = MeetingParticipant::where('meeting_id', $meeting->id)
            ->where('id', $participantId)
            ->firstOrFail();

        $participant->delete();

        return redirect()->back()
            ->with('success', 'تم إزالة المشارك بنجاح');
    }
}
