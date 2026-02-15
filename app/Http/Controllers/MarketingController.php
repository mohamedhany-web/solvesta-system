<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Sale;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    /**
     * Display a listing of the marketing campaigns and activities.
     */
    public function index()
    {
        // Get marketing-related projects
        $campaigns = Project::with(['client', 'projectManager'])
            ->where('project_type', 'marketing')
            ->orWhere('name', 'like', '%تسويق%')
            ->orWhere('name', 'like', '%حملة%')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get sales data for marketing insights
        $sales = Sale::with('client')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $stats = [
            'total_campaigns' => Project::where('project_type', 'marketing')
                ->orWhere('name', 'like', '%تسويق%')->count(),
            'active_campaigns' => Project::where('project_type', 'marketing')
                ->whereIn('status', ['planning', 'in_progress'])->count(),
            'total_leads' => Sale::whereIn('stage', ['lead', 'qualified'])->count(),
            'conversion_rate' => Sale::count() > 0 
                ? round((Sale::where('stage', 'won')->count() / Sale::count()) * 100) 
                : 0,
        ];

        $clients = Client::orderBy('created_at', 'desc')->take(10)->get();

        return view('marketing.index', compact('campaigns', 'sales', 'stats', 'clients'));
    }
}
