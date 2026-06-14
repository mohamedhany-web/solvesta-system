@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
@can('view-finance')
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 mb-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-5">
        <h2 class="text-lg font-bold font-tajawal" style="color: {{ $themeColor }};">المالية — المشروع</h2>
        @can('edit-projects')
            @if($financials['can_create_delivery'] && !$financials['delivery_invoice'])
            <form method="POST" action="{{ route('projects.finance.delivery-invoice', $project) }}">@csrf
                <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold">فاتورة تسليم 50%</button>
            </form>
            @elseif($financials['delivery_invoice'])
            <a href="{{ route('invoices.show', $financials['delivery_invoice']) }}" class="px-4 py-2 rounded-xl border font-bold text-sm text-blue-600">
                فاتورة التسليم — {{ $financials['delivery_invoice']->status }}
            </a>
            @endif
        @endcan
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6 text-center text-sm font-tajawal">
        <div class="rounded-xl p-4 border" style="background: {{ $themeColor }}08; border-color: {{ $themeColor }}25;">
            <p class="text-xs text-gray-500">قيمة العقد</p>
            <p class="text-xl font-bold">{{ number_format($financials['contract_value']) }}</p>
        </div>
        <div class="rounded-xl p-4 bg-emerald-50 border border-emerald-100">
            <p class="text-xs text-gray-500">محصّل</p>
            <p class="text-xl font-bold text-emerald-700">{{ number_format($financials['revenue_paid']) }}</p>
        </div>
        <div class="rounded-xl p-4 bg-amber-50 border border-amber-100">
            <p class="text-xs text-gray-500">مستحقات</p>
            <p class="text-xl font-bold text-amber-700">{{ number_format($financials['outstanding']) }}</p>
        </div>
        <div class="rounded-xl p-4 bg-red-50 border border-red-100">
            <p class="text-xs text-gray-500">مصروفات</p>
            <p class="text-xl font-bold text-red-700">{{ number_format($financials['expenses']) }}</p>
        </div>
        <div class="rounded-xl p-4 border border-indigo-100 bg-indigo-50/50">
            <p class="text-xs text-gray-500">ربح تقديري</p>
            <p class="text-xl font-bold {{ $financials['profit'] >= 0 ? 'text-indigo-700' : 'text-red-700' }}">{{ number_format($financials['profit']) }}</p>
            <p class="text-xs text-gray-500">{{ $financials['margin_percent'] }}% هامش</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm font-tajawal">
        @if($financials['deposit_invoice'])
        <div class="p-3 rounded-xl bg-gray-50 border">
            <strong>دفعة مقدمة:</strong>
            <a href="{{ route('invoices.show', $financials['deposit_invoice']) }}" class="text-blue-600">{{ $financials['deposit_invoice']->invoice_number }}</a>
            — {{ $financials['deposit_invoice']->status }}
        </div>
        @endif
        @if($financials['delivery_invoice'])
        <div class="p-3 rounded-xl bg-gray-50 border">
            <strong>عند التسليم:</strong>
            <a href="{{ route('invoices.show', $financials['delivery_invoice']) }}" class="text-blue-600">{{ $financials['delivery_invoice']->invoice_number }}</a>
            — {{ $financials['delivery_invoice']->status }}
        </div>
        @endif
    </div>

    @can('create-finance')
    <details class="mb-4">
        <summary class="cursor-pointer font-bold text-sm mb-2" style="color: {{ $themeColor }};">+ تسجيل مصروف للمشروع</summary>
        <form method="POST" action="{{ route('projects.finance.expenses', $project) }}" class="grid grid-cols-1 md:grid-cols-2 gap-3 p-4 bg-gray-50 rounded-xl mt-2">
            @csrf
            <input name="description" required placeholder="وصف المصروف" class="border rounded-lg px-3 py-2 md:col-span-2">
            <input type="number" step="0.01" name="amount" required placeholder="المبلغ" class="border rounded-lg px-3 py-2">
            <input type="date" name="expense_date" value="{{ today()->format('Y-m-d') }}" class="border rounded-lg px-3 py-2">
            <select name="expense_category" class="border rounded-lg px-3 py-2">
                <option value="software">برمجيات</option>
                <option value="professional_fees">رسوم مهنية</option>
                <option value="travel">سفر</option>
                <option value="salaries">رواتب/مستحقات</option>
                <option value="other">أخرى</option>
            </select>
            <select name="payment_method" class="border rounded-lg px-3 py-2">
                <option value="bank_transfer">تحويل بنكي</option>
                <option value="cash">نقدي</option>
            </select>
            <button class="md:col-span-2 py-2 rounded-lg text-white font-bold" style="background:{{ $themeColor }};">تسجيل المصروف</button>
        </form>
    </details>
    @endcan

    @if($projectExpenses->count())
    <h3 class="font-bold text-sm mb-2 font-tajawal">آخر مصروفات المشروع</h3>
    <ul class="text-sm space-y-2 font-tajawal">
        @foreach($projectExpenses as $exp)
        <li class="flex justify-between py-2 px-3 bg-gray-50 rounded-lg">
            <span>{{ $exp->description }} <span class="text-xs text-gray-500">({{ $exp->expense_date->format('Y/m/d') }})</span></span>
            <span class="font-bold text-red-700">{{ number_format($exp->amount) }} — {{ $exp->status }}</span>
        </li>
        @endforeach
    </ul>
    @endif
</div>
@endcan
