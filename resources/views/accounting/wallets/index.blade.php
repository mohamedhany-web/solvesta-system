@extends('layouts.app')

@section('page-title', 'المحافظ والمعاملات المالية')

@section('content')
<div class="w-full">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">المحافظ والمعاملات المالية</h1>
            <p class="text-gray-600">إدارة الخزائن والحسابات البنكية ومحافظ التحويل</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('accounting.wallets.index', request()->boolean('show_inactive') ? [] : ['show_inactive' => 1]) }}"
               class="px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">
                {{ request()->boolean('show_inactive') ? 'إخفاء المعطّلة' : 'عرض المعطّلة' }}
            </a>
            @can('edit-finance')
            <button type="button" onclick="openWalletModal()" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700">
                + محفظة جديدة
            </button>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white border rounded-xl p-6 shadow-sm">
            <p class="text-sm text-gray-600">إجمالي الرصيد (المحافظ النشطة)</p>
            <p class="text-3xl font-bold text-blue-700">{{ number_format($totalBalance, 2) }} <span class="text-sm">ج.م</span></p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
            <p class="text-sm text-green-700">إجمالي الوارد</p>
            <p class="text-3xl font-bold text-green-800">{{ number_format($totalIn, 2) }} ج.م</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <p class="text-sm text-red-700">إجمالي الصادر</p>
            <p class="text-3xl font-bold text-red-800">{{ number_format($totalOut, 2) }} ج.م</p>
        </div>
    </div>

  <div class="bg-white border rounded-xl overflow-hidden shadow-sm mb-8">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">قائمة المحافظ ({{ $wallets->count() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right">الاسم</th>
                        <th class="px-4 py-3 text-right">النوع</th>
                        <th class="px-4 py-3 text-right">الرصيد</th>
                        <th class="px-4 py-3 text-right">البنك / الحساب</th>
                        <th class="px-4 py-3 text-right">الحركات</th>
                        <th class="px-4 py-3 text-right">الحالة</th>
                        <th class="px-4 py-3 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($wallets as $wallet)
                    <tr class="hover:bg-gray-50 {{ !$wallet->is_active ? 'opacity-60 bg-gray-50' : '' }}">
                        <td class="px-4 py-3 font-bold text-gray-900">{{ $wallet->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $wallet->type_name }}</td>
                        <td class="px-4 py-3 font-bold text-blue-700">{{ number_format($wallet->current_balance, 2) }} {{ $wallet->currency }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">
                            @if($wallet->bank_name){{ $wallet->bank_name }}@endif
                            @if($wallet->account_number)<br>{{ $wallet->account_number }}@endif
                            @if(!$wallet->bank_name && !$wallet->account_number)—@endif
                        </td>
                        <td class="px-4 py-3">{{ $wallet->transactions_count }}</td>
                        <td class="px-4 py-3">
                            @if($wallet->is_active)
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800">نشطة</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-xs font-bold bg-gray-200 text-gray-700">معطّلة</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                <a href="{{ route('accounting.wallets.show', $wallet) }}" class="px-2 py-1 text-xs font-semibold text-blue-700 bg-blue-50 rounded hover:bg-blue-100">عرض</a>
                                @can('edit-finance')
                                <a href="{{ route('accounting.wallets.edit', $wallet) }}" class="px-2 py-1 text-xs font-semibold text-amber-800 bg-amber-50 rounded hover:bg-amber-100">تعديل</a>
                                @endcan
                                @can('delete-finance')
                                <form method="POST" action="{{ route('accounting.wallets.destroy', $wallet) }}" class="inline"
                                      onsubmit="return confirm('{{ $wallet->transactions_count > 0 ? 'سيتم تعطيل المحفظة لوجود حركات. متابعة؟' : 'حذف هذه المحفظة نهائياً؟' }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded hover:bg-red-100">
                                        {{ $wallet->transactions_count > 0 ? 'تعطيل' : 'حذف' }}
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                            لا توجد محافظ.
                            @can('edit-finance')
                            <button type="button" onclick="openWalletModal()" class="text-blue-600 font-bold hover:underline">أنشئ أول محفظة</button>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white border rounded-xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b">
            <h2 class="text-lg font-bold">آخر المعاملات (كل المحافظ)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right">التاريخ</th>
                        <th class="px-4 py-3 text-right">المحفظة</th>
                        <th class="px-4 py-3 text-right">النوع</th>
                        <th class="px-4 py-3 text-right">المبلغ</th>
                        <th class="px-4 py-3 text-right">الرصيد بعد</th>
                        <th class="px-4 py-3 text-right">الوصف</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($recentTransactions as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $t->transaction_date->format('Y/m/d') }}</td>
                        <td class="px-4 py-3 font-semibold">{{ $t->wallet?->name }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-xs font-bold {{ $t->direction === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $t->direction_label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-bold {{ $t->direction === 'in' ? 'text-green-700' : 'text-red-700' }}">
                            {{ $t->direction === 'in' ? '+' : '-' }}{{ number_format($t->amount, 2) }}
                        </td>
                        <td class="px-4 py-3">{{ number_format($t->balance_after, 2) }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ Str::limit($t->description, 40) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-10 text-center text-gray-500">لا توجد معاملات بعد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@can('edit-finance')
<div id="walletModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-lg font-bold">محفظة جديدة</h3>
            <button type="button" onclick="closeWalletModal()" class="text-2xl text-gray-500 leading-none">&times;</button>
        </div>
        <form method="POST" action="{{ route('accounting.wallets.store') }}" class="p-6 space-y-4">
            @csrf
            @include('accounting.wallets._form-fields')
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700">إنشاء المحفظة</button>
        </form>
    </div>
</div>
<script>
function openWalletModal() {
    const m = document.getElementById('walletModal');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}
function closeWalletModal() {
    const m = document.getElementById('walletModal');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}
document.getElementById('walletModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeWalletModal();
});
@if($errors->any() && !request()->routeIs('accounting.wallets.edit'))
document.addEventListener('DOMContentLoaded', openWalletModal);
@endif
</script>
@endcan
@endsection
