@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — Contact')

@push('body-class')
 sv-cinematic-page
@endpush

@push('styles')
@include('website.partials.cinematic-assets')
@endpush

@section('content')
@php $cn = \App\Helpers\SettingsHelper::getCompanyName(); @endphp

<div class="sv-os" dir="ltr">
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-page-hero-inner">
        <div class="sv-eyebrow sv-display mx-auto">
          <span class="sv-eyebrow-dot"></span>
          Contact &amp; Consultation
        </div>
        <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black sv-display text-gray-900 leading-tight">
          Let's Talk About<br>
          <span class="sv-neon-sweep">Your Next System</span>
        </h1>
        <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
          Tell us about your company or operational challenge — we'll propose a clear solution with a roadmap and phased delivery.
        </p>
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="form">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-start">
      <div class="sv-form-panel" id="contact-form">
        <div class="flex items-center justify-between gap-3 flex-wrap">
          <h2 class="text-xl font-extrabold text-gray-900">Send a Request</h2>
          <span class="text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-blue-100 bg-blue-50 text-blue-700">Enterprise</span>
        </div>

        @if(session('success'))
          <div class="sv-alert-success mt-5">{{ session('success') }}</div>
        @endif

        <form class="mt-6 space-y-4" action="{{ route('website.contact.submit') }}" method="POST">
          @csrf

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Request type</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label class="sv-radio-card">
                <input type="radio" name="type" value="contact" class="rounded border-gray-300 text-blue-600" {{ old('type', 'consultation') === 'contact' ? 'checked' : '' }}>
                <span class="text-sm font-bold text-gray-800">General contact</span>
              </label>
              <label class="sv-radio-card">
                <input type="radio" name="type" value="consultation" class="rounded border-gray-300 text-blue-600" {{ old('type', 'consultation') === 'consultation' ? 'checked' : '' }}>
                <span class="text-sm font-bold text-gray-800">Book consultation</span>
              </label>
            </div>
            @error('type')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Name *</label>
              <input type="text" name="name" value="{{ old('name') }}" required class="sv-form-input @error('name') is-error @enderror">
              @error('name')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Company</label>
              <input type="text" name="company" value="{{ old('company') }}" class="sv-form-input @error('company') is-error @enderror">
              @error('company')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
              <input type="email" name="email" value="{{ old('email') }}" class="sv-form-input @error('email') is-error @enderror">
              @error('email')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Phone</label>
              <input type="text" name="phone" value="{{ old('phone') }}" class="sv-form-input @error('phone') is-error @enderror">
              @error('phone')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Subject (optional)</label>
            <input type="text" name="subject" value="{{ old('subject') }}" class="sv-form-input @error('subject') is-error @enderror">
            @error('subject')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Message *</label>
            <textarea name="message" rows="6" required class="sv-form-textarea @error('message') is-error @enderror">{{ old('message') }}</textarea>
            @error('message')<div class="mt-2 text-sm text-red-600">{{ $message }}</div>@enderror
          </div>

          <button type="submit" class="sv-btn sv-btn-primary w-full">Submit Request</button>
          <p class="text-xs text-gray-500 text-center">Your request appears in the admin support queue.</p>
        </form>
      </div>

      <div class="space-y-6 lg:sticky lg:top-24">
        <div class="sv-contact-panel">
          <h2 class="text-xl font-extrabold text-gray-900 mb-5">Contact Details</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div class="sv-contact-field">
              <div class="text-xs text-gray-500 mb-1">Phone</div>
              <div class="font-extrabold text-gray-900">{{ \App\Helpers\SettingsHelper::getCompanyPhone() ?: '—' }}</div>
            </div>
            <div class="sv-contact-field">
              <div class="text-xs text-gray-500 mb-1">Email</div>
              <div class="font-extrabold text-gray-900 break-all">{{ \App\Helpers\SettingsHelper::getCompanyEmail() ?: '—' }}</div>
            </div>
            <div class="sv-contact-field sm:col-span-2">
              <div class="text-xs text-gray-500 mb-1">Address</div>
              <div class="font-extrabold text-gray-900">{{ \App\Helpers\SettingsHelper::getCompanyAddress() ?: '—' }}</div>
            </div>
          </div>
        </div>

        <div class="sv-panel-dark">
          <div class="text-sm font-extrabold text-orange-300">For B2B Teams</div>
          <h3 class="mt-2 text-2xl font-black sv-display leading-tight">Ready to Start?</h3>
          <p class="mt-3 text-blue-100 text-sm leading-relaxed">Book a short session to explain your needs — we'll propose scope and phase one.</p>
          <a href="#contact-form" class="sv-btn sv-btn-primary mt-6 w-full" style="background:#fff;color:var(--sv-blue)">Start Now</a>
        </div>

        <div class="flex flex-col gap-3">
          <a href="{{ route('website.services') }}" class="sv-btn sv-btn-ghost w-full text-center">View Services</a>
          <a href="{{ route('website.pricing') }}" class="sv-btn sv-btn-ghost w-full text-center">Enterprise Solutions</a>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
