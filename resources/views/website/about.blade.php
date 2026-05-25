@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — About')

@push('body-class')
 sv-cinematic-page
@endpush

@push('styles')
@include('website.partials.cinematic-assets')
@endpush

@section('content')
@php
  $cn = \App\Helpers\SettingsHelper::getCompanyName();
  $pillars = $pillars ?? [];
  $capabilities = $capabilities ?? [];
  $principles = $principles ?? [];
@endphp

<div class="sv-os" dir="ltr">
  {{-- Hero --}}
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-page-hero-inner">
        <div class="sv-eyebrow sv-display mx-auto">
          <span class="sv-eyebrow-dot"></span>
          About {{ $cn }}
        </div>
        <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black sv-display text-gray-900 leading-tight">
          We Don't Sell a Product.<br>
          <span class="sv-neon-sweep">We Engineer Your Company.</span>
        </h1>
        <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
          {{ $cn }} is a strategic AI &amp; software partner. We turn daily operations into clear processes, precise permissions, measurable data, and integrations that connect every department to your clients.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
          <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary w-full sm:w-auto">Book a Session</a>
          <a href="{{ route('website.services') }}" class="sv-btn sv-btn-ghost w-full sm:w-auto">Explore Services</a>
        </div>
      </div>
    </div>
  </section>

  {{-- Mission + capabilities --}}
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-start">
      <div>
        <div class="sv-section-label sv-display">What We Build</div>
        <h2 class="sv-section-title sv-display">An Operating System — Not Just a UI</h2>
        <p class="mt-4 text-gray-600 leading-relaxed">
          Our work starts inside your organization: we analyze how work actually flows, redesign procedures, then implement systems that teams use every day.
          The outcome is clear ownership, faster decisions, live reporting, and post-delivery support with enterprise standards.
        </p>

        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach($capabilities as $cap)
          <div class="sv-cap-card">
            <div class="text-[11px] font-extrabold uppercase tracking-wide text-orange-500">{{ $cap['label'] }}</div>
            <div class="mt-1 text-sm font-extrabold text-gray-900">{{ $cap['title'] }}</div>
            <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $cap['desc'] }}</p>
          </div>
          @endforeach
        </div>
      </div>

      <div class="sv-contact-panel lg:sticky lg:top-24">
        <div class="flex items-center justify-between gap-3 flex-wrap">
          <h3 class="text-xl font-extrabold text-gray-900">Get in Touch</h3>
          <span class="text-[10px] font-extrabold px-3 py-1.5 rounded-full border border-blue-100 bg-blue-50 text-blue-700">Enterprise</span>
        </div>

        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">Company</div>
            <div class="font-extrabold text-gray-900">{{ $cn }}</div>
          </div>
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">Email</div>
            <div class="font-extrabold text-gray-900 break-all">{{ \App\Helpers\SettingsHelper::getCompanyEmail() }}</div>
          </div>
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">Phone</div>
            <div class="font-extrabold text-gray-900">{{ \App\Helpers\SettingsHelper::getCompanyPhone() }}</div>
          </div>
          <div class="sv-contact-field sm:col-span-2">
            <div class="text-xs text-gray-500 mb-1">Address</div>
            <div class="font-extrabold text-gray-900">{{ \App\Helpers\SettingsHelper::getCompanyAddress() }}</div>
          </div>
        </div>

        <div class="mt-6 flex flex-col gap-3">
          <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary w-full">Contact Us</a>
          <a href="{{ route('client.login') }}" class="sv-btn sv-btn-ghost w-full">Client Portal</a>
        </div>
      </div>
    </div>
  </section>

  {{-- Pillars --}}
  <section class="sv-section sv-section--soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <div class="sv-section-label sv-display">Why Partner With Us</div>
        <h2 class="sv-section-title sv-display">A Digital Partner — Not a Vendor</h2>
        <p class="sv-section-desc mx-auto mt-3">We stay with you through operations and growth: consulting, build, and support with clear SLAs.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($pillars as $pillar)
        <div class="sv-pillar">
          <div class="mx-auto h-12 w-12 rounded-2xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #eff6ff, #fff7ed)">
            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          </div>
          <h3 class="text-lg font-extrabold text-gray-900">{{ $pillar['title'] }}</h3>
          <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $pillar['desc'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Principles --}}
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <div class="sv-section-label sv-display">How We Deliver</div>
      <h2 class="sv-section-title sv-display">How We Guarantee Results</h2>
      <p class="sv-section-desc mx-auto mt-3">Clear execution values so the system lives and scales inside your company.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($principles as $p)
      <div class="sv-svc-card">
        <div class="text-[11px] font-extrabold uppercase tracking-wide text-orange-500">{{ $p['label'] }}</div>
        <h3 class="mt-2 text-lg font-extrabold text-gray-900">{{ $p['title'] }}</h3>
        <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $p['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </section>

  {{-- Vision --}}
  <section class="sv-about">
    <div class="sv-section-label sv-display mb-4">Our Vision</div>
    <p class="sv-about-vision sv-display text-gray-800 max-w-3xl mx-auto">
      We engineer systems that turn companies into
      <span class="sv-about-glow-text">intelligent digital ecosystems.</span>
    </p>
    <p class="mt-6 text-gray-600 max-w-2xl mx-auto leading-relaxed px-4">
      {{ $cn }} doesn't hand off a project and disappear. We remain your partner through operations and growth — from strategy to build to long-term support.
    </p>
  </section>

  {{-- CTA --}}
  <section class="sv-cta-compact">
    <div class="max-w-3xl mx-auto text-center px-4 text-white">
      <h2 class="text-2xl sm:text-3xl font-black sv-display">Ready to Rebuild How You Operate?</h2>
      <p class="mt-4 text-blue-100 leading-relaxed">Book a free strategy session — no commitment required.</p>
      <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary w-full sm:w-auto" style="background:#fff;color:var(--sv-blue)">Start Now</a>
        <a href="{{ route('website.services') }}" class="sv-btn sv-btn-ghost w-full sm:w-auto text-white border-white/40 hover:bg-white/15 hover:text-white">View Services</a>
      </div>
    </div>
  </section>
</div>
@endsection
