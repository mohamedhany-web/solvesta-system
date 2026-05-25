

<?php $__env->startSection('page-title', 'التوظيف والوظائف'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
  <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">التوظيف والوظائف</h1>
      <p class="text-gray-600">إدارة الوظائف المعروضة على الموقع العام</p>
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-jobs')): ?>
    <a href="<?php echo e(route('job-postings.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition flex items-center justify-center shadow-sm font-bold">
      <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
      وظيفة جديدة
    </a>
    <?php endif; ?>
  </div>

  <?php if(session('success')): ?>
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="بحث في العنوان..."
             class="w-full px-4 py-2 border border-gray-300 rounded-lg">
      <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        <option value="">كل الحالات</option>
        <option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>مسودة</option>
        <option value="published" <?php if(request('status') === 'published'): echo 'selected'; endif; ?>>منشورة</option>
        <option value="closed" <?php if(request('status') === 'closed'): echo 'selected'; endif; ?>>مغلقة</option>
      </select>
      <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg font-bold hover:bg-black">تصفية</button>
    </form>
  </div>

  <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الوظيفة</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الحالة</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الطلبات</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">التحديث</th>
            <th class="px-6 py-3"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <div class="font-bold text-gray-900"><?php echo e($job->title); ?></div>
              <?php if($job->is_featured): ?><span class="text-xs text-orange-600 font-bold">مميزة</span><?php endif; ?>
              <div class="text-xs text-gray-500 mt-1"><?php echo e($job->department?->name ?? '—'); ?> · <?php echo e($job->employmentTypeLabel()); ?></div>
            </td>
            <td class="px-6 py-4">
              <?php
                $badge = match($job->status) {
                  'published' => 'bg-green-100 text-green-800',
                  'closed' => 'bg-gray-200 text-gray-700',
                  default => 'bg-amber-100 text-amber-800',
                };
              ?>
              <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo e($badge); ?>"><?php echo e($job->statusLabelAr()); ?></span>
            </td>
            <td class="px-6 py-4 text-sm font-bold text-blue-600"><?php echo e($job->applications_count); ?></td>
            <td class="px-6 py-4 text-sm text-gray-600"><?php echo e($job->updated_at->format('Y-m-d')); ?></td>
            <td class="px-6 py-4 text-left whitespace-nowrap">
              <a href="<?php echo e(route('job-postings.show', $job)); ?>" class="text-sm font-bold text-gray-700 hover:text-blue-600 ml-3">عرض</a>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-jobs')): ?>
              <a href="<?php echo e(route('job-postings.edit', $job)); ?>" class="text-sm font-bold text-blue-600 hover:underline ml-3">تعديل</a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="5" class="px-6 py-12 text-center text-gray-600">
              لا توجد وظائف. أنشئ وظيفة واضبط حالتها على «منشورة» لتظهر في
              <a href="<?php echo e(route('website.careers')); ?>" target="_blank" class="text-blue-600 font-bold hover:underline">صفحة التوظيف</a>.
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <?php if($jobs->hasPages()): ?>
    <div class="px-6 py-4 border-t border-gray-100"><?php echo e($jobs->links()); ?></div>
    <?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/job-postings/index.blade.php ENDPATH**/ ?>