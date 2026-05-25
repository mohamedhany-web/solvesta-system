<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Helpers\SettingsHelper::getCompanyName() }} — Client Portal</title>

    @php
        $faviconUrl = \App\Helpers\SettingsHelper::getFaviconUrl();
        $logoUrl = \App\Helpers\SettingsHelper::getLogoUrl();
        $companyName = \App\Helpers\SettingsHelper::getCompanyName();
    @endphp

    <link rel="icon" href="{{ $faviconUrl ?: '/favicon.ico' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    @include('client-auth.partials.client-login-inline-css')
</head>
<body class="sv-client-login-page">
    <div class="sv-client-bg" aria-hidden="true">
        <canvas id="sv-particles"></canvas>
        <div class="sv-client-bg-grid"></div>
        <div class="sv-client-glow sv-client-glow--b"></div>
        <div class="sv-client-glow sv-client-glow--o"></div>
    </div>

    <div class="sv-client-layout">
        <section class="sv-client-intro">
            <a href="{{ route('website.home') }}" class="sv-client-back">
                ← Back to {{ $companyName }}
            </a>

            <div class="sv-client-badge">
                <span class="sv-client-badge-dot"></span>
                Client Portal
            </div>

            <h1 class="sv-client-title">
                We care about<br>
                <span>your experience.</span>
            </h1>

            <p class="sv-client-lead">
                A secure space for your projects, invoices, support tickets, and reports — built with the same standards we deliver to your business.
            </p>

            <div class="sv-client-features">
                <div class="sv-client-feature">
                    <div class="sv-client-feature-icon">📊</div>
                    <div>
                        <h3>Live project tracking</h3>
                        <p>Milestones and delivery updates in one place.</p>
                    </div>
                </div>
                <div class="sv-client-feature">
                    <div class="sv-client-feature-icon">💳</div>
                    <div>
                        <h3>Transparent billing</h3>
                        <p>Invoices and payment status — always clear.</p>
                    </div>
                </div>
                <div class="sv-client-feature">
                    <div class="sv-client-feature-icon">🎫</div>
                    <div>
                        <h3>Support with SLA</h3>
                        <p>Structured tickets until every issue is closed.</p>
                    </div>
                </div>
                <div class="sv-client-feature">
                    <div class="sv-client-feature-icon">🔒</div>
                    <div>
                        <h3>Private & secure</h3>
                        <p>Your data — isolated and access-controlled.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="sv-client-form-side">
            <div class="sv-client-card">
                <div class="sv-client-card-head">
                    <div class="sv-client-logo">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $companyName }}">
                        @else
                            <div class="sv-client-logo-fallback">{{ mb_substr($companyName, 0, 1) }}</div>
                        @endif
                    </div>
                    <h2>Welcome back</h2>
                    <p class="sub">Sign in to your client dashboard</p>
                </div>

                @if($errors->any())
                    <div class="sv-client-alert sv-client-alert--error">
                        @foreach($errors->all() as $err)
                            <p>{{ $err }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('client.login.submit') }}">
                    @csrf

                    <div class="sv-client-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               autocomplete="email" placeholder="you@company.com"
                               class="@error('email') is-error @enderror">
                    </div>

                    <div class="sv-client-field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required
                               autocomplete="current-password" placeholder="Enter your password"
                               class="@error('password') is-error @enderror">
                    </div>

                    <div class="sv-client-row">
                        <label>
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        <a href="{{ route('website.contact') }}">Need help?</a>
                    </div>

                    <button type="submit" class="sv-client-submit">Enter Client Portal</button>
                </form>

                <p class="sv-client-foot">
                    For {{ $companyName }} clients only.<br>
                    <a href="{{ route('website.home') }}">Return to website</a>
                </p>
            </div>
        </section>
    </div>

    @include('client-auth.partials.client-login-inline-js')
</body>
</html>
