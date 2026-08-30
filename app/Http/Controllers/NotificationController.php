<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        if ($request->has('json') && $request->json) {
            $notifications = auth()->user()->notifications()
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'notifications' => $notifications,
                'unread_count' => auth()->user()->notifications()->unread()->count(),
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $user = auth()->user();
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        $query = $user->notifications();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('message', 'like', '%'.$search.'%');
            });
        }

        switch ($filter) {
            case 'unread':
                $query->where('is_read', false);
                break;
            case 'task':
                $query->where('type', 'task');
                break;
            case 'project':
                $query->where('type', 'project');
                break;
            case 'message':
                $query->where('type', 'message');
                break;
            case 'official_mail':
                $query->where('type', 'official_mail');
                break;
            case 'today':
                $query->whereDate('created_at', now());
                break;
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        $totalCount = $user->notifications()->count();
        $unreadCount = $user->notifications()->where('is_read', false)->count();
        $taskCount = $user->notifications()->where('type', 'task')->count();
        $projectCount = $user->notifications()->where('type', 'project')->count();
        $messageCount = $user->notifications()->where('type', 'message')->count();
        $mailCount = $user->notifications()->where('type', 'official_mail')->count();
        $todayCount = $user->notifications()->whereDate('created_at', now())->count();

        return view('notifications.index', compact(
            'notifications',
            'filter',
            'search',
            'totalCount',
            'unreadCount',
            'taskCount',
            'projectCount',
            'messageCount',
            'mailCount',
            'todayCount'
        ));
    }

    /**
     * Show a single notification and mark it as read.
     */
    public function show(Notification $notification): View
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        if (! $notification->is_read) {
            $notification->markAsRead();
            $notification->refresh();
        }

        return view('notifications.show', compact('notification'));
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(): JsonResponse
    {
        $count = auth()->user()->notifications()->unread()->count();

        return response()->json(['count' => $count], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, Notification $notification): JsonResponse|RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->markAsRead();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم تعليم الإشعار كمقروء');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse|RedirectResponse
    {
        auth()->user()->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة');
    }

    /**
     * Delete notification
     */
    public function destroy(Request $request, Notification $notification): JsonResponse|RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('notifications.index')
            ->with('success', 'تم حذف الإشعار');
    }
}
