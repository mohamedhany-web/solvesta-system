<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use Carbon\Carbon;
use App\Services\Accounting\AccountingPostingService;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // إحصائيات الفواتير
        $totalInvoices = Invoice::count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $pendingInvoices = Invoice::whereIn('status', ['sent', 'viewed', 'draft'])->count();
        $overdueInvoices = Invoice::overdue()->count();
        
        // إجمالي الإيرادات
        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');
        $monthlyRevenue = Invoice::currentMonth()->where('status', 'paid')->sum('total_amount');
        $pendingAmount = Invoice::whereIn('status', ['sent', 'viewed'])->sum('balance_amount');
        
        // الفواتير
        $invoices = Invoice::with(['client', 'project', 'contract'])
            ->when(request('contract_id'), function ($query) {
                $query->where('contract_id', request('contract_id'));
            })
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $projects = Project::whereIn('status', ['in_progress', 'completed', 'active'])->orderBy('status', 'desc')->orderBy('name')->get();
        $invoiceNumber = Invoice::generateInvoiceNumber();
        
        return view('invoices.create', compact('clients', 'projects', 'invoiceNumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
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
            
            // حساب الإجماليات
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            
            // التأكد من أن subtotal أكبر من 0
            if ($subtotal <= 0) {
                return back()->withErrors(['items' => 'يجب أن يكون إجمالي العناصر أكبر من صفر'])->withInput();
            }
            
            $taxRate = $validated['tax_rate'] ?? 0;
            $taxAmount = ($subtotal * $taxRate) / 100;
            $discountAmount = $validated['discount_amount'] ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;
            
            // التأكد من أن total_amount أكبر من 0
            if ($totalAmount <= 0) {
                return back()->withErrors(['items' => 'يجب أن يكون المبلغ الإجمالي أكبر من صفر'])->withInput();
            }
            
            // التأكد من وجود المستخدم
            $userId = auth()->id();
            if (!$userId) {
                return back()->withErrors(['error' => 'يجب تسجيل الدخول أولاً'])->withInput();
            }
            
            // إنشاء رقم الفاتورة مع التأكد من عدم التكرار
            $invoiceNumber = Invoice::generateInvoiceNumber();
            $maxAttempts = 10;
            $attempt = 0;
            while (Invoice::where('invoice_number', $invoiceNumber)->exists() && $attempt < $maxAttempts) {
                $invoiceNumber = Invoice::generateInvoiceNumber();
                $attempt++;
            }
            
            if ($attempt >= $maxAttempts) {
                return back()->withErrors(['error' => 'فشل في إنشاء رقم فاتورة فريد. يرجى المحاولة مرة أخرى'])->withInput();
            }
            
            // إنشاء البيانات (استخدام float مباشرة بدلاً من number_format)
            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'client_id' => $validated['client_id'],
                'project_id' => $validated['project_id'] ?? null,
                'invoice_date' => $validated['invoice_date'],
                'issue_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => (float) $subtotal,
                'amount' => (float) $subtotal,
                'tax_rate' => (float) $taxRate,
                'tax_amount' => (float) $taxAmount,
                'discount_amount' => (float) $discountAmount,
                'total_amount' => (float) $totalAmount,
                'paid_amount' => 0,
                'balance_amount' => (float) $totalAmount,
                'status' => 'draft',
                'items' => $validated['items'], // سيتم تحويله تلقائياً إلى JSON من خلال cast
                'notes' => $validated['notes'] ?? null,
                'created_by' => $userId,
            ];
            
            // Log البيانات قبل الحفظ للمساعدة في debugging
            \Log::info('Creating invoice', $invoiceData);
            
            // استخدام DB transaction لضمان الحفظ بشكل صحيح
            DB::beginTransaction();
            
            try {
                $invoice = Invoice::create($invoiceData);
                
                DB::commit();
                
                return redirect()->route('invoices.show', $invoice->id)
                    ->with('success', 'تم إنشاء الفاتورة بنجاح');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e; // إعادة رمي الخطأ للتعامل معه في catch العام
            }
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error creating invoice: ' . $e->getMessage());
            \Log::error('SQL: ' . $e->getSql());
            \Log::error('Bindings: ' . json_encode($e->getBindings()));
            
            return back()->withErrors(['error' => 'حدث خطأ في قاعدة البيانات: ' . $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            \Log::error('Error creating invoice: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->withErrors(['error' => 'حدث خطأ غير متوقع: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'contract', 'sale']);
        
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $projects = Project::whereIn('status', ['in_progress', 'completed', 'active'])->orderBy('status', 'desc')->orderBy('name')->get();
        
        return view('invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'sometimes|in:draft,sent,viewed,paid,overdue,cancelled',
            'payment_method' => 'nullable|string',
            'payment_date' => 'nullable|date',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);
        
        $invoice->update($validated);
        
        // تحديث الرصيد المتبقي
        if (isset($validated['paid_amount'])) {
            $invoice->update([
                'balance_amount' => $invoice->total_amount - $validated['paid_amount']
            ]);
        }
        
        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Invoice $invoice)
    {
        // لا يمكن حذف الفواتير المدفوعة
        if ($invoice->status === 'paid') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف الفواتير المدفوعة'
                ], 400);
            }
            return redirect()->back()->with('error', 'لا يمكن حذف الفواتير المدفوعة');
        }
        
        try {
            $invoice->delete();
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الفاتورة بنجاح'
                ]);
            }
            
            return redirect()->route('invoices.index')->with('success', 'تم حذف الفاتورة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Error deleting invoice: ' . $e->getMessage());
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف الفاتورة: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الفاتورة');
        }
    }
    
    /**
     * Mark invoice as sent
     */
    public function markAsSent(Invoice $invoice)
    {
        $invoice->update(['status' => 'sent']);
        // ترحيل قيد الاعتراف بالإيراد
        try {
            app(AccountingPostingService::class)->postInvoiceSent($invoice);
        } catch (\Throwable $e) {
            // يمكن لاحقاً تسجيل الخطأ في سجل النظام
        }
        
        return redirect()->back()->with('success', 'تم تحديث حالة الفاتورة إلى مرسل');
    }
    
    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Invoice $invoice)
    {
        try {
            $invoice->update([
                'status' => 'paid',
                'paid_amount' => $invoice->total_amount,
                'balance_amount' => 0,
                'payment_date' => now()
            ]);
            
            // ترحيل قيد التحصيل
            try {
                app(AccountingPostingService::class)->postInvoicePaid($invoice, (float) $invoice->total_amount);
            } catch (\Throwable $e) {
                // يمكن لاحقاً تسجيل الخطأ في سجل النظام
            }
            
            // إرجاع JSON إذا كان الطلب AJAX
            if (request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث حالة الفاتورة إلى مدفوع'
                ]);
            }
            
            return redirect()->back()->with('success', 'تم تحديث حالة الفاتورة إلى مدفوع');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }
}
