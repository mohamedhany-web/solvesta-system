@extends('layouts.app')

@section('page-title', 'تقدير التكلفة')

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $e = $estimation;
    $v = fn($k, $default = '') => old($k, $e?->$k ?? $defaults[$k] ?? $default);
@endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'تقدير التكلفة — Pre-Sales',
        'subtitle' => $sale->client?->name.' · '.$sale->product_service,
        'icon' => 'briefcase',
    ])

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('sales.show', $sale) }}" class="text-sm font-bold px-4 py-2 rounded-xl border border-gray-200 bg-white hover:shadow-md transition">← الفرصة</a>
        <a href="{{ route('pre-sales.index') }}" class="text-sm font-bold px-4 py-2 rounded-xl border border-gray-200 bg-white hover:shadow-md transition">طابور Pre-Sales</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-tajawal">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-2">
            <form method="POST" action="{{ route('pre-sales.estimate.store', $sale) }}" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 space-y-5" id="estimate-form">
                @csrf
                <h2 class="text-lg font-bold text-gray-900 font-tajawal border-b pb-3">مدخلات التقدير</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عدد الشاشات</label>
                        <input type="number" name="screen_count" value="{{ $v('screen_count', 0) }}" min="0"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm" id="screen_count">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">عدد المطورين</label>
                        <input type="number" name="developers_count" value="{{ $v('developers_count', 1) }}" min="1"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach(['dev'=>'تطوير','design'=>'تصميم','qa'=>'اختبار','pm'=>'إدارة مشروع'] as $key => $label)
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">ساعات {{ $label }}</label>
                        <input type="number" step="0.5" name="{{ $key }}_hours" value="{{ $v($key.'_hours', $key==='dev'?120:($key==='design'?40:($key==='qa'?20:10))) }}"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm hours-input" data-role="{{ $key }}">
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach(['dev'=>500,'design'=>400,'qa'=>350,'pm'=>450] as $key => $def)
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">سعر ساعة {{ $key }}</label>
                        <input type="number" step="1" name="hourly_rate_{{ $key }}" value="{{ $v('hourly_rate_'.$key, $def) }}"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm rate-input" data-role="{{ $key }}">
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">هامش ربح %</label>
                        <input type="number" step="0.5" name="margin_percent" value="{{ $v('margin_percent', 15) }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm" id="margin_percent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">المدة (أسابيع)</label>
                        <input type="number" name="duration_weeks" value="{{ $v('duration_weeks', 8) }}" min="1"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm" id="duration_weeks">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نطاق العمل</label>
                    <textarea name="scope_notes" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">{{ $v('scope_notes') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات تقنية</label>
                    <textarea name="technical_notes" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">{{ $v('technical_notes') }}</textarea>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold text-sm"
                            style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">حفظ مسودة</button>
                    <button type="submit" name="submit_for_approval" value="1" class="px-6 py-2.5 rounded-xl bg-blue-600 text-white font-bold text-sm">إرسال للاعتماد</button>
                    @can('edit-sales')
                    <button type="submit" name="approve_now" value="1" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm">حفظ واعتماد فوري</button>
                    @endcan
                </div>
            </form>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sticky top-4">
                <h3 class="font-bold text-gray-900 mb-4 font-tajawal">ملخص التكلفة</h3>
                <div id="live-summary" class="space-y-2 text-sm font-tajawal text-gray-700">
                    <p>المجموع الفرعي: <strong id="sum-subtotal">—</strong></p>
                    <p>بعد الهامش: <strong id="sum-total" class="text-xl" style="color: {{ $themeColor }};">—</strong></p>
                    <p class="text-xs text-gray-500">إجمالي الساعات: <span id="sum-hours">—</span></p>
                </div>
            </div>

            @if($e)
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <p class="text-xs text-gray-500 font-mono mb-2">{{ $e->reference_code }}</p>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">{{ $e->status_label }}</span>
                <p class="mt-3 text-2xl font-bold" style="color: {{ $themeColor }};">{{ number_format($e->total_cost) }} ج.م</p>

                @if($e->status === 'submitted' && auth()->user()->can('edit-sales'))
                <form method="POST" action="{{ route('pre-sales.estimations.approve', $e) }}" class="mt-4">
                    @csrf
                    <button class="w-full py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm">اعتماد التقدير</button>
                </form>
                @endif

                @if($e->status === 'approved')
                <form method="POST" action="{{ route('pre-sales.proposals.generate', $sale) }}" class="mt-4">
                    @csrf
                    <button class="w-full py-2.5 rounded-xl text-white font-bold text-sm"
                            style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        إصدار Proposal تلقائي →
                    </button>
                </form>
                @endif

                @if($e->proposal)
                <a href="{{ route('pre-sales.proposals.show', $e->proposal) }}" class="block mt-3 text-center text-blue-600 font-bold text-sm">عرض Proposal</a>
                @endif
            </div>
            @endif

            @if($sale->requirement_summary)
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-4 text-sm font-tajawal">
                <strong>ملخص المتطلبات:</strong><br>{{ $sale->requirement_summary }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    const roles = ['dev','design','qa','pm'];
    function calc() {
        let sub = 0, hours = 0;
        roles.forEach(r => {
            const h = parseFloat(document.querySelector(`[name="${r}_hours"]`)?.value) || 0;
            const rate = parseFloat(document.querySelector(`[name="hourly_rate_${r}"]`)?.value) || 0;
            sub += h * rate;
            hours += h;
        });
        const margin = parseFloat(document.getElementById('margin_percent')?.value) || 0;
        const total = sub * (1 + margin / 100);
        document.getElementById('sum-subtotal').textContent = sub.toLocaleString('ar-EG') + ' ج.م';
        document.getElementById('sum-total').textContent = total.toLocaleString('ar-EG') + ' ج.م';
        document.getElementById('sum-hours').textContent = hours.toLocaleString('ar-EG');
    }
    document.getElementById('estimate-form')?.addEventListener('input', calc);
    document.getElementById('screen_count')?.addEventListener('change', function() {
        const s = parseInt(this.value) || 0;
        if (s > 0) {
            const dev = document.querySelector('[name="dev_hours"]');
            const design = document.querySelector('[name="design_hours"]');
            if (dev && !dev.dataset.touched) dev.value = Math.round(s * 8);
            if (design && !design.dataset.touched) design.value = Math.round(s * 3);
        }
        calc();
    });
    document.querySelectorAll('.hours-input').forEach(el => el.addEventListener('input', () => el.dataset.touched = '1'));
    calc();
})();
</script>
@endsection
