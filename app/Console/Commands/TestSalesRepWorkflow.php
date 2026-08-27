<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\LoginActivityLog;
use App\Models\Notification;
use App\Models\Sale;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\EmailService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Console\Command;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class TestSalesRepWorkflow extends Command
{
    protected $signature = 'solvesta:test-sales-workflow {--keep : Keep test data after run}';

    protected $description = 'End-to-end workflow test for restricted sales reps (CRUD, pipeline, notifications, login)';

    protected string $testKey;

    /** @var array<int, string> */
    protected array $createdEmails = [];

    public function handle(): int
    {
        $this->testKey = 'wf-'.Str::lower(Str::random(6));
        $failures = 0;

        $this->ensureRolesExist();

        [$rep, $employee] = $this->ensureSalesRep();
        $admin = User::role(['super_admin', 'admin'])->first();

        if (! $admin) {
            $this->error('No admin user found — cannot verify admin notifications.');

            return self::FAILURE;
        }

        $adminNotifBefore = Notification::where('user_id', $admin->id)->count();

        $this->info('=== 1. Sidebar & account routes ===');
        $sidebarRoutes = [
            '/sales/dashboard' => 200,
            '/leads' => 200,
            '/leads/create' => 200,
            '/sales' => 200,
            '/sales/create' => 200,
            '/pre-sales' => 200,
            '/clients' => 200,
            '/clients/create' => 200,
            '/sales/guide' => 200,
            '/sales/account' => 200,
            '/messages' => 200,
            '/notifications' => 200,
            '/profile' => 200,
        ];

        foreach ($sidebarRoutes as $uri => $expected) {
            $failures += $this->runCase("GET {$uri}", fn () => $this->requestAs($rep, 'GET', $uri), $expected);
        }

        $this->newLine();
        $this->info('=== 2. Create Lead → admin notification ===');
        $leadEmail = "{$this->testKey}-lead@test.local";
        $leadResponse = $this->requestAs($rep, 'POST', '/leads', [
            'name' => 'Test Lead '.$this->testKey,
            'email' => $leadEmail,
            'phone' => '01000000001',
            'company' => 'Test Co',
            'source' => 'website',
            'estimated_budget' => 50000,
        ]);

        if (! in_array($leadResponse->getStatusCode(), [200, 302], true)) {
            $this->error('FAIL: Create lead — HTTP '.$leadResponse->getStatusCode());
            $failures++;
        } else {
            $lead = Lead::where('email', $leadEmail)->first();
            if (! $lead) {
                $this->error('FAIL: Lead not persisted');
                $failures++;
            } else {
                $this->info("PASS: Lead created (#{$lead->id})");
                if ((int) $lead->assigned_to !== (int) $rep->id) {
                    $this->error('FAIL: Lead not auto-assigned to rep');
                    $failures++;
                } else {
                    $this->info('PASS: Lead auto-assigned to rep');
                }

                $notif = Notification::where('user_id', $admin->id)
                    ->where('type', 'sales_lead_created')
                    ->where('data->lead_id', $lead->id)
                    ->exists();
                $failures += $notif ? 0 : $this->failOnce('Admin in-app notification for new lead');
            }
        }

        $this->newLine();
        $this->info('=== 3. Create Client → admin notification ===');
        $clientEmail = "{$this->testKey}-client@test.local";
        $clientResponse = $this->requestAs($rep, 'POST', '/clients', [
            'name' => 'Client '.$this->testKey,
            'email' => $clientEmail,
            'phone' => '01000000002',
            'status' => 'active',
            'client_type' => 'individual',
        ]);

        if (! in_array($clientResponse->getStatusCode(), [200, 302], true)) {
            $this->error('FAIL: Create client — HTTP '.$clientResponse->getStatusCode());
            $failures++;
            $client = null;
        } else {
            $client = Client::where('email', $clientEmail)->first();
            if (! $client) {
                $this->error('FAIL: Client not persisted');
                $failures++;
            } else {
                $this->info("PASS: Client created (#{$client->id})");
                if ((int) $client->assigned_to !== (int) $employee->id) {
                    $this->error('FAIL: Client not assigned to rep employee');
                    $failures++;
                } else {
                    $this->info('PASS: Client assigned to rep employee');
                }

                $notif = Notification::where('user_id', $admin->id)
                    ->where('type', 'sales_client_created')
                    ->where('data->client_id', $client->id)
                    ->exists();
                $failures += $notif ? 0 : $this->failOnce('Admin in-app notification for new client');
            }
        }

        $this->newLine();
        $this->info('=== 4. Create Sale → qualify → pre-sales → contract → invoice ===');

        if ($client) {
            $saleResponse = $this->requestAs($rep, 'POST', '/sales', [
                'product_service' => 'ERP Project '.$this->testKey,
                'client_id' => $client->id,
                'assigned_to' => $rep->id,
                'estimated_value' => 100000,
                'stage' => 'prospect',
                'probability_percentage' => 30,
            ]);

            if (! in_array($saleResponse->getStatusCode(), [200, 302], true)) {
                $this->error('FAIL: Create sale — HTTP '.$saleResponse->getStatusCode());
                $failures++;
                $sale = null;
            } else {
                $sale = Sale::where('client_id', $client->id)
                    ->where('product_service', 'ERP Project '.$this->testKey)
                    ->first();

                if (! $sale) {
                    $this->error('FAIL: Sale not persisted');
                    $failures++;
                } else {
                    $this->info("PASS: Sale created (#{$sale->id})");

                    $notif = Notification::where('user_id', $admin->id)
                        ->where('type', 'sales_opportunity_created')
                        ->where('data->sale_id', $sale->id)
                        ->exists();
                    $failures += $notif ? 0 : $this->failOnce('Admin notification for new sale');

                    $qualifyResponse = $this->requestAs($rep, 'POST', "/workflow/sales/{$sale->id}/qualify", [
                        'requirement_summary' => 'Scope test '.$this->testKey,
                    ]);
                    if (! in_array($qualifyResponse->getStatusCode(), [200, 302], true)) {
                        $this->error('FAIL: Qualify sale — HTTP '.$qualifyResponse->getStatusCode());
                        $failures++;
                    } else {
                        $sale->refresh();
                        if ($sale->qualification_status !== 'qualified') {
                            $this->error('FAIL: Sale not qualified');
                            $failures++;
                        } else {
                            $this->info('PASS: Sale qualified');
                        }
                    }

                    $estimateResponse = $this->requestAs($rep, 'POST', "/pre-sales/sales/{$sale->id}/estimate", [
                        'developers_count' => 2,
                        'dev_hours' => 200,
                        'design_hours' => 40,
                        'qa_hours' => 30,
                        'hourly_rate_dev' => 150,
                        'hourly_rate_design' => 120,
                        'hourly_rate_qa' => 100,
                        'hourly_rate_pm' => 130,
                        'margin_percent' => 20,
                        'approve_now' => 1,
                    ]);
                    if (! in_array($estimateResponse->getStatusCode(), [200, 302], true)) {
                        $this->error('FAIL: Store estimate — HTTP '.$estimateResponse->getStatusCode());
                        $failures++;
                    } else {
                        $this->info('PASS: Cost estimation approved');
                    }

                    $proposalResponse = $this->requestAs($rep, 'POST', "/pre-sales/sales/{$sale->id}/proposals/generate");
                    if (! in_array($proposalResponse->getStatusCode(), [200, 302], true)) {
                        $this->error('FAIL: Generate proposal — HTTP '.$proposalResponse->getStatusCode());
                        $failures++;
                    } else {
                        $sale->load('proposals');
                        $proposal = $sale->proposals->first();
                        if (! $proposal) {
                            $this->error('FAIL: Proposal not created');
                            $failures++;
                        } else {
                            $this->info("PASS: Proposal generated (#{$proposal->id})");

                            $acceptResponse = $this->requestAs($rep, 'POST', "/pre-sales/proposals/{$proposal->id}/accept");
                            if (! in_array($acceptResponse->getStatusCode(), [200, 302], true)) {
                                $this->error('FAIL: Accept proposal — HTTP '.$acceptResponse->getStatusCode());
                                $failures++;
                            } else {
                                $this->info('PASS: Proposal accepted');
                            }

                            $contractResponse = $this->requestAs($rep, 'POST', "/workflow/sales/{$sale->id}/create-contract");
                            if (! in_array($contractResponse->getStatusCode(), [200, 302], true)) {
                                $this->error('FAIL: Create contract — HTTP '.$contractResponse->getStatusCode());
                                $failures++;
                            } else {
                                $contract = Contract::where('sale_id', $sale->id)->first();
                                if (! $contract) {
                                    $this->error('FAIL: Contract not created');
                                    $failures++;
                                } else {
                                    $this->info("PASS: Contract created (#{$contract->id})");

                                    $showContract = $this->requestAs($rep, 'GET', "/contracts/{$contract->id}");
                                    $failures += $this->assertStatus($showContract, 200, 'View own contract');

                                    $invoiceResponse = $this->requestAs($rep, 'POST', "/workflow/contracts/{$contract->id}/deposit-invoice");
                                    if (! in_array($invoiceResponse->getStatusCode(), [200, 302], true)) {
                                        $this->error('FAIL: Deposit invoice — HTTP '.$invoiceResponse->getStatusCode());
                                        $failures++;
                                    } else {
                                        $invoice = Invoice::where('contract_id', $contract->id)->first();
                                        if (! $invoice) {
                                            $this->error('FAIL: Invoice not created');
                                            $failures++;
                                        } else {
                                            $this->info("PASS: Deposit invoice created (#{$invoice->id})");
                                            $showInvoice = $this->requestAs($rep, 'GET', "/invoices/{$invoice->id}");
                                            $failures += $this->assertStatus($showInvoice, 200, 'View own invoice');
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->newLine();
        $this->info('=== 5. Lead convert workflow ===');
        if (isset($lead)) {
            $convertLeadEmail = "{$this->testKey}-convert@test.local";
            $convertLead = Lead::create([
                'reference_code' => Lead::generateReferenceCode(),
                'source' => 'referral',
                'status' => 'qualified',
                'name' => 'Convert '.$this->testKey,
                'email' => $convertLeadEmail,
                'assigned_to' => $rep->id,
                'created_by' => $rep->id,
            ]);

            $convertResponse = $this->requestAs($rep, 'POST', "/leads/{$convertLead->id}/convert-to-sale");
            if (! in_array($convertResponse->getStatusCode(), [200, 302], true)) {
                $this->error('FAIL: Convert lead — HTTP '.$convertResponse->getStatusCode());
                $failures++;
            } else {
                $convertLead->refresh();
                if (! $convertLead->converted_sale_id) {
                    $this->error('FAIL: Lead not marked converted');
                    $failures++;
                } else {
                    $this->info('PASS: Lead converted to sale');
                }
            }
        }

        $this->newLine();
        $this->info('=== 6. Notifications API for sales rep ===');
        Notification::create([
            'user_id' => $rep->id,
            'type' => 'message',
            'title' => 'اختبار إشعار للمندوب',
            'message' => 'مرحباً — هذا إشعار تجريبي '.$this->testKey,
            'data' => ['test' => true, 'key' => $this->testKey],
            'is_read' => false,
        ]);

        $jsonResponse = $this->requestAs($rep, 'GET', '/notifications?json=1');
        if ($jsonResponse->getStatusCode() !== 200) {
            $this->error('FAIL: Notifications JSON — HTTP '.$jsonResponse->getStatusCode());
            $failures++;
        } else {
            $payload = json_decode($jsonResponse->getContent(), true);
            $hasTest = collect($payload['notifications'] ?? [])
                ->contains(fn ($n) => ($n['data']['key'] ?? null) === $this->testKey);
            if (! $hasTest) {
                $this->error('FAIL: Rep notifications JSON missing test notification');
                $failures++;
            } else {
                $this->info('PASS: Rep can fetch notifications (JSON)');
            }
        }

        $this->newLine();
        $this->info('=== 7. Login verification code flow ===');
        $rep->update(['login_verification_enabled' => true]);
        VerificationCode::where('user_id', $rep->id)->delete();

        $code = VerificationCode::createForUser($rep->id, 10);
        if (! $code?->code) {
            $this->error('FAIL: Verification code not generated');
            $failures++;
        } else {
            $this->info('PASS: Verification code generated ('.$code->code.')');

            LoginActivityLog::logActivity(
                $rep->id,
                'verification_code_sent',
                $code->code,
                $rep->email,
                'success',
                'اختبار إرسال كود التحقق'
            );

            $logExists = LoginActivityLog::where('user_id', $rep->id)
                ->where('activity_type', 'verification_code_sent')
                ->where('status', 'success')
                ->exists();
            $failures += $logExists ? 0 : $this->failOnce('Login activity log for verification code');

            if (config('mail.default') === 'log' || env('MAIL_MAILER') === 'log') {
                $mailer = new EmailService;
                $sent = $mailer->sendVerificationCode($rep->email, $code->code, $rep->name);
                $failures += $sent ? 0 : $this->failOnce('Verification email via EmailService (log driver)');
                if ($sent) {
                    $this->info('PASS: Verification email queued/logged (MAIL_MAILER=log)');
                }
            } else {
                $this->warn('SKIP: Live SMTP not tested — set MAIL_MAILER=log for safe local test');
            }
        }

        $rep->update(['login_verification_enabled' => false]);

        $this->newLine();
        $this->info('=== 8. Blocked modules still blocked ===');
        $blocked = [
            '/contracts' => 403,
            '/invoices' => 403,
            '/invoices/create' => 403,
            '/employees' => 403,
            '/executive' => 403,
        ];
        foreach ($blocked as $uri => $expected) {
            $failures += $this->runCase("Block {$uri}", fn () => $this->requestAs($rep, 'GET', $uri), $expected);
        }

        $adminNotifAfter = Notification::where('user_id', $admin->id)->count();
        $newAdminNotifs = $adminNotifAfter - $adminNotifBefore;
        $this->newLine();
        $this->info("Admin received {$newAdminNotifs} new in-app notification(s) during workflow.");

        if (! $this->option('keep')) {
            $this->cleanup();
        } else {
            $this->warn('Test data kept (--keep). Key: '.$this->testKey);
        }

        if ($failures > 0) {
            $this->error("Completed with {$failures} failure(s).");

            return self::FAILURE;
        }

        $this->info('All sales rep workflow checks passed.');

        return self::SUCCESS;
    }

    protected function ensureRolesExist(): void
    {
        if (! Role::where('name', 'sales_rep')->exists()) {
            $this->call('db:seed', ['--class' => RolePermissionSeeder::class]);
        }
    }

    /** @return array{0: User, 1: Employee} */
    protected function ensureSalesRep(): array
    {
        $email = "{$this->testKey}-rep@solvesta.test";
        $this->createdEmails[] = $email;

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Workflow Test Rep',
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
                'employee_id' => strtoupper($this->testKey),
                'first_name' => 'Workflow',
                'last_name' => 'Rep',
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

    protected function requestAs(User $user, string $method, string $uri, array $data = []): \Symfony\Component\HttpFoundation\Response
    {
        Auth::login($user);

        $session = app('session.store');
        $session->start();
        $session->put('verified', true);

        $token = Str::random(40);
        $session->put('_token', $token);

        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $data['_token'] = $token;
        }

        $request = Request::create($uri, $method, $data);
        $request->setLaravelSession($session);
        $request->setUserResolver(fn () => $user);
        $request->headers->set('X-CSRF-TOKEN', $token);

        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            $request->headers->set('Referer', url('/'));
        }

        $response = app(Kernel::class)->handle($request);
        app(Kernel::class)->terminate($request, $response);

        return $response;
    }

    protected function runCase(string $label, callable $callback, int $expectedStatus): int
    {
        Auth::logout();
        $response = $callback();

        return $this->assertStatus($response, $expectedStatus, $label);
    }

    protected function assertStatus(\Symfony\Component\HttpFoundation\Response $response, int $expected, string $label): int
    {
        $actual = $response->getStatusCode();
        if ($actual === $expected) {
            $this->info("PASS: {$label} ({$actual})");

            return 0;
        }

        $this->error("FAIL: {$label} — expected {$expected}, got {$actual}");

        return 1;
    }

    protected function failOnce(string $label): int
    {
        $this->error("FAIL: {$label}");

        return 1;
    }

    protected function cleanup(): void
    {
        $pattern = $this->testKey;

        Sale::where('product_service', 'like', "%{$pattern}%")->delete();
        Lead::where('email', 'like', "%{$pattern}%")->orWhere('name', 'like', "%{$pattern}%")->delete();
        Client::where('email', 'like', "%{$pattern}%")->delete();
        Notification::where('message', 'like', "%{$pattern}%")->delete();

        foreach ($this->createdEmails as $email) {
            if ($user = User::where('email', $email)->first()) {
                VerificationCode::where('user_id', $user->id)->delete();
                LoginActivityLog::where('user_id', $user->id)->delete();
                Employee::where('user_id', $user->id)->delete();
                $user->delete();
            }
        }
    }
}
