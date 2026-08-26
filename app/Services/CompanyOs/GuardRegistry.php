<?php

namespace App\Services\CompanyOs;

use App\Models\Employee;
use App\Models\FinancialInvoice;
use App\Models\Project;
use App\Models\WorkItem;
use Illuminate\Database\Eloquent\Model;

class GuardRegistry
{
    public static function check(string $key, Model $model): ?string
    {
        return match ($key) {
            'qa_ready' => self::qaReady($model),
            'delivery_ready' => self::deliveryReady($model),
            'invoice_send' => self::invoiceSend($model),
            'employee_activate' => self::employeeActivate($model),
            default => "حارس غير معروف: {$key}",
        };
    }

    private static function qaReady(Model $model): ?string
    {
        if (! $model instanceof Project) {
            return null;
        }

        if (! $model->project_manager_id) {
            return 'عيّن قائد المشروع قبل الانتقال إلى ضمان الجودة.';
        }

        $open = WorkItem::query()
            ->where('subject_type', $model->getMorphClass())
            ->where('subject_id', $model->getKey())
            ->where('is_required', true)
            ->where('status', 'open')
            ->where('blocks_state', 'qa')
            ->count();

        if ($open > 0) {
            return 'أكمل بنود التطوير المطلوبة قبل ضمان الجودة.';
        }

        return null;
    }

    private static function deliveryReady(Model $model): ?string
    {
        if (! $model instanceof Project) {
            return null;
        }

        $blockers = $model->bugs()
            ->whereIn('severity', ['critical', 'high'])
            ->whereNotIn('status', ['resolved', 'closed', 'duplicate'])
            ->count();

        if ($blockers > 0) {
            return "يوجد {$blockers} خطأ حرج/عالي مفتوح. أغلقه قبل التسليم.";
        }

        return null;
    }

    private static function invoiceSend(Model $model): ?string
    {
        if (! $model instanceof FinancialInvoice) {
            return null;
        }

        $pending = $model->morphMany(\App\Models\ApprovalRequest::class, 'subject')
            ->where('status', 'pending')
            ->exists();

        if ($pending) {
            return 'لا يمكن إرسال الفاتورة وهناك موافقة معلّقة.';
        }

        return null;
    }

    private static function employeeActivate(Model $model): ?string
    {
        if (! $model instanceof Employee) {
            return null;
        }

        if (! $model->user_id) {
            return 'اربط حساب مستخدم قبل تفعيل الموظف.';
        }

        if (! $model->department_id) {
            return 'حدّد القسم قبل تفعيل الموظف.';
        }

        return null;
    }
}
