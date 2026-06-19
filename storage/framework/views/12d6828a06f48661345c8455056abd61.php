

<?php $__env->startSection('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — ' . $training->title); ?>


<?php $__env->startSection('content'); ?>
<div class="sv-os" dir="ltr">
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <a href="<?php echo e(route('website.training')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-orange-500 transition mb-6">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Academy
      </a>
      <div class="max-w-3xl">
        <span class="sv-badge <?php echo e($training->status === 'ongoing' ? 'sv-badge--live' : ''); ?> mb-4 inline-block">
          <?php echo e($training->status === 'planned' ? 'Coming Soon' : 'Open for Applications'); ?>

        </span>
        <h1 class="text-3xl sm:text-4xl font-black sv-display text-gray-900 leading-tight"><?php echo e($training->title); ?></h1>
        <p class="mt-4 text-gray-600 leading-relaxed whitespace-pre-wrap"><?php echo e($training->description); ?></p>
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <?php if(session('success')): ?>
      <div class="sv-alert-success mb-8"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
      <div class="sv-alert-error mb-8" role="alert">
        <p class="font-bold mb-2">Could not submit application:</p>
        <ul class="list-disc list-inside text-sm space-y-1">
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($err); ?></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <div class="lg:col-span-1 sv-contact-panel">
        <h2 class="text-lg font-extrabold text-gray-900 mb-4">Program Details</h2>
        <div class="space-y-3 text-sm text-gray-700">
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">Department</div>
            <div class="font-extrabold"><?php echo e($training->department?->name ?? '—'); ?></div>
          </div>
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">Instructor</div>
            <div class="font-extrabold"><?php echo e($training->instructor?->name ?? '—'); ?></div>
          </div>
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">Start</div>
            <div class="font-extrabold"><?php echo e($training->start_date?->format('M d, Y') ?? '—'); ?></div>
          </div>
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">End</div>
            <div class="font-extrabold"><?php echo e($training->end_date?->format('M d, Y') ?? '—'); ?></div>
          </div>
          <div class="sv-contact-field">
            <div class="text-xs text-gray-500 mb-1">Max participants</div>
            <div class="font-extrabold"><?php echo e($training->max_participants ?? '—'); ?></div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-2 sv-form-panel">
        <h2 class="text-2xl font-black sv-display text-gray-900">Apply for This Program</h2>
        <p class="text-gray-600 text-sm mt-2 mb-6">Fill in the form below. We'll review your application and get in touch.</p>

        <form action="<?php echo e(route('website.training.apply', $training)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
          <?php echo csrf_field(); ?>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Full name *</label>
              <input name="full_name" value="<?php echo e(old('full_name')); ?>" required class="sv-form-input">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Email *</label>
              <input type="email" name="email" value="<?php echo e(old('email')); ?>" required class="sv-form-input">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Phone</label>
              <input name="phone" value="<?php echo e(old('phone')); ?>" class="sv-form-input">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">University</label>
              <input name="university" value="<?php echo e(old('university')); ?>" class="sv-form-input">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Major</label>
              <input name="major" value="<?php echo e(old('major')); ?>" class="sv-form-input">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Level</label>
              <input name="level" value="<?php echo e(old('level')); ?>" placeholder="Student / Fresh Grad" class="sv-form-input">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">LinkedIn</label>
              <input name="linkedin_url" value="<?php echo e(old('linkedin_url')); ?>" placeholder="https://linkedin.com/in/..." class="sv-form-input">
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Portfolio</label>
              <input name="portfolio_url" value="<?php echo e(old('portfolio_url')); ?>" placeholder="https://..." class="sv-form-input">
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Message (optional)</label>
            <textarea name="message" rows="4" class="sv-form-textarea"><?php echo e(old('message')); ?></textarea>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">CV (PDF/DOC, optional)</label>
            <input type="file" name="cv" accept=".pdf,.doc,.docx" class="sv-form-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-bold">
            <p class="text-xs text-gray-500 mt-2">Max 10MB.</p>
          </div>

          <button type="submit" class="sv-btn sv-btn-primary w-full sm:w-auto">Submit Application</button>
        </form>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\website\training-show.blade.php ENDPATH**/ ?>