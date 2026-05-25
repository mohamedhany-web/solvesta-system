@extends('website.layout')

@section('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — AI & Enterprise Engineering')

@push('body-class')
 sv-cinematic-page
@endpush

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/cinematic-home.css') }}?v=3">
@endpush

@section('content')
@php
  $cn = \App\Helpers\SettingsHelper::getCompanyName();
  $cinematicServices = $cinematicServices ?? [];
  $processSteps = $processSteps ?? [];
  $platformModules = $platformModules ?? [];
  $pillars = $pillars ?? [];
  $sectors = $sectors ?? [];
@endphp

<div class="sv-os" dir="ltr">
  <div id="sv-cursor" class="sv-cursor" aria-hidden="true"></div>
  <div id="sv-cursor-dot" class="sv-cursor-dot" aria-hidden="true"></div>

  {{-- 1. Hero --}}
  <section class="sv-hero" id="sv-hero">
    <canvas id="sv-particles" aria-hidden="true"></canvas>
    <div class="sv-hero-grid" aria-hidden="true"></div>
    <div class="sv-hero-glow sv-hero-glow--blue" data-parallax="0.04" aria-hidden="true"></div>
    <div class="sv-hero-glow sv-hero-glow--orange" data-parallax="-0.03" aria-hidden="true"></div>
    <div class="sv-data-stream" style="top:20%" aria-hidden="true"></div>
    <div class="sv-data-stream" style="top:60%; animation-delay:2s" aria-hidden="true"></div>

    <div class="sv-float-ui sv-float-ui--1">ERP <span>ONLINE</span></div>
    <div class="sv-float-ui sv-float-ui--2">AI <span>ACTIVE</span></div>
    <div class="sv-float-ui sv-float-ui--3">CRM <span>SYNC</span></div>
    <div class="sv-float-ui sv-float-ui--4">DATA <span>FLOW</span></div>

    <div class="sv-hero-content">
      <div class="sv-hero-eyebrow sv-eyebrow sv-display">
        <span class="sv-eyebrow-dot"></span>
        The Future of Business Engineering
      </div>

      <h1 class="sv-hero-title sv-display">
        We Don't Build Software.<br>
        <span class="sv-neon-sweep">We Rebuild Companies.</span>
      </h1>

      <p class="sv-hero-sub">
        <strong>{{ $cn }}</strong> is a strategic AI &amp; software partner engineering the next generation of intelligent enterprises — operations, infrastructure, automation, and full digital transformation.
      </p>

      <div class="sv-hero-cta mt-10 flex flex-wrap items-center justify-center gap-4">
        <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary sv-btn-magnetic">
          Start Transformation
        </a>
        <a href="{{ route('website.services') }}" class="sv-btn sv-btn-ghost sv-btn-magnetic">
          Explore Systems
        </a>
      </div>
    </div>
  </section>

  {{-- Stats --}}
  <section class="sv-stats-strip">
    <div class="sv-stats-grid max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4">
      @foreach([
        ['v' => '12+', 'l' => 'Operating Modules', 'o' => false],
        ['v' => 'B2B', 'l' => 'Enterprise Solutions', 'o' => true],
        ['v' => 'AI', 'l' => 'Embedded Intelligence', 'o' => false],
        ['v' => 'SLA', 'l' => 'Committed Support', 'o' => true],
      ] as $s)
      <div class="sv-stat-item">
        <div class="sv-stat-value {{ $s['o'] ? 'sv-stat-value--orange' : '' }}">{{ $s['v'] }}</div>
        <div class="text-xs text-gray-500 mt-1 font-bold">{{ $s['l'] }}</div>
      </div>
      @endforeach
    </div>
  </section>

  {{-- 2. Transformation --}}
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="sv-transform">
    <div class="sv-reveal text-center mb-10">
      <div class="sv-section-label sv-display">System Evolution</div>
      <h2 class="sv-section-title sv-display">From Chaos to Intelligent Order</h2>
      <p class="sv-section-desc mx-auto">A visible shift: fragmented spreadsheets and tools → one connected digital operating system with live data.</p>
    </div>

    <div class="sv-transform sv-reveal">
      <div class="sv-chaos">
        <div class="flex flex-col gap-2 h-full min-h-[200px]">
          <div class="text-xs font-extrabold text-gray-500 mb-2 uppercase tracking-wider">Legacy Operations</div>
          @foreach([40, 85, 55, 25, 70, 48] as $w)
          <div class="sv-chaos-bar" style="--w:{{ $w }}%"></div>
          @endforeach
          <p class="mt-auto pt-4 text-[11px] text-gray-500">Fragmented · Manual · No live visibility</p>
        </div>
      </div>
      <div class="sv-order">
        <div class="flex flex-col gap-2 h-full min-h-[200px]">
          <div class="text-xs font-extrabold mb-2 uppercase tracking-wider" style="color:var(--sv-blue)">AI-Powered Operating System</div>
          @foreach([88, 95, 82, 90, 86, 92] as $w)
          <div class="sv-order-bar" style="--w:{{ $w }}%"></div>
          @endforeach
          <p class="mt-auto pt-4 text-[11px] font-bold" style="color:var(--sv-blue)">Unified · Automated · Live Intelligence</p>
        </div>
      </div>
    </div>
  </section>

  {{-- Platform --}}
  <section class="sv-section sv-section--soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-reveal text-center mb-10">
        <div class="sv-section-label sv-display">{{ $cn }} Platform</div>
        <h2 class="sv-section-title sv-display">What's Inside the Operating System?</h2>
        <p class="sv-section-desc mx-auto">Real modules we build and run — from internal teams to your client portal.</p>
      </div>
      <div class="sv-modules-grid">
        @foreach($platformModules as $i => $mod)
        <div class="sv-module-card sv-reveal">
          <div class="sv-svc-icon {{ $i % 2 ? 'sv-svc-icon--orange' : '' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="{{ $mod['icon'] }}"/>
            </svg>
          </div>
          <h3 class="font-extrabold text-gray-900">{{ $mod['title'] }}</h3>
          <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $mod['desc'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- 3. Services --}}
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="sv-reveal mb-10 text-center sm:text-left">
      <div class="sv-section-label sv-display">Full Business Stack</div>
      <h2 class="sv-section-title sv-display">Engineering Your Digital Core</h2>
    </div>
    <div class="sv-services-grid">
      @foreach($cinematicServices as $svc)
      <div class="sv-svc-card sv-reveal">
        <div class="sv-svc-icon {{ ($svc['accent'] ?? 'blue') === 'orange' ? 'sv-svc-icon--orange' : '' }}">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <h3 class="font-extrabold text-gray-900">{{ $svc['title'] }}</h3>
        <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ $svc['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </section>

  {{-- 4. Process --}}
  <section class="sv-section sv-section--blue">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-reveal text-center mb-12">
        <div class="sv-section-label sv-display">Engineering Process</div>
        <h2 class="sv-section-title sv-display">How We Rebuild</h2>
      </div>
      <div class="sv-process sv-reveal">
        <div class="sv-process-line"><div class="sv-process-line-fill"></div></div>
        @foreach($processSteps as $step)
        <div class="sv-step">
          <div class="sv-step-dot"></div>
          <div class="text-[10px] font-extrabold" style="color:var(--sv-orange)">{{ $step['n'] }}</div>
          <h3 class="mt-1 text-lg font-extrabold text-gray-900">{{ $step['title'] }}</h3>
          <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $step['desc'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- 5. AI Dashboard --}}
  <section class="sv-section sv-section--soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-reveal text-center mb-8">
        <div class="sv-section-label sv-display">Live Intelligence Layer</div>
        <h2 class="sv-section-title sv-display">AI System Preview</h2>
        <div class="sv-ai-pulse mt-3 justify-center">Processing enterprise signals…</div>
      </div>
      <div class="sv-dashboard sv-reveal" id="sv-dashboard">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          @foreach([
            ['label' => 'Operational Efficiency', 'val' => 47, 'suffix' => '%', 'w' => 87],
            ['label' => 'Automation Rate', 'val' => 62, 'suffix' => '%', 'w' => 72],
            ['label' => 'System Performance', 'val' => 94, 'suffix' => '%', 'w' => 94],
            ['label' => 'Growth Index', 'val' => 3.2, 'suffix' => 'x', 'float' => true, 'w' => 68],
          ] as $m)
          <div class="sv-dash-metric">
            <div class="text-[11px] font-bold text-gray-500">{{ $m['label'] }}</div>
            <div class="sv-metric mt-2" data-count="{{ $m['val'] }}" data-suffix="{{ $m['suffix'] }}" @if(!empty($m['float'])) data-float="1" @endif>0{{ $m['suffix'] }}</div>
            <div class="sv-graph-bar mt-3"><span style="--w:{{ $m['w'] }}%"></span></div>
          </div>
          @endforeach
        </div>
        <div class="rounded-xl border border-gray-100 bg-gradient-to-t from-blue-50 to-white p-4 h-36 flex items-end gap-1.5">
          @foreach([40,65,45,80,55,90,70,85,60,95,75,88] as $h)
          <div class="flex-1 rounded-t-lg bg-gradient-to-t from-blue-600 to-orange-400 opacity-85" style="height:{{ $h }}%"></div>
          @endforeach
        </div>
        <p class="mt-4 text-center text-xs text-gray-500">Illustrative metrics — your live dashboards are built on your data and infrastructure.</p>
      </div>
    </div>
  </section>

  {{-- Client portal --}}
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div class="sv-reveal">
        <div class="sv-section-label sv-display">Client Portal</div>
        <h2 class="sv-section-title sv-display">Your Clients Come First</h2>
        <p class="mt-4 text-gray-600 leading-relaxed">
          A secure dedicated portal: projects, invoices, service reports, website issues, meeting requests, and support tickets — all connected to the same internal operating system.
        </p>
        <ul class="mt-5 space-y-2 text-sm text-gray-600">
          <li class="flex items-start gap-2"><span class="text-blue-600 font-bold">✓</span> Isolated client accounts with granular access</li>
          <li class="flex items-start gap-2"><span class="text-orange-500 font-bold">✓</span> Alerts for invoices and ticket updates</li>
          <li class="flex items-start gap-2"><span class="text-blue-600 font-bold">✓</span> Downloadable reports and shared documents</li>
        </ul>
        <a href="{{ route('client.login') }}" class="sv-btn sv-btn-primary sv-btn-magnetic mt-6">
          Client Portal Login
        </a>
      </div>
      <div class="grid grid-cols-2 gap-3 sv-reveal">
        @foreach([
          ['Projects', 'Milestones & delivery tracking'],
          ['Invoices', 'Paid / due / outstanding'],
          ['Support', 'Tickets + SLA'],
          ['Issues', 'Website reports & status'],
        ] as $tile)
        <div class="sv-portal-card">
          <div class="text-sm font-extrabold text-gray-900">{{ $tile[0] }}</div>
          <div class="mt-1 text-xs text-gray-500">{{ $tile[1] }}</div>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Why Solvesta --}}
  <section class="sv-section sv-section--soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-reveal text-center mb-10">
        <div class="sv-section-label sv-display">Digital Partner, Not a Vendor</div>
        <h2 class="sv-section-title sv-display">Why {{ $cn }}?</h2>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($pillars as $pillar)
        <div class="sv-pillar sv-reveal">
          <div class="mx-auto h-14 w-14 rounded-2xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg, #eff6ff, #fff7ed)">
            <svg class="w-7 h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          </div>
          <h3 class="text-lg font-extrabold text-gray-900">{{ $pillar['title'] }}</h3>
          <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $pillar['desc'] }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Sectors --}}
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="sv-reveal text-center mb-8">
      <div class="sv-section-label sv-display">Industries We Serve</div>
      <h2 class="sv-section-title sv-display">Deep Expertise — Tailored Solutions</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      @foreach($sectors as $sec)
      <div class="sv-sector sv-reveal">
        <span class="text-sm font-extrabold text-gray-800">{{ $sec }}</span>
      </div>
      @endforeach
    </div>
  </section>

  {{-- Training --}}
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="sv-reveal rounded-3xl border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-orange-50 p-8 sm:p-10 flex flex-col sm:flex-row items-center justify-between gap-6">
      <div>
        <h3 class="text-xl font-extrabold text-gray-900">Training & Internship Programs</h3>
        <p class="mt-2 text-gray-600 text-sm">Professional tracks aligned with market demand and modern technology stacks.</p>
      </div>
      <a href="{{ route('website.training') }}" class="sv-btn sv-btn-orange sv-btn-magnetic shrink-0">Explore Programs</a>
    </div>
  </section>

  {{-- About --}}
  <section class="sv-about sv-reveal">
    <div class="sv-section-label sv-display mb-4">Our Vision</div>
    <p class="sv-about-vision sv-display text-gray-800">
      We engineer systems that turn companies into
      <span class="sv-about-glow-text">intelligent digital ecosystems.</span>
    </p>
    <p class="mt-6 text-gray-600 max-w-2xl mx-auto leading-relaxed">
      {{ $cn }} — we don't hand off a project and disappear. We stay your partner through operations and growth: from consulting to build to support with clear SLAs.
    </p>
  </section>

  {{-- Final CTA --}}
  <section class="sv-cta-final sv-section">
    <div class="sv-cta-grid-expand" aria-hidden="true"></div>
    <div class="sv-burst" aria-hidden="true"></div>
    <div class="relative z-10 text-center px-4 max-w-3xl sv-reveal text-white">
      <h2 class="text-3xl sm:text-4xl font-black sv-display leading-tight">Build The Future</h2>
      <p class="mt-5 text-lg text-blue-100 leading-relaxed">
        Let's rebuild your company into a scalable intelligent system — free strategy session, no commitment required.
      </p>
      <div class="mt-10 flex flex-wrap justify-center gap-4">
        <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-primary sv-btn-magnetic text-base px-10 py-4">
          Start Now
        </a>
        <a href="{{ route('website.contact') }}" class="sv-btn sv-btn-ghost sv-btn-magnetic text-base px-10 py-4">
          Book Strategy Call
        </a>
      </div>
    </div>
  </section>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
<script src="{{ asset('js/cinematic-home.js') }}?v=3" defer></script>
@endpush
