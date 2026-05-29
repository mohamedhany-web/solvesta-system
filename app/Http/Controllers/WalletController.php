<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $query = Wallet::query()->withCount('transactions')->orderBy('name');

        if (! $request->boolean('show_inactive')) {
            $query->where('is_active', true);
        }

        $wallets = $query->get();

        $activeWallets = $wallets->where('is_active', true);
        $totalBalance = (float) $activeWallets->sum('current_balance');

        $totalIn = (float) WalletTransaction::where('direction', 'in')->sum('amount');
        $totalOut = (float) WalletTransaction::where('direction', 'out')->sum('amount');

        $recentTransactions = WalletTransaction::with(['wallet', 'invoice', 'creator'])
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->limit(30)
            ->get();

        return view('accounting.wallets.index', compact(
            'wallets',
            'totalBalance',
            'totalIn',
            'totalOut',
            'recentTransactions'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,transfer,other',
            'currency' => 'nullable|string|size:3',
            'opening_balance' => 'nullable|numeric|min:0',
            'account_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $opening = round((float) ($validated['opening_balance'] ?? 0), 2);

        $wallet = Wallet::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'currency' => $validated['currency'] ?? 'EGP',
            'opening_balance' => $opening,
            'current_balance' => $opening,
            'account_number' => $validated['account_number'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
        ]);

        if ($opening > 0) {
            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'direction' => 'in',
                'amount' => $opening,
                'balance_after' => $opening,
                'reference' => 'OPENING',
                'category' => 'opening',
                'description' => 'رصيد افتتاحي',
                'transaction_date' => now()->toDateString(),
                'created_by' => auth()->id(),
            ]);
        }

        return redirect()->route('accounting.wallets.show', $wallet)
            ->with('success', 'تم إنشاء المحفظة بنجاح');
    }

    public function edit(Wallet $wallet)
    {
        return view('accounting.wallets.edit', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:cash,bank,transfer,other',
            'currency' => 'nullable|string|size:3',
            'account_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $wallet->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'currency' => $validated['currency'] ?? 'EGP',
            'account_number' => $validated['account_number'] ?? null,
            'bank_name' => $validated['bank_name'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('accounting.wallets.show', $wallet)
            ->with('success', 'تم تحديث المحفظة بنجاح');
    }

    public function destroy(Wallet $wallet)
    {
        $hasMovements = $wallet->transactions()->exists() || $wallet->payments()->exists();

        if ($hasMovements) {
            $wallet->update(['is_active' => false]);

            return redirect()->route('accounting.wallets.index')
                ->with('success', 'تم تعطيل المحفظة لوجود حركات مالية مرتبطة بها (لا يمكن حذفها نهائياً).');
        }

        $wallet->delete();

        return redirect()->route('accounting.wallets.index')
            ->with('success', 'تم حذف المحفظة بنجاح');
    }

    public function show(Wallet $wallet)
    {
        $wallet->loadCount(['transactions', 'payments']);

        $transactions = $wallet->transactions()
            ->with(['invoice', 'payment', 'creator'])
            ->paginate(25);

        $stats = [
            'in' => (float) $wallet->transactions()->where('direction', 'in')->sum('amount'),
            'out' => (float) $wallet->transactions()->where('direction', 'out')->sum('amount'),
        ];

        return view('accounting.wallets.show', compact('wallet', 'transactions', 'stats'));
    }

    public function storeTransaction(Request $request, Wallet $wallet, WalletService $walletService)
    {
        if (! $wallet->is_active) {
            return back()->with('error', 'لا يمكن تسجيل حركة على محفظة معطّلة.');
        }

        $validated = $request->validate([
            'direction' => 'required|in:in,out',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string|max:500',
            'reference' => 'nullable|string|max:100',
        ]);

        try {
            $walletService->recordManualTransaction($wallet, $validated);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return back()->with('success', 'تم تسجيل الحركة بنجاح');
    }
}
