<?php

namespace App\Http\Controllers;

use App\Models\FinancialInvoice as Invoice;
use App\Models\Client;
use App\Models\Project;
use App\Models\Wallet;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinancialInvoiceController extends Controller
{
    public function index()
    {
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $pendingInvoices = Invoice::whereIn('status', ['sent', 'viewed', 'draft', 'partial'])->count();
        $overdueInvoices = Invoice::overdue()->count();

        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');
        $monthlyRevenue = Invoice::currentMonth()->where('status', 'paid')->sum('total_amount');
        $pendingAmount = Invoice::whereIn('status', ['sent', 'viewed', 'partial'])->sum('balance_due');

        $invoices = Invoice::with(['client', 'project', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('invoices.index', compact(
            'totalInvoices',
            'paidInvoices',
            'pendingInvoices',
            'overdueInvoices',
            'totalRevenue',
            'monthlyRevenue',
            'pendingAmount',
            'invoices'
        ));
    }

    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $projects = Project::whereIn('status', ['planning', 'in_progress', 'on_hold'])
            ->orderBy('name')
            ->get();
        $invoiceNumber = Invoice::generateInvoiceNumber();

        return view('invoices.create', compact('clients', 'projects', 'invoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_type' => 'nullable|in:sales,purchase,service',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:draft,sent,viewed,partial,paid,overdue,cancelled',
        ]);

        $totals = $this->calculateTotals($validated);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_type' => $validated['invoice_type'] ?? 'sales',
            'client_id' => $validated['client_id'],
            'project_id' => $validated['project_id'] ?? null,
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $totals['subtotal'],
            'tax_rate' => $totals['tax_rate'],
            'tax_amount' => $totals['tax_amount'],
            'discount_amount' => $totals['discount_amount'],
            'total_amount' => $totals['total_amount'],
            'paid_amount' => 0,
            'balance_due' => $totals['total_amount'],
            'status' => 'draft',
            'payment_status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        $this->syncItems($invoice, $validated['items']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الفاتورة بنجاح',
                'invoice_id' => $invoice->id,
            ]);
        }

        return redirect()
            ->route('financial-invoices.show', $invoice)
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show(Invoice $financialInvoice)
    {
        $financialInvoice->load([
            'client',
            'project',
            'createdBy',
            'items',
            'payments.wallet',
            'walletTransactions.wallet',
            'lifecycle.currentState',
            'lifecycle.definition.states',
            'workItems.assignee',
            'companyEvents.actor',
            'approvalRequests.requester',
        ]);

        $wallets = Wallet::where('is_active', true)->orderBy('name')->get();

        return view('invoices.show', [
            'invoice' => $financialInvoice,
            'wallets' => $wallets,
        ]);
    }

    public function print(Invoice $financialInvoice)
    {
        $financialInvoice->load(['client', 'project', 'items', 'payments.wallet']);

        return view('invoices.print', [
            'invoice' => $financialInvoice,
            'backUrl' => route('financial-invoices.show', $financialInvoice),
        ]);
    }

    public function recordPayment(Request $request, Invoice $financialInvoice, WalletService $walletService)
    {
        if (in_array($financialInvoice->status, ['paid', 'cancelled'], true) || (float) $financialInvoice->balance_due <= 0) {
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
            $payment = $walletService->recordInvoicePayment($financialInvoice, $validated);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $financialInvoice->refresh();

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل الدفعة بنجاح',
            'payment_id' => $payment->id,
            'invoice_status' => $financialInvoice->status,
            'balance_due' => $financialInvoice->balance_due,
            'paid_amount' => $financialInvoice->paid_amount,
        ]);
    }

    public function edit(Invoice $financialInvoice)
    {
        abort_unless(auth()->user()->can('edit-finance'), 403);

        if ($financialInvoice->status === 'paid') {
            return redirect()
                ->route('financial-invoices.show', $financialInvoice)
                ->with('error', 'لا يمكن تعديل فاتورة مدفوعة بالكامل. يمكنك عرضها أو طباعتها فقط.');
        }

        $financialInvoice->load(['client', 'project', 'items']);
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $projects = Project::whereIn('status', ['planning', 'in_progress', 'on_hold', 'completed'])
            ->orderBy('name')
            ->get();

        return view('invoices.edit', [
            'invoice' => $financialInvoice,
            'clients' => $clients,
            'projects' => $projects,
        ]);
    }

    public function update(Request $request, Invoice $financialInvoice)
    {
        abort_unless(auth()->user()->can('edit-finance'), 403);

        if ($financialInvoice->status === 'paid') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن تعديل فاتورة مدفوعة بالكامل',
                ], 422);
            }

            return back()->with('error', 'لا يمكن تعديل فاتورة مدفوعة بالكامل');
        }

        $validated = $request->validate([
            'invoice_type' => 'nullable|in:sales,purchase,service',
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $totals = $this->calculateTotals($validated);
        $paidAmount = (float) $financialInvoice->paid_amount;

        if ($totals['total_amount'] < $paidAmount) {
            $message = 'إجمالي الفاتورة الجديد أقل من المبلغ المدفوع حالياً ('.number_format($paidAmount, 2).' ج.م)';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return back()->withInput()->with('error', $message);
        }

        DB::transaction(function () use ($financialInvoice, $validated, $totals, $paidAmount) {
            $balanceDue = $totals['total_amount'] - $paidAmount;
            $status = $financialInvoice->status;

            if ($paidAmount > 0 && $balanceDue > 0) {
                $status = 'partial';
            } elseif ($paidAmount > 0 && $balanceDue <= 0) {
                $status = 'paid';
                $balanceDue = 0;
            }

            $financialInvoice->update([
                'invoice_type' => $validated['invoice_type'] ?? $financialInvoice->invoice_type,
                'client_id' => $validated['client_id'],
                'project_id' => $validated['project_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $totals['subtotal'],
                'tax_rate' => $totals['tax_rate'],
                'tax_amount' => $totals['tax_amount'],
                'discount_amount' => $totals['discount_amount'],
                'total_amount' => $totals['total_amount'],
                'balance_due' => $balanceDue,
                'status' => $status,
                'payment_status' => $paidAmount <= 0 ? 'unpaid' : ($balanceDue > 0 ? 'partial' : 'paid'),
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->syncItems($financialInvoice, $validated['items']);
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الفاتورة بنجاح',
                'invoice_id' => $financialInvoice->id,
            ]);
        }

        return redirect()
            ->route('financial-invoices.show', $financialInvoice)
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    public function destroy(Request $request, Invoice $financialInvoice)
    {
        abort_unless(auth()->user()->can('delete-finance') || auth()->user()->can('edit-finance'), 403);

        if ($financialInvoice->status === 'paid') {
            $message = 'لا يمكن حذف الفواتير المدفوعة بالكامل';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }

            return back()->with('error', $message);
        }

        if ($financialInvoice->payments()->exists()) {
            $message = 'لا يمكن حذف فاتورة عليها دفعات مسجّلة. ألغِ الفاتورة بدل الحذف.';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }

            return back()->with('error', $message);
        }

        DB::transaction(function () use ($financialInvoice) {
            $financialInvoice->items()->delete();
            $financialInvoice->delete();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف الفاتورة بنجاح',
            ]);
        }

        return redirect()
            ->route('financial-invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    public function markAsSent(Invoice $financialInvoice)
    {
        $financialInvoice->update(['status' => 'sent']);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الفاتورة إلى مرسل',
        ]);
    }

    public function markAsPaid(Invoice $financialInvoice)
    {
        $financialInvoice->update([
            'status' => 'paid',
            'payment_status' => 'paid',
            'paid_amount' => $financialInvoice->total_amount,
            'balance_due' => 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة الفاتورة إلى مدفوع',
        ]);
    }

    private function calculateTotals(array $validated): array
    {
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $subtotal += $qty * $price;
        }

        $taxRate = (float) ($validated['tax_rate'] ?? 0);
        $taxAmount = ($subtotal * $taxRate) / 100;
        $discountAmount = (float) ($validated['discount_amount'] ?? 0);
        $totalAmount = max(0, $subtotal + $taxAmount - $discountAmount);

        return [
            'subtotal' => $subtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
        ];
    }

    private function syncItems(Invoice $invoice, array $items): void
    {
        $invoice->items()->delete();

        foreach ($items as $item) {
            $qty = (float) ($item['quantity'] ?? 1);
            $price = (float) ($item['unit_price'] ?? 0);
            $description = $item['description'];

            $invoice->items()->create([
                'item_name' => $description,
                'description' => $description,
                'quantity' => $qty,
                'unit_price' => $price,
                'amount' => $qty * $price,
            ]);
        }
    }
}
