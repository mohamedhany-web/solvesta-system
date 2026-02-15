<?php

namespace App\Http\Controllers;

use App\Models\LoginActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginActivityController extends Controller
{
    /**
     * Display a listing of login activities.
     */
    public function index(Request $request): View
    {
        $query = LoginActivityLog::with('user')
            ->orderBy('activity_at', 'desc');

        // Filter by activity type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('activity_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('activity_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('activity_at', '<=', $request->date_to);
        }

        // Get users for filter dropdown
        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        
        // Apply user filter
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        $activities = $query->paginate(50)->withQueryString();

        // Statistics
        $stats = [
            'total' => LoginActivityLog::count(),
            'today' => LoginActivityLog::whereDate('activity_at', today())->count(),
            'logins' => LoginActivityLog::where('activity_type', 'login')->count(),
            'codes_sent' => LoginActivityLog::where('activity_type', 'verification_code_sent')->orWhere('activity_type', 'verification_code_resend')->count(),
            'codes_verified' => LoginActivityLog::where('activity_type', 'verification_code_verified')->count(),
            'failed' => LoginActivityLog::where('status', 'failed')->count(),
        ];

        return view('login-activity.index', compact('activities', 'stats', 'users'));
    }
}
