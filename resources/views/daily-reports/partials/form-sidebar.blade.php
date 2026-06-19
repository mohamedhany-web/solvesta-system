@php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $stats = $stats ?? [];
@endphp

<div class="space-y-4">
    <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm text-center">
        <p class="text-xs font-bold text-gray-500 mb-2">تاريخ اليوم</p>
        <p class="text-lg font-bold text-gray-900">{{ now()->locale('ar')->translatedFormat('l') }}</p>
        <p class="text-sm text-gray-600 mt-1" dir="ltr">{{ now()->format('Y-m-d') }}</p>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center shadow-sm">
            <p class="text-2xl font-bold" style="color: {{ $themeColor }};">{{ $stats['week_hours'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">ساعات الأسبوع</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center shadow-sm">
            <p class="text-2xl font-bold {{ ($stats['open_blockers'] ?? 0) > 0 ? 'text-red-600' : 'text-emerald-600' }}">{{ $stats['open_blockers'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">عوائق مفتوحة</p>
        </div>
    </div>

    <div class="rounded-2xl border border-amber-100 bg-amber-50/60 p-5 text-sm text-amber-900">
        <p class="font-bold mb-2">نصائح لتقرير جيد</p>
        <ul class="space-y-2 text-xs leading-relaxed list-disc list-inside">
            <li>اذكر ما أنجزته بشكل محدد وليس عاماً</li>
            <li>اربط التقرير بمشروع أو مهمة إن أمكن</li>
            <li>سجّل العوائق فوراً ليراها مديرك</li>
            <li>الساعات تقريبية — الأهم الوضوح</li>
        </ul>
    </div>

    <a href="{{ route('workspace.index') }}" class="block rounded-2xl border border-gray-200 bg-white p-4 text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm text-center">
        ← العودة لمساحة العمل
    </a>
</div>
