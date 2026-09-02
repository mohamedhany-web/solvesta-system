<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\MeetingParticipant;
use App\Models\Department;
use App\Models\Notification;
use App\Services\EmailService;
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
        $scope = request('scope', 'upcoming');

        $meetings = Meeting::with(['organizer', 'participants', 'department'])
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . request('search') . '%')
                        ->orWhere('description', 'like', '%' . request('search') . '%');
                });
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('type'), function ($query) {
                $query->where('type', request('type'));
            })
            ->when(request('mine') === '1', function ($query) use ($user) {
                $query->where('organizer_id', $user->id);
            })
            ->when($scope === 'today', function ($query) {
                $query->whereDate('start_time', today());
            })
            ->when($scope === 'upcoming', function ($query) {
                $query->where('start_time', '>=', now())
                    ->whereIn('status', ['scheduled', 'ongoing']);
            })
            // فلترة حسب القسم للموظفين العاديين
            ->when($user->hasRole('employee') && $user->employee, function ($query) use ($user) {
                $query->where('department_id', $user->employee->department_id);
            })
            ->when($scope === 'upcoming' || $scope === 'today', function ($query) {
                $query->orderBy('start_time', 'asc');
            }, function ($query) {
                $query->orderBy('start_time', 'desc');
            })
            ->paginate(15)
            ->withQueryString();

        $statuses = ['scheduled', 'ongoing', 'completed', 'cancelled'];
        $types = ['internal', 'external', 'online', 'in-person', 'hybrid'];

        $stats = [
            'today' => Meeting::query()
                ->when($user->hasRole('employee') && $user->employee, fn ($q) => $q->where('department_id', $user->employee->department_id))
                ->whereDate('start_time', today())
                ->count(),
            'upcoming' => Meeting::query()
                ->when($user->hasRole('employee') && $user->employee, fn ($q) => $q->where('department_id', $user->employee->department_id))
                ->where('start_time', '>=', now())
                ->whereIn('status', ['scheduled', 'ongoing'])
                ->count(),
            'mine' => Meeting::query()
                ->where('organizer_id', $user->id)
                ->where('start_time', '>=', now())
                ->whereIn('status', ['scheduled', 'ongoing'])
                ->count(),
        ];

        return view('meetings.index', compact('meetings', 'statuses', 'types', 'scope', 'stats'));
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
            'send_email_reminders' => 'nullable|boolean',
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
                'send_email_reminders' => $request->boolean('send_email_reminders', true),
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

            // تأكيد فوري بالإيميل للمنظم + المشاركين عند تفعيل التذكير
            if ($meeting->send_email_reminders) {
                try {
                    app(EmailService::class)->sendMeetingRemindersToRecipients(
                        $meeting->fresh(['organizer', 'participants.user']),
                        'تم جدولة اجتماع جديد'
                    );
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Meeting confirmation email failed', [
                        'meeting_id' => $meeting->id,
                        'error' => $e->getMessage(),
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
            'send_email_reminders' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $startChanged = $meeting->start_time?->format('Y-m-d H:i') !== \Carbon\Carbon::parse($request->start_time)->format('Y-m-d H:i');

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
            'send_email_reminders' => $request->boolean('send_email_reminders', true),
        ]);

        // إذا تغيّر الموعد، أعد تفعيل نوافذ التذكير
        if ($startChanged) {
            $meeting->update([
                'reminder_1d_sent_at' => null,
                'reminder_1h_sent_at' => null,
            ]);
        }

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
