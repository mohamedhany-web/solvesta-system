<?php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1.5';
    $isUpdate = (bool) ($existing ?? null);
?>

<form action="<?php echo e($action); ?>" method="POST" class="space-y-6">
    <?php echo csrf_field(); ?>
    <?php if(($method ?? 'POST') !== 'POST'): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    <?php if($isUpdate): ?>
    <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        <p class="font-bold">تقرير موجود لهذا اليوم</p>
        <p class="mt-1 text-blue-800/90">يمكنك تحديث البيانات أدناه — سيتم استبدال التقرير السابق عند الحفظ.</p>
    </div>
    <?php endif; ?>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">تفاصيل التقرير</h2>
            <p class="text-sm text-gray-500 mt-0.5">التاريخ والمشروع والمهمة المرتبطة</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="report_date" class="<?php echo e($labelClass); ?>">التاريخ <span class="text-red-500">*</span></label>
                    <input type="date" name="report_date" id="report_date"
                           value="<?php echo e(old('report_date', $existing?->report_date?->format('Y-m-d') ?? today()->format('Y-m-d'))); ?>"
                           max="<?php echo e(today()->format('Y-m-d')); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['report_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['report_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="hours_worked" class="<?php echo e($labelClass); ?>">ساعات العمل <span class="text-red-500">*</span></label>
                    <input type="number" step="0.25" min="0.25" max="24" name="hours_worked" id="hours_worked" required
                           value="<?php echo e(old('hours_worked', $existing?->hours_worked ?? 8)); ?>"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['hours_worked'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['hours_worked'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="project_id" class="<?php echo e($labelClass); ?>">المشروع</label>
                    <select name="project_id" id="project_id"
                            class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">— عام / بدون مشروع —</option>
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php if(old('project_id', $existing?->project_id) == $p->id): echo 'selected'; endif; ?>><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="task_id" class="<?php echo e($labelClass); ?>">المهمة (اختياري)</label>
                    <select name="task_id" id="task_id"
                            class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['task_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">— بدون مهمة —</option>
                        <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t->id); ?>" <?php if(old('task_id', $existing?->task_id) == $t->id): echo 'selected'; endif; ?>>
                                <?php echo e($t->project?->name); ?> — <?php echo e($t->title); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['task_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">ما أنجزته اليوم</h2>
            <p class="text-sm text-gray-500 mt-0.5">صف العمل بوضوح — ميزات، إصلاحات، اجتماعات</p>
        </div>
        <div class="p-6">
            <label for="work_summary" class="<?php echo e($labelClass); ?>">ملخص العمل <span class="text-red-500">*</span></label>
            <textarea name="work_summary" id="work_summary" rows="6" required
                      placeholder="مثال: أتممت واجهة تسجيل الدخول، راجعت PR #12، اجتمعت مع فريق التصميم..."
                      class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['work_summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      style="--tw-ring-color: <?php echo e($themeColor); ?>;"><?php echo e(old('work_summary', $existing?->work_summary)); ?></textarea>
            <?php $__errorArgs = ['work_summary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">العوائق (Blockers)</h2>
        </div>
        <div class="p-6 space-y-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="has_blocker" value="1"
                       <?php if(old('has_blocker', $existing?->has_blocker)): echo 'checked'; endif; ?>
                       class="h-4 w-4 rounded border-gray-300 focus:ring-2"
                       style="color: <?php echo e($themeColor); ?>; --tw-ring-color: <?php echo e($themeColor); ?>;"
                       id="has_blocker">
                <span class="text-sm font-semibold text-gray-800">يوجد عائق يعيق إنجاز العمل</span>
            </label>
            <div>
                <label for="blocker_description" class="<?php echo e($labelClass); ?>">وصف العائق</label>
                <textarea name="blocker_description" id="blocker_description" rows="3"
                          placeholder="ما الذي يمنعك من المتابعة؟ هل تحتاج مساعدة من أحد؟"
                          class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['blocker_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          style="--tw-ring-color: <?php echo e($themeColor); ?>;"><?php echo e(old('blocker_description', $existing?->blocker_description)); ?></textarea>
                <?php $__errorArgs = ['blocker_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
        <a href="<?php echo e($cancelUrl); ?>" class="inline-flex items-center justify-center gap-2 border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">إلغاء</a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95"
                style="background: <?php echo e($themeColor); ?>;"><?php echo e($submitLabel); ?></button>
    </div>
</form>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\daily-reports\partials\form.blade.php ENDPATH**/ ?>