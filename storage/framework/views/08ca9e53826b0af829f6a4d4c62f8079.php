

<?php $__env->startSection('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — Contact'); ?>


<?php $__env->startSection('content'); ?>
<?php $cn = \App\Helpers\SettingsHelper::getCompanyName(); ?>

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

        <?php if(session('success')): ?>
          <div class="sv-alert-success mt-5"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <form class="mt-6 space-y-4" action="<?php echo e(route('website.contact.submit')); ?>" method="POST">
          <?php echo csrf_field(); ?>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Request type</label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <label class="sv-radio-card">
                <input type="radio" name="type" value="contact" class="rounded border-gray-300 text-blue-600" <?php echo e(old('type', 'consultation') === 'contact' ? 'checked' : ''); ?>>
                <span class="text-sm font-bold text-gray-800">General contact</span>
              </label>
              <label class="sv-radio-card">
                <input type="radio" name="type" value="consultation" class="rounded border-gray-300 text-blue-600" <?php echo e(old('type', 'consultation') === 'consultation' ? 'checked' : ''); ?>>
                <span class="text-sm font-bold text-gray-800">Book consultation</span>
              </label>
            </div>
            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="mt-2 text-sm text-red-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Name *</label>
              <input type="text" name="name" value="<?php echo e(old('name')); ?>" required class="sv-form-input <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
              <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="mt-2 text-sm text-red-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Company</label>
              <input type="text" name="company" value="<?php echo e(old('company')); ?>" class="sv-form-input <?php $__errorArgs = ['company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
              <?php $__errorArgs = ['company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="mt-2 text-sm text-red-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
              <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="sv-form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
              <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="mt-2 text-sm text-red-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Phone</label>
              <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="sv-form-input <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
              <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="mt-2 text-sm text-red-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Subject (optional)</label>
            <input type="text" name="subject" value="<?php echo e(old('subject')); ?>" class="sv-form-input <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="mt-2 text-sm text-red-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Message *</label>
            <textarea name="message" rows="6" required class="sv-form-textarea <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('message')); ?></textarea>
            <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="mt-2 text-sm text-red-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
              <div class="font-extrabold text-gray-900"><?php echo e(\App\Helpers\SettingsHelper::getCompanyPhone() ?: '—'); ?></div>
            </div>
            <div class="sv-contact-field">
              <div class="text-xs text-gray-500 mb-1">Email</div>
              <div class="font-extrabold text-gray-900 break-all"><?php echo e(\App\Helpers\SettingsHelper::getCompanyEmail() ?: '—'); ?></div>
            </div>
            <div class="sv-contact-field sm:col-span-2">
              <div class="text-xs text-gray-500 mb-1">Address</div>
              <div class="font-extrabold text-gray-900"><?php echo e(\App\Helpers\SettingsHelper::getCompanyAddress() ?: '—'); ?></div>
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
          <a href="<?php echo e(route('website.services')); ?>" class="sv-btn sv-btn-ghost w-full text-center">View Services</a>
          <a href="<?php echo e(route('website.pricing')); ?>" class="sv-btn sv-btn-ghost w-full text-center">Enterprise Solutions</a>
        </div>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\website\contact.blade.php ENDPATH**/ ?>