<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SalesRepScope;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AuditSalesRepRoutes extends Command
{
    protected $signature = 'solvesta:audit-routes {--email= : Sales rep email to simulate}';

    protected $description = 'Audit route access for restricted sales reps (403 vs allowed)';

    public function handle(): int
    {
        $email = $this->option('email') ?? 'sales.manager@solvesta.com';
        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User not found: {$email}");

            return self::FAILURE;
        }

        if (! SalesRepScope::isRestricted($user)) {
            $this->warn("User {$email} is not a restricted sales rep — audit results may not apply.");
        }

        $allowed = [];
        $blocked = [];
        $skipped = [];

        foreach (Route::getRoutes() as $route) {
            $methods = array_diff($route->methods(), ['HEAD']);
            if ($methods !== ['GET']) {
                continue;
            }

            $name = $route->getName();
            if (! $name || Str::startsWith($name, ['website.', 'client.'])) {
                $skipped[] = $name ?? $route->uri();

                continue;
            }

            if (SalesRepScope::canAccessRoute($user, $name)) {
                $allowed[] = [$name, $route->uri()];
            } else {
                $blocked[] = [$name, $route->uri()];
            }
        }

        $this->info("Audit for: {$user->name} ({$email})");
        $this->newLine();
        $this->line('<fg=green>Allowed GET routes ('.count($allowed).'):</>');
        foreach ($allowed as [$name, $uri]) {
            $this->line("  ✓ {$name} → /{$uri}");
        }

        $this->newLine();
        $this->line('<fg=red>Blocked GET routes ('.count($blocked).'):</>');
        foreach ($blocked as [$name, $uri]) {
            $this->line("  ✗ {$name} → /{$uri}");
        }

        $this->newLine();
        $this->line('Skipped (non-GET or public/client portal): '.count($skipped));

        return self::SUCCESS;
    }
}
