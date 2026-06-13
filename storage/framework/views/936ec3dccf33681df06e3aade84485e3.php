<?php
  $job = $jobPosting ?? null;
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">المسمى الوظيفي <span class="text-red-500">*</span></label>
    <input type="text" name="title" value="<?php echo e(old('title', $job?->title)); ?>" required
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الرابط (slug)</label>
    <input type="text" name="slug" value="<?php echo e(old('slug', $job?->slug)); ?>" dir="ltr"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
           placeholder="يُولَّد تلقائيًا من العنوان إن تُرك فارغًا">
    <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة <span class="text-red-500">*</span></label>
    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
      <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($st); ?>" <?php if(old('status', $job?->status ?? 'draft') === $st): echo 'selected'; endif; ?>>
          <?php echo e($st === 'published' ? 'منشورة (ظاهرة في الموقع)' : ($st === 'closed' ? 'مغلقة' : 'مسودة')); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">ملخص قصير</label>
    <input type="text" name="summary" value="<?php echo e(old('summary', $job?->summary)); ?>" maxlength="500"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">وصف الوظيفة <span class="text-red-500">*</span></label>
    <textarea name="description" rows="6" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('description', $job?->description)); ?></textarea>
    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">المتطلبات</label>
    <textarea name="requirements" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo e(old('requirements', $job?->requirements)); ?></textarea>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الموقع</label>
    <input type="text" name="location" value="<?php echo e(old('location', $job?->location)); ?>"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="مثال: القاهرة / عن بُعد">
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع التوظيف</label>
    <select name="employment_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
      <?php $__currentLoopData = $employmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($key); ?>" <?php if(old('employment_type', $job?->employment_type ?? 'full_time') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">القسم (اختياري)</label>
    <select name="department_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
      <option value="">— بدون قسم —</option>
      <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($dept->id); ?>" <?php if((string) old('department_id', $job?->department_id) === (string) $dept->id): echo 'selected'; endif; ?>><?php echo e($dept->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">ترتيب العرض</label>
    <input type="number" name="sort_order" min="0" max="9999" value="<?php echo e(old('sort_order', $job?->sort_order ?? 0)); ?>"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
  </div>

  <div class="md:col-span-2 flex items-center gap-2">
    <input type="checkbox" name="is_featured" value="1" id="is_featured" class="rounded border-gray-300 text-blue-600"
           <?php if(old('is_featured', $job?->is_featured)): echo 'checked'; endif; ?>>
    <label for="is_featured" class="text-sm font-semibold text-gray-700">وظيفة مميزة (تظهر في أعلى القائمة)</label>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\job-postings\_form.blade.php ENDPATH**/ ?>