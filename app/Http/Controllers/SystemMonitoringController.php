<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\LoginActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SystemMonitoringController extends Controller
{
    /**
     * Display comprehensive system monitoring dashboard.
     */
    public function index(Request $request): View
    {
        // Get filters
        $filters = [
            'type' => $request->get('type', 'all'), // all, activity, login
            'action' => $request->get('action', 'all'),
            'user_id' => $request->get('user_id'),
            'model_type' => $request->get('model_type', 'all'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'ip_address' => $request->get('ip_address'),
            'status' => $request->get('status', 'all'), // all, success, failed
        ];

        // Combine ActivityLogs and LoginActivityLogs
        $activities = collect();
        
        // Get Activity Logs
        if (in_array($filters['type'], ['all', 'activity'])) {
            $activityLogs = ActivityLog::with('user')
                ->when($filters['action'] !== 'all', function ($query) use ($filters) {
                    $query->where('action', $filters['action']);
                })
                ->when($filters['user_id'], function ($query) use ($filters) {
                    $query->where('user_id', $filters['user_id']);
                })
                ->when($filters['model_type'] !== 'all', function ($query) use ($filters) {
                    $query->where('model_type', $filters['model_type']);
                })
                ->when($filters['date_from'], function ($query) use ($filters) {
                    $query->whereDate('created_at', '>=', $filters['date_from']);
                })
                ->when($filters['date_to'], function ($query) use ($filters) {
                    $query->whereDate('created_at', '<=', $filters['date_to']);
                })
                ->when($filters['ip_address'], function ($query) use ($filters) {
                    $query->where('ip_address', 'like', '%' . $filters['ip_address'] . '%');
                })
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($log) {
                    return [
                        'id' => 'activity_' . $log->id,
                        'type' => 'activity',
                        'user_id' => $log->user_id,
                        'user' => $log->user,
                        'action' => $log->action,
                        'action_name' => $log->action_name,
                        'description' => $log->description,
                        'model_type' => $log->model_type,
                        'model_id' => $log->model_id,
                        'model_name' => $log->model_name ?? null,
                        'ip_address' => $log->ip_address,
                        'user_agent' => $log->user_agent,
                        'status' => 'success',
                        'created_at' => $log->created_at,
                        'color' => $log->action_color,
                    ];
                });

            $activities = $activities->merge($activityLogs);
        }

        // Get Login Activity Logs
        if (in_array($filters['type'], ['all', 'login'])) {
            $loginLogs = LoginActivityLog::with('user')
                ->when($filters['action'] !== 'all', function ($query) use ($filters) {
                    $query->where('activity_type', $filters['action']);
                })
                ->when($filters['user_id'], function ($query) use ($filters) {
                    $query->where('user_id', $filters['user_id']);
                })
                ->when($filters['date_from'], function ($query) use ($filters) {
                    $query->whereDate('activity_at', '>=', $filters['date_from']);
                })
                ->when($filters['date_to'], function ($query) use ($filters) {
                    $query->whereDate('activity_at', '<=', $filters['date_to']);
                })
                ->when($filters['ip_address'], function ($query) use ($filters) {
                    $query->where('ip_address', 'like', '%' . $filters['ip_address'] . '%');
                })
                ->when($filters['status'] !== 'all', function ($query) use ($filters) {
                    $query->where('status', $filters['status']);
                })
                ->orderBy('activity_at', 'desc')
                ->get()
                ->map(function ($log) {
                    $actionNames = [
                        'login' => 'تسجيل الدخول',
                        'logout' => 'تسجيل الخروج',
                        'verification_code_sent' => 'إرسال كود التحقق',
                        'verification_code_verified' => 'التحقق من الكود',
                        'verification_code_resend' => 'إعادة إرسال الكود',
                    ];

                    return [
                        'id' => 'login_' . $log->id,
                        'type' => 'login',
                        'user_id' => $log->user_id,
                        'user' => $log->user,
                        'action' => $log->activity_type,
                        'action_name' => $actionNames[$log->activity_type] ?? $log->activity_type,
                        'description' => $log->description ?? '',
                        'model_type' => null,
                        'model_id' => null,
                        'model_name' => null,
                        'ip_address' => $log->ip_address,
                        'user_agent' => $log->user_agent,
                        'status' => $log->status,
                        'related_code' => $log->related_code,
                        'target_email' => $log->target_email,
                        'created_at' => $log->activity_at,
                        'color' => $log->status === 'success' ? 'green' : 'red',
                    ];
                });

            $activities = $activities->merge($loginLogs);
        }

        // Sort by created_at descending
        $activities = $activities->sortByDesc('created_at')->values();

        // Paginate manually
        $perPage = 50;
        $currentPage = $request->get('page', 1);
        $items = $activities->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedActivities = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $activities->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get statistics
        $stats = [
            'total_activities' => ActivityLog::count(),
            'total_login_activities' => LoginActivityLog::count(),
            'today_activities' => ActivityLog::whereDate('created_at', today())->count(),
            'today_login_activities' => LoginActivityLog::whereDate('activity_at', today())->count(),
            'failed_logins' => LoginActivityLog::where('activity_type', 'login')
                ->where('status', 'failed')
                ->count(),
            'successful_logins' => LoginActivityLog::where('activity_type', 'login')
                ->where('status', 'success')
                ->count(),
            'verification_codes_sent' => LoginActivityLog::whereIn('activity_type', ['verification_code_sent', 'verification_code_resend'])
                ->where('status', 'success')
                ->count(),
            'most_active_users' => ActivityLog::select('user_id', DB::raw('count(*) as count'))
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->with('user')
                ->get(),
            'recent_failed_attempts' => LoginActivityLog::where('status', 'failed')
                ->orderBy('activity_at', 'desc')
                ->limit(10)
                ->with('user')
                ->get(),
        ];

        // Get filter options
        $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'email']);
        $actions = ActivityLog::distinct()->pluck('action')->sort();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->sort();

        return view('system-monitoring.index', compact('paginatedActivities', 'stats', 'filters', 'users', 'actions', 'modelTypes'));
    }
}









