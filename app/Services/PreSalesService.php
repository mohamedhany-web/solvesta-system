<?php

namespace App\Services;

use App\Helpers\SettingsHelper;
use App\Models\CostEstimation;
use App\Models\Proposal;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class PreSalesService
{
    public function defaultRates(): array
    {
        return [
            'hourly_rate_dev' => 500,
            'hourly_rate_design' => 400,
            'hourly_rate_qa' => 350,
            'hourly_rate_pm' => 450,
            'margin_percent' => 15,
        ];
    }

    public function calculateTotals(array $data): array
    {
        $subtotal = round(
            ((float) ($data['dev_hours'] ?? 0) * (float) ($data['hourly_rate_dev'] ?? 500))
            + ((float) ($data['design_hours'] ?? 0) * (float) ($data['hourly_rate_design'] ?? 400))
            + ((float) ($data['qa_hours'] ?? 0) * (float) ($data['hourly_rate_qa'] ?? 350))
            + ((float) ($data['pm_hours'] ?? 0) * (float) ($data['hourly_rate_pm'] ?? 450)),
            2
        );

        $margin = (float) ($data['margin_percent'] ?? 15);
        $total = round($subtotal * (1 + ($margin / 100)), 2);

        $devHours = (float) ($data['dev_hours'] ?? 0);
        $devCount = max(1, (int) ($data['developers_count'] ?? 1));
        $durationWeeks = (int) ($data['duration_weeks'] ?? 0);
        if ($durationWeeks < 1 && $devHours > 0) {
            $durationWeeks = max(1, (int) ceil($devHours / ($devCount * 30)));
        }

        return [
            'subtotal' => $subtotal,
            'total_cost' => $total,
            'duration_weeks' => max(1, $durationWeeks),
        ];
    }

    public function storeEstimation(Sale $sale, array $data, ?CostEstimation $existing = null): CostEstimation
    {
        $totals = $this->calculateTotals($data);

        $payload = [
            'screen_count' => (int) ($data['screen_count'] ?? 0),
            'developers_count' => max(1, (int) ($data['developers_count'] ?? 1)),
            'dev_hours' => $data['dev_hours'] ?? 0,
            'design_hours' => $data['design_hours'] ?? 0,
            'qa_hours' => $data['qa_hours'] ?? 0,
            'pm_hours' => $data['pm_hours'] ?? 0,
            'hourly_rate_dev' => $data['hourly_rate_dev'] ?? 500,
            'hourly_rate_design' => $data['hourly_rate_design'] ?? 400,
            'hourly_rate_qa' => $data['hourly_rate_qa'] ?? 350,
            'hourly_rate_pm' => $data['hourly_rate_pm'] ?? 450,
            'margin_percent' => $data['margin_percent'] ?? 15,
            'subtotal' => $totals['subtotal'],
            'total_cost' => $totals['total_cost'],
            'duration_weeks' => $totals['duration_weeks'],
            'scope_notes' => $data['scope_notes'] ?? null,
            'technical_notes' => $data['technical_notes'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'estimated_by' => auth()->id(),
        ];

        if ($existing) {
            $existing->update($payload);

            return $existing->fresh();
        }

        return CostEstimation::create([
            ...$payload,
            'sale_id' => $sale->id,
            'reference_code' => CostEstimation::generateReferenceCode(),
        ]);
    }

    public function approveEstimation(CostEstimation $estimation): CostEstimation
    {
        $estimation->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return $estimation->fresh();
    }

    public function generateProposal(Sale $sale, CostEstimation $estimation): Proposal
    {
        if ($existing = Proposal::where('cost_estimation_id', $estimation->id)->first()) {
            return $existing;
        }

        return DB::transaction(function () use ($sale, $estimation) {
            $company = SettingsHelper::getCompanyName() ?: SettingsHelper::getSystemName();
            $client = $sale->client;
            $title = 'عرض سعر — '.$sale->product_service;

            $scope = $this->buildScopeText($sale, $estimation);
            $timeline = $this->buildTimelineText($estimation);
            $pricing = $this->buildPricingText($estimation);
            $description = $sale->requirement_summary ?: $sale->notes;

            $content = $this->buildProposalDocument(
                $company,
                $client?->name ?? 'العميل',
                $title,
                $description,
                $scope,
                $timeline,
                $pricing,
                $estimation
            );

            $proposal = Proposal::create([
                'sale_id' => $sale->id,
                'cost_estimation_id' => $estimation->id,
                'reference_code' => Proposal::generateReferenceCode(),
                'title' => $title,
                'project_description' => $description,
                'scope' => $scope,
                'timeline' => $timeline,
                'pricing_breakdown' => $pricing,
                'payment_terms' => '50% مقدماً عند التعاقد — 50% عند التسليم النهائي',
                'total_price' => $estimation->total_cost,
                'valid_until' => now()->addDays(30),
                'status' => 'draft',
                'generated_content' => $content,
                'created_by' => auth()->id(),
            ]);

            $sale->update([
                'stage' => 'proposal',
                'estimated_value' => $estimation->total_cost,
                'probability_percentage' => max($sale->probability_percentage, 55),
            ]);

            return $proposal;
        });
    }

    public function markProposalSent(Proposal $proposal): Proposal
    {
        $proposal->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return $proposal->fresh();
    }

    public function acceptProposal(Proposal $proposal): Proposal
    {
        return DB::transaction(function () use ($proposal) {
            $proposal->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            $proposal->sale->update([
                'stage' => 'negotiation',
                'actual_value' => $proposal->total_price,
                'estimated_value' => $proposal->total_price,
                'probability_percentage' => 75,
            ]);

            return $proposal->fresh();
        });
    }

    public function rejectProposal(Proposal $proposal, string $reason): Proposal
    {
        $proposal->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
        ]);

        return $proposal->fresh();
    }

    protected function buildScopeText(Sale $sale, CostEstimation $estimation): string
    {
        $lines = [];
        if ($estimation->screen_count > 0) {
            $lines[] = 'عدد الشاشات/الواجهات المقدرة: '.$estimation->screen_count;
        }
        $lines[] = 'الخدمة: '.$sale->product_service;
        if ($estimation->scope_notes) {
            $lines[] = $estimation->scope_notes;
        }
        if ($sale->requirement_summary) {
            $lines[] = 'ملخص المتطلبات: '.$sale->requirement_summary;
        }

        return implode("\n", $lines);
    }

    protected function buildTimelineText(CostEstimation $estimation): string
    {
        $weeks = $estimation->duration_weeks;
        $months = round($weeks / 4, 1);

        return "المدة التقديرية: {$weeks} أسبوع (حوالي {$months} شهر)\n"
            ."فريق التنفيذ: {$estimation->developers_count} مطور(ين)\n"
            ."المراحل: UI/UX → Backend → Frontend → Testing → التسليم";
    }

    protected function buildPricingText(CostEstimation $estimation): string
    {
        return implode("\n", [
            'تطوير: '.number_format($estimation->dev_hours, 1).' ساعة × '.number_format($estimation->hourly_rate_dev).' = '.number_format($estimation->dev_hours * $estimation->hourly_rate_dev).' ج.م',
            'تصميم: '.number_format($estimation->design_hours, 1).' ساعة × '.number_format($estimation->hourly_rate_design).' = '.number_format($estimation->design_hours * $estimation->hourly_rate_design).' ج.م',
            'اختبار: '.number_format($estimation->qa_hours, 1).' ساعة × '.number_format($estimation->hourly_rate_qa).' = '.number_format($estimation->qa_hours * $estimation->hourly_rate_qa).' ج.م',
            'إدارة مشروع: '.number_format($estimation->pm_hours, 1).' ساعة × '.number_format($estimation->hourly_rate_pm).' = '.number_format($estimation->pm_hours * $estimation->hourly_rate_pm).' ج.م',
            'المجموع الفرعي: '.number_format($estimation->subtotal).' ج.م',
            'هامش ('.$estimation->margin_percent.'%): '.number_format($estimation->total_cost - $estimation->subtotal).' ج.م',
            'الإجمالي: '.number_format($estimation->total_cost).' ج.م',
        ]);
    }

    protected function buildProposalDocument(
        string $company,
        string $clientName,
        string $title,
        ?string $description,
        string $scope,
        string $timeline,
        string $pricing,
        CostEstimation $estimation
    ): string {
        $date = now()->locale('ar')->translatedFormat('d F Y');
        $validUntil = now()->addDays(30)->locale('ar')->translatedFormat('d F Y');
        $total = number_format($estimation->total_cost);

        return <<<DOC
{$company}
═══════════════════════════════════════

{$title}
التاريخ: {$date}
صالح حتى: {$validUntil}

العميل: {$clientName}

── وصف المشروع ──
{$description}

── النطاق ──
{$scope}

── الجدول الزمني ──
{$timeline}

── تفصيل التكلفة ──
{$pricing}

── شروط الدفع ──
50% مقدماً عند التعاقد
50% عند التسليم النهائي والاعتماد

── الإجمالي ──
{$total} جنيه مصري

═══════════════════════════════════════
DOC;
    }
}
