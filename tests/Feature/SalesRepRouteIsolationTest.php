<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SalesRepScope;
use Mockery;
use Tests\TestCase;

class SalesRepRouteIsolationTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function restrictedSalesRep(): User
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->shouldReceive('hasRole')->with('sales_rep')->andReturn(true);
        $user->shouldReceive('can')->with('edit-employees')->andReturn(false);

        return $user;
    }

    public function test_route_allow_list_blocks_admin_modules_for_restricted_rep(): void
    {
        $user = $this->restrictedSalesRep();

        $blocked = [
            'marketing.index',
            'bd.index',
            'executive.dashboard',
            'executive.finance',
            'employees.index',
            'users.index',
            'reports.sales',
            'sales.kpi-targets.index',
            'accounting.index',
            'projects.index',
            'contracts.index',
            'contracts.create',
            'invoices.index',
            'invoices.create',
        ];

        foreach ($blocked as $route) {
            $this->assertFalse(
                SalesRepScope::canAccessRoute($user, $route),
                "Route {$route} should be blocked for restricted sales rep"
            );
        }
    }

    public function test_route_allow_list_permits_sales_workspace_for_restricted_rep(): void
    {
        $user = $this->restrictedSalesRep();

        $allowed = [
            'dashboard',
            'sales.dashboard',
            'sales.guide',
            'sales.index',
            'leads.index',
            'clients.index',
            'pre-sales.index',
            'workflow.sales.qualify',
            'profile.edit',
            'messages.index',
            'notifications.index',
        ];

        foreach ($allowed as $route) {
            $this->assertTrue(
                SalesRepScope::canAccessRoute($user, $route),
                "Route {$route} should be allowed for restricted sales rep"
            );
        }
    }

    public function test_non_restricted_users_bypass_route_allow_list(): void
    {
        $admin = Mockery::mock(User::class)->makePartial();
        $admin->shouldReceive('hasRole')->with('sales_rep')->andReturn(false);

        $this->assertTrue(SalesRepScope::canAccessRoute($admin, 'marketing.index'));
        $this->assertTrue(SalesRepScope::canAccessRoute($admin, 'executive.dashboard'));
    }
}
