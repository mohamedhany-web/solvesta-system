@php
    $wallet = $wallet ?? null;
    $isEdit = (bool) $wallet;
    $section = $section ?? 'all';
    $showBasic = in_array($section, ['all', 'basic'], true);
    $showBank = in_array($section, ['all', 'bank'], true);
    $showExtra = in_array($section, ['all', 'extra'], true);
    $inputClass = 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500';
    $labelClass = 'block text-sm font-semibold text-gray-700 mb-1.5';
@endphp

@if($showBasic)
<div>
    <label class="{{ $labelClass }}">اسم المحفظة <span class="text-red-500">*</span></label>
    <input type="text" name="name" value="{{ old('name', $wallet?->name) }}" required
           class="{{ $inputClass }}" placeholder="مثال: البنك الأهلي — حساب جاري">
    @error('name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="{{ $labelClass }}">النوع <span class="text-red-500">*</span></label>
    <select name="type" required class="{{ $inputClass }}">
        @foreach(['cash' => 'نقدية / خزينة', 'bank' => 'حساب بنكي', 'transfer' => 'تحويل / محفظة إلكترونية', 'other' => 'أخرى'] as $val => $label)
            <option value="{{ $val }}" @selected(old('type', $wallet?->type ?? 'cash') === $val)>{{ $label }}</option>
        @endforeach
    </select>
    @error('type')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="{{ $labelClass }}">العملة</label>
    <input type="text" name="currency" maxlength="3" value="{{ old('currency', $wallet?->currency ?? 'EGP') }}"
           class="{{ $inputClass }}" dir="ltr">
    @error('currency')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
</div>

@if(!$isEdit)
<div class="{{ ($useGrid ?? false) ? 'md:col-span-2' : '' }}">
    <label class="{{ $labelClass }}">رصيد افتتاحي</label>
    <input type="number" name="opening_balance" step="0.01" min="0" value="{{ old('opening_balance', 0) }}"
           class="{{ $inputClass }}" placeholder="0.00">
    @error('opening_balance')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
</div>
@elseif($section === 'basic')
<div class="md:col-span-2">
    <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 font-bold text-sm">ج.م</div>
        <div>
            <p class="font-bold text-gray-900">الرصيد الحالي: {{ number_format($wallet->current_balance, 2) }} {{ $wallet->currency }}</p>
            <p class="text-xs text-gray-600 mt-1">لا يُعدَّل الرصيد من هنا. استخدم <strong>إيداع / سحب</strong> من <a href="{{ route('accounting.wallets.show', $wallet) }}" class="text-blue-600 underline">صفحة المحفظة</a>.</p>
        </div>
    </div>
</div>
@endif
@endif

@if($showBank)
<div>
    <label class="{{ $labelClass }}">اسم البنك</label>
    <input type="text" name="bank_name" value="{{ old('bank_name', $wallet?->bank_name) }}"
           class="{{ $inputClass }}" placeholder="اسم البنك">
    @error('bank_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="{{ $labelClass }}">رقم الحساب</label>
    <input type="text" name="account_number" value="{{ old('account_number', $wallet?->account_number) }}"
           class="{{ $inputClass }}" placeholder="رقم الحساب أو IBAN" dir="ltr">
    @error('account_number')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
</div>
@endif

@if($showExtra)
<div>
    <label class="{{ $labelClass }}">ملاحظات</label>
    <textarea name="notes" rows="3" class="{{ $inputClass }}" placeholder="ملاحظات داخلية عن المحفظة">{{ old('notes', $wallet?->notes) }}</textarea>
    @error('notes')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
</div>

@if($isEdit)
<div class="flex items-center justify-between p-4 border border-gray-200 rounded-xl bg-gray-50">
    <div>
        <p class="text-sm font-bold text-gray-900">حالة المحفظة</p>
        <p class="text-xs text-gray-500 mt-0.5">المحافظ المعطّلة لا تظهر عند تسجيل دفعات الفواتير</p>
    </div>
    <label class="inline-flex items-center gap-2 cursor-pointer shrink-0">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               class="w-5 h-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
               @checked(old('is_active', $wallet->is_active))>
        <span class="text-sm font-semibold text-gray-800">نشطة</span>
    </label>
</div>
@endif
@endif
