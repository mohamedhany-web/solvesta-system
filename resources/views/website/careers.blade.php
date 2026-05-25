@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — Careers')


@section('content')
@php $cn = \App\Helpers\SettingsHelper::getCompanyName(); @endphp

<div class="sv-os" dir="ltr">
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-page-hero-inner">
        <div class="sv-eyebrow sv-display mx-auto">
          <span class="sv-eyebrow-dot"></span>
          Careers at {{ $cn }}
        </div>
        <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black sv-display text-gray-900 leading-tight">
          Build Intelligent<br>
          <span class="sv-neon-sweep">Enterprise Systems With Us</span>
        </h1>
        <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
          Join a team engineering real B2B platforms — operations, data, integrations, AI, and client-facing products.
        </p>
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(session('success'))
      <div class="sv-alert-success mb-8 max-w-3xl mx-auto">{{ session('success') }}</div>
    @endif

    <div class="text-center mb-10">
      <div class="sv-section-label sv-display">Open Positions</div>
      <h2 class="sv-section-title sv-display">Current Opportunities</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      @forelse($jobs as $job)
      <a href="{{ route('website.careers.show', $job->slug) }}" class="sv-training-card group">
        <div class="flex items-start justify-between gap-3">
          <h3 class="text-lg font-extrabold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $job->title }}</h3>
          @if($job->is_featured)<span class="sv-badge sv-badge--live shrink-0">Featured</span>@endif
        </div>
        @if($job->summary)
        <p class="text-sm text-gray-600 mt-3 leading-relaxed line-clamp-2">{{ $job->summary }}</p>
        @endif
        <div class="mt-5 flex flex-wrap gap-2 text-xs font-bold text-gray-500">
          @if($job->location)<span class="sv-badge">{{ $job->location }}</span>@endif
          <span class="sv-badge">{{ $job->employmentTypeLabel() }}</span>
          @if($job->department)<span class="sv-badge">{{ $job->department->name }}</span>@endif
        </div>
        <div class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-blue-600 group-hover:text-orange-500 transition-colors">
          View &amp; apply
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
      </a>
      @empty
      <div class="col-span-full sv-form-panel text-center py-12">
        <p class="text-gray-600">No open positions right now. Check back soon or send your profile via our contact page.</p>
        <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary mt-6 inline-flex">Contact Us</a>
      </div>
      @endforelse
    </div>
  </section>

  <section class="sv-cta-compact">
    <div class="max-w-3xl mx-auto text-center px-4 text-white">
      <h2 class="text-2xl sm:text-3xl font-black sv-display">Don't See Your Role?</h2>
      <p class="mt-4 text-blue-100 leading-relaxed">We're always interested in strong engineers and operators. Get in touch.</p>
      <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary mt-8 w-full sm:w-auto" style="background:#fff;color:var(--sv-blue)">General Application</a>
    </div>
  </section>
</div>
@endsection
