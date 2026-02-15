<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    /**
     * Display a listing of the design projects.
     */
    public function index()
    {
        // Get design-related projects
        $projects = Project::with(['client', 'projectManager', 'teamMembers', 'tasks'])
            ->where(function($query) {
                $query->where('project_type', 'design')
                    ->orWhere('name', 'like', '%تصميم%')
                    ->orWhere('description', 'like', '%تصميم%');
            })
            ->when(request('search'), function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('description', 'like', '%' . request('search') . '%');
            })
            ->when(request('status'), function ($query) {
                $query->where('status', request('status'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate statistics
        $designQuery = Project::where(function($query) {
            $query->where('project_type', 'design')
                ->orWhere('name', 'like', '%تصميم%')
                ->orWhere('description', 'like', '%تصميم%');
        });

        $stats = [
            'total' => (clone $designQuery)->count(),
            'active' => (clone $designQuery)->whereIn('status', ['planning', 'in_progress'])->count(),
            'completed' => (clone $designQuery)->where('status', 'completed')->count(),
            'on_hold' => (clone $designQuery)->where('status', 'on_hold')->count(),
        ];

        $designers = User::all();

        return view('design.index', compact('projects', 'stats', 'designers'));
    }
}
