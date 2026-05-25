

<?php $__env->startSection('page-title', 'وظيفة جديدة'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-4xl mx-auto">
  <div class="mb-8 flex items-center justify-between">
    <div>
      <h1 class="text-3xl font-bold text-gray-900">وظيفة جديدة</h1>
      <p class="text-gray-600 mt-1">ستظهر في الموقع عند اختيار حالة «منشورة»</p>
    </div>
    <a href="<?php echo e(route('job-postings.index')); ?>" class="text-gray-600 font-bold hover:text-gray-900">رجوع</a>
  </div>

  <?php if($errors->any()): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
      <ul class="list-disc list-inside"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
  <?php endif; ?>

  <div class="bg-white rounded-xl border border-gray-200 p-8">
    <form action="<?php echo e(route('job-postings.store')); ?>" method="POST" class="space-y-6">
      <?php echo csrf_field(); ?>
      <?php echo $__env->make('job-postings._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      <button type="submit" class="w-full sm:w-auto bg-blue-600 text-white px-8 py-3 rounded-xl font-extrabold hover:bg-blue-700">حفظ الوظيفة</button>
    </form>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/job-postings/create.blade.php ENDPATH**/ ?>