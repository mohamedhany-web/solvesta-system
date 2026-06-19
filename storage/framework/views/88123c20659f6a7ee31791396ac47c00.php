<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(\App\Helpers\SettingsHelper::getCompanyName()); ?> — Client Portal</title>

    <?php
        $faviconUrl = \App\Helpers\SettingsHelper::getFaviconUrl();
        $logoUrl = \App\Helpers\SettingsHelper::getLogoUrl();
        $companyName = \App\Helpers\SettingsHelper::getCompanyName();
    ?>

    <link rel="icon" href="<?php echo e($faviconUrl ?: '/favicon.ico'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php echo $__env->make('client-auth.partials.client-login-inline-css', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
            <a href="<?php echo e(route('website.home')); ?>" class="sv-client-back">
                ← Back to <?php echo e($companyName); ?>

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
                        <?php if($logoUrl): ?>
                            <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($companyName); ?>">
                        <?php else: ?>
                            <div class="sv-client-logo-fallback"><?php echo e(mb_substr($companyName, 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <h2>Welcome back</h2>
                    <p class="sub">Sign in to your client dashboard</p>
                </div>

                <?php if($errors->any()): ?>
                    <div class="sv-client-alert sv-client-alert--error">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($err); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('client.login.submit')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="sv-client-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required
                               autocomplete="email" placeholder="you@company.com"
                               class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>

                    <div class="sv-client-field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required
                               autocomplete="current-password" placeholder="Enter your password"
                               class="<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    </div>

                    <div class="sv-client-row">
                        <label>
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        <a href="<?php echo e(route('website.contact')); ?>">Need help?</a>
                    </div>

                    <button type="submit" class="sv-client-submit">Enter Client Portal</button>
                </form>

                <p class="sv-client-foot">
                    For <?php echo e($companyName); ?> clients only.<br>
                    <a href="<?php echo e(route('website.home')); ?>">Return to website</a>
                </p>
            </div>
        </section>
    </div>

    <?php echo $__env->make('client-auth.partials.client-login-inline-js', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-auth\login.blade.php ENDPATH**/ ?>