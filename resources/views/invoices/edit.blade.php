@extends('layouts.app')

@section('page-title', 'تعديل الفاتورة')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
@endphp
<div class="w-full max-w-7xl mx-auto font-tajawal">
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">تعديل الفاتورة</h1>
                <p class="text-sm text-gray-600">رقم {{ $invoice->invoice_number }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('financial-invoices.show', $invoice) }}" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 text-sm font-semibold inline-flex items-center">
                    العودة للعرض
                </a>
                @canany(['delete-finance', 'edit-finance'])
                <form method="POST" action="{{ route('financial-invoices.destroy', $invoice) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفاتورة؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-5 py-2.5 rounded-lg hover:bg-red-700 text-sm font-semibold">حذف</button>
                </form>
                @endcanany
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    @if((float) $invoice->paid_amount > 0)
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
        هذه الفاتورة عليها دفعات مسجّلة ({{ number_format($invoice->paid_amount, 2) }} ج.م). لا يمكن أن يكون الإجمالي الجديد أقل من المدفوع.
    </div>
    @endif

    <form id="invoiceForm" method="POST" action="{{ route('financial-invoices.update', $invoice) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">المعلومات الأساسية</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم الفاتورة</label>
                    <input type="text" value="{{ $invoice->invoice_number }}" readonly class="w-full px-3 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">العميل <span class="text-red-500">*</span></label>
                    <select name="client_id" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg">
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $invoice->client_id) == $client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المشروع</label>
                    <select name="project_id" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg">
                        <option value="">بدون مشروع</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id', $invoice->project_id) == $project->id)>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الإصدار <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ الاستحقاق <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date', optional($invoice->due_date)->format('Y-m-d')) }}" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة</label>
                    <select name="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg">
                        @foreach(['draft' => 'مسودة', 'sent' => 'مرسل', 'viewed' => 'تم المشاهدة', 'partial' => 'مدفوع جزئياً', 'overdue' => 'متأخر', 'cancelled' => 'ملغي'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('status', $invoice->status) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">بنود الخدمات</h3>
                <button type="button" onclick="addInvoiceItem()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-bold">إضافة خدمة</button>
            </div>
            <div id="invoiceItems" class="space-y-4"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">ملاحظات</h3>
                <textarea name="notes" rows="8" class="w-full px-4 py-3 border border-gray-300 rounded-xl">{{ old('notes', $invoice->notes) }}</textarea>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl shadow-md border border-blue-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">الإجماليات</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">المجموع الفرعي:</label>
                        <span id="subtotalDisplay" class="text-lg font-bold text-gray-900">0.00 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">نسبة الضريبة (%):</label>
                        <input type="number" name="tax_rate" value="{{ old('tax_rate', $invoice->tax_rate ?? 0) }}" min="0" max="100" step="0.01" onchange="calculateTotals()" oninput="calculateTotals()" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-right">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">قيمة الضريبة:</label>
                        <span id="taxDisplay" class="text-lg font-bold text-gray-900">0.00 ج.م</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">الخصم:</label>
                        <input type="number" name="discount_amount" value="{{ old('discount_amount', $invoice->discount_amount ?? 0) }}" min="0" step="0.01" onchange="calculateTotals()" oninput="calculateTotals()" class="w-32 px-3 py-2 border border-gray-300 rounded-lg text-right">
                    </div>
                    <div class="pt-4 border-t-2 border-blue-300 flex items-center justify-between">
                        <label class="text-lg font-bold text-gray-900">الإجمالي النهائي:</label>
                        <span id="totalDisplay" class="text-2xl font-bold text-blue-900">0.00 ج.م</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 pt-2">
            <a href="{{ route('financial-invoices.show', $invoice) }}" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50">إلغاء</a>
            <button type="submit" class="px-6 py-3 text-white rounded-xl font-medium" style="background: {{ $themeColor }};">حفظ التعديلات</button>
        </div>
    </form>
</div>

@php
    if (old('items')) {
        $seedItems = collect(old('items'))->values()->map(function ($i) {
            return [
                'description' => $i['description'] ?? '',
                'quantity' => $i['quantity'] ?? 1,
                'unit_price' => $i['unit_price'] ?? 0,
            ];
        })->values();
    } else {
        $seedItems = $invoice->items->map(function ($i) {
            return [
                'description' => $i->description ?: $i->item_name,
                'quantity' => (float) $i->quantity,
                'unit_price' => (float) $i->unit_price,
            ];
        })->values();
    }
@endphp
<script>
let itemCounter = 0;
const existingItems = @json($seedItems);

document.addEventListener('DOMContentLoaded', function () {
    if (existingItems.length) {
        existingItems.forEach(item => addInvoiceItem(item));
    } else {
        addInvoiceItem();
    }
    calculateTotals();
});

function addInvoiceItem(data = null) {
    itemCounter++;
    const description = data?.description ?? '';
    const price = data?.unit_price ?? 0;
    const qty = data?.quantity ?? 1;
    document.getElementById('invoiceItems').insertAdjacentHTML('beforeend', `
        <div class="invoice-item border border-gray-200 rounded-lg p-4 bg-gray-50" data-item="${itemCounter}">
            <div class="flex items-start gap-4">
                <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">وصف الخدمة <span class="text-red-500">*</span></label>
                        <input type="text" name="items[${itemCounter}][description]" value="${String(description).replace(/"/g, '&quot;')}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="وصف البند">
                    </div>
                    <input type="hidden" name="items[${itemCounter}][quantity]" value="${qty}" class="item-quantity">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">المبلغ (ج.م) <span class="text-red-500">*</span></label>
                        <input type="number" name="items[${itemCounter}][unit_price]" value="${price}" min="0" step="0.01" required
                               class="item-price w-full px-3 py-2 border border-gray-300 rounded-lg"
                               onchange="calculateTotals()" oninput="calculateTotals()">
                    </div>
                </div>
                <button type="button" onclick="removeInvoiceItem(${itemCounter})" class="mt-8 p-2 text-red-600 hover:bg-red-50 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    `);
    calculateTotals();
}

function removeInvoiceItem(itemId) {
    if (document.querySelectorAll('.invoice-item').length <= 1) {
        alert('يجب أن تحتوي الفاتورة على بند واحد على الأقل');
        return;
    }
    document.querySelector(`[data-item="${itemId}"]`)?.remove();
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.invoice-item').forEach(item => {
        subtotal += parseFloat(item.querySelector('.item-price').value) || 0;
    });
    const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
    const discount = parseFloat(document.querySelector('input[name="discount_amount"]').value) || 0;
    const tax = (subtotal * taxRate) / 100;
    const total = subtotal + tax - discount;
    document.getElementById('subtotalDisplay').textContent = subtotal.toFixed(2) + ' ج.م';
    document.getElementById('taxDisplay').textContent = tax.toFixed(2) + ' ج.م';
    document.getElementById('totalDisplay').textContent = total.toFixed(2) + ' ج.م';
}
</script>
@endsection
