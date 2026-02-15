<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request)
    {
        if ($request->has('json') && $request->json) {
            // Return JSON for dropdown
            $notifications = auth()->user()->notifications()
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
            
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => auth()->user()->notifications()->unread()->count()
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }
        
        $user = auth()->user();
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        $query = $user->notifications();

        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('message', 'like', '%' . $search . '%');
            });
        }

        // Apply filters
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
            case 'today':
                $query->whereDate('created_at', now());
                break;
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get counts for filters
        $totalCount = $user->notifications()->count();
        $unreadCount = $user->notifications()->where('is_read', false)->count();
        $taskCount = $user->notifications()->where('type', 'task')->count();
        $projectCount = $user->notifications()->where('type', 'project')->count();
        $messageCount = $user->notifications()->where('type', 'message')->count();
        $todayCount = $user->notifications()->whereDate('created_at', now())->count();

        return view('notifications.index', compact('notifications', 'filter', 'search', 'totalCount', 'unreadCount', 'taskCount', 'projectCount', 'messageCount', 'todayCount'));
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
    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        auth()->user()->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
