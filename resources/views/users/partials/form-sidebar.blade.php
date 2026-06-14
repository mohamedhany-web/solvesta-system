<div class="space-y-4">
    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm text-blue-900">
        <h3 class="font-bold mb-2">ملاحظات مهمة</h3>
        <ul class="space-y-2 leading-relaxed">
            <li>• كل مستخدم يُربط بسجل موظف في قسم محدد.</li>
            <li>• الأدوار تحدد صلاحيات الوصول في النظام.</li>
            <li>• رئيس القسم يُحدَّد من إعدادات الأقسام وليس من هنا.</li>
        </ul>
    </div>
    @if(isset($user) && $user->employee)
    <div class="rounded-2xl border border-gray-200 bg-white p-5 text-sm shadow-sm">
        <p class="font-bold text-gray-900 mb-2">بيانات سريعة</p>
        <dl class="space-y-2 text-gray-600">
            <div class="flex justify-between"><dt>القسم</dt><dd class="font-semibold text-gray-900">{{ $user->employee->department?->name ?? '—' }}</dd></div>
            <div class="flex justify-between"><dt>تاريخ الإنشاء</dt><dd>{{ $user->created_at->format('Y/m/d') }}</dd></div>
        </dl>
    </div>
    @endif
</div>
