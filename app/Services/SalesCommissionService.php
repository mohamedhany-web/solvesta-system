<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Collection;

class SalesCommissionService
{
    public function commissionRate(): float
    {
        return max(0, (float) config('sales.commission_rate', 5));
    }

    public function dealValue(Sale $sale): float
    {
        return (float) ($sale->actual_value ?? $sale->estimated_value ?? 0);
    }

    public function commissionForSale(Sale $sale): float
    {
        return round($this->dealValue($sale) * ($this->commissionRate() / 100), 2);
    }

    /** @return array{wins: Collection, total_revenue: float, total_commission: float, commission_rate: float} */
    public function summaryForUser(User $user): array
    {
        $wins = Sale::query()
            ->where('assigned_to', $user->id)
            ->where('stage', 'closed_won')
            ->with('client:id,name')
            ->orderByDesc('actual_close_date')
            ->orderByDesc('updated_at')
            ->get();

        $totalRevenue = $wins->sum(fn (Sale $s) => $this->dealValue($s));
        $totalCommission = $wins->sum(fn (Sale $s) => $this->commissionForSale($s));

        return [
            'wins' => $wins,
            'total_revenue' => round($totalRevenue, 2),
            'total_commission' => round($totalCommission, 2),
            'commission_rate' => $this->commissionRate(),
        ];
    }
}
