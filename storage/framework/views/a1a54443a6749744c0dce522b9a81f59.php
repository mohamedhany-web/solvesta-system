<?php
    $job = $jobPosting ?? null;
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1';
?>

<form action="<?php echo e($action); ?>" method="POST" class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
    <?php echo csrf_field(); ?>
    <?php if(($method ?? 'POST') !== 'POST'): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
        <h2 class="font-bold text-gray-900"><?php echo e($job ? 'تعديل بيانات الوظيفة' : 'بيانات الوظيفة الجديدة'); ?></h2>
        <p class="text-xs text-gray-500 mt-1">الوظائف ذات الحالة «منشورة» تظهر تلقائياً في صفحة التوظيف العامة</p>
    </div>

    <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="<?php echo e($labelClass); ?>">المسمى الوظيفي <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="<?php echo e(old('title', $job?->title)); ?>" required
                       class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">الرابط (slug)</label>
                <input type="text" name="slug" value="<?php echo e(old('slug', $job?->slug)); ?>" dir="ltr"
                       class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="auto-generated-if-empty"
                       style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                <p class="text-[10px] text-gray-400 mt-1">يُولَّد تلقائياً من العنوان إن تُرك فارغاً</p>
                <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">الحالة <span class="text-red-500">*</span></label>
                <select name="status" required class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($st); ?>" <?php if(old('status', $job?->status ?? 'draft') === $st): echo 'selected'; endif; ?>>
                            <?php echo e($st === 'published' ? 'منشورة (ظاهرة في الموقع)' : ($st === 'closed' ? 'مغلقة' : 'مسودة')); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="<?php echo e($labelClass); ?>">ملخص قصير</label>
                <input type="text" name="summary" value="<?php echo e(old('summary', $job?->summary)); ?>" maxlength="500"
                       class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;"
                       placeholder="سطر واحد يظهر في قائمة الوظائف">
            </div>

            <div class="md:col-span-2">
                <label class="<?php echo e($labelClass); ?>">وصف الوظيفة <span class="text-red-500">*</span></label>
                <textarea name="description" rows="6" required
                          class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          style="--tw-ring-color: <?php echo e($themeColor); ?>;"><?php echo e(old('description', $job?->description)); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="md:col-span-2">
                <label class="<?php echo e($labelClass); ?>">المتطلبات</label>
                <textarea name="requirements" rows="5" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;"><?php echo e(old('requirements', $job?->requirements)); ?></textarea>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">الموقع</label>
                <input type="text" name="location" value="<?php echo e(old('location', $job?->location)); ?>"
                       class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;"
                       placeholder="مثال: القاهرة / عن بُعد">
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">نوع التوظيف</label>
                <select name="employment_type" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__currentLoopData = $employmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php if(old('employment_type', $job?->employment_type ?? 'full_time') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">القسم (اختياري)</label>
                <select name="department_id" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <option value="">— بدون قسم —</option>
                    <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($dept->id); ?>" <?php if((string) old('department_id', $job?->department_id) === (string) $dept->id): echo 'selected'; endif; ?>><?php echo e($dept->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="<?php echo e($labelClass); ?>">ترتيب العرض</label>
                <input type="number" name="sort_order" min="0" max="9999" value="<?php echo e(old('sort_order', $job?->sort_order ?? 0)); ?>"
                       class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
            </div>

            <div class="md:col-span-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300"
                           style="accent-color: <?php echo e($themeColor); ?>;"
                           <?php if(old('is_featured', $job?->is_featured)): echo 'checked'; endif; ?>>
                    <span class="text-sm font-semibold text-gray-700">وظيفة مميزة — تظهر في أعلى القائمة</span>
                </label>
            </div>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap gap-3 justify-end">
        <a href="<?php echo e($cancelUrl); ?>" class="border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">إلغاء</a>
        <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
                style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
            <?php echo e($submitLabel); ?>

        </button>
    </div>
</form>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\job-postings\partials\form.blade.php ENDPATH**/ ?>