@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — Enterprise')

@push('body-class')
 sv-cinematic-page
@endpush

@push('styles')
@include('website.partials.cinematic-assets')
@endpush

@section('content')
@php
  $cn = \App\Helpers\SettingsHelper::getCompanyName();
  $tracks = $tracks ?? [];
  $infrastructure = $infrastructure ?? [];
  $deliverables = $deliverables ?? [];
  $methodology = $methodology ?? [];
  $examples = $examples ?? [];
@endphp

<div class="sv-os" dir="ltr">
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-page-hero-inner">
        <div class="sv-eyebrow sv-display mx-auto">
          <span class="sv-eyebrow-dot"></span>
          Enterprise B2B
        </div>
        <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black sv-display text-gray-900 leading-tight">
          Scalable Digital<br>
          <span class="sv-neon-sweep">Enterprise Systems</span>
        </h1>
        <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
          At {{ $cn }} we don't sell a price package. We define a clear scope, then build an operating system: processes, permissions, data, integrations, and post-delivery support — with measurable outcomes.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
          <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary w-full sm:w-auto">Book a Session</a>
          <a href="{{ route('website.services') }}" class="sv-btn sv-btn-ghost w-full sm:w-auto">View Services</a>
        </div>
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <div class="sv-section-label sv-display">Solution Tracks</div>
      <h2 class="sv-section-title sv-display">What We Build for Enterprises</h2>
      <p class="sv-section-desc mx-auto mt-3">Start with foundations that stabilize operations and data, then add integration, automation, and AI by priority.</p>
    </div>
    <div class="sv-services-grid">
      @foreach($tracks as $i => $t)
      <div class="sv-module-card">
        <div class="sv-svc-icon {{ $i % 2 ? 'sv-svc-icon--orange' : '' }}">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <h3 class="font-extrabold text-gray-900">{{ $t['title'] }}</h3>
        <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $t['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </section>

  <section class="sv-section sv-section--soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="sv-form-panel">
          <div class="sv-section-label sv-display">Technology Foundation</div>
          <h2 class="sv-section-title sv-display text-2xl">How We Build Infrastructure</h2>
          <p class="mt-4 text-gray-600 leading-relaxed text-sm">
            Infrastructure isn't "just a server." It's <strong class="text-gray-900">identity &amp; access</strong> + <strong class="text-gray-900">data model</strong> +
            <strong class="text-gray-900">integrations</strong> + <strong class="text-gray-900">operations &amp; monitoring</strong> + <strong class="text-gray-900">governance</strong>.
            A system that lasts years, scales with your company, and gives leadership accurate operational visibility.
          </p>
          <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($infrastructure as $item)
            <div class="sv-contact-field">
              <div class="font-extrabold text-gray-900 text-sm">{{ $item['title'] }}</div>
              <p class="mt-1 text-xs text-gray-600 leading-relaxed">{{ $item['desc'] }}</p>
            </div>
            @endforeach
          </div>
        </div>
        <div class="sv-contact-panel">
          <h3 class="text-xl font-extrabold text-gray-900">Week-One Deliverables</h3>
          <p class="mt-2 text-sm text-gray-600">Clear outputs from the first week of engagement.</p>
          <ul class="mt-5 space-y-3 text-sm text-gray-700">
            @foreach($deliverables as $d)
            <li class="flex items-start gap-2">
              <span class="mt-1.5 h-2 w-2 rounded-full shrink-0" style="background:var(--sv-orange)"></span>
              <span><strong class="text-gray-900">{{ $d['bold'] }}:</strong> {{ $d['text'] }}</span>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <div class="sv-form-panel">
        <div class="sv-section-label sv-display">Delivery Model</div>
        <h2 class="sv-section-title sv-display text-2xl">How We Execute</h2>
        <ul class="mt-5 space-y-3 text-sm text-gray-700">
          @foreach($methodology as $m)
          <li class="flex items-start gap-2">
            <span class="mt-1.5 h-2 w-2 rounded-full shrink-0" style="background:var(--sv-blue)"></span>
            <span><strong class="text-gray-900">{{ $m['bold'] }}</strong> {{ $m['text'] }}</span>
          </li>
          @endforeach
        </ul>
      </div>
      <div class="sv-panel-dark flex flex-col justify-between">
        <div>
          <h2 class="text-2xl font-black sv-display">We Commit to Continuity</h2>
          <p class="mt-3 text-blue-100 text-sm leading-relaxed">
            Post-delivery support isn't an add-on — it's part of the build. Structured tickets, department assignment, reporting, and SLA metrics.
          </p>
          <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="rounded-xl border border-white/15 bg-white/5 p-4">
              <div class="text-sm font-extrabold">Clear SLA</div>
              <div class="mt-1 text-xs text-blue-100">Response time + status tracking</div>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/5 p-4">
              <div class="text-sm font-extrabold">Professional Reports</div>
              <div class="mt-1 text-xs text-blue-100">Through ticket closure</div>
            </div>
          </div>
        </div>
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary w-full sm:w-auto" style="background:#fff;color:var(--sv-blue)">Book Intro Session</a>
          <a href="{{ route('website.services') }}" class="sv-btn sv-btn-ghost w-full sm:w-auto text-white border-white/40 hover:bg-white/15 hover:text-white">Services</a>
        </div>
        <p class="mt-4 text-xs text-blue-200/80">Scope and cost depend on your company size and needs.</p>
      </div>
    </div>
  </section>

  <section class="sv-section sv-section--soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <div class="sv-section-label sv-display">Use Cases</div>
        <h2 class="sv-section-title sv-display">Practical Enterprise Solutions</h2>
        <p class="sv-section-desc mx-auto mt-3">Ready-to-implement systems — not pages alone.</p>
      </div>
      <div class="sv-services-grid">
        @foreach($examples as $e)
        <div class="sv-svc-card">
          <h3 class="font-extrabold text-gray-900">{{ $e['t'] }}</h3>
          <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $e['d'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="sv-pillar text-left">
        <div class="text-sm font-extrabold text-gray-900 mb-2">Best for</div>
        <p class="text-sm text-gray-600 leading-relaxed">Companies turning operations into a measurable system: projects, finance, support, and governance.</p>
      </div>
      <div class="sv-pillar text-left">
        <div class="text-sm font-extrabold text-gray-900 mb-2">Outcome</div>
        <p class="text-sm text-gray-600 leading-relaxed">Leadership visibility, clear decision paths, less manual work with integrations and alerts.</p>
      </div>
      <div class="sv-pillar text-left">
        <div class="text-sm font-extrabold text-gray-900 mb-2">Next step</div>
        <p class="text-sm text-gray-600 leading-relaxed">Intro session + scope definition + phased roadmap with clear deliverables per stage.</p>
      </div>
    </div>
  </section>

  <section class="sv-cta-compact">
    <div class="max-w-3xl mx-auto text-center px-4 text-white">
      <h2 class="text-2xl sm:text-3xl font-black sv-display">Ready to Scope Your Enterprise Build?</h2>
      <p class="mt-4 text-blue-100 leading-relaxed">Let's map phase one with measurable deliverables.</p>
      <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary mt-8 w-full sm:w-auto" style="background:#fff;color:var(--sv-blue)">Contact Us</a>
    </div>
  </section>
</div>
@endsection
