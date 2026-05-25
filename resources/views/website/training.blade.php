@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — Academy')

@push('body-class')
 sv-cinematic-page
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cinematic-home.css') }}?v=3">
@endpush

@section('content')
@php $cn = \App\Helpers\SettingsHelper::getCompanyName(); @endphp

<div class="sv-os" dir="ltr">
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-page-hero-inner">
        <div class="sv-eyebrow sv-display mx-auto">
          <span class="sv-eyebrow-dot"></span>
          {{ $cn }} Academy
        </div>
        <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black sv-display text-gray-900 leading-tight">
          Training &amp;<br>
          <span class="sv-neon-sweep">Internship Programs</span>
        </h1>
        <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
          Hands-on learning on real B2B systems: operations, permissions, data, integrations, and post-delivery support — aligned with market demand and modern stacks.
        </p>
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(session('success'))
      <div class="sv-alert-success mb-8 max-w-3xl mx-auto">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
      <div>
        <div class="sv-section-label sv-display">Open Programs</div>
        <h2 class="sv-section-title sv-display">Available Opportunities</h2>
        <p class="mt-2 text-gray-600 text-sm">Planned or currently running programs.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse($trainings as $t)
      <a href="{{ route('website.training.show', $t) }}" class="sv-training-card group">
        <div class="flex items-start justify-between gap-3">
          <h3 class="text-lg font-extrabold text-gray-900 group-hover:text-blue-700 transition-colors">{{ $t->title }}</h3>
          <span class="sv-badge {{ $t->status === 'ongoing' ? 'sv-badge--live' : '' }} shrink-0">
            {{ $t->status === 'planned' ? 'Coming Soon' : 'Open' }}
          </span>
        </div>
        <p class="text-sm text-gray-600 mt-3 leading-relaxed line-clamp-3">{{ $t->description }}</p>
        <div class="mt-5 text-sm text-gray-600 space-y-1">
          <div><span class="text-gray-500">Department:</span> {{ $t->department?->name ?? '—' }}</div>
          <div><span class="text-gray-500">Duration:</span> {{ $t->start_date?->format('M d, Y') }} → {{ $t->end_date?->format('M d, Y') }}</div>
        </div>
        <div class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-blue-600 group-hover:text-orange-500 transition-colors">
          View details &amp; apply
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
      </a>
      @empty
      <div class="col-span-full sv-form-panel text-center py-12">
        <p class="text-gray-600">No training opportunities are open right now. Check back when a new program is announced.</p>
      </div>
      @endforelse
    </div>
  </section>

  <section class="sv-section sv-section--soft">
    <div class="max-w-3xl mx-auto px-4 text-center">
      <div class="sv-section-label sv-display">What You'll Learn</div>
      <p class="mt-3 text-gray-600 leading-relaxed text-sm">
        Real enterprise workflows — not toy demos. You'll work with permissions, operational data, client-facing modules, and the discipline of shipping maintainable software.
      </p>
      <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-ghost mt-6">Ask About Future Programs</a>
    </div>
  </section>

  <section class="sv-cta-compact">
    <div class="max-w-3xl mx-auto text-center px-4 text-white">
      <h2 class="text-2xl sm:text-3xl font-black sv-display">Build Skills on Real Systems</h2>
      <p class="mt-4 text-blue-100 leading-relaxed">Apply when a program opens — or contact us about upcoming cohorts.</p>
      <a href="{{ route('website.services') }}" class="sv-btn sv-btn-primary mt-8 w-full sm:w-auto" style="background:#fff;color:var(--sv-blue)">Explore Our Stack</a>
    </div>
  </section>
</div>
@endsection
