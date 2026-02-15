<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Client;
use App\Models\Employee;
use App\Models\User;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Account;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with(['client', 'salesRep', 'employee'])
            ->when(request('search'), function ($query) {
                $query->where('product_service', 'like', '%' . request('search') . '%')
                    ->orWhereHas('client', function ($q) {
                        $q->where('name', 'like', '%' . request('search') . '%');
                    });
            })
            ->when(request('stage'), function ($query) {
                $query->where('stage', request('stage'));
            })
            ->when(request('client_id'), function ($query) {
                $query->where('client_id', request('client_id'));
            })
            ->when(request('assigned_to'), function ($query) {
                $query->where('assigned_to', request('assigned_to'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // الحصول على الفواتير المرتبطة بالمبيعات
        $saleIds = $sales->pluck('id');
        $invoices = Invoice::whereIn('sale_id', $saleIds)->pluck('id', 'sale_id');

        $clients = Client::where('status', 'active')->get();
        $users = User::whereHas('employee')->get();
        $stages = ['lead', 'prospect', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];

        return view('sales.index', compact('sales', 'clients', 'users', 'stages', 'invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->get();
        $users = User::whereHas('employee')->get();
        $projects = Project::where('status', 'active')->get();
        $stages = ['lead', 'prospect', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        $leadSources = ['website', 'referral', 'cold_call', 'email', 'social_media', 'advertisement', 'event', 'other'];

        return view('sales.create', compact('clients', 'users', 'projects', 'stages', 'leadSources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_service' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'assigned_to' => 'required|exists:users,id',
            'estimated_value' => 'required|numeric|min:0',
            'actual_value' => 'nullable|numeric|min:0',
            'stage' => 'required|in:lead,prospect,proposal,negotiation,closed_won,closed_lost',
            'probability_percentage' => 'required|numeric|min:0|max:100',
            'expected_close_date' => 'nullable|date|after:today',
            'actual_close_date' => 'nullable|date',
            'lead_source' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'competitors' => 'nullable|array',
            'decision_makers' => 'nullable|array',
            'project_id' => 'nullable|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $sale = Sale::create($request->all());

            // If sale is closed won, create accounting entries
            if ($request->stage === 'closed_won') {
                $this->createAccountingEntries($sale);
            }

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'تم إنشاء عملية البيع بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء عملية البيع'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load(['client', 'salesRep']);
        
        // التحقق من وجود فاتورة مسبقة للبيع
        $existingInvoice = Invoice::where('sale_id', $sale->id)->first();
        
        return view('sales.show', compact('sale', 'existingInvoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $clients = Client::where('status', 'active')->get();
        $users = User::whereHas('employee')->get();
        $projects = Project::where('status', 'active')->get();
        $stages = ['lead', 'prospect', 'proposal', 'negotiation', 'closed_won', 'closed_lost'];
        $leadSources = ['website', 'referral', 'cold_call', 'email', 'social_media', 'advertisement', 'event', 'other'];

        return view('sales.edit', compact('sale', 'clients', 'users', 'projects', 'stages', 'leadSources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'deal_name' => 'required|string|max:255',
            'product_service' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'sales_rep_id' => 'required|exists:employees,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'status' => 'required|in:lead,qualified,proposal,sold,lost',
            'probability' => 'required|numeric|min:0|max:100',
            'expected_close_date' => 'required|date',
            'actual_close_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $sale->update($request->all());

        return redirect()->route('sales.index')
            ->with('success', 'تم تحديث عملية البيع بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'تم حذف عملية البيع بنجاح');
    }

    /**
     * Create accounting entries for a closed sale
     */
    private function createAccountingEntries(Sale $sale)
    {
        // Get or create accounts
        $accountsReceivable = Account::firstOrCreate(
            ['code' => '1200'],
            [
                'name' => 'حسابات القبض',
                'type' => 'asset',
                'description' => 'الأموال المستحقة من العملاء'
            ]
        );

        $salesRevenue = Account::firstOrCreate(
            ['code' => '4000'],
            [
                'name' => 'إيرادات المبيعات',
                'type' => 'revenue',
                'description' => 'إيرادات من بيع المنتجات والخدمات'
            ]
        );

        // Create journal entry
        $journalEntry = JournalEntry::create([
            'date' => now(),
            'reference' => 'SALE-' . $sale->id,
            'description' => 'بيع: ' . $sale->product_service . ' للعميل: ' . $sale->client->name,
            'total_amount' => $sale->actual_value ?? $sale->estimated_value,
            'status' => 'posted'
        ]);

        // Create journal entry lines
        $journalEntry->lines()->create([
            'account_id' => $accountsReceivable->id,
            'debit' => $sale->actual_value ?? $sale->estimated_value,
            'credit' => 0,
            'description' => 'حساب العميل: ' . $sale->client->name
        ]);

        $journalEntry->lines()->create([
            'account_id' => $salesRevenue->id,
            'debit' => 0,
            'credit' => $sale->actual_value ?? $sale->estimated_value,
            'description' => 'إيراد من بيع: ' . $sale->product_service
        ]);
    }

    /**
     * Update sale stage and handle accounting
     */
    public function updateStage(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'stage' => 'required|in:lead,prospect,proposal,negotiation,closed_won,closed_lost',
            'actual_value' => 'nullable|numeric|min:0',
            'actual_close_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $oldStage = $sale->stage;
            $sale->update($request->all());

            // If changing to closed won, create accounting entries
            if ($request->stage === 'closed_won' && $oldStage !== 'closed_won') {
                $this->createAccountingEntries($sale);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث مرحلة البيع بنجاح'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث مرحلة البيع'
            ], 500);
        }
    }

    /**
     * Generate invoice for a sale
     */
    public function generateInvoice(Sale $sale)
    {
        if ($sale->stage !== 'closed_won') {
            return redirect()->back()
                ->withErrors(['error' => 'يمكن إنشاء فاتورة فقط للمبيعات المكتملة']);
        }

        // التحقق من وجود فاتورة مسبقة للبيع
        $existingInvoice = Invoice::where('sale_id', $sale->id)->first();
        
        if ($existingInvoice) {
            return redirect()->route('invoices.show', $existingInvoice)
                ->with('info', 'تم إنشاء فاتورة لهذا البيع مسبقاً');
        }

        DB::beginTransaction();
        try {
            $amount = $sale->actual_value ?? $sale->estimated_value;
            $invoice = Invoice::create([
                'client_id' => $sale->client_id,
                'sale_id' => $sale->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'invoice_date' => now(),
                'due_date' => now()->addDays(30),
                'subtotal' => $amount,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $amount,
                'paid_amount' => 0,
                'balance_amount' => $amount,
                'status' => 'draft',
                'notes' => 'فاتورة مقابل بيع: ' . $sale->product_service,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withErrors(['error' => 'حدث خطأ أثناء إنشاء الفاتورة: ' . $e->getMessage()]);
        }
    }

    /**
     * Get sales statistics
     */
    public function getStatistics()
    {
        $totalSales = Sale::count();
        $wonSales = Sale::where('stage', 'closed_won')->count();
        $lostSales = Sale::where('stage', 'closed_lost')->count();
        $activeSales = Sale::whereNotIn('stage', ['closed_won', 'closed_lost'])->count();
        
        $totalRevenue = Sale::where('stage', 'closed_won')->sum('actual_value') ?? 
                       Sale::where('stage', 'closed_won')->sum('estimated_value');
        
        $monthlyRevenue = Sale::where('stage', 'closed_won')
            ->whereMonth('actual_close_date', now()->month)
            ->whereYear('actual_close_date', now()->year)
            ->sum('actual_value') ?? 
            Sale::where('stage', 'closed_won')
                ->whereMonth('actual_close_date', now()->month)
                ->whereYear('actual_close_date', now()->year)
                ->sum('estimated_value');

        return response()->json([
            'total_sales' => $totalSales,
            'won_sales' => $wonSales,
            'lost_sales' => $lostSales,
            'active_sales' => $activeSales,
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'conversion_rate' => $totalSales > 0 ? round(($wonSales / $totalSales) * 100, 2) : 0
        ]);
    }
}
