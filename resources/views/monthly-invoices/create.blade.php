@extends('layouts.app')

@section('page-title', 'إصدار فاتورة شهرية')

@section('content')
<div class="w-full max-w-5xl mx-auto">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">إصدار فاتورة شهرية</h1>
            <p class="text-gray-600">من اشتراك خدمة ما بعد البيع + بنود اختيارية من طلبات الموقع</p>
        </div>
        <a href="{{ route('invoices.index') }}" class="bg-gray-600 text-white px-5 py-2.5 rounded-lg hover:bg-gray-700 text-sm font-semibold">العودة</a>
    </div>

    @if(session('error'))
        <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('invoices.store') }}" id="monthlyInvoiceForm" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-5">
            <h2 class="text-lg font-bold text-gray-900">بيانات الاشتراك</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">العميل *</label>
                    <select id="client_id" class="w-full border-gray-300 rounded-xl">
                        <option value="">اختر العميل</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" @selected((string)$selectedClientId === (string)$client->id)>{{ $client->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">خدمة ما بعد البيع *</label>
                    <select name="client_service_id" id="client_service_id" required class="w-full border-gray-300 rounded-xl">
                        <option value="">اختر الخدمة</option>
                        @foreach($services as $service)
                            <option
                                value="{{ $service->id }}"
                                data-client="{{ $service->client_id }}"
                                data-amount="{{ $service->monthly_amount }}"
                                data-title="{{ $service->title }}"
                                @selected((string)$selectedServiceId === (string)$service->id)
                            >
                                {{ $service->title }} — {{ optional($service->client)->name }} ({{ number_format($service->monthly_amount, 2) }} ج.م)
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">تاريخ الفاتورة</label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', now()->toDateString()) }}" class="w-full border-gray-300 rounded-xl">
                </div>
                <div class="flex items-end">
                    <label class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700">
                        <input type="checkbox" name="advance_billing" value="1" checked class="rounded border-gray-300 text-blue-600">
                        تحديث تاريخ الفوترة التالي للخدمة
                    </label>
                </div>
            </div>

            <div class="rounded-xl bg-slate-50 border border-slate-200 p-4">
                <p class="text-sm text-slate-600">بند الاشتراك الشهري</p>
                <p class="text-xl font-bold text-slate-900" id="subscriptionLabel">—</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">ملاحظات</label>
                <textarea name="notes" rows="2" class="w-full border-gray-300 rounded-xl" placeholder="اختياري">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">متطلبات من الموقع</h2>
                    <p class="text-sm text-gray-500">اختر ميزات أو مشاكل لإضافتها كبنود إضافية بأسعار مخصّصة</p>
                </div>
                <button type="button" id="reloadBillables" class="text-sm font-semibold text-blue-600 hover:underline">تحديث القائمة</button>
            </div>

            <div id="billablesEmpty" class="text-sm text-gray-500 py-6 text-center border border-dashed rounded-xl">
                اختر عميلاً أولاً لعرض المتطلبات غير المفوترة.
            </div>

            <div id="billablesWrap" class="hidden space-y-6">
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">ميزات / تحسينات</h3>
                    <div id="featuresList" class="space-y-2"></div>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">مشاكل الموقع</h3>
                    <div id="issuesList" class="space-y-2"></div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">الإجمالي التقديري قبل الضريبة</p>
                <p class="text-2xl font-bold text-gray-900"><span id="estimateTotal">0.00</span> ج.م</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('invoices.index') }}" class="px-6 py-3 border rounded-xl text-gray-700 font-medium">إلغاء</a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">إصدار الفاتورة</button>
            </div>
        </div>
    </form>
</div>

<script>
(function () {
    const servicesSelect = document.getElementById('client_service_id');
    const clientSelect = document.getElementById('client_id');
    const subscriptionLabel = document.getElementById('subscriptionLabel');
    const estimateTotal = document.getElementById('estimateTotal');
    const featuresList = document.getElementById('featuresList');
    const issuesList = document.getElementById('issuesList');
    const billablesWrap = document.getElementById('billablesWrap');
    const billablesEmpty = document.getElementById('billablesEmpty');
    const billablesUrl = @json(route('invoices.billables'));

    function selectedServiceAmount() {
        const opt = servicesSelect.options[servicesSelect.selectedIndex];
        return opt && opt.value ? parseFloat(opt.dataset.amount || '0') : 0;
    }

    function selectedServiceTitle() {
        const opt = servicesSelect.options[servicesSelect.selectedIndex];
        return opt && opt.value ? (opt.dataset.title || opt.textContent) : '—';
    }

    function filterServicesByClient() {
        const clientId = clientSelect.value;
        Array.from(servicesSelect.options).forEach((opt, idx) => {
            if (idx === 0) return;
            const match = !clientId || String(opt.dataset.client) === String(clientId);
            opt.hidden = !match;
            if (!match && opt.selected) servicesSelect.value = '';
        });
        updateSubscription();
    }

    function updateSubscription() {
        const amount = selectedServiceAmount();
        subscriptionLabel.textContent = servicesSelect.value
            ? (selectedServiceTitle() + ' — ' + amount.toFixed(2) + ' ج.م')
            : '—';
        recalc();
    }

    function selectedExtrasTotal() {
        let total = 0;
        document.querySelectorAll('.billable-row input[type="checkbox"]:checked').forEach((cb) => {
            const amountInput = cb.closest('.billable-row').querySelector('.billable-amount');
            total += parseFloat(amountInput.value || '0') || 0;
        });
        return total;
    }

    function recalc() {
        estimateTotal.textContent = (selectedServiceAmount() + selectedExtrasTotal()).toFixed(2);
    }

    function rowHtml(item, type) {
        const label = type === 'feature'
            ? ((item.type_label || 'ميزة') + ' · ' + item.reference_code + ' — ' + item.title)
            : (item.reference_code + ' — ' + item.title);
        return `
            <div class="billable-row flex flex-col sm:flex-row sm:items-center gap-3 p-3 rounded-xl border border-gray-200 bg-gray-50">
                <label class="flex items-start gap-2 flex-1 text-sm">
                    <input type="checkbox" class="mt-1 rounded border-gray-300 text-blue-600 billable-check"
                           data-type="${type}" data-id="${item.id}">
                    <span>
                        <span class="font-semibold text-gray-900">${label}</span>
                        <span class="block text-xs text-gray-500">${item.status || ''}</span>
                    </span>
                </label>
                <div class="flex items-center gap-2">
                    <input type="number" step="0.01" min="0" value="0" class="billable-amount w-32 border-gray-300 rounded-lg text-sm" disabled>
                    <span class="text-xs text-gray-500">ج.م</span>
                </div>
                <input type="hidden" class="billable-desc" value="${label.replace(/"/g, '&quot;')}">
            </div>
        `;
    }

    async function loadBillables() {
        const clientId = clientSelect.value;
        featuresList.innerHTML = '';
        issuesList.innerHTML = '';
        if (!clientId) {
            billablesWrap.classList.add('hidden');
            billablesEmpty.classList.remove('hidden');
            billablesEmpty.textContent = 'اختر عميلاً أولاً لعرض المتطلبات غير المفوترة.';
            recalc();
            return;
        }

        billablesEmpty.textContent = 'جاري التحميل...';
        billablesEmpty.classList.remove('hidden');
        billablesWrap.classList.add('hidden');

        const res = await fetch(billablesUrl + '?client_id=' + encodeURIComponent(clientId), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        const features = data.features || [];
        const issues = data.issues || [];

        if (!features.length && !issues.length) {
            billablesEmpty.textContent = 'لا توجد ميزات أو مشاكل غير مفوترة لهذا العميل.';
            recalc();
            return;
        }

        featuresList.innerHTML = features.map((f) => rowHtml(f, 'feature')).join('') || '<p class="text-sm text-gray-500">لا توجد ميزات.</p>';
        issuesList.innerHTML = issues.map((i) => rowHtml(i, 'issue')).join('') || '<p class="text-sm text-gray-500">لا توجد مشاكل.</p>';
        billablesEmpty.classList.add('hidden');
        billablesWrap.classList.remove('hidden');
        bindBillableRows();
        recalc();
    }

    function bindBillableRows() {
        document.querySelectorAll('.billable-row').forEach((row) => {
            const check = row.querySelector('.billable-check');
            const amount = row.querySelector('.billable-amount');
            check.addEventListener('change', () => {
                amount.disabled = !check.checked;
                if (!check.checked) amount.value = '0';
                recalc();
            });
            amount.addEventListener('input', recalc);
        });
    }

    document.getElementById('monthlyInvoiceForm').addEventListener('submit', function () {
        document.querySelectorAll('input[name^="extras"]').forEach((el) => el.remove());
        let idx = 0;
        document.querySelectorAll('.billable-row').forEach((row) => {
            const check = row.querySelector('.billable-check');
            if (!check.checked) return;
            const amount = parseFloat(row.querySelector('.billable-amount').value || '0') || 0;
            const desc = row.querySelector('.billable-desc').value;
            const type = check.dataset.type;
            const id = check.dataset.id;
            const fields = { type, id, amount, description: desc };
            Object.keys(fields).forEach((key) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `extras[${idx}][${key}]`;
                input.value = fields[key];
                this.appendChild(input);
            });
            idx++;
        });
    });

    clientSelect.addEventListener('change', () => {
        filterServicesByClient();
        loadBillables();
    });
    servicesSelect.addEventListener('change', () => {
        const opt = servicesSelect.options[servicesSelect.selectedIndex];
        if (opt && opt.dataset.client) {
            clientSelect.value = opt.dataset.client;
        }
        updateSubscription();
        loadBillables();
    });
    document.getElementById('reloadBillables').addEventListener('click', loadBillables);

    filterServicesByClient();
    updateSubscription();
    if (clientSelect.value) loadBillables();
})();
</script>
@endsection
