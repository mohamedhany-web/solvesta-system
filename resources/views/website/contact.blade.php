@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' - تواصل')

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
        Contact & Consultation
      </span>
      <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black font-cairo text-gray-900" style="line-height:1.2">
        تواصل مع Solvesta
      </h1>
      <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl">
        اشرح لنا وضع شركتك أو التحدي التشغيلي — وسنقترح حلًا واضحًا بخارطة طريق وتنفيذ مرحلي.
      </p>
    </div>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
    <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-sm">
      <div class="flex items-center justify-between gap-3">
        <h2 class="text-xl font-extrabold text-gray-900">نموذج التواصل</h2>
        <span class="text-xs font-extrabold px-3 py-1.5 rounded-full border border-gray-200 bg-gray-50" style="color: {{ $tc }}">Enterprise</span>
      </div>

      @if(session('success'))
        <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 text-sm font-bold">
          {{ session('success') }}
        </div>
      @endif

      <form class="mt-6 space-y-4" action="{{ route('website.contact.submit') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="sm:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">نوع الطلب</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label class="flex items-center gap-2 px-4 py-3 rounded-2xl border border-gray-200 bg-white cursor-pointer hover:bg-gray-50">
                <input type="radio" name="type" value="contact" class="rounded border-gray-300" {{ old('type','consultation') === 'contact' ? 'checked' : '' }}>
                <span class="text-sm font-bold text-gray-800">تواصل عام</span>
              </label>
              <label class="flex items-center gap-2 px-4 py-3 rounded-2xl border border-gray-200 bg-white cursor-pointer hover:bg-gray-50">
                <input type="radio" name="type" value="consultation" class="rounded border-gray-300" {{ old('type','consultation') === 'consultation' ? 'checked' : '' }}>
                <span class="text-sm font-bold text-gray-800">حجز استشارة</span>
              </label>
            </div>
            @error('type')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl ring-brand focus:border-transparent @error('name') border-red-500 bg-red-50 @enderror">
            @error('name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الشركة</label>
            <input type="text" name="company" value="{{ old('company') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl ring-brand focus:border-transparent @error('company') border-red-500 bg-red-50 @enderror">
            @error('company')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">البريد</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl ring-brand focus:border-transparent @error('email') border-red-500 bg-red-50 @enderror">
            @error('email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">الهاتف</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl ring-brand focus:border-transparent @error('phone') border-red-500 bg-red-50 @enderror">
            @error('phone')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">الموضوع (اختياري)</label>
          <input type="text" name="subject" value="{{ old('subject') }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl ring-brand focus:border-transparent @error('subject') border-red-500 bg-red-50 @enderror">
          @error('subject')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">الرسالة</label>
          <textarea name="message" rows="6" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl ring-brand focus:border-transparent @error('message') border-red-500 bg-red-50 @enderror">{{ old('message') }}</textarea>
          @error('message')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
        </div>

        <button class="w-full px-6 py-3.5 rounded-2xl text-white font-extrabold btn-brand shadow-md hover:shadow-lg transition">إرسال الطلب</button>
        <p class="mt-3 text-xs text-gray-500">سيظهر الطلب داخل لوحة الأدمن في قسم الدعم الفني.</p>
      </form>
    </div>

    <div class="space-y-6">
      <div class="bg-white rounded-3xl border border-gray-200 p-8 shadow-sm">
        <h2 class="text-xl font-extrabold text-gray-900 mb-5">بيانات التواصل</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-700">
          <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5">
            <div class="text-xs text-gray-500 mb-1">الهاتف</div>
            <div class="font-extrabold">{{ \App\Helpers\SettingsHelper::getCompanyPhone() ?: '—' }}</div>
          </div>
          <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5">
            <div class="text-xs text-gray-500 mb-1">البريد</div>
            <div class="font-extrabold break-words">{{ \App\Helpers\SettingsHelper::getCompanyEmail() ?: '—' }}</div>
          </div>
          <div class="sm:col-span-2 bg-gray-50 rounded-2xl border border-gray-200 p-5">
            <div class="text-xs text-gray-500 mb-1">العنوان</div>
            <div class="font-extrabold">{{ \App\Helpers\SettingsHelper::getCompanyAddress() ?: '—' }}</div>
          </div>
        </div>
      </div>

      <div class="rounded-3xl bg-gray-950 p-8 text-white overflow-hidden relative">
        <div class="absolute -top-16 right-0 h-56 w-56 rounded-full blur-3xl opacity-25" style="background: {{ $tc }}"></div>
        <div class="relative">
          <div class="text-sm font-extrabold" style="color: {{ $tc }}">لشركات B2B</div>
          <h3 class="mt-2 text-2xl font-black font-cairo" style="line-height:1.25">جاهز نبدأ؟</h3>
          <p class="mt-3 text-gray-300 leading-relaxed">احجز جلسة قصيرة لشرح احتياجك، وسنقترح نطاق التنفيذ والمرحلة الأولى.</p>
          <a href="{{ route('website.contact') }}#form" class="mt-6 inline-flex w-full items-center justify-center px-6 py-3 rounded-2xl text-white font-extrabold btn-brand shadow-md hover:shadow-lg transition">
            ابدأ الآن
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

