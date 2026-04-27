@extends('website.layout')

@section('title', ($case['title'] ?? 'Case Study') . ' - ' . \App\Helpers\SettingsHelper::getCompanyName())

@section('content')
@php
  $tc = \App\Helpers\SettingsHelper::getThemeColor();
@endphp

<section class="bg-gradient-to-b from-gray-50 to-white border-b border-gray-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-18">
    <div class="max-w-3xl">
      <a href="{{ route('website.case-studies.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-gray-900">
        <span aria-hidden="true">→</span>
        العودة إلى نماذج الأعمال
      </a>
      <div class="mt-6 flex items-center gap-3 flex-wrap">
        <span class="text-xs font-bold px-3 py-1.5 rounded-full border border-gray-200 bg-white text-gray-700">{{ $case['sector'] ?? 'قطاع' }}</span>
        <span class="text-xs font-extrabold px-3 py-1.5 rounded-full border border-gray-200 bg-white" style="color:{{ $tc }}">AI Integration</span>
      </div>
      <h1 class="mt-5 text-3xl sm:text-4xl lg:text-5xl font-black font-cairo text-gray-900" style="line-height:1.2">
        {{ $case['title'] ?? '' }}
      </h1>
      <p class="mt-5 text-gray-600 leading-relaxed max-w-2xl">
        {{ $case['excerpt'] ?? '' }}
      </p>
    </div>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-2xl border border-gray-200 p-7">
        <div class="text-xs font-bold mb-2" style="color:{{ $tc }}">المشكلة</div>
        <div class="text-gray-700 leading-relaxed">{{ $case['problem'] ?? '' }}</div>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 p-7">
        <div class="text-xs font-bold mb-3" style="color:{{ $tc }}">النظام الذي قمنا ببنائه</div>
        <ul class="space-y-2 text-gray-700">
          @foreach(($case['built'] ?? []) as $b)
            <li class="flex items-start gap-2">
              <span class="mt-2 h-2 w-2 rounded-full" style="background:{{ $tc }}"></span>
              <span class="leading-relaxed">{{ $b }}</span>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 p-7">
        <div class="text-xs font-bold mb-3" style="color:{{ $tc }}">تكاملات الذكاء الاصطناعي (AI)</div>
        <ul class="space-y-2 text-gray-700">
          @foreach(($case['ai'] ?? []) as $a)
            <li class="flex items-start gap-2">
              <span class="mt-2 h-2 w-2 rounded-full bg-gray-900"></span>
              <span class="leading-relaxed">{{ $a }}</span>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="bg-gray-50 rounded-2xl border border-gray-200 p-7">
        <div class="text-xs font-bold mb-3" style="color:{{ $tc }}">النتائج</div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          @foreach(($case['outcomes'] ?? []) as $o)
            <div class="bg-white rounded-2xl border border-gray-200 p-5">
              <div class="text-sm font-extrabold text-gray-900 leading-relaxed">{{ $o }}</div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <aside class="space-y-4">
      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="text-sm font-extrabold text-gray-900">هل تريد حالة مشابهة؟</div>
        <p class="mt-2 text-sm text-gray-600 leading-relaxed">صف لنا التحديات التشغيلية داخل شركتك وسنقترح نظامًا مناسبًا مع AI.</p>
        <a href="{{ route('website.contact') }}" class="mt-4 inline-flex w-full items-center justify-center px-5 py-3 rounded-2xl text-white font-extrabold btn-brand shadow-md hover:shadow-lg transition">
          احجز جلسة
        </a>
      </div>

      <div class="bg-white rounded-2xl border border-gray-200 p-6">
        <div class="text-sm font-extrabold text-gray-900 mb-3">حالات أخرى</div>
        <div class="space-y-2">
          @foreach(($caseStudies ?? []) as $c)
            @continue(($c['slug'] ?? null) === ($case['slug'] ?? null))
            <a href="{{ route('website.case-studies.show', $c['slug']) }}" class="block px-4 py-3 rounded-2xl border border-gray-200 hover:bg-gray-50 transition">
              <div class="text-xs text-gray-500">{{ $c['sector'] }}</div>
              <div class="text-sm font-extrabold text-gray-900 mt-0.5">{{ $c['title'] }}</div>
            </a>
          @endforeach
        </div>
      </div>
    </aside>

  </div>
</section>
@endsection

