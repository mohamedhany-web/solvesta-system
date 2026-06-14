@php $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="space-y-6">
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-5">
        <h3 class="font-bold text-sm text-gray-900 mb-3 flex items-center gap-2">
            <span class="w-1 h-4 rounded-full" style="background: {{ $themeColor }};"></span>
            نشر على الموقع
        </h3>
        <ul class="text-xs text-gray-600 space-y-2 leading-relaxed">
            <li>اختر حالة <strong>منشورة</strong> لإظهار الوظيفة في صفحة التوظيف العامة.</li>
            <li>الوظائف <strong>المميزة</strong> تظهر أولاً في القائمة.</li>
            <li>يمكنك إغلاق الوظيفة لإيقاف استقبال طلبات جديدة دون حذفها.</li>
        </ul>
        <a href="{{ route('website.careers') }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-1 mt-4 text-xs font-bold hover:underline" style="color: {{ $themeColor }};">
            معاينة صفحة التوظيف
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
        </a>
    </div>

    @if(isset($jobPosting) && $jobPosting)
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-5">
        <h3 class="font-bold text-sm text-gray-900 mb-3">معلومات سريعة</h3>
        <dl class="text-xs space-y-2">
            <div class="flex justify-between"><dt class="text-gray-500">الحالة</dt><dd class="font-bold">{{ $jobPosting->statusLabelAr() }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">الطلبات</dt><dd class="font-bold" style="color: {{ $themeColor }};">{{ $jobPosting->applications_count ?? 0 }}</dd></div>
            @if($jobPosting->published_at)
            <div class="flex justify-between"><dt class="text-gray-500">تاريخ النشر</dt><dd>{{ $jobPosting->published_at->format('Y/m/d') }}</dd></div>
            @endif
            <div class="flex justify-between"><dt class="text-gray-500">آخر تحديث</dt><dd>{{ $jobPosting->updated_at->format('Y/m/d') }}</dd></div>
        </dl>
        @if($jobPosting->slug)
        <p class="text-[10px] text-gray-400 mt-3 break-all" dir="ltr">{{ route('website.careers.show', $jobPosting->slug) }}</p>
        @endif
    </div>
    @endif
</div>
