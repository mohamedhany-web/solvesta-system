@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' - حلول الشركات')

@section('content')
@php
  $tc = \App\Helpers\SettingsHelper::getThemeColor();
  $cn = \App\Helpers\SettingsHelper::getCompanyName();
@endphp

{{-- Hero --}}
<section class="relative overflow-hidden bg-gradient-to-b from-gray-50 to-white border-b border-gray-100">
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
    <div class="absolute -top-24 right-0 h-72 w-72 rounded-full blur-3xl opacity-15 translate-x-1/3" style="background: {{ $tc }}"></div>
    <div class="absolute bottom-0 left-0 h-72 w-72 rounded-full blur-3xl opacity-10 -translate-x-1/3" style="background: {{ $tc }}"></div>
  </div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    <div class="max-w-3xl">
      <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-white text-sm font-bold text-gray-700 shadow-sm">
        <span class="h-2 w-2 rounded-full" style="background: {{ $tc }}"></span>
        Enterprise B2B
      </span>
      <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black font-cairo text-gray-900" style="line-height:1.2">
        حلول الشركات — بناء مؤسسة رقمية قابلة للتوسع
      </h1>
      <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl">
        في {{ $cn }} لا نبيع “باقة أسعار”. نحدد نطاقًا واضحًا ثم نبني منظومة تشغيل: إجراءات، صلاحيات، بيانات، تكاملات، ودعم ما بعد البيع — بنتائج قابلة للقياس.
      </p>

      <div class="mt-8 flex flex-col sm:flex-row gap-3">
        <a href="{{ route('website.contact') }}" class="inline-flex items-center justify-center px-7 py-3.5 rounded-2xl text-white font-extrabold btn-brand shadow-lg hover:shadow-xl transition">
          احجز جلسة
        </a>
        <a href="{{ route('website.case-studies.index') }}" class="inline-flex items-center justify-center px-7 py-3.5 rounded-2xl border border-gray-200 bg-white hover:bg-gray-50 font-bold text-gray-800 transition">
          نماذج الأعمال
        </a>
      </div>
    </div>
  </div>
</section>

{{-- Solution tracks --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
  <div class="text-center mb-14">
    <h2 class="text-3xl sm:text-4xl font-black font-cairo text-gray-900">مسارات الحلول</h2>
    <p class="mt-4 text-gray-600 max-w-2xl mx-auto leading-relaxed">اختر ما تحتاجه شركتك الآن… ثم نتوسع معك بإطلاق مرحلي.</p>
  </div>

  @php
    $tracks = [
      ['title'=>'منظومة تشغيل (ERP/CRM)','desc'=>'مشاريع + مهام + فرق + اعتمادات + تقارير تشغيلية.'],
      ['title'=>'مالية وتحصيل','desc'=>'فواتير + دفعات + أرصدة + تقارير + متابعة متأخرات.'],
      ['title'=>'بوابة العميل','desc'=>'وصول آمن لعميلك لمتابعة المشاريع والفواتير والدعم.'],
      ['title'=>'ما بعد البيع (Support + SLA)','desc'=>'تذاكر منظمة، تعيين للأقسام، تقارير حتى الإغلاق.'],
      ['title'=>'حوكمة وصلاحيات','desc'=>'RBAC + Audit + سياسات وصول ومسارات قرار.'],
      ['title'=>'AI داخل النظام','desc'=>'تصنيف + تلخيص + تنبؤ + تنبيهات استباقية.'],
    ];
  @endphp

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($tracks as $t)
      <div class="bg-white rounded-2xl border border-gray-200 p-7 hover:shadow-xl hover:border-gray-300 transition-all">
        <div class="h-12 w-12 rounded-xl flex items-center justify-center mb-5" style="background: color-mix(in srgb, {{ $tc }} 12%, transparent)">
          <div class="h-2.5 w-2.5 rounded-full" style="background: {{ $tc }}"></div>
        </div>
        <h3 class="text-lg font-extrabold text-gray-900 mb-2">{{ $t['title'] }}</h3>
        <p class="text-sm text-gray-600 leading-relaxed">{{ $t['desc'] }}</p>
      </div>
    @endforeach
  </div>
</section>

{{-- Delivery model --}}
<section class="bg-gray-50 border-y border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
      <div class="bg-white rounded-3xl border border-gray-200 p-8">
        <h2 class="text-2xl font-black font-cairo text-gray-900 mb-4">منهجية التنفيذ</h2>
        <div class="space-y-3 text-sm text-gray-700 leading-relaxed">
          <div class="flex items-start gap-2">
            <span class="mt-1 h-2 w-2 rounded-full" style="background: {{ $tc }}"></span>
            <div><span class="font-bold">اكتشاف وتشخيص:</span> تحليل دورة العمل الحالية وتحديد نقاط الألم والأولويات.</div>
          </div>
          <div class="flex items-start gap-2">
            <span class="mt-1 h-2 w-2 rounded-full" style="background: {{ $tc }}"></span>
            <div><span class="font-bold">تصميم بنية تشغيلية:</span> إجراءات + صلاحيات + نموذج بيانات + مسارات اعتمادات.</div>
          </div>
          <div class="flex items-start gap-2">
            <span class="mt-1 h-2 w-2 rounded-full" style="background: {{ $tc }}"></span>
            <div><span class="font-bold">تطبيق وإطلاق مرحلي:</span> تنفيذ سريع ثم توسع تدريجي مع تدريب الفرق.</div>
          </div>
          <div class="flex items-start gap-2">
            <span class="mt-1 h-2 w-2 rounded-full" style="background: {{ $tc }}"></span>
            <div><span class="font-bold">قياس وتحسين:</span> KPIs + تقارير + تحسينات مستمرة بناءً على البيانات.</div>
          </div>
        </div>
      </div>

      <div class="rounded-3xl border border-gray-200 bg-gray-950 p-8 text-white relative overflow-hidden">
        <div class="absolute -top-16 right-0 h-56 w-56 rounded-full blur-3xl opacity-25" style="background: {{ $tc }}"></div>
        <div class="relative">
          <h2 class="text-2xl font-black font-cairo mb-4">نتعهد بالاستمرارية</h2>
          <p class="text-gray-200 leading-relaxed">
            دعم ما بعد البيع ليس “إضافة” — هو جزء من البناء. تذاكر منظمة، تعيين للأقسام، تقارير، ومؤشرات SLA.
          </p>
          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
              <div class="text-sm font-extrabold">SLA واضح</div>
              <div class="mt-1 text-sm text-gray-300">وقت استجابة + تتبع حالة</div>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
              <div class="text-sm font-extrabold">تقارير مهنية</div>
              <div class="mt-1 text-sm text-gray-300">حتى إغلاق التذكرة</div>
            </div>
          </div>
          <div class="mt-8 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('website.contact') }}" class="inline-flex w-full justify-center px-6 py-3 rounded-2xl text-white font-extrabold btn-brand shadow-md hover:shadow-lg transition">
              اطلب جلسة تعريف
            </a>
            <a href="{{ route('website.services') }}" class="inline-flex w-full justify-center px-6 py-3 rounded-2xl border border-white/20 bg-white/10 hover:bg-white/15 font-bold transition">
              استعرض الخدمات
            </a>
          </div>
          <p class="mt-4 text-xs text-gray-400">التنفيذ والتكلفة حسب نطاق شركتك واحتياجاتها.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Quick guidance cards --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-7">
      <div class="text-sm font-extrabold text-gray-900 mb-2">مناسب لـ</div>
      <div class="text-sm text-gray-600 leading-relaxed">الشركات التي تريد توحيد التشغيل والمالية والدعم في منصة واحدة.</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-7">
      <div class="text-sm font-extrabold text-gray-900 mb-2">النتيجة</div>
      <div class="text-sm text-gray-600 leading-relaxed">شفافية تشغيلية، تقارير لحظية، واستجابة أسرع مع ما بعد البيع.</div>
    </div>
    <div class="bg-white rounded-2xl border border-gray-200 p-7">
      <div class="text-sm font-extrabold text-gray-900 mb-2">الخطوة التالية</div>
      <div class="text-sm text-gray-600 leading-relaxed">جلسة تعريف لتحديد النطاق وخارطة طريق التنفيذ المرحلي.</div>
    </div>
  </div>
</section>
@endsection

