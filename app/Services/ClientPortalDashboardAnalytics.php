<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientAccount;
use App\Models\ClientMeetingRequest;
use App\Models\ClientServiceReport;
use App\Models\ClientSystemFeature;
use App\Models\ClientWebsiteIssue;
use App\Models\FinancialInvoice;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ClientPortalDashboardAnalytics
{
    protected const MONTHS = 6;

    protected array $arabicMonths = [
        1 => 'يناير', 2 => 'فبراير', 3 => 'مارس', 4 => 'أبريل',
        5 => 'مايو', 6 => 'يونيو', 7 => 'يوليو', 8 => 'أغسطس',
        9 => 'سبتمبر', 10 => 'أكتوبر', 11 => 'نوفمبر', 12 => 'ديسمبر',
    ];

    public function build(Client $client, ClientAccount $account): array
    {
        $months = $this->monthBuckets();
        $monthKeys = $months->pluck('key')->all();
        $monthLabels = $months->pluck('label')->all();

        $ticketStatus = $this->groupedStatus(
            Ticket::where('client_id', $client->id),
            'status',
            [
                'open' => 'مفتوح',
                'in_progress' => 'قيد التنفيذ',
                'pending_client' => 'بانتظارك',
                'resolved' => 'محلول',
                'closed' => 'مغلق',
            ],
            [
                'open' => '#f59e0b',
                'in_progress' => '#3b82f6',
                'pending_client' => '#8b5cf6',
                'resolved' => '#10b981',
                'closed' => '#6b7280',
            ]
        );

        $projectStatus = $this->groupedStatus(
            Project::where('client_id', $client->id),
            'status',
            [
                'planning' => 'قيد التخطيط',
                'in_progress' => 'قيد التنفيذ',
                'on_hold' => 'متوقف',
                'completed' => 'مكتمل',
                'cancelled' => 'ملغي',
            ],
            [
                'planning' => '#eab308',
                'in_progress' => '#3b82f6',
                'on_hold' => '#f97316',
                'completed' => '#10b981',
                'cancelled' => '#ef4444',
            ]
        );

        $activity = [
            'tickets' => $this->monthlyCounts(Ticket::where('client_id', $client->id), $monthKeys),
            'serviceReports' => $this->monthlyCounts(ClientServiceReport::where('client_id', $client->id), $monthKeys),
        ];

        $technical = null;
        if ($account->canAccessTechnicalRequests()) {
            $activity['websiteIssues'] = $this->monthlyCounts(ClientWebsiteIssue::where('client_id', $client->id), $monthKeys);
            $activity['meetings'] = $this->monthlyCounts(ClientMeetingRequest::where('client_id', $client->id), $monthKeys);
            $activity['features'] = $this->monthlyFeatureCounts($client->id, $monthKeys);

            $technical = [
                'websiteIssues' => $this->groupedStatus(
                    ClientWebsiteIssue::where('client_id', $client->id),
                    'status',
                    ['open' => 'مفتوح', 'in_progress' => 'قيد المعالجة', 'resolved' => 'تم الحل', 'closed' => 'مغلق'],
                    ['open' => '#f59e0b', 'in_progress' => '#3b82f6', 'resolved' => '#10b981', 'closed' => '#6b7280']
                ),
                'meetings' => $this->groupedStatus(
                    ClientMeetingRequest::where('client_id', $client->id),
                    'status',
                    ['pending' => 'قيد المراجعة', 'confirmed' => 'مؤكد', 'completed' => 'مكتمل', 'declined' => 'مرفوض', 'cancelled' => 'ملغى'],
                    ['pending' => '#f59e0b', 'confirmed' => '#3b82f6', 'completed' => '#10b981', 'declined' => '#ef4444', 'cancelled' => '#9ca3af']
                ),
                'features' => $this->groupedFeatureStatus($client->id),
            ];
        }

        $billing = null;
        if ($account->canAccessBilling()) {
            $billing = [
                'monthlyIssued' => [
                    'regular' => $this->monthlyInvoiceAmounts(Invoice::where('client_id', $client->id), 'created_at', 'total_amount', $monthKeys),
                    'financial' => $this->monthlyInvoiceAmounts(FinancialInvoice::where('client_id', $client->id), 'invoice_date', 'total_amount', $monthKeys),
                ],
                'monthlyPaid' => [
                    'regular' => $this->monthlyInvoiceAmounts(Invoice::where('client_id', $client->id), 'created_at', 'paid_amount', $monthKeys),
                    'financial' => $this->monthlyInvoiceAmounts(FinancialInvoice::where('client_id', $client->id), 'invoice_date', 'paid_amount', $monthKeys),
                ],
                'paymentOverview' => $this->paymentOverview($client),
                'outstanding' => [
                    'regular' => (float) Invoice::where('client_id', $client->id)->where('status', '!=', 'paid')->where('status', '!=', 'cancelled')->sum('balance_amount'),
                    'financial' => (float) FinancialInvoice::where('client_id', $client->id)->where('payment_status', '!=', 'paid')->sum('balance_due'),
                ],
            ];
        }

        $totalTickets = (int) Ticket::where('client_id', $client->id)->count();
        $closedTickets = (int) Ticket::where('client_id', $client->id)->whereIn('status', ['resolved', 'closed'])->count();

        return [
            'months' => $monthLabels,
            'activity' => $activity,
            'ticketsByStatus' => $ticketStatus,
            'projectsByStatus' => $projectStatus,
            'technical' => $technical,
            'billing' => $billing,
            'kpis' => [
                'ticketResolutionRate' => $totalTickets > 0 ? round(($closedTickets / $totalTickets) * 100) : 0,
                'activeProjects' => (int) Project::where('client_id', $client->id)->whereIn('status', ['planning', 'in_progress'])->count(),
                'openTickets' => (int) Ticket::where('client_id', $client->id)->whereIn('status', ['open', 'in_progress', 'pending_client'])->count(),
            ],
        ];
    }

    protected function monthBuckets(): Collection
    {
        return collect(range(self::MONTHS - 1, 0))
            ->map(function (int $offset) {
                $date = now()->subMonths($offset)->startOfMonth();

                return [
                    'key' => $date->format('Y-m'),
                    'label' => ($this->arabicMonths[(int) $date->format('n')] ?? $date->format('m')).' '.$date->format('Y'),
                ];
            });
    }

    protected function monthlyCounts($query, array $monthKeys): array
    {
        $rows = (clone $query)
            ->where('created_at', '>=', now()->subMonths(self::MONTHS - 1)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        return array_map(fn (string $key) => (int) ($rows[$key] ?? 0), $monthKeys);
    }

    protected function monthlyFeatureCounts(int $clientId, array $monthKeys): array
    {
        $rows = ClientSystemFeature::query()
            ->whereHas('project', fn ($q) => $q->where('client_id', $clientId))
            ->where('created_at', '>=', now()->subMonths(self::MONTHS - 1)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        return array_map(fn (string $key) => (int) ($rows[$key] ?? 0), $monthKeys);
    }

    protected function monthlyInvoiceAmounts($query, string $dateColumn, string $amountColumn, array $monthKeys): array
    {
        $rows = (clone $query)
            ->where($dateColumn, '>=', now()->subMonths(self::MONTHS - 1)->startOfMonth())
            ->selectRaw("DATE_FORMAT({$dateColumn}, '%Y-%m') as month_key, SUM({$amountColumn}) as total")
            ->groupBy('month_key')
            ->pluck('total', 'month_key');

        return array_map(fn (string $key) => round((float) ($rows[$key] ?? 0), 2), $monthKeys);
    }

    protected function groupedStatus($query, string $column, array $labels, array $colors): array
    {
        $rows = (clone $query)
            ->selectRaw("{$column}, COUNT(*) as total")
            ->groupBy($column)
            ->pluck('total', $column);

        $resultLabels = [];
        $data = [];
        $palette = [];

        foreach ($labels as $key => $label) {
            $count = (int) ($rows[$key] ?? 0);
            if ($count === 0) {
                continue;
            }
            $resultLabels[] = $label;
            $data[] = $count;
            $palette[] = $colors[$key] ?? '#94a3b8';
        }

        if ($data === []) {
            return [
                'labels' => ['لا توجد بيانات'],
                'data' => [1],
                'colors' => ['#e5e7eb'],
                'empty' => true,
            ];
        }

        return [
            'labels' => $resultLabels,
            'data' => $data,
            'colors' => $palette,
            'empty' => false,
        ];
    }

    protected function groupedFeatureStatus(int $clientId): array
    {
        $rows = ClientSystemFeature::query()
            ->whereHas('project', fn ($q) => $q->where('client_id', $clientId))
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $labels = [
            'submitted' => 'مُرسَل',
            'reviewing' => 'قيد المراجعة',
            'approved' => 'معتمد',
            'in_progress' => 'قيد التنفيذ',
            'testing' => 'اختبار',
            'completed' => 'مكتمل',
            'rejected' => 'مرفوض',
            'cancelled' => 'ملغى',
        ];

        $colors = [
            'submitted' => '#94a3b8',
            'reviewing' => '#f59e0b',
            'approved' => '#3b82f6',
            'in_progress' => '#6366f1',
            'testing' => '#8b5cf6',
            'completed' => '#10b981',
            'rejected' => '#ef4444',
            'cancelled' => '#9ca3af',
        ];

        $resultLabels = [];
        $data = [];
        $palette = [];

        foreach ($labels as $key => $label) {
            $count = (int) ($rows[$key] ?? 0);
            if ($count === 0) {
                continue;
            }
            $resultLabels[] = $label;
            $data[] = $count;
            $palette[] = $colors[$key];
        }

        if ($data === []) {
            return ['labels' => ['لا توجد طلبات'], 'data' => [1], 'colors' => ['#e5e7eb'], 'empty' => true];
        }

        return ['labels' => $resultLabels, 'data' => $data, 'colors' => $palette, 'empty' => false];
    }

    protected function paymentOverview(Client $client): array
    {
        $regularPaid = (int) Invoice::where('client_id', $client->id)->where('status', 'paid')->count();
        $regularPartial = (int) Invoice::where('client_id', $client->id)->where('status', 'partial')->count();
        $regularUnpaid = (int) Invoice::where('client_id', $client->id)
            ->whereNotIn('status', ['paid', 'partial', 'cancelled', 'draft'])
            ->count();

        $finPaid = (int) FinancialInvoice::where('client_id', $client->id)->where('payment_status', 'paid')->count();
        $finPartial = (int) FinancialInvoice::where('client_id', $client->id)->where('payment_status', 'partial')->count();
        $finUnpaid = (int) FinancialInvoice::where('client_id', $client->id)
            ->whereNotIn('payment_status', ['paid', 'partial'])
            ->count();

        $paid = $regularPaid + $finPaid;
        $partial = $regularPartial + $finPartial;
        $unpaid = $regularUnpaid + $finUnpaid;

        if ($paid + $partial + $unpaid === 0) {
            return ['labels' => ['لا توجد فواتير'], 'data' => [1], 'colors' => ['#e5e7eb'], 'empty' => true];
        }

        return [
            'labels' => ['مدفوعة بالكامل', 'مدفوعة جزئياً', 'غير مدفوعة'],
            'data' => [$paid, $partial, $unpaid],
            'colors' => ['#10b981', '#f59e0b', '#ef4444'],
            'empty' => false,
        ];
    }
}
