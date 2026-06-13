@extends('layouts.app')

@section('page-title', 'شرح البورتال')

@section('content')
@php $cPortal = Auth::guard('client')->user(); @endphp
<div class="w-full max-w-full">
    <div class="mb-8">
        <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl p-6 sm:p-8 border border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">شرح البورتال</h1>
                    <p class="text-gray-600 text-sm sm:text-base max-w-3xl leading-relaxed">
                        دليلك الكامل لاستخدام بوابة العميل — ماذا يفعل كل قسم، ومتى تستخدمه، وكيف تتابع طلباتك وفواتيرك ومشاريعك من مكان واحد.
                    </p>
                </div>
                <a href="{{ route('client.dashboard') }}" class="shrink-0 inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition">
                    العودة للوحة
                </a>
            </div>
        </div>
    </div>

    {{-- مقدمة --}}
    <section class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-3">ما هي بوابة العميل؟</h2>
        <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-4">
            بوابة العميل هي مساحتك الخاصة لمتابعة علاقتك مع الشركة بعد التعاقد: مشاريعك، فواتيرك، تقارير الخدمة، المستندات، طلبات الدعم، والتواصل مع الفريق — دون الحاجة للاتصال أو البريد في كل مرة.
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <div class="rounded-xl bg-blue-50 border border-blue-100 p-4">
                <p class="font-bold text-blue-900 mb-1">لوحة مركزية</p>
                <p class="text-blue-800/80">ملخص سريع لما يحتاج انتباهك: تذاكر، فواتير، اجتماعات، وإشعارات.</p>
            </div>
            <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4">
                <p class="font-bold text-emerald-900 mb-1">متابعة شفافة</p>
                <p class="text-emerald-800/80">كل طلب له حالة وتاريخ — تعرف أين وصل ومتى يُتوقع الرد.</p>
            </div>
            <div class="rounded-xl bg-violet-50 border border-violet-100 p-4">
                <p class="font-bold text-violet-900 mb-1">قنوات واضحة</p>
                <p class="text-violet-800/80">كل نوع طلب له مسار مخصص (دعم، بلاغ موقع، ميزة…) لتسريع المعالجة.</p>
            </div>
        </div>
    </section>

    {{-- أنواع الحسابات --}}
    <section class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-3">أنواع حسابات البوابة</h2>
        <p class="text-gray-600 text-sm mb-5">قد لا ترى كل الأقسام حسب صلاحية حسابك — هذا طبيعي ومقصود لحماية البيانات.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">نوع الحساب</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-700">ما الذي تراه؟</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-900">مالك / كامل</td>
                        <td class="px-4 py-3 text-gray-600">كل الأقسام: مالية + تقنية + دعم.</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-900">محاسبي</td>
                        <td class="px-4 py-3 text-gray-600">الفواتير والمستندات والتقارير — دون طلبات تقنية (بلاغات، اجتماعات، ميزات).</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-semibold text-gray-900">تقني</td>
                        <td class="px-4 py-3 text-gray-600">الدعم والبلاغات والاجتماعات والميزات — دون تفاصيل الفواتير المالية.</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if($cPortal && $cPortal->portalRole() !== 'owner')
        <p class="mt-4 text-sm rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-indigo-900">
            <span class="font-bold">حسابك الحالي:</span>
            @if($cPortal->portalRole() === 'billing') محاسبي
            @elseif($cPortal->portalRole() === 'technical') تقني
            @else {{ $cPortal->portalRole() }}
            @endif
        </p>
        @endif
    </section>

    {{-- أقسام البورتال --}}
    <section class="mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-5">أقسام البورتال بالتفصيل</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">لوحة العميل</h3>
                    <a href="{{ route('client.dashboard') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">الصفحة الرئيسية: بطاقات إحصائية، تنبيهات «يحتاج إجراء منك»، تحليلات شهرية، فواتير قريبة الاستحقاق، وآخر الأنشطة.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">الإشعارات</h3>
                    <a href="{{ route('client.notifications') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">تحديثات التذاكر، البلاغات، الاجتماعات، التقارير، والفواتير — مع رابط مباشر للعنصر المعني.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">مشاريعي</h3>
                    <a href="{{ route('client.projects') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">مشاريعك الجارية والمكتملة مع الحالة والتقدم ومدير المشروع — للمتابعة دون التواصل اليدوي في كل استفسار.</p>
            </div>

            @if(!$cPortal || $cPortal->canAccessBilling())
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">فواتيري</h3>
                    <a href="{{ route('client.invoices') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">فواتير المشاريع (عادية) والفواتير المالية — المبالغ المتبقية، تواريخ الاستحقاق، وأي رابط دفع متاح.</p>
            </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">تقارير الخدمة</h3>
                    <a href="{{ route('client.service-reports') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">تقارير دورية يشاركها الفريق معك (صيانة، متابعة، تسليم…) مع إمكانية التنزيل.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">المستندات المشتركة</h3>
                    <a href="{{ route('client.documents') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">عقود، عروض أسعار، محاضر تسليم، وملفات رسمية يشاركها الفريق معك بأمان.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">تذاكر الدعم</h3>
                    <a href="{{ route('client.support.tickets') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">القناة الرئيسية للتواصل مع الفريق: استفسارات، مشاكل تشغيل، فواتير، طلبات عامة — مع محادثة ومتابعة حالة.</p>
            </div>

            @if(!$cPortal || $cPortal->canAccessTechnicalRequests())
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">ميزات وتحسينات النظام</h3>
                    <a href="{{ route('client.system-features.index') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">طلب ميزة جديدة أو تحسين على نظامك — يُصنَّف ضمن «مشاريع نظامك» مع سجل توثيقي طويل (مراجعة → تنفيذ → اكتمال).</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">بلاغات الموقع</h3>
                    <a href="{{ route('client.website-issues.index') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">عطل محدد في الموقع أو البوابة: صفحة لا تعمل، خطأ ظاهر، سلوك خاطئ — مع رابط الصفحة ولقطات شاشة.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">طلبات الاجتماعات</h3>
                    <a href="{{ route('client.meeting-requests.index') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">طلب اجتماع مع الفريق (أونلاين أو حضوري) مع أوقات مفضلة ومتابعة التأكيد والرابط.</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <h3 class="font-bold text-gray-900">التقويم</h3>
                    <a href="{{ route('client.calendar') }}" class="text-xs font-bold text-blue-600 hover:underline shrink-0">فتح ←</a>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">عرض المواعيد المؤكدة والاجتماعات القادمة في تقويم واحد.</p>
            </div>
            @endif
        </div>
    </section>

    {{-- الفروقات بين قنوات الطلبات --}}
    <section class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-2">الفرق بين تذاكر الدعم وميزات النظام وبلاغات الموقع</h2>
        <p class="text-sm text-gray-600 mb-6">اختيار القناة الصحيحة يُسرّع الرد ويضع طلبك عند الفريق المناسب.</p>

        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-bold text-gray-800">القسم</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-800">متى تستخدمه؟</th>
                        <th class="px-4 py-3 text-right font-bold text-gray-800">ما يميّزه</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="bg-amber-50/40">
                        <td class="px-4 py-3 font-bold text-amber-950">تذاكر الدعم</td>
                        <td class="px-4 py-3 text-gray-700">استفسار عام، مشكلة تشغيل، فواتير، أي طلب يحتاج متابعة مباشرة</td>
                        <td class="px-4 py-3 text-gray-700">محادثة، فئات (تقني / فواتير / عام)، أولوية، ربط بمشروع</td>
                    </tr>
                    <tr class="bg-blue-50/40">
                        <td class="px-4 py-3 font-bold text-blue-950">ميزات وتحسينات النظام</td>
                        <td class="px-4 py-3 text-gray-700">تريد ميزة جديدة أو تحسيناً على نظامك (ليس عطلاً في صفحة)</td>
                        <td class="px-4 py-3 text-gray-700">مشروع نظام + سجل توثيقي طويل، حالات مراجعة وتنفيذ</td>
                    </tr>
                    <tr class="bg-orange-50/40">
                        <td class="px-4 py-3 font-bold text-orange-950">بلاغات الموقع</td>
                        <td class="px-4 py-3 text-gray-700">عطل محدد في واجهة الموقع أو البوابة</td>
                        <td class="px-4 py-3 text-gray-700">رابط الصفحة + إرفاق صور (لقطات شاشة)</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="rounded-xl border-2 border-dashed border-gray-200 bg-gray-50 p-5">
            <p class="font-bold text-gray-900 mb-3">دليل سريع — أي قسم أختار؟</p>
            <ul class="space-y-2 text-sm text-gray-700">
                <li class="flex gap-2"><span class="text-amber-600 font-bold shrink-0">←</span> <span><strong>صفحة لا تعمل أو خطأ ظاهر؟</strong> → بلاغات الموقع</span></li>
                <li class="flex gap-2"><span class="text-blue-600 font-bold shrink-0">←</span> <span><strong>تريد ميزة أو تحسين على النظام؟</strong> → ميزات وتحسينات النظام</span></li>
                <li class="flex gap-2"><span class="text-emerald-600 font-bold shrink-0">←</span> <span><strong>سؤال، فاتورة، أو أي شيء آخر؟</strong> → تذاكر الدعم</span></li>
                <li class="flex gap-2"><span class="text-cyan-600 font-bold shrink-0">←</span> <span><strong>تريد اجتماعاً مع الفريق؟</strong> → طلبات الاجتماعات (أو من زر «طلب جديد» في الأعلى)</span></li>
            </ul>
        </div>

        <div class="flex flex-wrap gap-3 mt-6">
            <a href="{{ route('client.support.tickets.create') }}" class="px-4 py-2.5 rounded-xl bg-amber-600 text-white text-sm font-bold hover:bg-amber-700">+ تذكرة دعم</a>
            @if(!$cPortal || $cPortal->canAccessTechnicalRequests())
            <a href="{{ route('client.system-features.create') }}" class="px-4 py-2.5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700">+ طلب ميزة</a>
            <a href="{{ route('client.website-issues.create') }}" class="px-4 py-2.5 rounded-xl bg-orange-600 text-white text-sm font-bold hover:bg-orange-700">+ بلاغ موقع</a>
            <a href="{{ route('client.meeting-requests.create') }}" class="px-4 py-2.5 rounded-xl bg-cyan-600 text-white text-sm font-bold hover:bg-cyan-700">+ طلب اجتماع</a>
            @endif
        </div>
    </section>

    {{-- الشريط العلوي --}}
    <section class="mb-8 bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-3">الشريط العلوي وزر «طلب جديد»</h2>
        <p class="text-sm text-gray-600 leading-relaxed mb-4">
            في أعلى الصفحة تجد عنوان القسم الحالي، وزر <strong>طلب جديد</strong> يفتح قائمة سريعة لإنشاء:
        </p>
        <ul class="text-sm text-gray-700 space-y-2 pr-4 list-disc">
            <li>تذكرة دعم</li>
            @if(!$cPortal || $cPortal->canAccessTechnicalRequests())
            <li>بلاغ عن الموقع</li>
            <li>طلب اجتماع</li>
            @endif
        </ul>
        <p class="text-sm text-gray-500 mt-4">من أيقونة الحساب يمكنك الوصول للوحة، الإشعارات، شرح البورتال، وتسجيل الخروج.</p>
    </section>

    {{-- تواصل --}}
    <section class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-3">تحتاج مساعدة إضافية؟</h2>
        <p class="text-sm text-gray-600 mb-4">للاستفسارات المحاسبية أو العاجلة خارج البورتال:</p>
        <div class="flex flex-wrap gap-4 text-sm">
            <div class="rounded-lg bg-gray-50 border border-gray-200 px-4 py-3">
                <span class="text-gray-500 block text-xs mb-1">الهاتف</span>
                <span class="font-bold text-gray-900">{{ \App\Helpers\SettingsHelper::getCompanyPhone() ?: '—' }}</span>
            </div>
            <div class="rounded-lg bg-gray-50 border border-gray-200 px-4 py-3">
                <span class="text-gray-500 block text-xs mb-1">البريد</span>
                <span class="font-bold text-gray-900">{{ \App\Helpers\SettingsHelper::getCompanyEmail() ?: '—' }}</span>
            </div>
        </div>
        <a href="{{ route('website.contact') }}" class="inline-flex mt-5 px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-bold text-gray-800 hover:bg-gray-50 transition">
            صفحة اتصل بنا
        </a>
    </section>
</div>
@endsection
