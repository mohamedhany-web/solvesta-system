<?php

namespace App\Http\Controllers;

use App\Services\WorkflowTrackService;
use App\Services\WorkspaceGovernanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkspaceOpsController extends Controller
{
    public function index(Request $request, WorkspaceGovernanceService $governance, WorkflowTrackService $tracks): View
    {
        $user = $request->user();
        abort_unless(
            $user->hasAnyRole(['super_admin', 'admin', 'project_manager'])
            || $user->can('view-all-tasks')
            || $user->can('view-reports'),
            403
        );

        $track = $request->get('track');
        if ($track && ! isset($tracks->tracks()[$track])) {
            $track = null;
        }

        $projectId = $request->integer('project_id') ?: null;
        $snapshot = $governance->executiveSnapshot($track, $projectId);
        $availableTracks = $tracks->tracks();

        return view('workspace.ops', compact('snapshot', 'track', 'projectId', 'availableTracks'));
    }
}
