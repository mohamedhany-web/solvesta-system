<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Contract;
use App\Services\ClientServiceBillingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClientServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientService::with(['client', 'contract'])->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhere('service_number', 'like', "%{$q}%");
            });
        }

        $services = $query->paginate(20)->withQueryString();

        $stats = [
            'active' => ClientService::where('status', 'active')->count(),
            'due_today' => ClientService::billableToday()->count(),
            'monthly_mrr' => (float) ClientService::where('status', 'active')->sum('monthly_amount'),
        ];

        $clients = Client::orderBy('name')->get(['id', 'name']);

        return view('accounting.client-services.index', compact('services', 'stats', 'clients'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $contracts = Contract::with('client')
            ->when($request->client_id, fn ($q) => $q->where('client_id', $request->client_id))
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        $prefill = [
            'client_id' => $request->client_id,
            'contract_id' => $request->contract_id,
        ];

        return view('accounting.client-services.create', compact('clients', 'contracts', 'prefill'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateService($request);
        $validated['service_number'] = ClientService::generateServiceNumber();
        $validated['created_by'] = auth()->id();

        if ($validated['status'] === 'active' && empty($validated['next_billing_date'])) {
            $validated['next_billing_date'] = $this->initialBillingDate(
                $validated['start_date'],
                (int) $validated['billing_day']
            )->toDateString();
        }

        $service = ClientService::create($validated);

        return redirect()
            ->route('accounting.client-services.show', $service)
            ->with('success', 'تم تسجيل خدمة ما بعد البيع بنجاح.');
    }

    public function show(ClientService $clientService)
    {
        $clientService->load([
            'client',
            'contract',
            'creator',
            'financialInvoices' => fn ($q) => $q->orderByDesc('invoice_date')->limit(24),
        ]);

        return view('accounting.client-services.show', compact('clientService'));
    }

    public function edit(ClientService $clientService)
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $contracts = Contract::where('client_id', $clientService->client_id)->orderByDesc('id')->get();

        return view('accounting.client-services.edit', compact('clientService', 'clients', 'contracts'));
    }

    public function update(Request $request, ClientService $clientService)
    {
        $validated = $this->validateService($request, $clientService);

        if ($validated['status'] === 'active' && ! $clientService->next_billing_date && empty($validated['next_billing_date'])) {
            $validated['next_billing_date'] = $this->initialBillingDate(
                $validated['start_date'],
                (int) $validated['billing_day']
            )->toDateString();
        }

        $clientService->update($validated);

        return redirect()
            ->route('accounting.client-services.show', $clientService)
            ->with('success', 'تم تحديث الخدمة.');
    }

    public function destroy(ClientService $clientService)
    {
        if ($clientService->financialInvoices()->exists()) {
            return back()->with('error', 'لا يمكن حذف خدمة مرتبطة بفواتير. يمكنك إنهاؤها بدلاً من ذلك.');
        }

        $clientService->delete();

        return redirect()
            ->route('accounting.client-services.index')
            ->with('success', 'تم حذف الخدمة.');
    }

    public function generateInvoice(ClientService $clientService, ClientServiceBillingService $billing)
    {
        try {
            $invoice = $billing->generateMonthlyInvoice($clientService);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'تعذر إنشاء الفاتورة: '.$e->getMessage());
        }

        return redirect()
            ->route('financial-invoices.show', $invoice)
            ->with('success', 'تم إصدار فاتورة الخدمة '.$invoice->invoice_number);
    }

    protected function validateService(Request $request, ?ClientService $existing = null): array
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'contract_id' => 'nullable|exists:contracts,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'monthly_amount' => 'required|numeric|min:0',
            'billing_day' => 'required|integer|min:1|max:28',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|size:3',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'next_billing_date' => 'nullable|date',
            'payment_terms_days' => 'required|integer|min:1|max:90',
            'status' => 'required|in:draft,active,paused,ended',
            'auto_invoice' => 'boolean',
            'notes' => 'nullable|string|max:5000',
        ]);

        if (! empty($validated['contract_id'])) {
            $contract = Contract::find($validated['contract_id']);
            if ($contract && (int) $contract->client_id !== (int) $validated['client_id']) {
                abort(422, 'العقد المختار لا يخص هذا العميل.');
            }
        }

        $validated['auto_invoice'] = $request->boolean('auto_invoice', true);
        $validated['tax_rate'] = $validated['tax_rate'] ?? 0;
        $validated['currency'] = $validated['currency'] ?? 'EGP';

        return $validated;
    }

    protected function initialBillingDate(string $startDate, int $billingDay): Carbon
    {
        $start = Carbon::parse($startDate);
        $day = min(max($billingDay, 1), 28);
        $candidate = $start->copy()->day($day);
        if ($candidate->lt($start)) {
            $candidate = $start->copy()->addMonthNoOverflow()->day($day);
        }

        return $candidate;
    }
}
