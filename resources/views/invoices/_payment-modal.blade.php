@php
    $balanceDue = (float) ($invoice->balance_due ?? max(0, ($invoice->total_amount ?? 0) - ($invoice->paid_amount ?? 0)));
    $isFinancial = request()->routeIs('financial-invoices.*');
    $isProject = request()->routeIs('invoices.*');
    $canPay = ($isFinancial || $isProject) && $balanceDue > 0;
    $paymentUrl = $isFinancial
        ? route('financial-invoices.payments.store', $invoice)
        : ($isProject ? route('invoices.payments.store', $invoice) : null);
@endphp

@if($canPay && ($wallets ?? collect())->isNotEmpty())
<div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900">تسجيل دفعة</h3>
            <button type="button" onclick="closePaymentModal()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <form id="invoicePaymentForm" class="p-6 space-y-4">
            @csrf
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm">
                <div class="flex justify-between"><span>المتبقي:</span><strong id="balanceDueLabel">{{ number_format($balanceDue, 2) }} ج.م</strong></div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">المحفظة المستلمة *</label>
                <select name="wallet_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">اختر المحفظة</option>
                    @foreach($wallets as $w)
                        <option value="{{ $w->id }}">{{ $w->name }} ({{ $w->type_name }} — {{ number_format($w->current_balance, 2) }} ج.م)</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">المبلغ *</label>
                <input type="number" name="amount" step="0.01" min="0.01" max="{{ $balanceDue }}" value="{{ $balanceDue }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">تاريخ الدفعة *</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">طريقة الدفع *</label>
                    <select name="payment_method" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="cash">نقدي</option>
                        <option value="bank_transfer">تحويل بنكي</option>
                        <option value="check">شيك</option>
                        <option value="credit_card">بطاقة</option>
                        <option value="online">إلكتروني</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">رقم مرجعي</label>
                <input type="text" name="reference_number" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="رقم التحويل / الإيصال">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">ملاحظات</label>
                <textarea name="description" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="وصف اختياري للدفعة"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closePaymentModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg font-semibold text-gray-700">إلغاء</button>
                <button type="submit" id="paymentSubmitBtn" class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg font-bold hover:bg-green-700">تسجيل الدفعة</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPaymentModal() {
    const m = document.getElementById('paymentModal');
    if (m) { m.classList.remove('hidden'); m.classList.add('flex'); }
}
function closePaymentModal() {
    const m = document.getElementById('paymentModal');
    if (m) { m.classList.add('hidden'); m.classList.remove('flex'); }
}
document.getElementById('invoicePaymentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('paymentSubmitBtn');
    if (btn.disabled) return;
    btn.disabled = true;
    btn.textContent = 'جاري الحفظ…';
    const formData = new FormData(this);
    fetch('{{ $paymentUrl }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData,
    })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
        if (ok && data.success) {
            if (typeof showNotification === 'function') showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 800);
        } else {
            if (typeof showNotification === 'function') showNotification(data.message || 'خطأ', 'error');
            btn.disabled = false;
            btn.textContent = 'تسجيل الدفعة';
        }
    })
    .catch(() => {
        if (typeof showNotification === 'function') showNotification('حدث خطأ في الاتصال', 'error');
        btn.disabled = false;
        btn.textContent = 'تسجيل الدفعة';
    });
});
@if(request()->boolean('pay'))
document.addEventListener('DOMContentLoaded', openPaymentModal);
@endif
</script>
@elseif($canPay && ($wallets ?? collect())->isEmpty())
<p class="no-print text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
    لا توجد محافظ مالية. <a href="{{ route('accounting.wallets.index') }}" class="font-bold underline">أنشئ محفظة</a> لتسجيل الدفعات.
</p>
@endif
