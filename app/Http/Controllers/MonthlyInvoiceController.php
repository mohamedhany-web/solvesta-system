<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\FinancialInvoice;
use App\Models\Wallet;
use App\Services\ClientServiceBillingService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class MonthlyInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $base = FinancialInvoice::monthlyService()->where('status', '!=', 'cancelled');

        if ($request->filled('client_id')) {
            $base->where('client_id', $request->integer('client_id'));
        }
        if ($request->filled('client_service_id')) {
            $base->where('client_service_id', $request->integer('client_service_id'));
        }
        if ($request->filled('status')) {
            $base->where('status', $request->string('status'));
        }
        if ($request->filled('month')) {
            $month = $this->parseYearMonth((string) $request->input('month'));
            if ($month) {
                $base->whereYear('invoice_date', $month['y'])->whereMonth('invoice_date', $month['m']);
            }
        }

        $statsQuery = FinancialInvoice::monthlyService()->where('status', '!=', 'cancelled');
        $totalInvoices = (clone $statsQuery)->count();
        $paidInvoices = (clone $statsQuery)->where(function ($q) {
            $q->where('status', 'paid')->orWhere('payment_status', 'paid');
        })->count();
        $pendingInvoices = (clone $statsQuery)->whereIn('status', ['sent', 'viewed', 'draft', 'partial', 'overdue'])->count();
        $overdueInvoices = (clone $statsQuery)->overdue()->count();
        $totalRevenue = (float) (clone $statsQuery)->sum('paid_amount');
        $monthlyRevenue = (float) (clone $statsQuery)
            ->whereYear('invoice_date', now()->year)
            ->whereMonth('invoice_date', now()->month)
            ->sum('paid_amount');
        $pendingAmount = (float) (clone $statsQuery)
            ->where(function ($q) {
                $q->where('status', '!=', 'paid')
                    ->where(function ($inner) {
                        $inner->whereNull('payment_status')->orWhere('payment_status', '!=', 'paid');
                    });
            })
            ->sum('balance_due');

        $invoices = (clone $base)
            ->with(['client', 'clientService', 'createdBy'])
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $clients = Client::where('status', 'active')->orderBy('name')->get(['id', 'name']);
        $services = ClientService::with('client:id,name')
            ->whereIn('status', ['active', 'paused'])
            ->orderBy('title')
            ->get(['id', 'client_id', 'title', 'monthly_amount']);

        return view('monthly-invoices.index', compact(
            'invoices',
            'clients',
            'services',
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'overdueInvoices',
            'totalRevenue',
            'monthlyRevenue',
            'pendingAmount'
        ));
    }

    public function create(Request $request, ClientServiceBillingService $billing)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $services = ClientService::with('client:id,name')
            ->whereIn('status', ['active', 'paused'])
            ->orderBy('title')
            ->get();

        $selectedClientId = $request->integer('client_id') ?: null;
        $selectedServiceId = $request->integer('client_service_id') ?: null;
        $requirements = ['features' => collect(), 'issues' => collect()];

        if ($selectedClientId) {
            $requirements = $billing->unbilledRequirementsForClient($selectedClientId);
        }

        return view('monthly-invoices.create', compact(
            'clients',
            'services',
            'selectedClientId',
            'selectedServiceId',
            'requirements'
        ));
    }

    public function billables(Request $request, ClientServiceBillingService $billing)
    {
        $clientId = $request->integer('client_id');
        abort_unless($clientId > 0, 422, 'client_id مطلوب');

        $data = $billing->unbilledRequirementsForClient($clientId);

        return response()->json([
            'features' => $data['features']->map(fn ($f) => [
                'id' => $f->id,
                'reference_code' => $f->reference_code,
                'title' => $f->title,
                'type' => $f->type,
                'type_label' => $f->type_label,
                'status' => $f->status_label,
                'priority' => $f->priority_label,
            ]),
            'issues' => $data['issues']->map(fn ($i) => [
                'id' => $i->id,
                'reference_code' => $i->reference_code,
                'title' => $i->title,
                'status' => $i->status_label,
            ]),
        ]);
    }

    public function store(Request $request, ClientServiceBillingService $billing)
    {
        $validated = $request->validate([
            'client_service_id' => 'required|exists:client_services,id',
            'invoice_date' => 'nullable|date',
            'notes' => 'nullable|string|max:2000',
            'advance_billing' => 'nullable|boolean',
            'extras' => 'nullable|array',
            'extras.*.type' => 'required_with:extras|in:feature,issue',
            'extras.*.id' => 'required_with:extras|integer',
            'extras.*.amount' => 'required_with:extras|numeric|min:0',
            'extras.*.description' => 'nullable|string|max:500',
        ]);

        $service = ClientService::findOrFail($validated['client_service_id']);
        $extras = array_values(array_filter($validated['extras'] ?? [], fn ($row) => isset($row['id'], $row['type'])));

        try {
            $invoice = $billing->generateMonthlyInvoice(
                $service,
                ! empty($validated['invoice_date']) ? \Carbon\Carbon::parse($validated['invoice_date']) : null,
                $extras,
                $request->boolean('advance_billing', true),
                $validated['notes'] ?? null
            );
        } catch (\InvalidArgumentException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'تم إصدار الفاتورة الشهرية بنجاح');
    }

    public function show(FinancialInvoice $invoice)
    {
        $this->assertMonthly($invoice);

        $invoice->load([
            'client',
            'clientService',
            'project',
            'createdBy',
            'items.billable',
            'payments.wallet',
            'walletTransactions.wallet',
        ]);

        $wallets = Wallet::where('is_active', true)->orderBy('name')->get();

        return view('invoices.show', [
            'invoice' => $invoice,
            'wallets' => $wallets,
            'isMonthlyInvoice' => true,
        ]);
    }

    public function print(FinancialInvoice $invoice)
    {
        $this->assertMonthly($invoice);
        $invoice->load(['client', 'clientService', 'items', 'payments.wallet']);

        return view('invoices.print', [
            'invoice' => $invoice,
            'backUrl' => route('invoices.show', $invoice),
        ]);
    }

    public function recordPayment(Request $request, FinancialInvoice $invoice, WalletService $walletService)
    {
        $this->assertMonthly($invoice);

        if (in_array($invoice->status, ['paid', 'cancelled'], true) || (float) $invoice->balance_due <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تسجيل دفعة على فاتورة مدفوعة أو ملغاة',
            ], 422);
        }

        $validated = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,check,credit_card,online',
            'reference_number' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $payment = $walletService->recordInvoicePayment($invoice, $validated);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $invoice->refresh();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدفعة بنجاح',
            'payment_id' => $payment->id,
            'invoice_status' => $invoice->status,
            'balance_due' => $invoice->balance_due,
            'paid_amount' => $invoice->paid_amount,
        ]);
    }

    protected function assertMonthly(FinancialInvoice $invoice): void
    {
        $isMonthly = $invoice->invoice_type === 'service' || $invoice->client_service_id !== null;
        abort_unless($isMonthly, 404);
    }

    /** @return array{y:int,m:int}|null */
    protected function parseYearMonth(?string $value): ?array
    {
        if (! $value || ! preg_match('/^(\d{4})-(\d{2})$/', $value, $m)) {
            return null;
        }

        return ['y' => (int) $m[1], 'm' => (int) $m[2]];
    }
}
