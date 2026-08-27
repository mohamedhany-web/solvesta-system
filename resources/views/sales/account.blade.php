@extends('layouts.app')

@section('page-title', 'حسابي — الراتب والعمولات')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $rate = $commissionSummary['commission_rate'];
    $wins = $commissionSummary['wins'];
@endphp

<div class="w-full max-w-full font-tajawal">
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl">{{ session('success') }}</div>
    @endif

    <div class="mb-6 rounded-2xl p-6 shadow-xl border overflow-hidden relative"
         style="background: linear-gradient(135deg, {{ $themeColor }}15 0%, {{ $themeColor }}05 100%); border-color: {{ $themeColor }}30;">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">حسابي الوظيفي</h1>
                <p class="text-gray-600 mt-1">{{ $employee->displayName() }} · {{ $employee->employee_id }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('sales.dashboard') }}" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold text-gray-700 hover:bg-gray-50">لوحة المبيعات</a>
                <a href="{{ route('profile.edit') }}" class="px-4 py-2 rounded-xl text-white text-sm font-bold" style="background: {{ $themeColor }};">الملف الشخصي</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">بيانات الموظف</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-gray-500">الاسم</dt><dd class="font-medium text-gray-900">{{ $employee->displayName() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">المنصب</dt><dd class="font-medium text-gray-900">{{ $employee->position ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">البريد</dt><dd class="font-medium text-gray-900">{{ $user->email }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">الهاتف</dt><dd class="font-medium text-gray-900">{{ $employee->phone ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">تاريخ التعيين</dt><dd class="font-medium text-gray-900">{{ optional($employee->hire_date)->format('Y/m/d') ?? '—' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">الحالة</dt><dd class="font-medium text-gray-900">{{ $employee->status ?? '—' }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">الراتب</h2>
            <p class="text-3xl font-bold text-gray-900 mb-2">{{ number_format($salary, 2) }} <span class="text-lg font-normal text-gray-500">ج.م / شهر</span></p>
            @if($contract)
            <p class="text-sm text-gray-600">عقد نشط: {{ $contract->contract_number }}</p>
            <p class="text-xs text-gray-500 mt-1">من {{ $contract->start_date->format('Y/m/d') }}</p>
            @else
            <p class="text-sm text-gray-500">الراتب الأساسي المسجّل في ملف الموظف</p>
            @endif
            <p class="text-xs text-amber-600 mt-4">* الراتب للعرض فقط — التعديل من الموارد البشرية</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">العمولات (Wins)</h2>
            <p class="text-3xl font-bold text-emerald-600 mb-1">{{ number_format($commissionSummary['total_commission'], 2) }} ج.م</p>
            <p class="text-sm text-gray-600 mb-3">إجمالي عمولة من {{ $wins->count() }} صفقة Won</p>
            <p class="text-xs text-gray-500">نسبة العمولة: {{ number_format($rate, 1) }}% من قيمة الصفقة</p>
            <p class="text-sm font-medium text-gray-800 mt-3">إيراد Won: {{ number_format($commissionSummary['total_revenue'], 0) }} ج.م</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">بيانات التحويل (للراتب / العمولة)</h2>
            <form method="POST" action="{{ route('sales.account.payout') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">طريقة التحويل المفضلة</label>
                    <select name="preferred_payout_method" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm">
                        <option value="">— اختر —</option>
                        @foreach($payoutMethods as $key => $label)
                        <option value="{{ $key }}" @selected(old('preferred_payout_method', $employee->preferred_payout_method) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">حساب بنكي</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <input type="text" name="bank_name" value="{{ old('bank_name', $employee->bank_name) }}" placeholder="اسم البنك" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}" placeholder="رقم الحساب / IBAN" class="border border-gray-300 rounded-xl px-3 py-2 text-sm">
                        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder', $employee->bank_account_holder) }}" placeholder="اسم صاحب الحساب" class="border border-gray-300 rounded-xl px-3 py-2 text-sm sm:col-span-2">
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">فودافون كاش</h3>
                    <input type="text" name="vodafone_cash_number" value="{{ old('vodafone_cash_number', $employee->vodafone_cash_number) }}" placeholder="01xxxxxxxxx" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">InstaPay</h3>
                    <input type="text" name="instapay_identifier" value="{{ old('instapay_identifier', $employee->instapay_identifier) }}" placeholder="رقم الموبايل أو InstaPay ID" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات</label>
                    <textarea name="payout_notes" rows="2" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm" placeholder="أي تعليمات إضافية للتحويل">{{ old('payout_notes', $employee->payout_notes) }}</textarea>
                </div>

                @if($errors->any())
                <div class="p-3 bg-red-50 text-red-700 rounded-lg text-sm">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl text-white font-bold text-sm" style="background: {{ $themeColor }};">حفظ بيانات التحويل</button>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-900">صفقات Won والعمولة</h2>
                <p class="text-sm text-gray-500">كل الصفقات المغلقة بـ Won المخصصة لك</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">الفرصة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">العميل</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">القيمة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">العمولة</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">التاريخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($wins as $sale)
                        @php
                            $value = (float) ($sale->actual_value ?? $sale->estimated_value ?? 0);
                            $comm = round($value * ($rate / 100), 2);
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('sales.show', $sale) }}" class="text-blue-600 hover:underline font-medium">{{ Str::limit($sale->product_service, 30) }}</a>
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $sale->client?->name ?? '—' }}</td>
                            <td class="px-4 py-3 font-medium">{{ number_format($value, 0) }} ج.م</td>
                            <td class="px-4 py-3 font-medium text-emerald-600">{{ number_format($comm, 2) }} ج.م</td>
                            <td class="px-4 py-3 text-gray-500">{{ optional($sale->actual_close_date ?? $sale->updated_at)->format('Y/m/d') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                لا توجد صفقات Won بعد — أغلق فرصة بمرحلة Won لتظهر هنا مع عمولتك
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
