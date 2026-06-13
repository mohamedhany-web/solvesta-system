

<?php $__env->startSection('page-title', 'دليل نظام المحاسبة'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl mx-auto prose prose-slate max-w-none">
    <div class="mb-8 not-prose">
        <a href="<?php echo e(route('accounting.index')); ?>" class="text-sm text-blue-600 font-semibold hover:underline">← لوحة التحكم المالية</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">دليل نظام المحاسبة في Solvesta</h1>
        <p class="text-gray-600 mt-2">شرح مفصّل لدورة العمل المالية: من العقد والخدمة إلى الفاتورة والتحصيل والتقارير.</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-8 not-prose text-sm text-blue-900">
        <strong>ملخص سريع:</strong> تُسجَّل خدمة ما بعد البيع مرتبطة بعميل (وعقد إن وُجد) → عند التفعيل يُصدر النظام فاتورة شهرية تلقائياً → يُحصَّل المبلغ عبر «تسجيل دفعة» واختيار المحفظة → تنعكس الحركة في المحفظة والتقارير.
    </div>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">1. هيكل قسم المحاسبة</h2>
        <ul class="list-disc pr-6 space-y-2 text-gray-700">
            <li><strong>لوحة التحكم المالية:</strong> نظرة عامة على الإيرادات والمصروفات والذمم؛ تُحسب من القيود إن وُجدت، وإلا من الفواتير والمدفوعات الفعلية.</li>
            <li><strong>شجرة الحسابات:</strong> أصول، خصوم، حقوق ملكية، إيرادات، مصروفات — أساس القيود المحاسبية المزدوجة.</li>
            <li><strong>المحافظ والمعاملات:</strong> خزينة، بنك، تحويل — كل تحصيل فاتورة يُسجَّل في محفظة محددة.</li>
            <li><strong>خدمات ما بعد البيع:</strong> اشتراكات شهرية مرتبطة بالعميل والعقد مع فوترة آلية.</li>
            <li><strong>القيود المحاسبية:</strong> قيود يومية (مسودة → اعتماد → ترحيل) لربط الحسابات رسمياً.</li>
            <li><strong>الفواتير المالية:</strong> فواتير خدمات وإيرادات الشركة (مستقلة عن فواتير المشاريع القديمة).</li>
            <li><strong>المدفوعات والمصروفات:</strong> تتبع التدفقات النقدية والصرف.</li>
            <li><strong>التقارير:</strong> ميزانية، قائمة دخل، تدفقات نقدية، ميزان مراجعة.</li>
        </ul>
    </section>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">2. خدمات ما بعد البيع والعقود</h2>
        <h3 class="font-bold text-gray-800 mt-4">الخطوات (من طرفكم)</h3>
        <ol class="list-decimal pr-6 space-y-2 text-gray-700">
            <li>أنشئوا عقداً في <strong>الشؤون القانونية → العقود</strong> (نوع خدمة/صيانة إن أمكن).</li>
            <li>من <strong>المحاسبة → خدمات ما بعد البيع → خدمة جديدة</strong>: اختاروا العميل، اربطوا العقد، حدّدوا المبلغ الشهري ويوم الفوترة (1–28).</li>
            <li>غيّروا الحالة إلى <strong>نشطة</strong> — يُحدَّد موعد الفوترة القادم تلقائياً إن تُرك فارغاً.</li>
            <li>فعّلوا «إصدار فواتير شهرية تلقائياً» — يعمل جدول النظام يومياً (06:30) أو زر «إصدار فاتورة الآن» يدوياً.</li>
        </ol>
        <h3 class="font-bold text-gray-800 mt-4">ما يراه العميل</h3>
        <p class="text-gray-700">في <strong>بوابة العملاء → خدماتي واشتراكاتي</strong> يظهر الاشتراك والمبلغ وموعد الفاتورة القادمة وآخر الفواتير. يُرسل إشعار عند كل فاتورة جديدة، ويستطيع الدفع من <strong>الفواتير</strong>.</p>
    </section>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">3. الفواتير المالية والتحصيل</h2>
        <ul class="list-disc pr-6 space-y-2 text-gray-700">
            <li>كل فاتورة لها رقم <code class="bg-gray-100 px-1 rounded">FINV-YYYYMM-####</code>، حالة إرسال، وحالة دفع (مدفوعة / غير مدفوعة / جزئية).</li>
            <li>من صفحة الفاتورة: <strong>تسجيل دفعة</strong> — أدخلوا المبلغ و<strong>المحفظة</strong> (نقدي، بنك، تحويل).</li>
            <li>الدفعة تُحدّث رصيد الفاتورة وترحّل حركة وارد على المحفظة المختارة.</li>
            <li><strong>طباعة / PDF:</strong> من رابط الطباعة — تصميم فاتورة خدمات (وصف + مبلغ، بدون عمود كمية).</li>
        </ul>
    </section>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">4. المحافظ</h2>
        <p class="text-gray-700">المحفظة تمثّل مكان تجميع الأموال. عند تحصيل فاتورة تُختار المحفظة في نموذج الدفعة. يمكن أيضاً تسجيل حركة يدوية (وارد/صادر) من صفحة المحفظة. الرصيد الحالي = الرصيد الافتتاحي + الوارد − الصادر.</p>
    </section>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">5. القيود المحاسبية (اختياري متقدم)</h2>
        <p class="text-gray-700">للمحاسبة الرسمية المزدوجة: أنشئوا قيداً بسطور مدين/دائن متوازنة، اعتمدوه، ثم رحّلوه. بعد الترحيل تنعكس الأرقام في لوحة التحكم والتقارير بدقة أعلى من مجرد تجميع الفواتير.</p>
    </section>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">6. الفرق بين أنواع الفواتير</h2>
        <table class="w-full text-sm border rounded-lg overflow-hidden not-prose">
            <thead class="bg-gray-50"><tr><th class="p-3 text-right border-b">النوع</th><th class="p-3 text-right border-b">الاستخدام</th></tr></thead>
            <tbody class="text-gray-700">
                <tr><td class="p-3 border-b font-bold">فواتير مالية</td><td class="p-3 border-b">إيرادات الشركة، خدمات ما بعد البيع، فواتير عامة</td></tr>
                <tr><td class="p-3 border-b font-bold">فواتير المشاريع</td><td class="p-3 border-b">مرتبطة بمشاريع تنفيذية (نظام أقدم)</td></tr>
            </tbody>
        </table>
    </section>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">7. الصلاحيات</h2>
        <p class="text-gray-700">الوصول يعتمد على صلاحيات المستخدم: <code class="bg-gray-100 px-1 rounded">view-finance</code> للعرض، <code class="bg-gray-100 px-1 rounded">create-finance</code> / <code class="bg-gray-100 px-1 rounded">edit-finance</code> للإنشاء والتعديل والتحصيل، <code class="bg-gray-100 px-1 rounded">delete-finance</code> للحذف.</p>
    </section>

    <section class="mb-10">
        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-4">8. أوامر النظام (للمسؤول التقني)</h2>
        <pre class="bg-gray-900 text-green-100 p-4 rounded-xl text-sm overflow-x-auto not-prose">php artisan migrate
php artisan client-services:generate-invoices</pre>
        <p class="text-gray-600 text-sm mt-2">الأمر الثاني يُشغَّل يومياً عبر المجدول؛ يمكن تشغيله يدوياً لاختبار الفوترة.</p>
    </section>

    <div class="not-prose flex flex-wrap gap-3 mt-8">
        <a href="<?php echo e(route('accounting.client-services.index')); ?>" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-emerald-700">خدمات ما بعد البيع</a>
        <a href="<?php echo e(route('financial-invoices.index')); ?>" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700">الفواتير المالية</a>
        <a href="<?php echo e(route('accounting.wallets.index')); ?>" class="border px-5 py-2.5 rounded-xl font-bold hover:bg-gray-50">المحافظ</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\guide.blade.php ENDPATH**/ ?>