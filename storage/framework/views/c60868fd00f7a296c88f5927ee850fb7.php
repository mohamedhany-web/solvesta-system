<?php $__env->startSection('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — Services'); ?>


<?php $__env->startSection('content'); ?>
<?php
  $cn = \App\Helpers\SettingsHelper::getCompanyName();
  $coreServices = $coreServices ?? [];
  $engineeringServices = $engineeringServices ?? [];
  $aiServices = $aiServices ?? [];
  $transformationSteps = $transformationSteps ?? [];
  $platformModules = $platformModules ?? [];
?>

<div class="sv-os" dir="ltr">
  
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="sv-page-hero-inner">
        <div class="sv-eyebrow sv-display mx-auto">
          <span class="sv-eyebrow-dot"></span>
          Software &amp; AI — Enterprise
        </div>
        <h1 class="mt-6 text-3xl sm:text-4xl lg:text-5xl font-black sv-display text-gray-900 leading-tight">
          Engineering Your<br>
          <span class="sv-neon-sweep">Digital Core</span>
        </h1>
        <p class="mt-5 text-lg text-gray-600 leading-relaxed max-w-2xl mx-auto">
          We work with <strong class="text-gray-800">companies and institutions</strong> that need data sovereignty, reliable operations, and clear SLAs — not disposable consumer products.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
          <a href="<?php echo e(route('website.contact')); ?>" class="sv-btn sv-btn-primary w-full sm:w-auto">Request a Quote</a>
          <a href="<?php echo e(route('website.pricing')); ?>" class="sv-btn sv-btn-ghost w-full sm:w-auto">Enterprise Solutions</a>
        </div>
      </div>
    </div>
  </section>

  
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <div class="sv-section-label sv-display">Full Business Stack</div>
      <h2 class="sv-section-title sv-display">What We Deliver</h2>
      <p class="sv-section-desc mx-auto mt-3">End-to-end capabilities — from transformation consulting to AI layers on your operational data.</p>
    </div>
    <div class="sv-services-grid">
      <?php $__currentLoopData = $coreServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="sv-svc-card">
        <div class="sv-svc-icon <?php echo e(($svc['accent'] ?? 'blue') === 'orange' ? 'sv-svc-icon--orange' : ''); ?>">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
          </svg>
        </div>
        <h3 class="font-extrabold text-gray-900"><?php echo e($svc['title']); ?></h3>
        <p class="mt-3 text-sm text-gray-600 leading-relaxed"><?php echo e($svc['desc']); ?></p>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </section>

  
  <section class="sv-section sv-section--soft">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl mx-auto text-center mb-10">
        <div class="sv-section-label sv-display">Build &amp; Operate</div>
        <h2 class="sv-section-title sv-display">Enterprise Engineering, Integration &amp; Ops</h2>
        <p class="mt-4 text-gray-600 leading-relaxed">
          From <strong class="text-gray-800">transformation consulting and architecture</strong> to <strong class="text-gray-800">development, integration, and operations</strong> — with privacy, permissions, and audit trails as organizations require.
        </p>
        <p class="mt-3 text-gray-600 leading-relaxed text-sm">
          Measurable phased delivery (clear roadmap, prototypes, progressive production), documented outputs, team training, and <strong class="text-gray-800">operational support with SLA</strong> so the system becomes how you work — not an isolated project.
        </p>
      </div>
      <div class="sv-services-grid">
        <?php $__currentLoopData = $engineeringServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="sv-module-card">
          <div class="sv-svc-icon <?php echo e($i % 2 ? 'sv-svc-icon--orange' : ''); ?>">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($svc['icon']); ?>"/>
            </svg>
          </div>
          <h3 class="font-extrabold text-gray-900"><?php echo e($svc['title']); ?></h3>
          <p class="mt-2 text-sm text-gray-600 leading-relaxed"><?php echo e($svc['desc']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </section>

  
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <div class="sv-section-label sv-display">Intelligence Layer</div>
      <h2 class="sv-section-title sv-display">AI, Data &amp; Executive Decision Support</h2>
      <p class="sv-section-desc mx-auto mt-3">Intelligence built on your data and processes — transparent boundaries, auditable, tied to real KPIs.</p>
    </div>
    <div class="sv-services-grid">
      <?php $__currentLoopData = $aiServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="sv-module-card">
        <div class="sv-svc-icon <?php echo e($i % 2 ? '' : 'sv-svc-icon--orange'); ?>">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($svc['icon']); ?>"/>
          </svg>
        </div>
        <h3 class="font-extrabold text-gray-900"><?php echo e($svc['title']); ?></h3>
        <p class="mt-2 text-sm text-gray-600 leading-relaxed"><?php echo e($svc['desc']); ?></p>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="sv-commit-box mt-10 max-w-3xl mx-auto text-center">
      <p class="text-sm text-gray-700 leading-relaxed">
        <strong class="text-gray-900">Our commitment to enterprises:</strong> technical and operational documentation, team training, clear support channels, and ongoing maintenance and evolution — so your solution stays auditable and scalable as you grow.
      </p>
    </div>
  </section>

  
  <section class="sv-section sv-section--blue">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-10">
        <div class="sv-section-label sv-display">Digital Transformation</div>
        <h2 class="sv-section-title sv-display">From Traditional Ops to a Digital Path</h2>
        <p class="sv-section-desc mx-auto mt-3">
          If work relies on memory, notebooks, and scattered files — that's a common starting point. Rebuilding means <strong class="text-gray-800">layering change step by step</strong> until data and decisions are traceable.
        </p>
      </div>
      <div class="sv-steps-grid">
        <?php $__currentLoopData = $transformationSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="sv-step-card">
          <span class="sv-step-card-num"><?php echo e($step['n']); ?></span>
          <h3 class="font-extrabold text-gray-900 text-sm"><?php echo e($step['title']); ?></h3>
          <p class="mt-2 text-sm text-gray-600 leading-relaxed"><?php echo e($step['text']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>
  </section>

  
  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
      <div class="sv-section-label sv-display"><?php echo e($cn); ?> Platform</div>
      <h2 class="sv-section-title sv-display">Ready-Made Operating Modules</h2>
      <p class="sv-section-desc mx-auto mt-3">
        Beyond custom solutions, integrated modules accelerate adoption: projects, HR, invoicing, client portal, support, and reporting — as part of your transformation or as a foundation to expand later.
      </p>
      <p class="text-xs text-gray-500 max-w-xl mx-auto mt-2">Direct integration with development and AI paths when needed — no lock-in to a black box you don't own.</p>
    </div>
    <div class="sv-modules-grid">
      <?php $__currentLoopData = $platformModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $mod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="sv-module-card">
        <div class="sv-svc-icon <?php echo e($i % 2 ? 'sv-svc-icon--orange' : ''); ?>">
          <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($mod['icon']); ?>"/>
          </svg>
        </div>
        <h3 class="font-extrabold text-gray-900"><?php echo e($mod['title']); ?></h3>
        <p class="mt-2 text-sm text-gray-600 leading-relaxed"><?php echo e($mod['desc']); ?></p>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </section>

  
  <section class="sv-cta-compact">
    <div class="max-w-3xl mx-auto text-center px-4 text-white">
      <h2 class="text-2xl sm:text-3xl font-black sv-display">Let's Scope Your Next System</h2>
      <p class="mt-4 text-blue-100 leading-relaxed">Tell us your operational pain points — we'll map a phased path with clear deliverables.</p>
      <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="<?php echo e(route('website.contact')); ?>" class="sv-btn sv-btn-primary w-full sm:w-auto" style="background:#fff;color:var(--sv-blue)">Get a Quote</a>
        <a href="<?php echo e(route('website.about')); ?>" class="sv-btn sv-btn-ghost w-full sm:w-auto text-white border-white/40 hover:bg-white/15 hover:text-white">About Us</a>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\website\services.blade.php ENDPATH**/ ?>