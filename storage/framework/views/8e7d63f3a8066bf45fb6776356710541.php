

<?php $__env->startSection('title', \App\Helpers\SettingsHelper::getCompanyName() . ' — ' . $job->title); ?>

<?php $__env->startPush('body-class'); ?>
 sv-cinematic-page
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/cinematic-home.css')); ?>?v=3">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="sv-os" dir="ltr">
  <section class="sv-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <a href="<?php echo e(route('website.careers')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-orange-500 transition mb-6">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Careers
      </a>
      <div class="max-w-3xl">
        <div class="flex flex-wrap gap-2 mb-4">
          <span class="sv-badge"><?php echo e($job->employmentTypeLabel()); ?></span>
          <?php if($job->location): ?><span class="sv-badge"><?php echo e($job->location); ?></span><?php endif; ?>
          <?php if($job->department): ?><span class="sv-badge"><?php echo e($job->department->name); ?></span><?php endif; ?>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black sv-display text-gray-900 leading-tight"><?php echo e($job->title); ?></h1>
        <?php if($job->summary): ?>
        <p class="mt-4 text-lg text-gray-600 leading-relaxed"><?php echo e($job->summary); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="sv-section max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <?php if($errors->any() && empty($hideApplicationForm)): ?>
      <div class="sv-alert-error mb-8" role="alert">
        <p class="font-bold mb-2">Could not submit application:</p>
        <ul class="list-disc list-inside text-sm space-y-1">
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($err); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <div class="lg:col-span-1 space-y-6">
        <div class="sv-contact-panel">
          <h2 class="text-lg font-extrabold text-gray-900 mb-4">Role Overview</h2>
          <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap leading-relaxed"><?php echo e($job->description); ?></div>
        </div>
        <?php if($job->requirements): ?>
        <div class="sv-contact-panel">
          <h2 class="text-lg font-extrabold text-gray-900 mb-4">Requirements</h2>
          <div class="text-sm text-gray-700 whitespace-pre-wrap leading-relaxed"><?php echo e($job->requirements); ?></div>
        </div>
        <?php endif; ?>
      </div>

      <div class="lg:col-span-2 sv-form-panel">
        <?php if($hideApplicationForm): ?>
          <div class="text-center py-8 sm:py-12">
            <div class="mx-auto w-16 h-16 rounded-full flex items-center justify-center mb-5" style="background: linear-gradient(135deg, #eff6ff, #fff7ed)">
              <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <h2 class="text-2xl font-black sv-display text-gray-900">Application Submitted</h2>
            <p class="mt-3 text-gray-600 text-sm leading-relaxed max-w-md mx-auto">
              Thank you for applying to <strong class="text-gray-900"><?php echo e($job->title); ?></strong>.
              Our team will review your application and contact you if there is a match.
            </p>
            <?php if(session('success')): ?>
              <p class="mt-4 text-sm font-bold text-green-700"><?php echo e(session('success')); ?></p>
            <?php endif; ?>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
              <a href="<?php echo e(route('website.careers')); ?>" class="sv-btn sv-btn-primary w-full sm:w-auto">Browse Other Roles</a>
              <a href="<?php echo e(route('website.contact')); ?>" class="sv-btn sv-btn-ghost w-full sm:w-auto">Contact Us</a>
            </div>
          </div>
        <?php else: ?>
          <h2 class="text-2xl font-black sv-display text-gray-900">Apply for This Position</h2>
          <p class="text-gray-600 text-sm mt-2 mb-6">Submit your details and CV. We'll review and get back to you.</p>

          <form action="<?php echo e(route('website.careers.apply', $job->slug)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
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
                <label class="block text-sm font-bold text-gray-700 mb-2">LinkedIn</label>
                <input name="linkedin_url" value="<?php echo e(old('linkedin_url')); ?>" class="sv-form-input" placeholder="https://linkedin.com/in/...">
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 mb-2">Portfolio / GitHub</label>
                <input name="portfolio_url" value="<?php echo e(old('portfolio_url')); ?>" class="sv-form-input" placeholder="https://...">
              </div>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">Cover letter (optional)</label>
              <textarea name="message" rows="5" class="sv-form-textarea"><?php echo e(old('message')); ?></textarea>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-2">CV (PDF/DOC) *</label>
              <input type="file" name="cv" accept=".pdf,.doc,.docx" required class="sv-form-input file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-bold">
              <p class="text-xs text-gray-500 mt-2">Max 10MB.</p>
            </div>
            <button type="submit" class="sv-btn sv-btn-primary w-full sm:w-auto">Submit Application</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('website.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/website/career-show.blade.php ENDPATH**/ ?>