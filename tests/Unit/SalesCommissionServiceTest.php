<?php

namespace Tests\Unit;

use App\Models\Sale;
use App\Services\SalesCommissionService;
use Tests\TestCase;

class SalesCommissionServiceTest extends TestCase
{
    public function test_commission_calculated_from_deal_value(): void
    {
        config(['sales.commission_rate' => 10]);

        $service = new SalesCommissionService;

        $sale = new Sale([
            'actual_value' => 100000,
            'estimated_value' => 50000,
        ]);

        $this->assertSame(100000.0, $service->dealValue($sale));
        $this->assertSame(10000.0, $service->commissionForSale($sale));
    }

    public function test_commission_uses_estimated_when_no_actual(): void
    {
        config(['sales.commission_rate' => 5]);

        $service = new SalesCommissionService;

        $sale = new Sale(['estimated_value' => 200000]);

        $this->assertSame(10000.0, $service->commissionForSale($sale));
    }
}
