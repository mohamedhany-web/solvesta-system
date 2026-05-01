@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' - ' . $training->title)

@section('content')
@php
  $tc = \App\Helpers\SettingsHelper::getThemeColor();
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-gray-50 to-white border-b border-gray-100">
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
    <div class="absolute -top-24 right-0 h-72 w-72 rounded-full blur-3xl opacity-15 translate-x-1/3" style="background: {{ $tc }}"></div>
    <div class="absolute bottom-0 left-0 h-72 w-72 rounded-full blur-3xl opacity-10 -translate-x-1/3" style="background: {{ $tc }}"></div>
  </div>

  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 sm:py-16">
    <a href="{{ route('website.training') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-700 hover:text-gray-900 transition">
      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
      رجوع إلى التدريب
    </a>
    <h1 class="mt-5 text-3xl sm:text-4xl font-black font-cairo text-gray-900">{{ $training->title }}</h1>
    <p class="mt-4 text-gray-600 leading-relaxed max-w-3xl whitespace-pre-wrap">{{ $training->description }}</p>
  </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
  @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl" role="alert">
      <p class="font-bold mb-2">تعذر إرسال الطلب:</p>
      <ul class="list-disc list-inside text-sm space-y-1">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-200 p-6">
      <div class="text-sm font-extrabold text-gray-900 mb-3">تفاصيل سريعة</div>
      <div class="space-y-2 text-sm text-gray-700">
        <div><span class="text-gray-500">القسم:</span> {{ $training->department?->name ?? '—' }}</div>
        <div><span class="text-gray-500">المدرب:</span> {{ $training->instructor?->name ?? '—' }}</div>
        <div><span class="text-gray-500">البداية:</span> {{ $training->start_date?->format('Y-m-d') }}</div>
        <div><span class="text-gray-500">النهاية:</span> {{ $training->end_date?->format('Y-m-d') }}</div>
        <div><span class="text-gray-500">الحد الأقصى:</span> {{ $training->max_participants }}</div>
      </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-8">
      <h2 class="text-2xl font-black font-cairo text-gray-900 mb-2">التسجيل في المنحة التدريبية</h2>
      <p class="text-gray-600 mb-6">املأ البيانات التالية وسيتم مراجعة الطلب والتواصل معك.</p>

      <form action="{{ route('website.training.apply', $training) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">الاسم بالكامل *</label>
            <input name="full_name" value="{{ old('full_name') }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">البريد الإلكتروني *</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">رقم الهاتف</label>
            <input name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">الجامعة</label>
            <input name="university" value="{{ old('university') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">التخصص</label>
            <input name="major" value="{{ old('major') }}" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">المستوى</label>
            <input name="level" value="{{ old('level') }}" placeholder="Student / Fresh Grad" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">LinkedIn</label>
            <input name="linkedin_url" value="{{ old('linkedin_url') }}" placeholder="https://linkedin.com/in/..." class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Portfolio</label>
            <input name="portfolio_url" value="{{ old('portfolio_url') }}" placeholder="https://..." class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
          </div>
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">رسالة (اختياري)</label>
          <textarea name="message" rows="4" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">{{ old('message') }}</textarea>
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-2">السيرة الذاتية (PDF) (اختياري)</label>
          <input type="file" name="cv" accept=".pdf,.doc,.docx" class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50">
          <p class="text-xs text-gray-500 mt-2">حد أقصى 10MB.</p>
        </div>

        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-7 py-3.5 rounded-2xl text-white font-extrabold btn-brand shadow-lg hover:shadow-xl transition">
          إرسال طلب التسجيل
        </button>
      </form>
    </div>
  </div>
</section>
@endsection

