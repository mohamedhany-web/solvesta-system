<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SalesActivityAdminAlertService
{
    public function notifyLeadCreated(Lead $lead, User $actor): void
    {
        if (! $this->shouldNotify($actor)) {
            return;
        }

        $lead->loadMissing(['assignee']);
        $url = url(route('leads.show', $lead, false));
        $title = 'Lead جديد — '.$actor->name;
        $message = ($lead->company ?: $lead->name).' ('.$lead->reference_code.')';

        $this->notifyInApp($title, $message, 'sales_lead_created', [
            'lead_id' => $lead->id,
            'url' => $url,
            'actor_id' => $actor->id,
        ]);

        $this->sendMail($title, $this->buildBody('تسجيل Lead جديد', [
            'الموظف' => e($actor->name).' ('.e($actor->email).')',
            'المرجع' => e($lead->reference_code),
            'الاسم' => e($lead->name),
            'الشركة' => e($lead->company ?: '—'),
            'البريد' => e($lead->email ?: '—'),
            'الهاتف' => e($lead->phone ?: '—'),
            'المصدر' => e($lead->source_label),
            'الحالة' => e($lead->status_label),
            'الميزانية المتوقعة' => $lead->estimated_budget ? number_format((float) $lead->estimated_budget, 2).' ج.م' : '—',
            'المسؤول' => e($lead->assignee?->name ?? '—'),
        ], $url));
    }

    public function notifyLeadStatusUpdated(Lead $lead, User $actor, string $oldStatus): void
    {
        if (! $this->shouldNotify($actor)) {
            return;
        }

        $url = url(route('leads.show', $lead, false));
        $title = 'تحديث Lead — '.$actor->name;
        $message = $lead->reference_code.' → '.$lead->status_label;

        $this->notifyInApp($title, $message, 'sales_lead_updated', [
            'lead_id' => $lead->id,
            'url' => $url,
        ]);

        $this->sendMail($title, $this->buildBody('تحديث حالة Lead', [
            'الموظف' => e($actor->name),
            'المرجع' => e($lead->reference_code),
            'العميل المحتمل' => e($lead->name),
            'الشركة' => e($lead->company ?: '—'),
            'الحالة السابقة' => e((new Lead(['status' => $oldStatus]))->status_label),
            'الحالة الجديدة' => e($lead->status_label),
            'سبب الفقد' => e($lead->lost_reason ?: '—'),
        ], $url));
    }

    public function notifyLeadConverted(Lead $lead, Sale $sale, User $actor): void
    {
        if (! $this->shouldNotify($actor)) {
            return;
        }

        $url = url(route('sales.show', $sale, false));
        $title = 'تحويل Lead → فرصة — '.$actor->name;
        $message = $lead->reference_code.' → '.$sale->product_service;

        $this->notifyInApp($title, $message, 'sales_lead_converted', [
            'sale_id' => $sale->id,
            'lead_id' => $lead->id,
            'url' => $url,
        ]);

        $this->sendMail($title, $this->buildBody('تحويل Lead إلى فرصة مبيعات', [
            'الموظف' => e($actor->name),
            'Lead' => e($lead->reference_code).' — '.e($lead->name),
            'الفرصة' => e($sale->product_service),
            'العميل' => e($sale->client?->name ?? '—'),
            'القيمة المتوقعة' => number_format((float) ($sale->estimated_value ?? 0), 2).' ج.م',
            'المرحلة' => e($sale->stage),
        ], $url));
    }

    public function notifySaleCreated(Sale $sale, User $actor): void
    {
        if (! $this->shouldNotify($actor)) {
            return;
        }

        $sale->loadMissing(['client', 'salesRep']);
        $url = url(route('sales.show', $sale, false));
        $title = 'فرصة مبيعات جديدة — '.$actor->name;
        $message = ($sale->client?->name ?? '—').' · '.number_format((float) ($sale->estimated_value ?? 0), 0).' ج.م';

        $this->notifyInApp($title, $message, 'sales_opportunity_created', [
            'sale_id' => $sale->id,
            'url' => $url,
        ]);

        $this->sendMail($title, $this->buildBody('إنشاء فرصة مبيعات', [
            'الموظف' => e($actor->name),
            'المنتج/الخدمة' => e($sale->product_service),
            'العميل' => e($sale->client?->name ?? '—'),
            'القيمة المتوقعة' => number_format((float) ($sale->estimated_value ?? 0), 2).' ج.م',
            'المرحلة' => e($sale->stage),
            'الاحتمالية' => ($sale->probability_percentage ?? 0).'%',
            'تاريخ الإغلاق المتوقع' => optional($sale->expected_close_date)->format('Y-m-d') ?? '—',
            'المندوب' => e($sale->salesRep?->name ?? '—'),
        ], $url));
    }

    public function notifySaleStageUpdated(Sale $sale, User $actor, string $oldStage): void
    {
        if (! $this->shouldNotify($actor)) {
            return;
        }

        $sale->loadMissing(['client']);
        $url = url(route('sales.show', $sale, false));
        $title = 'تحديث مرحلة صفقة — '.$actor->name;
        $message = ($sale->client?->name ?? '#'.$sale->id).' · '.$oldStage.' → '.$sale->stage;

        $this->notifyInApp($title, $message, 'sales_stage_updated', [
            'sale_id' => $sale->id,
            'url' => $url,
        ]);

        $this->sendMail($title, $this->buildBody('تحديث مرحلة الصفقة', [
            'الموظف' => e($actor->name),
            'الفرصة' => e($sale->product_service),
            'العميل' => e($sale->client?->name ?? '—'),
            'المرحلة السابقة' => e($oldStage),
            'المرحلة الجديدة' => e($sale->stage),
            'القيمة الفعلية' => $sale->actual_value ? number_format((float) $sale->actual_value, 2).' ج.م' : '—',
            'تاريخ الإغلاق' => optional($sale->actual_close_date)->format('Y-m-d') ?? '—',
        ], $url));
    }

    public function notifyClientCreated(Client $client, User $actor): void
    {
        if (! $this->shouldNotify($actor)) {
            return;
        }

        $url = url(route('clients.show', $client, false));
        $title = 'عميل جديد — '.$actor->name;
        $message = $client->name;

        $this->notifyInApp($title, $message, 'sales_client_created', [
            'client_id' => $client->id,
            'url' => $url,
        ]);

        $this->sendMail($title, $this->buildBody('إضافة عميل جديد', [
            'الموظف' => e($actor->name),
            'اسم العميل' => e($client->name),
            'الشركة' => e($client->company_name ?: '—'),
            'البريد' => e($client->email),
            'الهاتف' => e($client->phone ?: '—'),
            'الصناعة' => e($client->industry ?: '—'),
            'الحالة' => e($client->status),
        ], $url));
    }

    protected function shouldNotify(User $actor): bool
    {
        return $actor->hasRole('sales_rep')
            || ($actor->can('view-sales') && ! $actor->hasAnyRole(['super_admin', 'admin']));
    }

    protected function notifyInApp(string $title, string $message, string $type, array $data): void
    {
        $admins = User::role(['super_admin', 'admin'])->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'is_read' => false,
            ]);
        }
    }

    protected function sendMail(string $subject, string $htmlBody): void
    {
        $recipients = $this->resolveAlertEmails();
        if ($recipients === []) {
            Log::info('Sales activity mail skipped: no alert recipients.', ['subject' => $subject]);

            return;
        }

        try {
            $mailer = new EmailService;
            foreach ($recipients as $to) {
                if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                $ok = $mailer->sendEmail($to, $subject, $htmlBody, 'Solvesta — المبيعات');
                if (! $ok) {
                    Log::warning('Sales activity mail failed.', ['to' => $to, 'subject' => $subject]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Sales activity mail exception: '.$e->getMessage(), ['subject' => $subject]);
        }
    }

    protected function resolveAlertEmails(): array
    {
        $configured = config('sales.alert_emails', []);
        if ($configured !== []) {
            return $configured;
        }

        return User::role(['super_admin', 'admin'])
            ->pluck('email')
            ->filter(fn ($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }

    protected function buildBody(string $headline, array $rows, string $adminUrl): string
    {
        $lines = '';
        foreach ($rows as $label => $value) {
            $lines .= '<tr><td style="padding:8px 12px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:bold;width:160px;">'
                .$label
                .'</td><td style="padding:8px 12px;border:1px solid #e5e7eb;">'
                .$value
                .'</td></tr>';
        }

        return '<div dir="rtl" style="font-family:Tahoma,Arial,sans-serif;font-size:14px;color:#111827;">'
            .'<h2 style="margin:0 0 12px;">'.$headline.'</h2>'
            .'<p style="color:#6b7280;margin:0 0 16px;">'.now()->format('Y-m-d H:i').'</p>'
            .'<table style="border-collapse:collapse;width:100%;max-width:600px;">'.$lines.'</table>'
            .'<p style="margin-top:16px;"><a href="'.e($adminUrl).'" style="color:#2563eb;font-weight:bold;">فتح في النظام</a></p>'
            .'</div>';
    }
}
