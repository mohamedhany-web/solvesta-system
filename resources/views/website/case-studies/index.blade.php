@extends('website.layout')

@section('title', 'نماذج من أعمالنا - ' . \App\Helpers\SettingsHelper::getCompanyName())

@section('content')
@php
  $tc = \App\Helpers\SettingsHelper::getThemeColor();
  $cn = \App\Helpers\SettingsHelper::getCompanyName();
@endphp

<section class="bg-gradient-to-b from-gray-50 to-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    <div class="max-w-3xl mx-auto text-center">
      <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-white text-sm font-bold text-gray-700 shadow-sm">
        <span class="h-2 w-2 rounded-full" style="background:{{ $tc }}"></span>
        Case Studies
      </span>
      <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black font-cairo text-gray-900" style="line-height:1.2">
        أمثلة واقعية… تُثبت أننا نبني المؤسسة رقميًا
      </h1>
      <p class="mt-5 text-gray-600 leading-relaxed">
        هذه نماذج من حلول Solvesta: نأخذ مشكلة تشغيلية حقيقية داخل شركة، ثم نبني نظامًا متكاملًا — ونضيف تكاملات AI تجعل القرار أسرع والتشغيل أذكى.
      </p>
    </div>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($caseStudies as $c)
      <a href="{{ route('website.case-studies.show', $c['slug']) }}" class="block bg-white rounded-2xl border border-gray-200 p-7 hover:shadow-xl hover:border-gray-300 transition-all">
        <div class="flex items-center justify-between gap-3">
          <span class="text-xs font-bold px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50 text-gray-700">{{ $c['sector'] }}</span>
          <span class="text-xs font-extrabold" style="color:{{ $tc }}">AI + Systems</span>
        </div>
        <h2 class="mt-4 text-lg font-extrabold text-gray-900">{{ $c['title'] }}</h2>
        <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ $c['excerpt'] }}</p>
        <div class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold" style="color:{{ $tc }}">
          اقرأ التفاصيل
          <span aria-hidden="true">←</span>
        </div>
      </a>
    @endforeach
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
  <div class="rounded-3xl bg-gray-950 p-8 sm:p-12 text-white overflow-hidden relative">
    <div class="absolute -top-20 right-0 w-72 h-72 rounded-full opacity-25 blur-3xl" style="background:{{ $tc }}"></div>
    <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
      <div>
        <h3 class="text-2xl sm:text-3xl font-black font-cairo" style="line-height:1.25">نبدأ بحالة شركتك؟</h3>
        <p class="mt-3 text-gray-300 leading-relaxed">صف لنا المشكلة التشغيلية… ونقترح حلًا واضحًا بخارطة طريق وتنفيذ مرحلي.</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-3 lg:justify-end">
        <a href="{{ route('website.contact') }}" class="inline-flex items-center justify-center px-7 py-3.5 rounded-2xl text-white font-extrabold btn-brand shadow-lg hover:shadow-xl transition">
          احجز جلسة
        </a>
        <a href="{{ route('client.login') }}" class="inline-flex items-center justify-center px-7 py-3.5 rounded-2xl border border-white/20 bg-white/10 hover:bg-white/15 font-bold transition">
          بوابة العملاء
        </a>
      </div>
    </div>
  </div>
</section>
@endsection

