@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' - التدريب')

@section('content')
@php
  $tc = \App\Helpers\SettingsHelper::getThemeColor();
  $cn = \App\Helpers\SettingsHelper::getCompanyName();
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-gray-50 to-white border-b border-gray-100">
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
    <div class="absolute -top-24 right-0 h-72 w-72 rounded-full blur-3xl opacity-15 translate-x-1/3" style="background: {{ $tc }}"></div>
    <div class="absolute bottom-0 left-0 h-72 w-72 rounded-full blur-3xl opacity-10 -translate-x-1/3" style="background: {{ $tc }}"></div>
  </div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    <div class="max-w-3xl">
      <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 bg-white text-sm font-bold text-gray-700 shadow-sm">
        <span class="h-2 w-2 rounded-full" style="background: {{ $tc }}"></span>
        Solvesta Academy
      </span>
      <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black font-cairo text-gray-900" style="line-height:1.2">
        التدريب والـ Internship
      </h1>
      <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl">
        عند فتح Internship في {{ $cn }} ستجد البرامج المتاحة هنا للتسجيل. هدفنا تدريب عملي على بناء أنظمة B2B حقيقية: تشغيل، صلاحيات، بيانات، تكاملات، ودعم ما بعد البيع.
      </p>
    </div>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
      {{ session('success') }}
    </div>
  @endif

  <div class="flex items-end justify-between gap-4 mb-8">
    <div>
      <h2 class="text-2xl font-black font-cairo text-gray-900">الفرص المتاحة</h2>
      <p class="text-gray-600 mt-2">برامج مخطط لها أو جارية الآن.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($trainings as $t)
      <a href="{{ route('website.training.show', $t) }}" class="group bg-white rounded-2xl border border-gray-200 p-7 hover:shadow-xl hover:border-gray-300 transition-all">
        <div class="flex items-center justify-between gap-3">
          <div class="text-lg font-extrabold text-gray-900 group-hover:text-gray-900">{{ $t->title }}</div>
          <span class="text-xs font-bold px-3 py-1 rounded-full"
                style="background: color-mix(in srgb, {{ $tc }} 12%, transparent); color: color-mix(in srgb, {{ $tc }} 70%, #000);">
            {{ $t->status === 'planned' ? 'قريبًا' : 'جاري' }}
          </span>
        </div>
        <div class="text-sm text-gray-600 mt-3 leading-relaxed line-clamp-3">
          {{ $t->description }}
        </div>
        <div class="mt-5 text-sm text-gray-600">
          <div><span class="text-gray-500">القسم:</span> {{ $t->department?->name ?? '—' }}</div>
          <div class="mt-1"><span class="text-gray-500">المدة:</span> {{ $t->start_date?->format('Y-m-d') }} → {{ $t->end_date?->format('Y-m-d') }}</div>
        </div>
        <div class="mt-6 inline-flex items-center gap-2 text-sm font-extrabold" style="color: {{ $tc }}">
          فتح التفاصيل والتسجيل
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </div>
      </a>
    @empty
      <div class="col-span-full bg-white rounded-2xl border border-gray-200 p-10 text-center text-gray-600">
        لا توجد فرص تدريب متاحة الآن. تابع هذه الصفحة عند فتح Internship جديد.
      </div>
    @endforelse
  </div>
</section>
@endsection

