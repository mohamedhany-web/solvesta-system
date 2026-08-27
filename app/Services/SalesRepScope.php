<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Lead;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SalesRepScope
{
    /** @return string[] */
    public static function allowedRoutePatterns(): array
    {
        return [
            'dashboard',
            'profile.*',
            'messages.*',
            'notifications.*',
            'logout',
            'verification.*',
            'sales.dashboard',
            'sales.index',
            'sales.create',
            'sales.store',
            'sales.show',
            'sales.edit',
            'sales.update',
            'sales.update-stage',
            'sales.generate-invoice',
            'sales.statistics',
            'sales.guide',
            'sales.account',
            'sales.account.payout',
            'leads.*',
            'clients.*',
            'contracts.show',
            'invoices.show',
            'invoices.print',
            'pre-sales.*',
            'workflow.*',
        ];
    }

    public static function canAccessRoute(User $user, ?string $routeName): bool
    {
        if (! self::isRestricted($user) || ! $routeName) {
            return true;
        }

        foreach (self::allowedRoutePatterns() as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }

        return false;
    }

    public static function isRestricted(User $user): bool
    {
        return $user->hasRole('sales_rep') && ! $user->can('edit-employees');
    }

    public static function employeeId(User $user): ?int
    {
        return $user->employee?->id;
    }

    public static function scopeClients(Builder $query, User $user): Builder
    {
        if (! self::isRestricted($user)) {
            return $query;
        }

        $employeeId = self::employeeId($user);

        return $employeeId
            ? $query->where('assigned_to', $employeeId)
            : $query->whereRaw('1 = 0');
    }

    public static function scopeSales(Builder $query, User $user): Builder
    {
        if (! self::isRestricted($user)) {
            return $query;
        }

        return $query->where('assigned_to', $user->id);
    }

    public static function scopeLeads(Builder $query, User $user): Builder
    {
        if (! self::isRestricted($user)) {
            return $query;
        }

        return $query->where(function ($q) use ($user) {
            $q->where('assigned_to', $user->id)
                ->orWhere('created_by', $user->id);
        });
    }

    public static function scopeContracts(Builder $query, User $user): Builder
    {
        if (! self::isRestricted($user)) {
            return $query;
        }

        $employeeId = self::employeeId($user);

        return $query->where(function ($q) use ($user, $employeeId) {
            $q->whereHas('sale', fn ($s) => $s->where('assigned_to', $user->id));
            if ($employeeId) {
                $q->orWhereHas('client', fn ($c) => $c->where('assigned_to', $employeeId));
            }
        });
    }

    public static function scopeInvoices(Builder $query, User $user): Builder
    {
        if (! self::isRestricted($user)) {
            return $query;
        }

        $employeeId = self::employeeId($user);

        return $query->where(function ($q) use ($user, $employeeId) {
            $q->whereHas('sale', fn ($s) => $s->where('assigned_to', $user->id));
            if ($employeeId) {
                $q->orWhereHas('client', fn ($c) => $c->where('assigned_to', $employeeId));
            }
        });
    }

    public static function ownsClient(User $user, Client $client): bool
    {
        if (! self::isRestricted($user)) {
            return true;
        }

        $employeeId = self::employeeId($user);

        return $employeeId && (int) $client->assigned_to === (int) $employeeId;
    }

    public static function ownsSale(User $user, Sale $sale): bool
    {
        if (! self::isRestricted($user)) {
            return true;
        }

        return (int) $sale->assigned_to === (int) $user->id;
    }

    public static function ownsLead(User $user, Lead $lead): bool
    {
        if (! self::isRestricted($user)) {
            return true;
        }

        return (int) $lead->assigned_to === (int) $user->id
            || (int) $lead->created_by === (int) $user->id;
    }

    public static function authorizeClient(User $user, Client $client): void
    {
        if (! self::ownsClient($user, $client)) {
            abort(403, 'غير مصرح لك — هذا العميل ليس ضمن محفظتك.');
        }
    }

    public static function authorizeSale(User $user, Sale $sale): void
    {
        if (! self::ownsSale($user, $sale)) {
            abort(403, 'غير مصرح لك — هذه الفرصة ليست مخصصة لك.');
        }
    }

    public static function authorizeLead(User $user, Lead $lead): void
    {
        if (! self::ownsLead($user, $lead)) {
            abort(403, 'غير مصرح لك — هذا الـ Lead ليس ضمن مسؤولياتك.');
        }
    }

    public static function authorizeContract(User $user, Contract $contract): void
    {
        if (! self::isRestricted($user)) {
            return;
        }

        $contract->loadMissing(['sale', 'client']);

        $allowed = ($contract->sale && self::ownsSale($user, $contract->sale))
            || ($contract->client && self::ownsClient($user, $contract->client));

        if (! $allowed) {
            abort(403, 'غير مصرح لك — هذا العقد خارج نطاق عملك.');
        }
    }

    public static function authorizeInvoice(User $user, \App\Models\Invoice $invoice): void
    {
        if (! self::isRestricted($user)) {
            return;
        }

        $invoice->loadMissing(['sale', 'client']);

        $allowed = ($invoice->sale && self::ownsSale($user, $invoice->sale))
            || ($invoice->client && self::ownsClient($user, $invoice->client));

        if (! $allowed) {
            abort(403, 'غير مصرح لك — هذه الفاتورة خارج نطاق عملك.');
        }
    }

    /** @return int[] */
    public static function clientIdsForUser(User $user): array
    {
        if (! self::isRestricted($user)) {
            return [];
        }

        $employeeId = self::employeeId($user);
        if (! $employeeId) {
            return [];
        }

        return Client::where('assigned_to', $employeeId)->pluck('id')->all();
    }

    public static function assertClientIdOwned(User $user, int $clientId): void
    {
        if (! self::isRestricted($user)) {
            return;
        }

        $employeeId = self::employeeId($user);
        if (! $employeeId || ! Client::where('id', $clientId)->where('assigned_to', $employeeId)->exists()) {
            abort(403, 'لا يمكنك استخدام عميل خارج محفظتك.');
        }
    }

    /**
     * Restricted reps manage contracts/invoices only through the sale workflow — not standalone modules.
     */
    public static function denyStandaloneContractInvoiceModules(): void
    {
        if (self::isRestricted(auth()->user())) {
            abort(403, 'العقود والفواتير تُنشأ من داخل صفحة الفرصة (Pipeline) بعد قبول العرض. راجع «دليل النظام» من القائمة.');
        }
    }
}
