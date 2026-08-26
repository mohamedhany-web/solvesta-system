<?php

namespace App\Services\CompanyOs;

use App\Models\ApprovalRequest;
use App\Models\Employee;
use App\Models\EntityLifecycle;
use App\Models\FinancialInvoice;
use App\Models\Project;

class SlaEngine
{
    public function __construct(private ExceptionService $exceptions) {}

    public function scan(): int
    {
        $opened = 0;
        $opened += $this->scanLifecycleSlas();
        $opened += $this->scanProjectDeadlines();
        $opened += $this->scanOverdueInvoices();
        $opened += $this->scanPendingApprovals();

        return $opened;
    }

    private function scanLifecycleSlas(): int
    {
        $count = 0;
        $due = EntityLifecycle::query()
            ->with(['currentState', 'subject'])
            ->whereNotNull('sla_due_at')
            ->where('sla_due_at', '<', now())
            ->whereNull('sla_breached_at')
            ->get();

        foreach ($due as $lifecycle) {
            $subject = $lifecycle->subject;
            if (! $subject || $lifecycle->currentState?->is_terminal) {
                continue;
            }

            $lifecycle->update(['sla_breached_at' => now()]);
            $severity = $lifecycle->currentState->severity_if_breached ?: 'warning';
            $this->exceptions->open(
                $subject,
                'sla_breached',
                'تجاوز SLA — '.$lifecycle->currentState->label_ar,
                $severity,
                'الحالة «'.$lifecycle->currentState->label_ar.'» تجاوزت المهلة المتفق عليها.',
                $lifecycle->owner_id
            );
            $count++;
        }

        return $count;
    }

    private function scanProjectDeadlines(): int
    {
        $count = 0;
        $projects = Project::query()
            ->whereNotNull('end_date')
            ->where('end_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get();

        foreach ($projects as $project) {
            $days = now()->startOfDay()->diffInDays($project->end_date);
            $this->exceptions->open(
                $project,
                'project_overdue',
                'مشروع متأخر عن الجدول',
                $days >= 3 ? 'critical' : 'warning',
                $project->name.' تجاوز تاريخ التسليم بـ '.$days.' يوم.',
                $project->project_manager_id
            );
            $count++;
        }

        return $count;
    }

    private function scanOverdueInvoices(): int
    {
        $count = 0;
        $invoices = FinancialInvoice::query()
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->whereDate('due_date', '<', now())
            ->get();

        foreach ($invoices as $invoice) {
            if ($invoice->status === 'sent') {
                $invoice->forceFill(['status' => 'overdue'])->saveQuietly();
            }

            $this->exceptions->open(
                $invoice,
                'invoice_overdue',
                'فاتورة متأخرة — '.$invoice->invoice_number,
                'critical',
                'فاتورة مستحقة ولم تُسدَّد. المتبقي: '.number_format((float) $invoice->balance_due).' ج.م',
                $invoice->created_by
            );
            $count++;
        }

        return $count;
    }

    private function scanPendingApprovals(): int
    {
        $count = 0;
        $pending = ApprovalRequest::query()
            ->with('subject')
            ->where('status', 'pending')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        foreach ($pending as $request) {
            if (! $request->subject) {
                continue;
            }
            $this->exceptions->open(
                $request->subject,
                'approval_pending',
                'موافقة معلّقة أكثر من 24 ساعة',
                'warning',
                'سلسلة الاعتماد لم تكتمل.',
                $request->requested_by
            );
            $count++;
        }

        return $count;
    }

    public function onboardingOpenCount(): int
    {
        return Employee::query()
            ->whereHas('lifecycle.currentState', fn ($q) => $q->where('key', 'onboarding'))
            ->count();
    }
}
