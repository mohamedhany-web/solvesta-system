<?php
    $selectedModules = old('sidebar_modules', $department->sidebar_modules ?? []);
    $parentDepartments = $parentDepartments ?? collect();
?>

<div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">القسم الرئيسي (اختياري)</label>
    <select name="parent_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">قسم رئيسي مستقل</option>
        <?php $__currentLoopData = $parentDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($parent->id); ?>" <?php if(old('parent_id', $department->parent_id ?? null) == $parent->id): echo 'selected'; endif; ?>>
                <?php echo e($parent->name); ?> (<?php echo e($parent->code); ?>)
            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <p class="text-xs text-gray-500 mt-1">مثال: «تطوير الويب» تحت «قسم التطوير»</p>
    <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">ما يظهر لموظفي هذا القسم في القائمة الجانبية</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 border border-gray-200 rounded-xl bg-gray-50/80">
        <?php $__currentLoopData = \App\Models\Department::SIDEBAR_MODULES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="sidebar_modules[]" value="<?php echo e($key); ?>"
                       <?php if(in_array($key, (array) $selectedModules, true)): echo 'checked'; endif; ?>
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <span><?php echo e($label); ?></span>
            </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <p class="text-xs text-gray-500 mt-2">اترك الكل غير محدد لوراثة الإعدادات من القسم الرئيسي. الأقسام التقنية عادة: مشاريع + مهام + بيئة التطوير + GitHub.</p>
    <?php $__errorArgs = ['sidebar_modules'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الدور الافتراضي عند إنشاء موظف</label>
    <select name="default_role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <option value="">— يدوي —</option>
        <?php $__currentLoopData = \App\Services\DepartmentProfileService::assignableRoles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($role); ?>" <?php if(old('default_role', $department->default_role ?? null) === $role): echo 'selected'; endif; ?>>
                <?php echo e((\App\Services\DepartmentProfileService::roleLabels())[$role] ?? $role); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">المسمى الافتراضي (مثال: مبرمج)</label>
    <input type="text" name="default_position" value="<?php echo e(old('default_position', $department->default_position ?? '')); ?>"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="مبرمج ويب">
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">قالب KPI</label>
    <select name="kpi_profile" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <option value="">افتراضي</option>
        <?php $__currentLoopData = \App\Services\DepartmentProfileService::kpiProfileLabels(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php if(old('kpi_profile', $department->kpi_profile ?? null) === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">مسار الترقية (Career Track)</label>
    <input type="text" name="career_track" value="<?php echo e(old('career_track', $department->career_track ?? '')); ?>"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="technical, sales, hr...">
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\departments\partials\form-fields.blade.php ENDPATH**/ ?>