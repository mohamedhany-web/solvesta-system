@extends('layouts.app')

@section('page-title', $wallet->name)

@section('content')
<div class="w-full">
    {{-- ترويسة --}}
    <div class="mb-6">
        <a href="{{ route('accounting.wallets.index') }}" class="text-sm text-blue-600 font-semibold hover:underline inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            المحافظ والمعاملات
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">{{ session('error') }}</div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-l from-slate-800 to-slate-900 text-white px-6 py-6 sm:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="text-xs font-bold uppercase tracking-wide bg-white/15 px-2.5 py-1 rounded-lg">{{ $wallet->type_name }}</span>
                        @if($wallet->is_active)
                            <span class="text-xs font-bold bg-green-500/90 px-2.5 py-1 rounded-lg">نشطة</span>
                        @else
                            <span class="text-xs font-bold bg-gray-500 px-2.5 py-1 rounded-lg">معطّلة</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $wallet->name }}</h1>
                    <p class="text-slate-300 text-sm mt-1">{{ $wallet->currency }}
                        @if($wallet->bank_name) · {{ $wallet->bank_name }}@endif
                        @if($wallet->account_number) · {{ $wallet->account_number }}@endif
                    </p>
                </div>
                <div class="text-right lg:text-left">
                    <p class="text-slate-400 text-sm mb-1">الرصيد الحالي</p>
                    <p class="text-3xl sm:text-4xl font-bold text-white">{{ number_format($wallet->current_balance, 2) }} <span class="text-lg font-semibold">ج.م</span></p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 mt-5 pt-5 border-t border-white/20">
                @can('edit-finance')
                <a href="{{ route('accounting.wallets.edit', $wallet) }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-bold transition">تعديل المحفظة</a>
                @endcan
                @can('delete-finance')
                <form method="POST" action="{{ route('accounting.wallets.destroy', $wallet) }}" class="inline"
                      onsubmit="return confirm('{{ $wallet->transactions_count > 0 ? 'سيتم تعطيل المحفظة. متابعة؟' : 'حذف المحفظة نهائياً؟' }}')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600/90 hover:bg-red-600 text-white rounded-lg text-sm font-bold transition">
                        {{ $wallet->transactions_count > 0 ? 'تعطيل' : 'حذف' }}
                    </button>
                </form>
                @endcan
            </div>
        </div>
        @if($wallet->notes)
        <div class="px-6 py-3 bg-slate-50 border-t text-sm text-gray-600">
            <span class="font-semibold text-gray-800">ملاحظات:</span> {{ $wallet->notes }}
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <p class="text-sm text-gray-500 mb-1">الرصيد الحالي</p>
            <p class="text-2xl font-bold text-blue-700">{{ number_format($wallet->current_balance, 2) }} ج.م</p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="text-sm text-green-700 mb-1">إجمالي الوارد</p>
            <p class="text-2xl font-bold text-green-800">{{ number_format($stats['in'], 2) }} ج.م</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-5">
            <p class="text-sm text-red-700 mb-1">إجمالي الصادر</p>
            <p class="text-2xl font-bold text-red-800">{{ number_format($stats['out'], 2) }} ج.م</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        @can('edit-finance')
        <div class="xl:col-span-1">
            @if(!$wallet->is_active)
            <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-xl text-sm">
                المحفظة معطّلة. <a href="{{ route('accounting.wallets.edit', $wallet) }}" class="font-bold underline">فعّلها</a> لتسجيل حركات.
            </div>
            @endif
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 sticky top-4">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">+</span>
                    حركة يدوية
                </h2>
                <form method="POST" action="{{ route('accounting.wallets.transactions.store', $wallet) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">النوع</label>
                        <select name="direction" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="in">إيداع</option>
                            <option value="out">سحب</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">المبلغ (ج.م)</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">التاريخ</label>
                        <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">مرجع</label>
                        <input type="text" name="reference" placeholder="رقم إيصال / تحويل"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">الوصف</label>
                        <input type="text" name="description" placeholder="وصف الحركة"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2.5">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition disabled:opacity-50"
                            @disabled(!$wallet->is_active)>تسجيل الحركة</button>
                </form>
            </div>
        </div>
        @endcan

        <div class="{{ auth()->user()?->can('edit-finance') ? 'xl:col-span-2' : 'xl:col-span-3' }}">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">سجل المعاملات</h2>
                    <span class="text-sm text-gray-500">{{ $transactions->total() }} حركة</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600">
                                <th class="px-4 py-3 text-right font-semibold">التاريخ</th>
                                <th class="px-4 py-3 text-right font-semibold">النوع</th>
                                <th class="px-4 py-3 text-right font-semibold">المبلغ</th>
                                <th class="px-4 py-3 text-right font-semibold">الرصيد بعد</th>
                                <th class="px-4 py-3 text-right font-semibold">الفاتورة</th>
                                <th class="px-4 py-3 text-right font-semibold">الوصف</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $t)
                            <tr class="hover:bg-blue-50/50 transition">
                                <td class="px-4 py-3 whitespace-nowrap">{{ $t->transaction_date->format('Y/m/d') }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-bold {{ $t->direction === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $t->direction_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold {{ $t->direction === 'in' ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $t->direction === 'in' ? '+' : '−' }}{{ number_format($t->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-800">{{ number_format($t->balance_after, 2) }}</td>
                                <td class="px-4 py-3">
                                    @if($t->invoice)
                                        <a href="{{ route('financial-invoices.show', $t->invoice) }}" class="text-blue-600 font-semibold hover:underline">{{ $t->invoice->invoice_number }}</a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600 max-w-[200px] truncate" title="{{ $t->description }}">{{ $t->description ?: '—' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center text-gray-500">
                                    <p class="font-medium">لا توجد معاملات بعد</p>
                                    <p class="text-sm mt-1">سجّل إيداعاً أو سحباً من النموذج الجانبي</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($transactions->hasPages())
                <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">{{ $transactions->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
