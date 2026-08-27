@extends('layouts.app')

@section('page-title', 'دليل نظام المبيعات')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'دليل نظام المبيعات',
        'subtitle' => 'شرح مساحة عملك خطوة بخطوة — من Lead حتى العقد والفاتورة',
        'icon' => 'doc',
    ])

    {{-- مسار العمل --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-8 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 font-tajawal">مسار البيع المعتمد</h2>
        <p class="text-gray-600 mb-6 font-tajawal">لا تُنشأ العقود أو الفواتير من قوائم منفصلة — كل شيء يمر عبر الفرصة (Pipeline) داخل صفحة البيع.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            @foreach([
                ['step' => '1', 'title' => 'Lead', 'desc' => 'تسجيل عميل محتمل جديد', 'route' => route('leads.create'), 'label' => 'إضافة Lead'],
                ['step' => '2', 'title' => 'Pipeline', 'desc' => 'تحويل Lead لفرصة ومتابعة المراحل', 'route' => route('sales.index'), 'label' => 'الفرص'],
                ['step' => '3', 'title' => 'Pre-Sales', 'desc' => 'تقدير تكلفة وإصدار Proposal', 'route' => route('pre-sales.index'), 'label' => 'العروض'],
                ['step' => '4', 'title' => 'عميل', 'desc' => 'إدارة محفظة عملائك فقط', 'route' => route('clients.index'), 'label' => 'عملائي'],
            ] as $item)
            <div class="rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full text-white text-sm font-bold mb-3"
                      style="background: {{ $themeColor }};">{{ $item['step'] }}</span>
                <h3 class="font-bold text-gray-900 mb-1">{{ $item['title'] }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ $item['desc'] }}</p>
                <a href="{{ $item['route'] }}" class="text-sm font-bold" style="color: {{ $themeColor }};">{{ $item['label'] }} ←</a>
            </div>
            @endforeach
        </div>

        <div class="mt-6 p-5 rounded-xl border-2 border-dashed" style="border-color: {{ $themeColor }}40; background: {{ $themeColor }}08;">
            <h3 class="font-bold text-gray-900 mb-2">بعد قبول العرض (Proposal accepted)</h3>
            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 font-tajawal">
                <li>افتح الفرصة من <strong>Pipeline</strong> → قسم «سلسلة التدفق التجاري».</li>
                <li>اضغط <strong>إنشاء عقد</strong> (مسودة عقد مرتبطة بالفرصة).</li>
                <li>بعد العقد: <strong>فاتورة 50% مقدماً</strong> من نفس الصفحة (وليس من قائمة الفواتير).</li>
                <li>يمكنك <strong>عرض</strong> العقد أو الفاتورة من الرابط داخل الفرصة فقط.</li>
            </ol>
        </div>
    </div>

    {{-- شرح القائمة --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-tajawal">عناصر القائمة الجانبية</h2>
            <dl class="space-y-4 text-sm font-tajawal">
                <div>
                    <dt class="font-bold text-gray-900">لوحة المبيعات</dt>
                    <dd class="text-gray-600 mt-1">أهدافك، KPIs، وإنجازاتك الشهرية — صفحتك الرئيسية.</dd>
                </div>
                <div>
                    <dt class="font-bold text-gray-900">Leads</dt>
                    <dd class="text-gray-600 mt-1">عملاء محتملون جدد قبل التأهيل. أنت ترى فقط Leads المسندة لك.</dd>
                </div>
                <div>
                    <dt class="font-bold text-gray-900">Pipeline — الفرص</dt>
                    <dd class="text-gray-600 mt-1">صفقات نشطة: تأهيل، عروض، عقود، وفواتير — كلها من هنا.</dd>
                </div>
                <div>
                    <dt class="font-bold text-gray-900">Pre-Sales</dt>
                    <dd class="text-gray-600 mt-1">تقديرات التكلفة والـ Proposals المرسلة للعملاء.</dd>
                </div>
                <div>
                    <dt class="font-bold text-gray-900">عملائي</dt>
                    <dd class="text-gray-600 mt-1">العملاء المسجلون في محفظتك فقط.</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4 font-tajawal">ما لا يظهر لك (عمداً)</h2>
            <ul class="space-y-3 text-sm text-gray-700 font-tajawal">
                <li class="flex gap-2"><span class="text-red-500">✗</span> <span><strong>قائمة العقود</strong> — العقد يُنشأ من الفرصة بعد قبول العرض.</span></li>
                <li class="flex gap-2"><span class="text-red-500">✗</span> <span><strong>قائمة الفواتير</strong> — الفاتورة تُنشأ من الفرصة/العقد (دفعة مقدمة).</span></li>
                <li class="flex gap-2"><span class="text-red-500">✗</span> <span><strong>HR، المحاسبة، Marketing، BD</strong> — خارج نطاق مندوب المبيعات.</span></li>
                <li class="flex gap-2"><span class="text-red-500">✗</span> <span><strong>بيانات زملاء المبيعات</strong> — كل مندوب يرى محفظته فقط.</span></li>
            </ul>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-8 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4 font-tajawal">لوحة المبيعات والأهداف</h2>
        <p class="text-gray-600 text-sm font-tajawal mb-4">
            من <a href="{{ route('sales.dashboard') }}" class="font-bold" style="color: {{ $themeColor }};">لوحة المبيعات</a>
            تتابع: Leads جديدة، فرص مفتوحة، صفقات مغلقة، والتقدم نحو هدفك الشهري.
            لا يمكنك عرض لوحة مندوب آخر — الأرقام خاصة بك فقط.
        </p>
    </div>

    {{-- أسئلة --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-4 font-tajawal">أسئلة شائعة</h2>
        <div class="space-y-4 text-sm font-tajawal">
            <details class="group border border-gray-200 rounded-xl p-4">
                <summary class="font-bold cursor-pointer text-gray-900">كيف أنشئ فاتورة؟</summary>
                <p class="mt-2 text-gray-600">من Pipeline → افتح الفرصة → بعد إنشاء العقد اضغط «فاتورة 50% مقدماً». لا تستخدم أي زر «فاتورة جديدة» منفصل.</p>
            </details>
            <details class="group border border-gray-200 rounded-xl p-4">
                <summary class="font-bold cursor-pointer text-gray-900">كيف أنشئ عقداً؟</summary>
                <p class="mt-2 text-gray-600">من صفحة الفرصة بعد موافقة العميل على الـ Proposal → «إنشاء عقد».</p>
            </details>
            <details class="group border border-gray-200 rounded-xl p-4">
                <summary class="font-bold cursor-pointer text-gray-900">لماذا لا أرى كل عملاء الشركة؟</summary>
                <p class="mt-2 text-gray-600">النظام يعزل بيانات كل مندوب — ترى فقط العملاء والفرص والـ Leads المسندة لك.</p>
            </details>
            <details class="group border border-gray-200 rounded-xl p-4">
                <summary class="font-bold cursor-pointer text-gray-900">من أتواصل معه للدعم؟</summary>
                <p class="mt-2 text-gray-600">استخدم <a href="{{ route('messages.index') }}" class="font-bold" style="color: {{ $themeColor }};">الرسائل</a> أو تواصل مع مدير المبيعات / الإدارة.</p>
            </details>
        </div>
    </div>
</div>
@endsection
