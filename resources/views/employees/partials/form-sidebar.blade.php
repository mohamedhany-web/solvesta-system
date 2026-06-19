@php $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="space-y-4">
    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm text-blue-900">
        <h3 class="font-bold mb-2">إرشادات الإضافة</h3>
        <ul class="space-y-2 leading-relaxed text-blue-800/90">
            <li>• يمكن ربط موظف بمستخدم موجود أو إنشاء حساب جديد تلقائياً.</li>
            <li>• الرقم التوظيفي يُولَّد تلقائياً عند الحفظ.</li>
            <li>• القسم والمسمى الوظيفي يظهران في التقارير ومساحة العمل.</li>
        </ul>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm text-sm">
        <p class="font-bold text-gray-900 mb-3">حساب المستخدم</p>
        <div class="space-y-2 text-gray-600">
            <p><span class="font-semibold text-gray-800">مستخدم موجود:</span> اختر من القائمة المستخدمين غير المرتبطين بموظف.</p>
            <p><span class="font-semibold text-gray-800">حساب جديد:</span> فعّل الخيار وأدخل كلمة مرور — يُنشأ الحساب بنفس البريد.</p>
        </div>
    </div>

    @if(isset($users) && $users->isNotEmpty())
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm text-sm">
        <p class="font-bold text-gray-900 mb-2">مستخدمون متاحون للربط</p>
        <p class="text-2xl font-bold mb-1" style="color: {{ $themeColor }};">{{ $users->count() }}</p>
        <p class="text-xs text-gray-500">بدون سجل موظف حالياً</p>
    </div>
    @endif
</div>
