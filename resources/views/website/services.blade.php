@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' - الخدمات')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
  <div class="text-center mb-10">
    <h1 class="text-3xl sm:text-4xl font-extrabold font-cairo text-gray-900">الخدمات</h1>
    <p class="mt-3 text-gray-600 text-lg">منظومة متكاملة تغطي التشغيل الداخلي وخدمة العملاء وما بعد البيع.</p>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @php
      $cards = [
        ['title'=>'إدارة المشاريع والمهام','desc'=>'متابعة التقدم، توزيع المهام، تقارير الأداء وصلاحيات حسب الدور.','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5'],
        ['title'=>'الموارد البشرية','desc'=>'موظفين، حضور وانصراف، إجازات، رواتب، وتحليلات.','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857'],
        ['title'=>'الفواتير والمدفوعات','desc'=>'فواتير عادية ومالية، متابعة الرصيد، وتحديثات حالة الدفع.','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        ['title'=>'بوابة العميل','desc'=>'العميل يشاهد بياناته ومشاريعه وفواتيره ويقدم طلبات الدعم.','icon'=>'M12 11c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3zm8 0c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3z'],
        ['title'=>'ما بعد البيع (الدعم)','desc'=>'تذاكر أعطال/طلبات، تعيين للموظفين، وتواصل منظم مع العميل.','icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
        ['title'=>'تقارير وتحليلات','desc'=>'مؤشرات تشغيل ومالية، تقارير قابلة للطباعة والتصدير.','icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z'],
      ];
    @endphp

    @foreach($cards as $c)
      <div class="bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg transition">
        <div class="h-12 w-12 rounded-xl flex items-center justify-center mb-4" style="background: color-mix(in srgb, {{ $themeColor }} 15%, transparent)">
          <svg class="h-6 w-6" style="color: {{ $themeColor }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $c['icon'] }}" />
          </svg>
        </div>
        <h3 class="text-lg font-extrabold text-gray-900">{{ $c['title'] }}</h3>
        <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $c['desc'] }}</p>
      </div>
    @endforeach
  </div>

  <div class="mt-12 text-center">
    <a href="{{ route('website.contact') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-white font-extrabold btn-brand shadow-md hover:shadow-lg transition">اطلب عرض سعر</a>
  </div>
</section>
@endsection

