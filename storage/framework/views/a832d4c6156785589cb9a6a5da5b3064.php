<div class="space-y-4">
    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 shadow-sm">
        <h3 class="font-bold text-blue-900 mb-3">قواعد بدء المشروع</h3>
        <ul class="text-sm text-blue-800 space-y-2 leading-relaxed">
            <li class="flex gap-2"><span class="font-bold">•</span> المشروع لا يبدأ التنفيذ إلا بعد دفع <strong>50% مقدماً</strong> من العقد.</li>
            <li class="flex gap-2"><span class="font-bold">•</span> اربط المشروع بالعميل الصحيح لتظهر بياناته في PMO والمالية.</li>
            <li class="flex gap-2"><span class="font-bold">•</span> اختر <strong>القسم المنفّذ</strong> — رئيس القسم يعيّن قائد الفريق والفريق.</li>
            <li class="flex gap-2"><span class="font-bold">•</span> لا تُعيّن الفريق من هنا؛ يتم الإسناد من لوحة مدير القسم.</li>
        </ul>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-2">حالات المشروع</h3>
        <dl class="text-sm space-y-2">
            <div class="flex justify-between gap-2"><dt class="font-semibold text-gray-700">تخطيط</dt><dd class="text-gray-500 text-xs">قبل البدء</dd></div>
            <div class="flex justify-between gap-2"><dt class="font-semibold text-gray-700">قيد التنفيذ</dt><dd class="text-gray-500 text-xs">جاري العمل</dd></div>
            <div class="flex justify-between gap-2"><dt class="font-semibold text-gray-700">معلق</dt><dd class="text-gray-500 text-xs">بانتظار قرار</dd></div>
            <div class="flex justify-between gap-2"><dt class="font-semibold text-gray-700">مكتمل</dt><dd class="text-gray-500 text-xs">تم التسليم</dd></div>
        </dl>
    </div>

    <?php if(isset($project)): ?>
    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5 text-sm">
        <p class="font-bold text-indigo-900 mb-2">اختصارات</p>
        <div class="space-y-2">
            <a href="<?php echo e(route('projects.show', $project)); ?>" class="block text-indigo-700 font-semibold hover:underline">عرض تفاصيل المشروع ←</a>
            <a href="<?php echo e(route('pmo.index')); ?>" class="block text-indigo-700 font-semibold hover:underline">لوحة PMO ←</a>
        </div>
    </div>
    <?php else: ?>
    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5 text-sm text-emerald-900">
        <p class="font-bold mb-1">بعد الإنشاء</p>
        <p class="leading-relaxed">يُرسل المشروع لرئيس القسم المختار لتعيين Team Leader والفريق، ثم تُوزَّع المهام من PMO.</p>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\projects\partials\form-sidebar.blade.php ENDPATH**/ ?>