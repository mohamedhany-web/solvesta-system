<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\WorkItem;
use App\Services\CompanyOs\ApprovalEngine;
use App\Services\CompanyOs\WorkflowEngine;
use App\Services\CompanyOs\WorkItemService;
use Illuminate\Http\Request;
use RuntimeException;

class CompanyOsController extends Controller
{
    public function transition(Request $request, string $type, int $id, WorkflowEngine $engine, ApprovalEngine $approvals)
    {
        $this->assertCanTransition($request);

        $to = $request->validate([
            'to_state' => 'required|string|max:64',
        ])['to_state'];

        $model = $this->resolve($type, $id);

        try {
            $engine->ensureLifecycle($model);
            $fromKey = $model->lifecycleStateKey();

            if ($type === 'financial_invoice' && $fromKey === 'draft' && $to === 'pending_approval') {
                $approvals->request($model, $request->user());
            }

            $engine->transition($model, $to, $request->user());
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'تم تحديث دورة الحياة.');
    }

    public function completeWorkItem(Request $request, WorkItem $workItem, WorkItemService $workItems)
    {
        $this->assertCanTransition($request);
        $workItems->complete($workItem, $request->user());

        return back()->with('success', 'تم إكمال بند العمل.');
    }

    public function decideApproval(Request $request, ApprovalRequest $approval, ApprovalEngine $approvals)
    {
        abort_unless($request->user()->can('approve-lifecycle') || $approvals->userCanAct($approval, $request->user()), 403);

        $data = $request->validate([
            'decision' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            if ($data['decision'] === 'approve') {
                $approvals->approve($approval, $request->user(), $data['notes'] ?? null);
            } else {
                $approvals->reject($approval, $request->user(), $data['notes'] ?? null);
            }
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $data['decision'] === 'approve' ? 'تم الاعتماد.' : 'تم الرفض.');
    }

    private function resolve(string $type, int $id)
    {
        $class = WorkflowEngine::modelClass($type);

        return $class::findOrFail($id);
    }

    private function assertCanTransition(Request $request): void
    {
        $user = $request->user();
        if ($user->can('transition-lifecycle') || $user->hasRole(['super_admin', 'admin', 'project_manager', 'hr', 'accountant'])) {
            return;
        }

        abort(403, 'غير مصرح بتغيير دورة الحياة.');
    }
}
