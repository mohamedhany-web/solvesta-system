<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Lead;
use App\Models\Sale;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class TestSalesRepRouteAccess extends Command
{
    protected $signature = 'solvesta:test-routes {--keep : Keep test users and records after run}';

    protected $description = 'Run live HTTP route isolation tests for restricted sales reps';

    /** @var array<int, string> */
    protected array $createdUserEmails = [];

    /** @var array<int, int> */
    protected array $createdClientIds = [];

    public function handle(): int
    {
        $this->ensureRolesExist();

        [$repA, $employeeA] = $this->ensureSalesRep('route-test-rep-a');
        [$repB, $employeeB] = $this->ensureSalesRep('route-test-rep-b');

        $clientA = $this->ensureClient($employeeA, 'route-test-client-a');
        $clientB = $this->ensureClient($employeeB, 'route-test-client-b');
        $saleA = $this->ensureSale($repA, $clientA, 'route-test-sale-a');
        $saleB = $this->ensureSale($repB, $clientB, 'route-test-sale-b');
        $leadA = $this->ensureLead($repA, 'route-test-lead-a');
        $leadB = $this->ensureLead($repB, 'route-test-lead-b');

        $failures = 0;

        $failures += $this->runCase('Block executive dashboard', fn () => $this->getAs($repA, '/executive'), 403);
        $failures += $this->runCase('Block marketing', fn () => $this->getAs($repA, '/marketing'), 403);
        $failures += $this->runCase('Block BD', fn () => $this->getAs($repA, '/bd'), 403);
        $failures += $this->runCase('Block employees', fn () => $this->getAs($repA, '/employees'), 403);
        $failures += $this->runCase('Block KPI targets admin', fn () => $this->getAs($repA, '/sales/kpi-targets'), 403);
        $failures += $this->runCase('Block contracts index', fn () => $this->getAs($repA, '/contracts'), 403);
        $failures += $this->runCase('Block invoices index', fn () => $this->getAs($repA, '/invoices'), 403);
        $failures += $this->runCase('Block invoice create', fn () => $this->getAs($repA, '/invoices/create'), 403);

        $failures += $this->runCase('Allow sales dashboard', fn () => $this->getAs($repA, '/sales/dashboard'), 200);
        $failures += $this->runCase('Allow sales guide', fn () => $this->getAs($repA, '/sales/guide'), 200);
        $failures += $this->runCase('Allow leads index', fn () => $this->getAs($repA, '/leads'), 200);
        $failures += $this->runCase('Allow own client', fn () => $this->getAs($repA, "/clients/{$clientA->id}"), 200);
        $failures += $this->runCase('Allow own sale', fn () => $this->getAs($repA, "/sales/{$saleA->id}"), 200);
        $failures += $this->runCase('Allow own lead', fn () => $this->getAs($repA, "/leads/{$leadA->id}"), 200);

        $failures += $this->runCase('Block other client', fn () => $this->getAs($repA, "/clients/{$clientB->id}"), 403);
        $failures += $this->runCase('Block other sale', fn () => $this->getAs($repA, "/sales/{$saleB->id}"), 403);
        $failures += $this->runCase('Block other lead', fn () => $this->getAs($repA, "/leads/{$leadB->id}"), 403);

        $stats = $this->getAs($repA, '/sales/statistics/data');
        if ($stats->getStatusCode() !== 200) {
            $this->error('FAIL: Sales statistics endpoint — expected 200, got '.$stats->getStatusCode());
            $failures++;
        } else {
            $payload = json_decode($stats->getContent(), true);
            $total = (int) ($payload['total_sales'] ?? -1);
            $scopedCount = Sale::where('assigned_to', $repA->id)->count();
            if ($total !== $scopedCount) {
                $this->error("FAIL: Sales statistics leak — returned {$total}, expected {$scopedCount}");
                $failures++;
            } else {
                $this->info("PASS: Sales statistics scoped ({$total})");
            }
        }

        $dashboard = $this->getAs($repA, '/sales/dashboard?user_id='.$repB->id);
        if ($dashboard->getStatusCode() !== 200) {
            $this->error('FAIL: Sales dashboard with user_id param — expected 200, got '.$dashboard->getStatusCode());
            $failures++;
        } else {
            $view = $dashboard->original;
            $repUserId = $view instanceof View ? ($view->getData()['repUser']->id ?? null) : null;
            if ((int) $repUserId !== (int) $repA->id) {
                $this->error('FAIL: Sales dashboard user_id hijack — showing rep '.$repUserId.' instead of '.$repA->id);
                $failures++;
            } else {
                $this->info('PASS: Sales dashboard ignores user_id for restricted rep');
            }
        }

        if (! $this->option('keep')) {
            $this->cleanup();
        } else {
            $this->warn('Test data kept (--keep). Emails: '.implode(', ', $this->createdUserEmails));
        }

        if ($failures > 0) {
            $this->error("Completed with {$failures} failure(s).");

            return self::FAILURE;
        }

        $admin = User::where('email', 'loransmogay@gmail.com')->first()
            ?? User::role('super_admin')->first()
            ?? User::role('admin')->first();

        if ($admin) {
            $this->newLine();
            $this->info("Verifying admin access ({$admin->email})...");
            $adminFailures = 0;
            $adminFailures += $this->runCase('Admin can access executive', fn () => $this->getAs($admin, '/executive'), 200);
            $adminFailures += $this->runCase('Admin can access employees', fn () => $this->getAs($admin, '/employees'), 200);
            $adminFailures += $this->runCase('Admin can access marketing', fn () => $this->getAs($admin, '/marketing'), 200);

            if ($adminFailures > 0) {
                $this->error("Admin access checks failed ({$adminFailures}).");

                return self::FAILURE;
            }
        } else {
            $this->warn('No admin user found — skipped admin access verification.');
        }

        $this->info('All route isolation checks passed.');

        return self::SUCCESS;
    }

    protected function ensureRolesExist(): void
    {
        if (! Role::where('name', 'sales_rep')->exists()) {
            $this->call('db:seed', ['--class' => RolePermissionSeeder::class]);
        }
    }

    /** @return array{0: User, 1: Employee} */
    protected function ensureSalesRep(string $key): array
    {
        $email = "{$key}@solvesta.test";
        $this->createdUserEmails[] = $email;

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => strtoupper(str_replace('-', ' ', $key)),
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole('sales_rep')) {
            $user->assignRole('sales_rep');
        }

        $employee = Employee::firstOrCreate(
            ['user_id' => $user->id],
            [
                'employee_id' => strtoupper($key),
                'first_name' => 'Route',
                'last_name' => 'Test',
                'email' => $email,
                'position' => 'Sales Representative',
                'hire_date' => now()->toDateString(),
                'salary' => 5000,
                'employment_type' => 'full_time',
                'status' => 'active',
            ]
        );

        return [$user->fresh(), $employee];
    }

    protected function ensureClient(Employee $employee, string $key): Client
    {
        $client = Client::firstOrCreate(
            ['email' => "{$key}@client.test"],
            [
                'name' => $key,
                'status' => 'active',
                'client_type' => 'individual',
                'assigned_to' => $employee->id,
            ]
        );

        $this->createdClientIds[] = $client->id;

        return $client;
    }

    protected function ensureSale(User $user, Client $client, string $product): Sale
    {
        return Sale::firstOrCreate(
            [
                'client_id' => $client->id,
                'assigned_to' => $user->id,
                'product_service' => $product,
            ],
            [
                'estimated_value' => 10000,
                'stage' => 'proposal',
                'probability_percentage' => 50,
            ]
        );
    }

    protected function ensureLead(User $user, string $name): Lead
    {
        return Lead::firstOrCreate(
            ['email' => "{$name}@lead.test"],
            [
                'reference_code' => Lead::generateReferenceCode(),
                'source' => 'website',
                'status' => 'new',
                'name' => $name,
                'assigned_to' => $user->id,
                'created_by' => $user->id,
            ]
        );
    }

    protected function getAs(User $user, string $uri): \Symfony\Component\HttpFoundation\Response
    {
        Auth::login($user);

        $session = app('session.store');
        $session->start();
        $session->put('verified', true);

        $request = Request::create($uri, 'GET');
        $request->setLaravelSession($session);
        $request->setUserResolver(fn () => $user);

        $response = app(Kernel::class)->handle($request);
        app(Kernel::class)->terminate($request, $response);

        return $response;
    }

    protected function runCase(string $label, callable $callback, int $expectedStatus): int
    {
        Auth::logout();
        $response = $callback();
        $actual = $response->getStatusCode();

        if ($actual === $expectedStatus) {
            $this->info("PASS: {$label} ({$actual})");

            return 0;
        }

        $this->error("FAIL: {$label} — expected {$expectedStatus}, got {$actual}");

        return 1;
    }

    protected function cleanup(): void
    {
        Sale::whereIn('product_service', ['route-test-sale-a', 'route-test-sale-b'])->delete();
        Lead::whereIn('email', ['route-test-lead-a@lead.test', 'route-test-lead-b@lead.test'])->delete();
        Client::whereIn('email', ['route-test-client-a@client.test', 'route-test-client-b@client.test'])->delete();

        foreach ($this->createdUserEmails as $email) {
            if ($user = User::where('email', $email)->first()) {
                Employee::where('user_id', $user->id)->delete();
                $user->delete();
            }
        }
    }
}
